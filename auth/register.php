<h1>Registreren</h1>
<?php if ( isset($_GET['register']) && $_GET['register']!=='ok' ) echo '<p class="err">Controleer je invoer.</p>'; ?>
<form method="post" action="<?php echo esc_url( home_url('/admin/register') ); ?>">
    <?php wp_nonce_field('custom_register_nonce'); ?>
    <p><label>Gebruikersnaam<br><input type="text" name="user_login" required></label></p>
    <p><label>E-mail<br><input type="email" name="user_email" required></label></p>
    <p><label>Wachtwoord<br><input type="password" name="user_pass" required></label></p>
    <p><label>Herhaal wachtwoord<br><input type="password" name="user_pass_confirm" required></label></p>
    <input type="hidden" name="action" value="custom_register">
    <p><button type="submit">Account aanmaken</button></p>
</form>
