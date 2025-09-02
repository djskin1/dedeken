<?php
/**
 * Template Name: Contact met Leaflet
 */
get_header();

$locations = get_field('locaties', 'option'); // of op pagina zelf
$markers = [];

if ($locations) {
  foreach ($locations as $loc) {
    $markers[] = [
      'lat'     => floatval($loc['latitude']),
      'lng'     => floatval($loc['longitude']),
      'title'   => $loc['locatienaam'],
      'address' => $loc['adres'],
    ];
  }
}
?>
<section class="contact-block">
  <div class="contact-left">
    <?php if ($locations): foreach ($locations as $loc): ?>
      <h2><?php echo esc_html($loc['locatienaam']); ?></h2>
      <?php if ($loc['foto']) echo wp_get_attachment_image($loc['foto']['ID'],'medium'); ?>
      <p><?php echo esc_html($loc['adres']); ?></p>
      <p><a href="tel:<?php echo preg_replace('/\s+/', '', $loc['telefoon']); ?>">
        <?php echo esc_html($loc['telefoon']); ?>
      </a></p>
      <p><a href="mailto:<?php echo antispambot($loc['email']); ?>">
        <?php echo esc_html(antispambot($loc['email'])); ?>
      </a></p>
    <?php endforeach; endif; ?>
  </div>

  <div class="contact-right">
    <?php if ($markers): ?>
      <div id="leaflet-map"
         style="width:100%;height:400px"
        data-markers='<?php echo esc_attr( wp_json_encode( $markers ) ); ?>'>
      </div>
    <?php endif; ?>

    <div class="cf7-wrap">
      <?php echo do_shortcode('[contact-form-7 id="123" title="Contact"]'); ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
