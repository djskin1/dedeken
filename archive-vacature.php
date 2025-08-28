<?php get_header(); 

// Filters
$filter_dienst = isset($_GET['dienst']) ? sanitize_text_field($_GET['dienst']) : '';
$filter_loc    = isset($_GET['loc']) ? sanitize_text_field($_GET['loc']) : '';

// Query args
$args = [
  'post_type' => 'vacature',
  'posts_per_page' => 12,
  'paged' => max(1, get_query_var('paged', 1)),
  'meta_query' => [],
];

// Filter dienstverband
if ($filter_dienst) {
  $args['meta_query'][] = [
    'key' => 'dienstverband',
    'value' => $filter_dienst,
    'compare' => '='
  ];
}
// Filter locatie (bevat)
if ($filter_loc) {
  $args['meta_query'][] = [
    'key' => 'locatie',
    'value' => $filter_loc,
    'compare' => 'LIKE'
  ];
}

$q = new WP_Query($args);
?>

<section class="jobs-hero py-5 text-center" style="background:#274D4D;color:#fff;">
  <div class="container">
    <h1 class="mb-2">Vacatures</h1>
    <p class="lead">Sluit je aan bij De Deken &amp; Zn B.V.</p>
  </div>
</section>

<section class="jobs-filters py-3">
  <div class="container">
    <form class="jobs-filter-form" method="get" action="">
      <div class="filters-row">
        <label>Dienstverband
          <select name="dienst">
            <option value="">Alle</option>
            <option value="fulltime" <?php selected($filter_dienst,'fulltime'); ?>>Fulltime</option>
            <option value="parttime" <?php selected($filter_dienst,'parttime'); ?>>Parttime</option>
            <option value="stage"    <?php selected($filter_dienst,'stage'); ?>>Stage</option>
            <option value="bbL"      <?php selected($filter_dienst,'bbL'); ?>>BBL</option>
          </select>
        </label>
        <label>Locatie
          <input type="text" name="loc" value="<?php echo esc_attr($filter_loc); ?>" placeholder="Bijv. Goes">
        </label>
        <button class="btn" type="submit">Filter</button>
        <?php if ($filter_dienst || $filter_loc): ?>
          <a class="btn btn-reset" href="<?php echo esc_url(get_post_type_archive_link('vacature')); ?>">Reset</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</section>

<section class="jobs-grid py-4">
  <div class="container">
    <?php if ($q->have_posts()): ?>
      <div class="row g-4">
        <?php while ($q->have_posts()): $q->the_post(); 
          $loc = get_field('locatie');
          $dienst = get_field('dienstverband');
          $uren = get_field('uren');
          $salaris = get_field('salaris');
        ?>
        <div class="col-12 col-md-6 col-lg-4">
          <article class="job-card h-100">
            <div class="job-card__body">
              <h3 class="h5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <?php if($loc): ?><p class="job-meta"><strong>Locatie:</strong> <?php echo esc_html($loc); ?></p><?php endif; ?>
              <ul class="job-tags">
                <?php if($dienst): ?><li><?php echo esc_html(ucfirst($dienst)); ?></li><?php endif; ?>
                <?php if($uren): ?><li><?php echo esc_html($uren); ?> u/w</li><?php endif; ?>
                <?php if($salaris): ?><li><?php echo esc_html($salaris); ?></li><?php endif; ?>
              </ul>
              <p class="job-excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
            </div>
            <div class="job-card__actions">
              <a class="btn btn-apply" href="<?php the_permalink(); ?>">Bekijk vacature</a>
            </div>
          </article>
        </div>
        <?php endwhile; ?>
      </div>

      <div class="jobs-pagination mt-4">
        <?php
          echo paginate_links([
            'total' => $q->max_num_pages,
            'current' => max(1, get_query_var('paged', 1))
          ]);
        ?>
      </div>
    <?php else: ?>
      <p><em>Geen vacatures gevonden.</em></p>
    <?php endif; wp_reset_postdata(); ?>
  </div>
</section>

<?php get_footer(); ?>
