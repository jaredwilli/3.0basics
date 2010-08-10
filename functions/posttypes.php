<?php
// Initialize the Class and add the action
add_action('init', 'pTypesInit');
function pTypesInit() {
    global $sites;
    $sites = new TypeSites();
}

// Create a post type class for 'Site' posts
// To use as a bookmarking post type for sites you want to save/share.
class TypeSites {

	// Store the data
	public $meta_fields = array( 'title', 'description', 'site', 'siteurl', 'category', 'post_tags' );
	public $siteurl = 'http://';

	// The post type constructor
	public function TypeSites() {

        $siteArgs = array(
			'labels' => array(
                'name' => __( 'Sites', 'post type general name' ),
                'singular_name' => __( 'Site', 'post type singular name' ),
                'add_new' => __( 'Add New', 'site' ),
                'add_new_item' => __( 'Add New Site' ),
                'edit_item' => __( 'Edit Site' ),
                'new_item' => __( 'New Site' ),
                'view_item' => __( 'View Site' ),
                'search_items' => __( 'Search Sites' ),
                'not_found' =>  __( 'No sites found in search' ),
                'not_found_in_trash' => __( 'No sites found in Trash' ),
			),
			'public' => true, 
			'show_ui' => true,
			'_builtin' => false,
			'hierarchical' => false,
			'query_var' => 'site',
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => 'site' ), // Permalinks. Fixes a 404 bug
			'menu_icon'  => get_bloginfo( 'template_directory' ).'/images/sites-icon.png',
			'taxonomies' =>  array( 'category', 'post_tag' ), // Add tags and categories taxonomies
			'supports' => array( 'title','editor','author','comments' )
        );
        register_post_type( 'site', $siteArgs );	

	// Initialize the methods
        add_action( 'admin_init', array( &$this, 'admin_init' ));
        /*
		add_action( 'template_redirect', array( &$this, 'template_redirect' ));
		*/
        add_action( 'wp_insert_post', array( &$this, 'wp_insert_post' ), 10, 2 );

		add_filter( 'manage_posts_custom_column', array( &$this, 'site_custom_columns' ));
		add_action( 'manage_edit-site_columns', array( &$this, 'site_edit_columns' ));
	}

	// Create the columns and heading title text
	public function site_edit_columns($columns) {
        $columns = array(
			'cb' 		=> '<input type="checkbox" />',
			'title' 	=> 'Site Title',
			'url'	 	=> 'URL',
			'category'	=> 'Category',
			'post_tags' => 'Tags',
			'siteurl' 	=> 'Screenshot',
        );
        return $columns;
    }
	// switching cases based on which $column we show the content of it
    public function site_custom_columns($column) { 
		global $post;
        switch ($column) {
            case "title" : the_title();
                break;
            case "url" : $m = $this->mshot(100); echo '<a href="'.$m[0].'" target="_blank">'.$m[0].'</a>';
				break;
            case "category" : the_category();
				break;				
            case "post_tags" : the_tags('',', ');
                break;
            case "siteurl" : $m = $this->mshot(150); echo $m[1];
				break;
        }
    }

	/**
	 * Causes all pages to show a 404 page so commented out for now
	
	// Template redirect for custom templates
    public function template_redirect() {
        global $wp_query;
        if ( $wp_query->query_vars['post_type'] == 'site' ) {
            get_template_part( 'single-site' ); // a custom single-slug.php template
            die();
        } else {
			$wp_query->is_404 = true;
		}
    }
	*/

	// For inserting new 'site' post type posts
    public function wp_insert_post($post_id, $post = null) {
        if ($post->post_type == 'site') {
            foreach ($this->meta_fields as $key) {
                $value = @$_POST[$key];
                if (empty($value)) {
                    delete_post_meta($post_id, $key);
                    continue;
                }
                if (!is_array($value)) {
                    if (!update_post_meta($post_id, $key, $value)) {
                        add_post_meta($post_id, $key, $value);
                    }
                } else {
                    delete_post_meta($post_id, $key);
                    foreach ($value as $entry) add_post_meta($post_id, $key, $entry);
                }
            }
        }
    }

	// Add meta box
	function admin_init() {
        add_meta_box( 'sites-meta', 'Site Url (required)', array( &$this, 'meta_options' ), 'site', 'side', 'high' );
    }

	// Admin post meta contents
	public function meta_options() {
		global $post, $siteurl;
		$custom = get_post_custom($post->ID);
		$siteurl = get_post_custom($post->ID, 'siteurl', true );
		
		$myurl = trailingslashit( get_post_meta( $post->ID, 'siteurl', true ) );
		if ( $myurl != '' ) {
			// Check if url has http:// or not so works either way
			if ( preg_match( "/http(s?):\/\//", $myurl )) {
				$siteurl = get_post_meta( $post->ID, 'siteurl', true );
				$mshoturl = 'http://s.WordPress.com/mshots/v1/' . urlencode( $myurl );
			} else {
				$siteurl = 'http://' . get_post_meta( $post->ID, 'siteurl', true );
				$mshoturl = 'http://s.WordPress.com/mshots/v1/' . urlencode('http://'.$myurl);
			}
			$imgsrc  = '<img src="' . $mshoturl . '?w=250" width="250" />';
		} ?>

		<p><label>Enter a valid Url below:<br />
		<input id="siteurl" size="26" name="siteurl" value="<?php echo $siteurl; ?>" /></label></p>
		<p><?php echo '<a href="' . $siteurl . '">' . $imgsrc . '</a>'; ?></p>

	<?php
	} // end meta options

    public function mshot($mshotsize) {
        global $post;
        $imgWidth = $mshotsize;
        $myurl = get_post_meta($post->ID, 'siteurl', true);
		if ( $myurl != '' ) {
/* /^ ((http(s)?)+:\/\/)?(www\d?.)?|([a-zA-Z0-9\.\-_])\.+)?([a-zA-Z0-9]+\-?)+(\.\w[2,6])+(\/?([a-zA-Z0-9]+?[\\\/\-\.\?&#%=_]+?\/))?$/
*/
			$mshoturl = '';
			if ( preg_match( "/http(s?):\/\//", $myurl )) {
				$siteurl = get_post_meta( $post->ID, 'siteurl', true );
				$mshoturl = 'http://s.wordpress.com/mshots/v1/' . urlencode( $myurl );
			} else {
				$siteurl = 'http://' . get_post_meta( $post->ID, 'siteurl', true );
				$mshoturl = 'http://s.wordpress.com/mshots/v1/' . urlencode('http://'.$myurl );
			}
		}
        $mshotimg = '<img src="'.$mshoturl.'?w='.$imgWidth.'" alt="'.get_the_title().'" title="'.get_the_title().'" />';
        
        return array( $siteurl, $mshotimg );
	}
			
} // end of TypeSites{} class
?>