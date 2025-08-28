<?php ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
   
  <header class="site-header bg-teal text-white py-2">
      <nav class="navbar navbar-expand-lg navbar-dark" style="background:#274D4D;">
        <div class="container d-flex align-items-center justify-content-between">
           <a href="<?php echo esc_url(home_url('/')); ?>" class="logo>
        <?php if(has_custom_logo()) { the_custom_logo(); } else { ?>
          <strong>De Deken &amp; Zn B.V.</strong>
        <?php } ?>
      </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="mainNav">
            <?php

            $walker = class_exists('WP_Bootstrap_Navwalker') ? new WP_Bootstrap_Navwalker() : null;
              
              wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
                'fallback_cb'    => '__return_false',
                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                'depth'          => 2,
                'walker'         => $walker // optioneel: gebruik een walker voor dropdowns
              ]);
            ?>
          </div>
        </div>
        <?php if ( function_exists('have_rows') && have_rows('social_links', 'option') ) : ?>
  <div class="header-social d-flex align-items-center ms-3">
    <?php while ( have_rows('social_links', 'option') ) : the_row();
      $show  = get_sub_field('show');
      if ($show === false) continue;
      $url   = esc_url( get_sub_field('url') );
      $icon  = esc_attr( get_sub_field('icon_class') );
      $label = esc_html( get_sub_field('platform') ?: 'Social' );
      if ( ! $url || ! $icon ) continue;
    ?>
      <a href="<?php echo $url; ?>" class="social-link" target="_blank" rel="noopener" aria-label="<?php echo $label; ?>">
        <i class="<?php echo $icon; ?>"></i>
      </a>
    <?php endwhile; ?>
  </div>
<?php endif; ?>
      </nav>
  </header>
  <main id="content">
