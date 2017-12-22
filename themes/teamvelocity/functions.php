<?php

/** Constants */
defined('THEME_URI') || define('THEME_URI', get_template_directory_uri());
defined('THEME_PATH') || define('THEME_PATH', realpath(__DIR__));

include_once THEME_PATH . '/includes/functions.php';
require_once THEME_PATH . '/includes/register-sidebar.php';

// Constants
defined('DISALLOW_FILE_EDIT') || define('DISALLOW_FILE_EDIT', FALSE);
defined('TEXT_DOMAIN') || define('TEXT_DOMAIN', 'jp-basic');
define('JPB_THEME_PATH', realpath(__DIR__));

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

/*
  Favicon Admin
 */

function favicon() {
    echo '<link rel="shortcut icon" href="', get_template_directory_uri(), '/favicon.ico" />', "\n";
}

add_action('admin_head', 'favicon');

/**
 * Add scripts and styles to all Admin pages
 */
function jscustom_admin_scripts() {
    wp_enqueue_media();
    wp_register_script('custom-upload', get_template_directory_uri() . '/js/media-uploader.js', array('jquery'));
    wp_enqueue_script('custom-upload');
}

add_action('admin_print_scripts', 'jscustom_admin_scripts');

add_filter('update_footer', 'right_admin_footer_text_output', 11);

function right_admin_footer_text_output($text) {
    $text = 'Develop by Alexander Contreras';
    return $text;
}

//Theme settings
require(get_template_directory() . '/inc/theme-options.php');

//include_once __DIR__ . '/includes/register-script.php';
include_once __DIR__ . '/includes/register-script-local.php';
include_once __DIR__ . '/includes/register-style.php';
//include_once __DIR__ . '/includes/register-style-local.php';

add_action('wp_enqueue_scripts', function () {

    /* Styles */
    wp_enqueue_style('bootstrap');
    wp_enqueue_style('animate');
    wp_enqueue_style('hover');
    wp_enqueue_style('font-awesome');
    // Theme
    wp_enqueue_style('main-theme');

    /* Scripts */
    wp_enqueue_script('modernizr');
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap');
    wp_enqueue_script('jquery-form');

    // Bootstrap Alerts
    wp_register_script('bootstrap-alerts', apply_filters('js_cdn_uri', THEME_URI . '/js/bootstrap-alerts.min.js', 'bootstrap-alerts'), array('jquery', 'bootstrap'), NULL, TRUE);
    wp_enqueue_script('bootstrap-alerts');


    // Add defer atribute
    do_action('defer_script', array('jquery-form', 'bootstrap-alerts'));

    // Bootstrap complemetary text align
    wp_register_style('bs-text-align', THEME_URI . '/css/bootstrap-text-align.min.css', array('bootstrap'), '1.0');
    wp_enqueue_style('bs-text-align');

    // Wordpress Core
    wp_register_style('wordpress-core', THEME_URI . '/css/wordpress-core.min.css', array('bootstrap', 'bs-text-align'), '1.0');
    wp_enqueue_style('wordpress-core');

    if (is_child_theme()) {
        // Theme
        wp_register_style('theme', get_stylesheet_uri(), array('animate'), '1.0');
        wp_enqueue_style('theme');
    }
});

include_once __DIR__ . '/includes/theme-features.php';

/**
 * Encoded Mailto Link
 *
 * Create a spam-protected mailto link written in Javascript
 *
 * @param	string	the email address
 * @param	string	the link title
 * @param	mixed	any attributes
 * @return	string
 */
function safe_mailto($email, $title = '', $attributes = '') {
    $title = (string) $title;

    if ($title === '') {
        $title = $email;
    }

    $x = str_split('<a href="mailto:', 1);

    for ($i = 0, $l = strlen($email); $i < $l; $i++) {
        $x[] = '|' . ord($email[$i]);
    }

    $x[] = '"';

    if ($attributes !== '') {
        if (is_array($attributes)) {
            foreach ($attributes as $key => $val) {
                $x[] = ' ' . $key . '="';
                for ($i = 0, $l = strlen($val); $i < $l; $i++) {
                    $x[] = '|' . ord($val[$i]);
                }
                $x[] = '"';
            }
        } else {
            for ($i = 0, $l = strlen($attributes); $i < $l; $i++) {
                $x[] = $attributes[$i];
            }
        }
    }

    $x[] = '>';

    $temp = array();
    for ($i = 0, $l = strlen($title); $i < $l; $i++) {
        $ordinal = ord($title[$i]);

        if ($ordinal < 128) {
            $x[] = '|' . $ordinal;
        } else {
            if (count($temp) === 0) {
                $count = ($ordinal < 224) ? 2 : 3;
            }

            $temp[] = $ordinal;
            if (count($temp) === $count) {
                $number = ($count === 3) ? (($temp[0] % 16) * 4096) + (($temp[1] % 64) * 64) + ($temp[2] % 64) : (($temp[0] % 32) * 64) + ($temp[1] % 64);
                $x[] = '|' . $number;
                $count = 1;
                $temp = array();
            }
        }
    }

    $x[] = '<';
    $x[] = '/';
    $x[] = 'a';
    $x[] = '>';

    $x = array_reverse($x);

    $output = "<script type=\"text/javascript\">\n"
            . "\t//<![CDATA[\n"
            . "\tvar l=new Array();\n";

    for ($i = 0, $c = count($x); $i < $c; $i++) {
        $output .= "\tl[" . $i . "] = '" . $x[$i] . "';\n";
    }

    $output .= "\n\tfor (var i = l.length-1; i >= 0; i=i-1) {\n"
            . "\t\tif (l[i].substring(0, 1) === '|') document.write(\"&#\"+unescape(l[i].substring(1))+\";\");\n"
            . "\t\telse document.write(unescape(l[i]));\n"
            . "\t}\n"
            . "\t//]]>\n"
            . '</script>';

    return $output;
}

require_once __DIR__ . '/admin/admin.php';


// Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');

class Custom_Walker extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
        // depth dependent classes
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr(implode(' ', $depth_classes));

        // passed classes
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        if (!in_array($item->object, array('custom'))) {
            $post_data = get_post($item->object_id);
            $classes[] = $post_data->post_type . '-' . $post_data->post_name;
        }

        $class_names = esc_attr(implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item)));

        // build html
        $output .= $indent . '<li id="nav-menu-item-' . $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

        // link attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

        $item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s', $args->before, $attributes, $args->link_before, apply_filters('the_title', $item->title, $item->ID), $args->link_after, $args->after
        );

        // build html
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

}

/* Facebook Head */

add_action('wp_head', function() {
    if (is_page() or is_singular()) {
        $current_post = get_post();
        $meta['og:title'] = esc_attr($current_post->post_title);
        $meta['og:description'] = esc_attr($current_post->post_excerpt);
        $meta['og:site_name'] = get_bloginfo('name');
        $meta['og:url'] = get_permalink($current_post->ID);
        $meta['og:image'] = wp_get_attachment_url(get_post_thumbnail_id($current_post->ID));
        foreach ($meta as $key => $value) {
            if (!empty($value)) {
                printf('<meta name="%s" content="%s" />', $key, $value);
                echo "\n";
            }
        }
    }
}, 1);

//Cut images
if (function_exists('add_image_size')) {
    add_image_size('blog-image', 555, 343, true);
    add_image_size('single-image', 1170, 480, true);
}

// Here go metabox

function rw_register_meta_box() {
    if (!class_exists('RW_Meta_Box') or ! is_admin())
        return;
    $post_ID = !empty($_POST['post_ID']) ?
            $_POST['post_ID'] :
            (!empty($_GET['post']) ? $_GET['post'] : FALSE);

    $post_name = '';
    if ($post_ID) {
        $current_post = get_post($post_ID);
        if ($current_post) {
            $current_post_type = $current_post->post_type;
            $post_name = $current_post->post_name;
        } else {
            $post_name = '';
        }
    }

    if ($post_name == 'home') {

        $meta_box[] = array(
            'title' => 'Information',
            'pages' => array('page'),
            'fields' => array(
                array(
                    'name' => 'Title Pillars',
                    'id' => 'title_pillars',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Title CEO',
                    'id' => 'title_ceo',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Sub Title CEO',
                    'id' => 'subtitle_ceo',
                    'type' => 'text',
                )
            ),
        );

        $meta_box[] = array(
            'title' => 'Boxes',
            'pages' => array('page'),
            'fields' => array(
                array(
                    'id' => 'boxes_id',
                    'type' => 'group',
                    'clone' => true,
                    'fields' => array(
                        array(
                            'name' => __('Icon', 'jp-basic'),
                            'id' => 'icon_img',
                            'type' => 'image_advanced',
                            'max_file_uploads' => 1,
                        ),
                        array(
                            'name' => 'Title',
                            'id' => 'title_boxes',
                            'type' => 'text',
                        ),
                        array(
                            'name' => 'Background',
                            'id' => 'bg_boxes',
                            'type' => 'text',
                        ),
                    ),
                ),
            ),
        );
    }

    if ($post_name == 'resources') {

        $meta_box[] = array(
            'title' => 'Boxes',
            'pages' => array('page'),
            'fields' => array(
                array(
                    'id' => 'boxes_r',
                    'type' => 'group',
                    'clone' => true,
                    'fields' => array(
                        array(
                            'name' => __('Icon', 'jp-basic'),
                            'id' => 'icon_img_r',
                            'type' => 'image_advanced',
                            'max_file_uploads' => 1,
                        ),
                        array(
                            'name' => 'Title',
                            'id' => 'title_boxes_r',
                            'type' => 'text',
                        )
                    ),
                ),
            ),
        );
    }

    $meta_box[] = array(
        'title' => 'Information',
        'pages' => array('team'),
        'fields' => array(
            array(
                'name' => 'Job Position',
                'id' => 'position_member',
                'type' => 'wysiwyg',
            )
        ),
    );


    $meta_box[] = array(
        'title' => 'Additional info',
        'pages' => array('team'),
        'fields' => array(
            array(
                'name' => 'Email',
                'id' => 'email_member',
                'type' => 'text',
            ),
            array(
                'name' => 'Phone',
                'id' => 'phone_member',
                'type' => 'text',
            )
        ),
    );

    if (is_array($meta_box)) {
        foreach ($meta_box as $value) {
            new RW_Meta_Box($value);
        }
    }
}

add_action('wp_ajax_rwmb_reorder_images', array("RWMB_Image_Field", 'wp_ajax_reorder_images'));
add_action('wp_ajax_rwmb_delete_file', array("RWMB_File_Field", 'wp_ajax_delete_file'));
add_action('wp_ajax_rwmb_attach_media', array("RWMB_Image_Advanced_Field", 'wp_ajax_attach_media'));
add_action('admin_init', 'rw_register_meta_box');

// Register Custom Post Type
function custom_team() {

    $labels = array(
        'name' => _x('Teams', 'Post Type General Name', 'jp-basic'),
        'singular_name' => _x('Team', 'Post Type Singular Name', 'jp-basic'),
        'menu_name' => __('Team', 'jp-basic'),
        'name_admin_bar' => __('Team', 'jp-basic'),
        'archives' => __('Item Archives', 'jp-basic'),
        'attributes' => __('Item Attributes', 'jp-basic'),
        'parent_item_colon' => __('Parent Item:', 'jp-basic'),
        'all_items' => __('All Items', 'jp-basic'),
        'add_new_item' => __('Add New Item', 'jp-basic'),
        'add_new' => __('Add New', 'jp-basic'),
        'new_item' => __('New Item', 'jp-basic'),
        'edit_item' => __('Edit Item', 'jp-basic'),
        'update_item' => __('Update Item', 'jp-basic'),
        'view_item' => __('View Item', 'jp-basic'),
        'view_items' => __('View Items', 'jp-basic'),
        'search_items' => __('Search Item', 'jp-basic'),
        'not_found' => __('Not found', 'jp-basic'),
        'not_found_in_trash' => __('Not found in Trash', 'jp-basic'),
        'featured_image' => __('Featured Image', 'jp-basic'),
        'set_featured_image' => __('Set featured image', 'jp-basic'),
        'remove_featured_image' => __('Remove featured image', 'jp-basic'),
        'use_featured_image' => __('Use as featured image', 'jp-basic'),
        'insert_into_item' => __('Insert into item', 'jp-basic'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'jp-basic'),
        'items_list' => __('Items list', 'jp-basic'),
        'items_list_navigation' => __('Items list navigation', 'jp-basic'),
        'filter_items_list' => __('Filter items list', 'jp-basic'),
    );
    $args = array(
        'label' => __('Team', 'jp-basic'),
        'description' => __('Post Type Description', 'jp-basic'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-groups',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'rewrite' => true,
        'capability_type' => 'page',
    );
    register_post_type('team', $args);
}

add_action('init', 'custom_team', 0);

// Register Custom Post Type
function custom_resources() {

    $labels = array(
        'name' => _x('Resources', 'Post Type General Name', 'jp-basic'),
        'singular_name' => _x('Resources', 'Post Type Singular Name', 'jp-basic'),
        'menu_name' => __('Resources', 'jp-basic'),
        'name_admin_bar' => __('Resources', 'jp-basic'),
        'archives' => __('Item Archives', 'jp-basic'),
        'attributes' => __('Item Attributes', 'jp-basic'),
        'parent_item_colon' => __('Parent Item:', 'jp-basic'),
        'all_items' => __('All Items', 'jp-basic'),
        'add_new_item' => __('Add New Item', 'jp-basic'),
        'add_new' => __('Add New', 'jp-basic'),
        'new_item' => __('New Item', 'jp-basic'),
        'edit_item' => __('Edit Item', 'jp-basic'),
        'update_item' => __('Update Item', 'jp-basic'),
        'view_item' => __('View Item', 'jp-basic'),
        'view_items' => __('View Items', 'jp-basic'),
        'search_items' => __('Search Item', 'jp-basic'),
        'not_found' => __('Not found', 'jp-basic'),
        'not_found_in_trash' => __('Not found in Trash', 'jp-basic'),
        'featured_image' => __('Featured Image', 'jp-basic'),
        'set_featured_image' => __('Set featured image', 'jp-basic'),
        'remove_featured_image' => __('Remove featured image', 'jp-basic'),
        'use_featured_image' => __('Use as featured image', 'jp-basic'),
        'insert_into_item' => __('Insert into item', 'jp-basic'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'jp-basic'),
        'items_list' => __('Items list', 'jp-basic'),
        'items_list_navigation' => __('Items list navigation', 'jp-basic'),
        'filter_items_list' => __('Filter items list', 'jp-basic'),
    );
    $args = array(
        'label' => __('Resources', 'jp-basic'),
        'description' => __('Post Type Description', 'jp-basic'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-cloud',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'rewrite' => true,
        'capability_type' => 'page',
    );
    register_post_type('resources', $args);
}

add_action('init', 'custom_resources', 0);
