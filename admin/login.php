<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<main class="auth auth--login">
<div class="container">
<h1>Inloggen</h1>
<?php if (isset($_GET['auth_error'])): ?>
<div class="notice notice-error"><p><?php echo wp_kses_post(wp_unslash($_GET['auth_error'])); ?></p></div>
<?php endif; ?>


<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
<p><label>Gebruikersnaam of e‑mail<br><input type="text" name="log" required></label></p>
<p><label>Wachtwoord<br><input type="password" name="pwd" required></label></p>
<p><label><input type="checkbox" name="remember" value="1"> Onthoud mij</label></p>
<?php wp_nonce_field('custom_login_nonce'); ?>
<input type="hidden" name="action" value="custom_login">
<button type="submit">Inloggen</button>
</form>


<p><a href="<?php echo esc_url(home_url('/' . AUTH_LOSTPW_SLUG . '/')); ?>">Wachtwoord vergeten?</a></p>
<p><a href="<?php echo esc_url(home_url('/' . AUTH_REG_SLUG . '/')); ?>">Account aanmaken</a></p>
</div>
</main>
<?php get_footer(); ?>