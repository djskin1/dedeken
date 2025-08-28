<?php get_header(); 
$loc      = get_field('locatie');
$dienst   = get_field('dienstverband');
$uren     = get_field('uren');
$salaris  = get_field('salaris');
$sluit    = get_field('sluitingsdatum');
$apply    = get_field('apply_url');
$contact  = get_field('contactpersoon');
?>

<section class="job-hero py-5" style="background:#274D4D;color:#fff;">
  <div class="container">
    <h1 class="mb-1"><?php the_title(); ?></h1>
    <ul class="job-hero-meta">
      <?php if($loc): ?><li>📍 <?php echo esc_html($loc); ?></li><?php endif; ?>
      <?php if($dienst): ?><li>⏱ <?php echo esc_html(ucfirst($dienst)); ?></li><?php endif; ?>
      <?php if($uren): ?><li>🗓 <?php echo esc_html($uren); ?> u/w</li><?php endif; ?>
      <?php if($sluit): ?><li>⏳ Sluit: <?php echo esc_html($sluit); ?></li><?php endif; ?>
    </ul>
    <?php if($apply): ?>
      <a class="btn btn-lg btn-apply" href="<?php echo esc_url($apply); ?>">Solliciteer direct</a>
    <?php endif; ?>
  </div>
</section>

<section class="job-content py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8">
        <?php the_content(); ?>
      </div>
      <aside class="col-lg-4">
        <div class="job-side">
          <?php if($salaris): ?><p><strong>Salaris:</strong> <?php echo esc_html($salaris); ?></p><?php endif; ?>
          <?php if(!empty($contact['naam'])): ?>
            <div class="job-contact">
              <h5>Contactpersoon</h5>
              <p><strong><?php echo esc_html($contact['naam']); ?></strong><br>
              <?php if(!empty($contact['email'])): ?>
                <a href="mailto:<?php echo antispambot($contact['email']); ?>">E-mail</a><br>
              <?php endif; ?>
              <?php if(!empty($contact['telefoon'])): ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $contact['telefoon'])); ?>">Bel</a>
              <?php endif; ?>
              </p>
            </div>
          <?php endif; ?>
          <section class="job-apply-form py-5">
            <div class="container">
              <h2>Solliciteer direct</h2>
                <?php
                  // WPForms shortcode met hidden field voor vacaturetitel
                  echo do_shortcode('[contact-form-7 id="7d6083c" title="job"]');
                ?>
            </div>
          </section>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php get_footer(); ?>
