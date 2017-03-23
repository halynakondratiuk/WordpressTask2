<?php
/**
 * Plugin Name: WP Contacts
 * Plugin URI: http://work.com/wordpress/
 * Version: 1.1
 * Author: Halyna Kondratiuk
 * Author URI: http://work.com/wordpress/
 * Description: A simple plugin creates custom post type contacts
 */

 function register_custom_post_type() {

     $labels = array(
            'name'               => _x( 'Contacts', 'post type general name' ),
            'singular_name'      => _x( 'Contact', 'post type singular name' ),
            'menu_name'          => _x( 'Contacts', 'admin menu' ),
            'name_admin_bar'     => _x( 'Contact', 'add new on admin bar' ),
            'add_new'            => _x( 'Add New', 'contact' ),
            'add_new_item'       => __( 'Add New Contact' ),
            'new_item'           => __( 'New Contact' ),
            'edit_item'          => __( 'Edit Contact' ),
            'view_item'          => __( 'View Contact' ),
            'all_items'          => __( 'All Contacts' ),
            'search_items'       => __( 'Search Contacts' ),
            'parent_item_colon'  => __( 'Parent Contacts:' ),
            'not_found'          => __( 'No Conttacts found.' ),
            'not_found_in_trash' => __( 'No Contacts found in Trash.' ),
        );
    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
	'exclude_from_search'   => false,
	'show_in_nav_menus'     => true,
	'show_ui'               => true,
	'show_in_menu'          => true,
	'show_in_admin_bar'     => true,
	'menu_position'         => 10,
	'menu_icon'             => 'dashicons-businessman',
	'can_export'            => true,
	'delete_with_user'      => false,
	'hierarchical'          => false,	
	'has_archive'           => false,
	'query_var'             => true,
	'capability_type'       => 'post',
	'map_meta_cap'          => true,

        'rewrite'               => array(
                 'slug'         => 'contacts',
                 'with_front'   => true,
		 'pages'        => true,
                 'feeds'        => true,
),
        'support'               => array(
                 'title',
                 'editor',
                 'author',
		 'thumbnail' 
)
);

         register_post_type( 'contact', $args );   
}
         add_action( 'init', 'register_custom_post_type' );
         add_action( 'add_meta_boxes', 'register_meta_boxes' ); 
         function register_meta_boxes() {
    add_meta_box( 'contact-details', 'Contact Details', 'output_meta_box', 'contact', 'normal', 'high' );   
}
function output_meta_box($post) {  
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
      <div>
            <label for="meta-box-tel">Telephone</label>
            <input name="meta-box-tel" type="text" value="<?php echo get_post_meta($post->ID, "meta-box-tel", true); ?>">
            <br>
	    <label for="meta-box-email">Email</label>
            <input name="meta-box-email" type="text" value="<?php echo get_post_meta($post>ID, "meta-box-email", true); ?>">
 	    <br>
	    <label for="meta-box-adrs">Address</label>
            <input name="meta-box-adrs" type="text" value="<?php echo get_post_meta($post->ID, "meta-box-adrs", true); ?>">
      </div>
    <?php 
}
function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "contacts";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_tel_value = "";
    $meta_box_email_value = "";
    $meta_box_adrs_value = "";

    if(isset($_POST["meta-box-tel"]))
    {
        $meta_box_tel_value = $_POST["meta-box-tel"];
    }   
    update_post_meta($post_id, "meta-box-tel", $meta_box_tel_value);

    if(isset($_POST["meta-box-email"]))
    {
        $meta_box_email_value = $_POST["meta-box-email"];
    }   
    update_post_meta($post_id, "meta-box-email", $meta_box_email_value);

    if(isset($_POST["meta-box-adrs"]))
    {
        $meta_box_adrs_value = $_POST["meta-box-adrs"];
    }   
    update_post_meta($post_id, "meta-box-adrs", $meta_box_adrs_value);
}

add_action("save_post", "save_custom_meta_box", 10, 3);

?>

