<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/amitsbisht/
 * @since      1.0.0
 *
 * @package    Image_Seo_Wp
 * @subpackage Image_Seo_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Image_Seo_Wp
 * @subpackage Image_Seo_Wp/admin
 * @author     Amit Singh <asamitdbz@gmail.com>
 */
class Image_Seo_Wp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Image_Seo_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Image_Seo_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/image-seo-wp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Image_Seo_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Image_Seo_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/image-seo-wp-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * This function adds a custom column inside the WP Media Library
	 *
	 * @param [type] $cols
	 * @return void
	 */
	public function image_seo_wp_alt_column( $cols ) {
		$cols['alt_tag'] = 'Alt Tag';
    	return $cols;
	}
	
	/**
	  * This function adds data to the custom alt_tag column
	  *
	  * @param [type] $col_name
	  * @param [type] $id
	  * @return void
	  */
	public function image_seo_wp_alt_custom_column( $col_name, $id ) {
		if( $col_name !== 'alt_tag' ) {
			return;
		}
		echo '<input type="text" id="imageAltTag" data-id="'. $id . '" value="'. get_post_meta( $id, '_wp_attachment_image_alt', 'true' ) .'">
		<span class="update_text" style="color:green;display:block;padding: 5px;"></span><input type="hidden" name="action" value="save_img_alt">';
	}

	/**
	 * This function adds scripts on media screen onlu
	 *
	 * @return void
	 */
	public function image_seo_wp_script() {
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

	/**
	 * This function is a AJAX callback for image Alt input field
	 *
	 * @return void
	 */
	public function image_seo_alt_ajax_callback() {
		$updated = update_post_meta( $_POST['id'], '_wp_attachment_image_alt', $_POST['alt'] );
		echo $updated;
	}

}