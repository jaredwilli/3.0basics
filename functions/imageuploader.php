<?php 

// Actively used in theme
// THIS IS THE CUSTOM WRITE PANEL IMAGE UPLOADER
// Uploads images that get set in the custom fields. 
// For uploading post thumbnails, and easily upload images, .


// Custom fields for WP write panel
// This code is protected under Creative Commons License: http://creativecommons.org/licenses/by-nc-nd/3.0/

function woothemes_metabox_create() {
    global $post;
    $woo_metaboxes = get_option('woo_custom_template');     
    $output = '';
    $output .= '<table class="woo_metaboxes_table">'."\n";
    foreach ($woo_metaboxes as $woo_id => $woo_metabox) {
    if(        
			$woo_metabox['type'] == 'text' 
    OR      $woo_metabox['type'] == 'select' 
    OR      $woo_metabox['type'] == 'checkbox' 
    OR      $woo_metabox['type'] == 'textarea'
    OR      $woo_metabox['type'] == 'radio'
    )
            $woo_metaboxvalue = get_post_meta($post->ID,$woo_metabox["name"],true);
            
            if ($woo_metaboxvalue == "" || !isset($woo_metaboxvalue)) {
                $woo_metaboxvalue = $woo_metabox['std'];
            }
            if($woo_metabox['type'] == 'text'){
            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_id.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><input class="woo_input_text" type="'.$woo_metabox['type'].'" value="'.$woo_metaboxvalue.'" name="woothemes_'.$woo_metabox["name"].'" id="'.$woo_id.'"/>';
                $output .= '<span class="woo_metabox_desc">'.$woo_metabox['desc'].'</span></td>'."\n";
                $output .= "\t".'<td></td></tr>'."\n";  
                              
            }
            
            elseif ($woo_metabox['type'] == 'textarea'){
            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_metabox.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><textarea class="woo_input_textarea" name="woothemes_'.$woo_metabox["name"].'" id="'.$woo_id.'">' . $woo_metaboxvalue . '</textarea>';
                $output .= '<span class="woo_metabox_desc">'.$woo_metabox['desc'].'</span></td>'."\n";
                $output .= "\t".'<td></td></tr>'."\n";  
                              
            }

            elseif ($woo_metabox['type'] == 'select'){
                       
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_id.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><select class="woo_input_select" id="'.$woo_id.'" name="woothemes_'. $woo_metabox["name"] .'">';
                $output .= '<option value="">Select to return to default</option>';
                
                $array = $woo_metabox['options'];
                
                if($array){
                
                    foreach ( $array as $id => $option ) {
                        $selected = '';
                       
                                                       
                        if($woo_metabox['default'] == $option && empty($woo_metaboxvalue)){$selected = 'selected="selected"';} 
                        else  {$selected = '';}
                        
                        if($woo_metaboxvalue == $option){$selected = 'selected="selected"';}
                        else  {$selected = '';}  
                        
                        $output .= '<option value="'. $option .'" '. $selected .'>' . $option .'</option>';
                    }
                }
                
                $output .= '</select><span class="woo_metabox_desc">'.$woo_metabox['desc'].'</span></td></td><td></td>'."\n";
                $output .= "\t".'</tr>'."\n";
            }
            
            elseif ($woo_metabox['type'] == 'checkbox'){
            
                if($woo_metaboxvalue == 'true') { $checked = ' checked="checked"';} else {$checked='';}

                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_id.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><input type="checkbox" '.$checked.' class="woo_input_checkbox" value="true"  id="'.$woo_id.'" name="woothemes_'. $woo_metabox["name"] .'" />';
                $output .= '<span class="woo_metabox_desc" style="display:inline">'.$woo_metabox['desc'].'</span></td></td><td></td>'."\n";
                $output .= "\t".'</tr>'."\n";
            }
            
            elseif ($woo_metabox['type'] == 'radio'){
            
                $array = $woo_metabox['options'];
            
            if($array){
            
            $output .= "\t".'<tr>';
            $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_id.'">'.$woo_metabox['label'].'</label></th>'."\n";
            $output .= "\t\t".'<td>';
            
                foreach ( $array as $id => $option ) {
                              
                    if($woo_metaboxvalue == $option) { $checked = ' checked';} else {$checked='';}

                        $output .= '<input type="radio" '.$checked.' value="' . $id . '" class="woo_input_radio"  id="'.$woo_id.'" name="woothemes_'. $woo_metabox["name"] .'" />';
                        $output .= '<span class="woo_input_radio_desc" style="display:inline">'. $option .'</span><div class="woo_spacer"></div>';
                    }
                    $output .=  '</td></td><td></td>'."\n";
                    $output .= "\t".'</tr>'."\n";    
                 }
            }
            
            elseif($woo_metabox['type'] == 'upload')
            {
            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="woo_metabox_names"><label for="'.$woo_id.'">'.$woo_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td class="woo_metabox_fields">'. woothemes_uploader_custom_fields($post->ID,$woo_metabox["name"],$woo_metabox["default"],$woo_metabox["desc"]);
                $output .= '</td>'."\n";
                $output .= "\t".'</tr>'."\n";
                
            }
        }
    
    $output .= '</table>'."\n\n";
    echo $output;
}

function woothemes_uploader_custom_fields($pID,$id,$std,$desc){

    // Start Uploader
    $upload = get_post_meta( $pID, $id, true);
    $uploader .= '<input class="woo_input_text" name="'.$id.'" type="text" value="'.$upload.'" />';
    $uploader .= '<div class="clear"></div>'."\n";
    $uploader .= '<input type="file" name="attachement_'.$id.'" />';
    $uploader .= '<input type="submit" class="button button-highlighted" value="Save" name="save"/>';
    $uploader .= '<span class="woo_metabox_desc">'.$desc.'</span></td>'."\n".'<td class="woo_metabox_image"><a href="'. $upload .'"><img src="'.get_bloginfo('template_url').'/thumb.php?src='.$upload.'&w=150&h=80&zc=1" alt="" /></a>';

return $uploader;
}




function woothemes_metabox_handle(){   
    
    global $globals;
    $woo_metaboxes = get_option('woo_custom_template');     
    $pID = $_POST['post_ID'];
    $upload_tracking = array();
    
    if ($_POST['action'] == 'editpost'){                                   
        foreach ($woo_metaboxes as $woo_metabox) { // On Save.. this gets looped in the header response and saves the values submitted
            if($woo_metabox['type'] == 'text' OR $woo_metabox['type'] == 'select' OR $woo_metabox['type'] == 'checkbox' OR $woo_metabox['type'] == 'textarea' ) // Normal Type Things...
                {
                    $var = "woothemes_".$woo_metabox["name"];
                    if (isset($_POST[$var])) {            
                        if( get_post_meta( $pID, $woo_metabox["name"] ) == "" )
                            add_post_meta($pID, $woo_metabox["name"], $_POST[$var], true );
                        elseif($_POST[$var] != get_post_meta($pID, $woo_metabox["name"], true))
                            update_post_meta($pID, $woo_metabox["name"], $_POST[$var]);
                        elseif($_POST[$var] == "") {
                           delete_post_meta($pID, $woo_metabox["name"], get_post_meta($pID, $woo_metabox["name"], true));
                        }
                    }
                    elseif(!isset($_POST[$var]) && $woo_metabox['type'] == 'checkbox') { 
                        update_post_meta($pID, $woo_metabox["name"], 'false'); 
                    }      
                    else {
                          delete_post_meta($pID, $woo_metabox["name"], get_post_meta($pID, $woo_metabox["name"], true)); // Deletes check boxes OR no $_POST
                    }    
                }
          
            elseif($woo_metabox['type'] == 'upload') // So, the upload inputs will do this rather
                {
                $id = $woo_metabox['name'];
                $override['action'] = 'editpost';
                    if(!empty($_FILES['attachement_'.$id]['name'])){ //New upload          
                           $uploaded_file = wp_handle_upload($_FILES['attachement_' . $id ],$override); 
                           $uploaded_file['option_name']  = $woo_metabox['label'];
                           $upload_tracking[] = $uploaded_file;
                           update_post_meta($pID, $id, $uploaded_file['url']);
                    }
                    elseif(empty( $_FILES['attachement_'.$id]['name']) && isset($_POST[ $id ])){
                        update_post_meta($pID, $id, $_POST[ $id ]); 
                    }
                    elseif($_POST[ $id ] == '')  { delete_post_meta($pID, $id, get_post_meta($pID, $id, true));
                    }
                }
               // Error Tracking - File upload was not an Image
               update_option('woo_custom_upload_tracking', $upload_tracking);
            }
        }
}

function woothemes_metabox_add() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box('woothemes-settings',get_option('woo_themename').' Custom Settings','woothemes_metabox_create','post','normal');
        add_meta_box('woothemes-settings',get_option('woo_themename').' Custom Settings','woothemes_metabox_create','page','normal');
    }
}

function woothemes_metabox_header(){
?>
<script type="text/javascript">

    jQuery(document).ready(function(){
        jQuery('form#post').attr('enctype','multipart/form-data');
        jQuery('form#post').attr('encoding','multipart/form-data');
        jQuery('.woo_metaboxes_table th:last, .woo_metaboxes_table td:last').css('border','0');
        var val = jQuery('input#title').attr('value');
        if(val == ''){ 
        jQuery('.woo_metabox_fields .button-highlighted').after("<em class='woo_red_note'>This is the post thumbnail image uploader.</em>");
        };
        <?php //Errors
        $error_occurred = false;
        $upload_tracking = get_option('woo_custom_upload_tracking');
        if(!empty($upload_tracking)){
        $output = '<div style="clear:both;height:20px;"></div><div class="errors"><ul>' . "\n";
            $error_shown == false;
            foreach($upload_tracking as $array )
            {
                 if(array_key_exists('error', $array)){
                        $error_occurred = true;
                        ?>
                        jQuery('form#post').before('<div class="updated fade"><p>WooThemes Upload Error: <strong><?php echo $array['option_name'] ?></strong> - <?php echo $array['error'] ?></p></div>');
                        <?php
                }
            }
        }
        delete_option('woo_upload_custom_errors');
        ?>
    });

</script>
<style type="text/css">
.woo_input_text { margin:0 0 10px 0; background:#f4f4f4; color:#444; width:80%; font-size:11px; padding: 5px;}
.woo_input_select { margin:0 0 10px 0; background:#f4f4f4; color:#444; width:60%; font-size:11px; padding: 5px;}
.woo_input_checkbox { margin:0 10px 0 0; }
.woo_input_radio { margin:0 10px 0 0; }
.woo_input_radio_desc { font-size: 12px; color: #666 ; }
.woo_spacer { display: block; height:5px}
.woo_metabox_desc { font-size:10px; color:#aaa; display:block}
.woo_metaboxes_table{ border-collapse:collapse; width:100%}
.woo_metaboxes_table tr:hover th,
.woo_metaboxes_table tr:hover td { background:#f8f8f8}
.woo_metaboxes_table th,
.woo_metaboxes_table td{ border-bottom:1px solid #ddd; padding:10px 10px;text-align: left; vertical-align:top}
.woo_metabox_names { width:20%}
.woo_metabox_fields { width:70%}
.woo_metabox_image { text-align: right;}
.woo_red_note { margin-left: 5px; color: #c77; font-size: 10px;}
.woo_input_textarea { width:80%; height:120px;margin:0 0 10px 0; background:#f0f0f0; color:#444;font-size:11px;padding: 5px;}
</style>
<?php
}
add_action('edit_post', 'woothemes_metabox_handle');
add_action('admin_menu', 'woothemes_metabox_add'); // Triggers Woothemes_metabox_create
add_action('admin_head', 'woothemes_metabox_header');

?>