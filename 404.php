<?php get_header(); ?>

<div class="container" style="text-align: center; padding: 80px 20px;">
    <h1 style="font-size: 3rem; color:var(--ddk-orange);">404</h1>
    <p>Oeps... de Pagina die je zoek bestaat niet.</p>
    <a href="<?php echo esc_url('/'); ?>" class="btn">Terug naar Home</a>
</div>

<div style ="margin: 20px; max-width: 400px; margin-left: auto; margin-right: auto;">
    <?php get_search_form(); ?>
</div>

<?php get_footer(); ?>