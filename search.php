<?php get_header(); ?>

<div class="container">
  <h1>Zoekresultaten voor: "<?php echo get_search_query(); ?>"</h1>

  <?php if ( have_posts() ) : ?>
    <ul class="search-results">
      <?php while ( have_posts() ) : the_post(); ?>
        <li>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else : ?>
    <p>Geen resultaten gevonden. Probeer een andere zoekopdracht.</p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>