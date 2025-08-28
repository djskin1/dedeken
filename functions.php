<?php
// Theme bootstrap file

require_once get_template_directory() . '/inc/setup.php';

//assets

add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'ddk-style', get_stylesheet_uri(), [], wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style('ddk-main', get_template_directory_uri() . '/assets/css/main.css', ['ddk-style'], null);
    wp_enqueue_script('ddk-scripts', get_template_directory_uri() . '/assets/js/main.js', [], null, true);
});

//boodstrap
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css', [], null);
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
});

//fonts
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@400;500&display=swap', [], null);
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', [], '6.5.1');
});

//elementor

add_action('after_setup_theme', function() {
    //basis
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    register_nav_menus([
        'primary' => __('Primary Menu', 'ddk'),
        'footer'  => __('Footer Menu', 'ddk'),
    ]);
});

//search
function add_search_to_menu($items, $args) {
    if ($args->theme_location == 'primary') {
        $search_form = '<li class="nav-item search-item">' . get_search_form(false) . '</li>';
        $items .= $search_form;
    }
    return $items;
}

//site settings
if ( function_exists('acf_add_options_page') ) {
    acf_add_options_page([
        'page_title'  => 'Site Instellingen',
        'menu_title'  => 'Site Instellingen',
        'menu_slug'   => 'site-instellingen',
        'capability'  => 'manage_options',
        'redirect'    => false,
    ]);
}

//vacatures
add_action('init', function() {
    register_post_type('vacature', [
        'label' => 'Vacatures',
        'labels' => [
            'name' => 'vacatures',
            'singular_name' => 'vacature',
            'add_new' => 'Nieuwe vacature',
            'add_new_item' => 'Nieuwe vacature toevoegen',
            'edit_item' => 'Vacature bewerken',
            'new_item' => 'Nieuwe vacature',
            'view_item' => 'Bekijk vacature',
            'search_items' => 'Zoek vacatures',
            'not_found' => 'Geen vacatures gevonden',
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'vacature'],
        'menu_position' => 20,
        'menu_icon' => 'dashicons-id',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => false,
    ]);
});

//admin

//filters
add_filter('show_admin_bar', '__return_false');
add_filter('wp_nav_menu_items', 'add_search_to_menu', 10, 2);

?>