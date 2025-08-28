<?php if ( is_user_logged_in() ) { wp_safe_redirect( admin_url() ); exit; } ?>
<h1>Inloggen</h1>
<?php if ( isset($_GET['login']) && $_GET['login']==='failed' ) echo '<p class="err">Onjuiste gegevens.</p>'; ?>
<form method="post" action="<?php echo esc_url( home_url('/admin/login') ); ?>">
    <?php wp_nonce_field('custom_login_nonce'); ?>
    <p><label>Gebruikersnaam of e-mail<br><input type="text" name="log" required></label></p>
    <p><label>Wachtwoord<br><input type="password" name="pwd" required></label></p>
    <p><label><input type="checkbox" name="rememberme" value="1"> Onthoud mij</label></p>
    <input type="hidden" name="action" value="custom_login">
    <p><button type="submit">Inloggen</button></p>
    <p><a href="<?php echo esc_url( home_url('/admin/lost-password') ); ?>">Wachtwoord vergeten?</a></p>
</form>
