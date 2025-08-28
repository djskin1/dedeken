<h1>Wachtwoord vergeten</h1>
<?php
if ( isset($_GET['lost']) ) {
    $msgs = [
        'sent'     => 'E-mail verstuurd (check je inbox).',
        'mailfail' => 'Er ging iets mis met het verzenden.',
        'notfound' => 'Gebruiker niet gevonden.',
        'empty'    => 'Vul een gebruikersnaam of e-mail in.',
        'error'    => 'Onbekende fout.'
    ];
    if ( isset($msgs[$_GET['lost']]) ) echo '<p class="notice">' . esc_html($msgs[$_GET['lost']]) . '</p>';
}
?>
<form method="post" action="<?php echo esc_url( home_url('/admin/lost-password') ); ?>">
    <?php wp_nonce_field('custom_lostpassword_nonce'); ?>
    <p><label>Gebruikersnaam of e-mail<br><input type="text" name="user_login" required></label></p>
    <input type="hidden" name="action" value="custom_lostpassword">
    <p><button type="submit">Stuur reset-link</button></p>
</form>
