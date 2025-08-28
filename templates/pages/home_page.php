<?php

/**
 * 1) HERO bovenaan
 */
if ( have_rows('home_sections') ) :
  while ( have_rows('home_sections') ) : the_row();
    if ( get_row_layout() === 'hero' ) {
      get_template_part('templates/home/section', 'hero');
      break; // toon de eerste hero en ga verder
    }
  endwhile;
  // reset pointer om later opnieuw door de secties te kunnen loopen
  reset_rows(); // werkt binnen dezelfde query
endif;

/**
 * 2) PAGINACONTENT uit de WP-editor
 */
?>
<section class="page-content py-5">
  <div class="container">
    <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
  </div>
</section>
<?php

/**
 * 3) DIENSTEN direct na de content (als aanwezig)
 *    We zoeken expliciet naar één 'diensten' layout en tonen die hier.
 */
$diensten_rendered = false;
if ( have_rows('home_sections') ) :
  while ( have_rows('home_sections') ) : the_row();
    if ( get_row_layout() === 'diensten' ) {
      get_template_part('templates/home/section', 'diensten');
      $diensten_rendered = true;
      break;
    }
  endwhile;
  reset_rows();
endif;

/**
 * 4) DE REST van de secties, in de ACF-volgorde,
 *    behalve 'hero' (al getoond) en 'diensten' (zojuist getoond).
 */
if ( have_rows('home_sections') ) :
  while ( have_rows('home_sections') ) : the_row();

    $layout = get_row_layout();

    if ( $layout === 'hero' ) continue;
    if ( $layout === 'diensten' && $diensten_rendered ) continue;

    if ( $layout === 'cta' ) {
      get_template_part('templates/home/section', 'cta');

    } elseif ( $layout === 'featured' ) {
      get_template_part('templates/home/section', 'featured');

    } elseif ( $layout === 'text_image' ) {
      get_template_part('templates/home/section', 'text-image');

    } else {
      // fallback: onbekende/nieuwe layout → eigen partial aanmaken
      // get_template_part('templates/home/section', $layout);
    }

  endwhile;
endif;

?>
