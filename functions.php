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
    //instellingen
const AUTH_LOGIN_SLUG = 'inloggen';
const AUTH_REG_SLUG = 'registreren';
const AUTH_LOSTPW_SLUG = 'wachtwoord-vergeten';
const AUTH_TPL_DIR = '/admin';

//query vars
add_filter('query_vars', function ($vars) {
    $vars[] = 'custom_login';
    $vars[] = 'custom_register';
    $vars[] = 'custom_lostpw';
    return $vars;
});

//rewrite rules
add_action('init', function() {
    add_rewrite_rule('^' . AUTH_LOGIN_SLUG . '/?$', 'index.php?custom_login=1', 'top');
    add_rewrite_rule('^' . AUTH_REG_SLUG . '/?$', 'index.php?custom_register=1', 'top');
    add_rewrite_rule('^' . AUTH_LOSTPW_SLUG . '/?$', 'index.php?custom_lostpw=1', 'top');
});

//flush
add_action('after_switch_theme', function() {
    flush_rewrite_rules();
});

//laad juiste template
add_filter('template_include', function ($template) {
    $base = get_template_directory() . AUTH_TPL_DIR;

    if (get_query_var('custom_login')) {
        $tpl = $base . '/login.php';
        if (file_exists($tpl)) return $tpl;
    }
    if (get_query_var('custom_register')) {
        $tpl = $base . '/register.php';
        if (file_exists($tpl)) return $tpl;
    }
    if (get_query_var('custom_lostpw')) {
        $tpl = $base . '/lost-password.php';
        if (file_exists($tpl)) return $tpl;
    }
    return $template;
});

// handelers
 //login
add_action('admin_post_nopriv_custom_login', function() {
    check_admin_referer('custom_login_nonce');

    $creds = [
        'user_login' => sanitize_user($_POST['log'] ?? ''),
        'user_password' => $_POST['pwd'] ?? '',
        'remember' => isset($_POST['remember']),
    ];

    $user = wp_signon($creds, is_ssl());
    if (is_wp_error($user)) {
        $msg = rawurldecode($user->get_error_message());
        wp_safe_redirect(add_query_arg('auth_error', $msg, home_url('/' . AUTH_LOGIN_SLUG . '/')));
        exit; 
    }

    $redirect = !empty($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) :
    wp_safe_redirect($redirect);
    exit; 
});

//register
add_action('admin_post_nopriv_custom_register', function() {
    check_admin_referer('custom_register_nonce');

    $username = sanitize_user($_POST['user_login'] ?? '');
    $email = sanitize_email($_POST['user_email'] ?? '');
    $pass1 = $_POST['user_pass'] ?? '';
    $pass2 = $_POST['user_pass_confirm'] ?? '';

    if ($username === '' || $email === '' || $pass1 === '' || $pass1 !== $pass2 || !is_email($email)) {
        $err = rawurlencode(__('Controleer je invoer.','theme'));
        wp_safe_redirect(add_query_arg(';auth_error', $err, home_url('/' . AUTH_REG_SLUG . '/')))
        exit;
    }

    if (username_exists($username) || email_exists($email)) {
        $err = rawurlencode(__('Gebruikersnaam of e-mail bestaat al.','theme'));
        wp_safe_redirect(add_query_arg('auth_error', $err, home_url('/' . AUTH_REG_SLUG . '/')));
        exit;
    }

    $user_id = wp_create_user($username, $pass1, $email);
    if (is_wp_error($user_id)) {
        $err = rawurlencode($user_id->get_error_message());
        wp_safe_redirect(add_query_arg('auth_error', $err, home_url('/' . AUTH_REG_SLUG . '/')));
        exit;
    }

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    wp_safe_redirect(home_url());
    exit;
});

// ====== Form handlers (admin-post.php) ======


// Login handler (guest)
add_action('admin_post_nopriv_custom_login', function () {
check_admin_referer('custom_login_nonce');


$creds = [
'user_login' => isset($_POST['log']) ? sanitize_user(wp_unslash($_POST['log'])) : '',
'user_password' => isset($_POST['pwd']) ? $_POST['pwd'] : '',
'remember' => !empty($_POST['remember'])
];


$user = wp_signon($creds, is_ssl());


if (is_wp_error($user)) {
$msg = rawurlencode($user->get_error_message());
wp_safe_redirect(add_query_arg('auth_error', $msg, home_url('/' . AUTH_LOGIN_SLUG . '/')));
exit;
}


$redirect = !empty($_POST['redirect_to']) ? esc_url_raw(wp_unslash($_POST['redirect_to'])) : admin_url();
wp_safe_redirect($redirect);
exit;
});


// Registratie handler (guest)
add_action('admin_post_nopriv_custom_register', function () {
check_admin_referer('custom_register_nonce');


// Eenvoudige honeypot
if (!empty($_POST['website'])) {
wp_safe_redirect(add_query_arg('auth_error', rawurlencode(__('Ongeldige inzending.','theme')), home_url('/' . AUTH_REG_SLUG . '/')));
exit;
}


$username = isset($_POST['user_login']) ? sanitize_user(wp_unslash($_POST['user_login'])) : '';
$email = isset($_POST['user_email']) ? sanitize_email(wp_unslash($_POST['user_email'])) : '';
$pass1 = isset($_POST['user_pass']) ? $_POST['user_pass'] : '';
$pass2 = isset($_POST['user_pass_confirm']) ? $_POST['user_pass_confirm'] : '';


// Validaties
if ($username === '' || $email === '' || $pass1 === '') {
$err = rawurlencode(__('Vul alle verplichte velden in.','theme'));
wp_safe_redirect(add_query_arg('auth_error', $err, home_url('/' . AUTH_REG_SLUG . '/')));
exit;
}
if (!is_email($email)) {
$err = rawurlencode(__('Ongeldig e‑mailadres.','theme'));
wp_safe_redirect(add_query_arg('auth_error', $err, home_url('/' . AUTH_REG_SLUG . '/')));
exit;
}
if ($pass1 !== $pass2)) {
$err = rawurlencode(__('Wachtwoorden komen niet overeen.','theme'));
wp_safe_redirect(add_query_arg('auth_error', $err, home_url('/' . AUTH_REG_SLUG . '/')));
exit;
}
if (username_exists($username) || email_exists($email)) {
$err = rawurlencode(__('Gebruikersnaam of e‑mail bestaat al.','theme'));
wp_safe_redirect(add_query_arg('auth_error', $err, home_url('/' . AUTH_REG_SLUG . '/')));
exit;
}


$user_id = wp_create_user($username, $pass1, $email);


if (is_wp_error($user_id)) {
$err = rawurlencode($user_id->get_error_message());
wp_safe_redirect(add_query_arg('auth_error', $err, home_url('/' . AUTH_REG_SLUG . '/')));
exit;
}


// Automatisch inloggen na registratie (optioneel)
wp_set_current_user($user_id);
wp_set_auth_cookie($user_id);


// Redirect na registratie
$redirect = home_url('/');
wp_safe_redirect($redirect);
exit;
});


//filters
add_filter('show_admin_bar', '__return_false');
add_filter('wp_nav_menu_items', 'add_search_to_menu', 10, 2);

?>