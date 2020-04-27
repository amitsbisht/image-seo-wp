<?php
/**
 * Plugin Name: Image SEO
 * Author: Amit Singh
 * Description: Adds an input field on the media gallery screen and allows to update alt tags asynchronously with AJAX.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

add_filter( 'manage_media_columns', 'image_seo_alt_media_column' );
function image_seo_alt_media_column( $cols ) {
    $cols['alt_tag'] = 'Alt Tag';
    return $cols;
}

add_action( 'manage_media_custom_column', 'image_seo_alt_media_custom_column', 10, 2 );
function image_seo_alt_media_custom_column( $col_name, $id ) {
    if( $col_name !== 'alt_tag' ) {
        return;
    }
    echo '<input type="text" id="imageAltTag" data-id="'. $id . '" value="'. get_post_meta( $id, '_wp_attachment_image_alt', 'true' ) .'">
    <span class="update_text" style="color:green;display:block;padding: 5px;"></span><input type="hidden" name="action" value="save_img_alt">';
}

add_action('admin_print_scripts', 'image_seo_script', 99); 
function image_seo_script() {
    echo "<script>
    jQuery(document).ready(function( $ ) {
        $('#imageAltTag').on('blur', function(e){
            var obj = {
                'alt': e.target.value,
                'id': e.target.dataset.id,
                'action': 'save_img_alt',
            };
            $.ajax({
                type: 'post',
                url: '". admin_url('admin-ajax.php')  ."',
                data: obj,
                dataType: 'json',
                success: function (data) {
                    $('.update_text').text('Alt Tag Saved!');
                }
            });
        });
    });
    </script>";
}

add_action( 'wp_ajax_save_img_alt', 'image_seo_alt_callback' );
function image_seo_alt_callback() {
    $updated = update_post_meta( $_POST['id'], '_wp_attachment_image_alt', $_POST['alt'] );
    echo $updated;
}