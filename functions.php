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

//Page Header
function mijn_theme_customize_register( $wp_customize ) {
    // Instelling toevoegen
    $wp_customize->add_setting( 'header_logo_text', array(
        'default'   => 'De Deken & Zn B.V.',
        'transport' => 'refresh',
    ));

    // Control toevoegen
    $wp_customize->add_control( 'header_logo_text_control', array(
        'label'    => __( 'Fallback Logo Tekst', 'mijn-theme' ),
        'section'  => 'title_tagline', // zelfde sectie als Site Identity
        'settings' => 'header_logo_text',
        'type'     => 'text',
    ));
}
add_action( 'customize_register', 'mijn_theme_customize_register' );

/* Menu */
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
add_action('init', function () {
    // Login
    if ( isset($_POST['action']) && $_POST['action'] === 'custom_login' ) {
        check_admin_referer('custom_login_nonce');

        $creds = array(
            'user_login'    => sanitize_text_field($_POST['log'] ?? ''),
            'user_password' => $_POST['pwd'] ?? '',
            'remember'      => !empty($_POST['rememberme']),
        );

        $user = wp_signon($creds, is_ssl());
        if ( is_wp_error($user) ) {
            wp_safe_redirect( add_query_arg('login', 'failed', home_url('auth/login')) );
        } else {
            // Naar dashboard of gewenste pagina
            wp_safe_redirect( apply_filters('custom_login_redirect', admin_url(), $user) );
        }
        exit;
    }

    // Register
    if ( isset($_POST['action']) && $_POST['action'] === 'custom_register' ) {
        check_admin_referer('custom_register_nonce');

        $username = sanitize_user($_POST['user_login'] ?? '');
        $email    = sanitize_email($_POST['user_email'] ?? '');
        $pass1    = $_POST['user_pass'] ?? '';
        $pass2    = $_POST['user_pass_confirm'] ?? '';

        if ( empty($username) || empty($email) || empty($pass1) || $pass1 !== $pass2 ) {
            wp_safe_redirect( add_query_arg('register', 'invalid', home_url('auth/register')) ); exit;
        }

        $user_id = wp_create_user($username, $pass1, $email);
        if ( is_wp_error($user_id) ) {
            wp_safe_redirect( add_query_arg('register', 'failed', home_url('auth/register')) ); exit;
        }

        // (Optioneel) auto-login na registratie
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);
        wp_safe_redirect( home_url('/') ); exit;
    }

    // Lost password (verstuur reset e-mail)
    if ( isset($_POST['action']) && $_POST['action'] === 'custom_lostpassword' ) {
        check_admin_referer('custom_lostpassword_nonce');

        $login_or_email = sanitize_text_field($_POST['user_login'] ?? '');
        if ( empty($login_or_email) ) {
            wp_safe_redirect( add_query_arg('lost', 'empty', home_url('auth/lost-password.php')) ); exit;
        }

        // Laat WP de mail & key genereren
        $user = get_user_by(str_contains($login_or_email, '@') ? 'email' : 'login', $login_or_email);
        if ( !$user ) {
            wp_safe_redirect( add_query_arg('lost', 'notfound', home_url('auth/lost-password.php')) ); exit;
        }

        $key = get_password_reset_key($user);
        if ( is_wp_error($key) ) {
            wp_safe_redirect( add_query_arg('lost', 'error', home_url('auth/lost-password.php')) ); exit;
        }

        // Bouw je eigen e-mail met een link naar je custom reset pagina
        $reset_url = add_query_arg(array(
            'key'   => rawurlencode($key),
            'login' => rawurlencode($user->user_login),
        ), home_url('auth/reset-password'));

        $sent = wp_mail(
            $user->user_email,
            sprintf('[%s] Wachtwoord resetten', wp_specialchars_decode(get_option('blogname'), ENT_QUOTES)),
            "Hallo,\n\nKlik op onderstaande link om je wachtwoord te resetten:\n\n{$reset_url}\n\nAls je dit niet hebt aangevraagd, kun je deze mail negeren."
        );

        wp_safe_redirect( add_query_arg('lost', $sent ? 'sent' : 'mailfail', home_url('auth/lost-password.php')) );
        exit;
    }

    // Reset password (via link met key + login)
    if ( isset($_POST['action']) && $_POST['action'] === 'custom_resetpassword' ) {
        check_admin_referer('custom_resetpassword_nonce');

        $login = sanitize_user($_POST['rp_login'] ?? '');
        $key   = sanitize_text_field($_POST['rp_key'] ?? '');
        $pass1 = $_POST['pass1'] ?? '';
        $pass2 = $_POST['pass2'] ?? '';

        if ( empty($login) || empty($key) || empty($pass1) || $pass1 !== $pass2 ) {
            wp_safe_redirect( add_query_arg(array('rp' => 'invalid'), home_url('auth/reset-password.php')) ); exit;
        }

        $user = check_password_reset_key($key, $login);
        if ( is_wp_error($user) ) {
            wp_safe_redirect( add_query_arg(array('rp' => 'badkey'), home_url('auth/reset-password.php')) ); exit;
        }

        reset_password($user, $pass1);

        // Klaar → naar login met melding
        wp_safe_redirect( add_query_arg('reset', 'done', home_url('auth/login')) ); exit;
    }
});

add_action('template_redirect', function () {
    $req = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    if ($req === 'garagebeheer') { // of jouw WPS slug
        wp_safe_redirect( home_url('auth/login') );
        exit;
    }
});

//contact

// 1) ACF Options Page
if ( function_exists('acf_add_options_page') ) {
    acf_add_options_page([
        'page_title' => 'Contact instellingen',
        'menu_title' => 'Contact instellingen',
        'menu_slug'  => 'contact-instellingen',
        'capability' => 'manage_options',
        'redirect'   => false,
        'position'   => 61,
        'icon_url'   => 'dashicons-location-alt',
    ]);
}

add_action('wp_enqueue_scripts', function () {
    // Leaflet CSS + JS (CDN)
    wp_enqueue_style(
        'leaflet-css',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
        [],
        '1.9.4'
    );
    wp_enqueue_script(
        'leaflet-js',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
        [],
        '1.9.4',
        true
    );

    // Jouw init script
    wp_enqueue_script(
        'leaflet-init',
        get_stylesheet_directory_uri() . '/assets/js/leaflet-init.js',
        ['leaflet-js'],
        '1.0',
        true
    );

    // (optioneel) basis CSS
    wp_add_inline_style('leaflet-css', '
      #leaflet-map{width:100%;height:400px}
      .leaflet-container{z-index:1}
    ');
});


//filters
add_filter('show_admin_bar', '__return_false');
add_filter('wp_nav_menu_items', 'add_search_to_menu', 10, 2);
add_filter('login_url', function($url, $redirect){ return add_query_arg('redirect_to', $redirect, home_url('auth/login')); }, 10, 2);
add_filter('lostpassword_url', function($url, $redirect){ return home_url('auth/lost-password'); }, 10, 2);
add_filter('register_url', function($url){ return home_url('auth/register'); }, 10, 1);


?>