<?php
// ACF velden
$title      = get_sub_field('hero_title');
$subtitle   = get_sub_field('hero_subtitle');
$btn_text   = get_sub_field('hero_btn_text');
$btn_url    = get_sub_field('hero_btn_url');
$hero_logo  = get_sub_field('hero_logo');
$hero_image = get_sub_field('hero_image');

// hero_overlay veld = achtergrondkleur
$bg_color   = get_sub_field('hero_overlay') ?: '#274D4D'; // fallback
?>
<section class="home-hero py-5" style="background: <?php echo esc_attr($bg_color); ?>; color:#fff;">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8">
        
        <?php if($hero_logo): ?>
          <div class="hero-brand mb-3">
            <img src="<?php echo esc_url($hero_logo['url']); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="hero-logo">
          </div>
        <?php endif; ?>

        <?php if($hero_image): ?>
          <div class="hero-img mb-4">
            <img src="<?php echo esc_url($hero_image['url']); ?>" alt="<?php echo esc_attr($title ?: 'Hero afbeelding'); ?>" class="img-fluid hero-image">
          </div>
        <?php endif; ?>

        <?php if($title): ?><h1 class="mb-2"><?php echo esc_html($title); ?></h1><?php endif; ?>
        <?php if($subtitle): ?><p class="lead mb-4"><?php echo esc_html($subtitle); ?></p><?php endif; ?>

        <?php if($btn_text && $btn_url): ?>
          <a class="btn btn-lg hero-cta" href="<?php echo esc_url($btn_url); ?>">
            <?php echo esc_html($btn_text); ?>
          </a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>
