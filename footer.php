    </main>
    <footer class="site-footer bg-teal text-white py-4">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> <a href="<?php site_url(); ?>"> de Deken</a>. All rights reserved en Layout by <a href="https://djskinproductions.nl">djskinproductions</a></p>
        </div>
        <?php if (has_nav_menu('footer')) : ?>
            <?php wp_nav_menu([
                'theme_location' => 'footer', 
                'container' => false,
                'menu_class' => 'footer-nav',
                'depth' => 1,
                'fallback_cb' => false,
            ]); ?>
        <?php endif; ?>
    </footer>
</body>
</html>