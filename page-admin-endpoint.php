<?php
/**
 * Template Name: Admin Endpoint
 */
get_header();

$parent_slug = get_post_field('post_name', wp_get_post_parent_id(get_the_ID()));
$current_slug = get_post_field('post_name', get_the_ID());

// Controleer of deze pagina echt onder /admin (of jouw ouder) hangt
if ( $parent_slug !== 'auth' ) {
    echo '<p>Misconfiguratie: deze template verwacht een subpagina van /admin.</p>';
    get_footer();
    exit;
}

// Probeer child + parent theme via locate_template
$relative = "admin/{$current_slug}.php";
$template_path = locate_template( [$relative], false, false );

if ( $template_path && file_exists($template_path) ) {
    // laad en stop daarna de rest van de WP loop
    include $template_path;
} else {
    status_header(404);
    echo '<h1>404</h1><p>Template niet gevonden: ' . esc_html($relative) . '</p>';
}

get_footer();
