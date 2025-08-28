<?php
/**
 * Template Name: Contact (ACF + CF7)
 */
get_header();

$locations = function_exists('get_field') ? get_field('locations', 'option') : [];
$default_cf7 = function_exists('get_field') ? get_field('default_cf7_shortcode', 'option') : '';

if (empty($locations)) {
    echo '<p>Er zijn nog geen locaties geconfigureerd. Voeg ze toe via <em>Contact instellingen</em> in de WP-admin.</p>';
    get_footer(); exit;
}

foreach ($locations as $loc) :
    // Veilig uitlezen:
    $title   = isset($loc['title']) ? $loc['title'] : '';
    $photo   = isset($loc['photo']) ? $loc['photo'] : null;
    $address = isset($loc['address']) ? $loc['address'] : '';
    $phone   = isset($loc['phone']) ? $loc['phone'] : '';
    $email   = isset($loc['email']) ? $loc['email'] : '';
    $map     = isset($loc['map']) && is_array($loc['map']) ? $loc['map'] : null; // ['lat','lng','address']
    $cf7     = !empty($loc['cf7_shortcode']) ? $loc['cf7_shortcode'] : $default_cf7;
?>
<section class="contact-block">
  <div class="contact-left">
    <?php if ($photo && isset($photo['ID'])) : ?>
      <?php echo wp_get_attachment_image($photo['ID'], 'large', false, ['class'=>'company-photo','alt'=>esc_attr($title ?: get_bloginfo('name'))]); ?>
    <?php endif; ?>

    <div class="company-details">
      <?php if ($title): ?>
        <h2><?php echo esc_html($title); ?></h2>
      <?php endif; ?>

      <?php if ($address): ?>
        <div><span class="label">Adres:</span><br><?php echo wp_kses_post(nl2br($address)); ?></div>
      <?php endif; ?>

      <?php if ($phone): ?>
        <div><span class="label">Telefoon:</span><br><a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></div>
      <?php endif; ?>

      <?php if ($email): ?>
        <div><span class="label">E-mail:</span><br><a href="mailto:<?php echo antispambot($email); ?>"><?php echo esc_html(antispambot($email)); ?></a></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="contact-right">
    <?php if ($map && isset($map['lat'], $map['lng'])): ?>
      <div class="map-wrap">
        <div class="acf-location-map" data-lat="<?php echo esc_attr($map['lat']); ?>" data-lng="<?php echo esc_attr($map['lng']); ?>" style="width:100%;height:100%"></div>
      </div>
    <?php endif; ?>

    <?php if (!empty($cf7)) : ?>
      <div class="cf7-wrap">
        <?php echo do_shortcode( $cf7 ); ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php endforeach; ?>

<?php get_footer(); ?>
