<?php

?>
<section class="about-intro py-5">
    <div class="container text-center">
        <h1><?php the_title(); ?></h1>
        <div class="intro-text>">
            <?php the_content(); ?>
        </div>
    </div>
</section>

<?php if (have_rows('about_history')): ?>
<section class="about-history py-5 bg-light">
    <div class="container">
        <h2 class="mb-4">Onze Geschiedenis</h2>
        <div class="timeline">
            <?php while (have_rows('about_history')) : the_row(); 
                $year   = trim(get_sub_field('year'));
                $head   = get_sub_field('heading');
                $text   = get_sub_field('text');
                $img    = get_sub_field('image');
                $badge  = get_sub_field('badge_color') ?: '#E95C2D';
                $side   = get_sub_field('side') ?: 'auto';
                if ($side === 'auto') { $side = ($i % 2 === 0) ? 'left' : 'right'; }
                $i++;
            ?>
            <article class="timeline-item timeline-item--<?php echo esc_attr($side); ?>">
                <div class="timeline-badge" style="background:<?php echo esc_attr($badge); ?>;">
                    <?php echo esc_html($year ?: '—'); ?>
                </div>

                <div class="timeline-card">
                    <?php if ($img && !empty($img['url'])): ?>
                    <div class="timeline-media">
                        <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt'] ?: ($head ?: 'Geschiedenis')); ?>">
                    </div>
                    <?php endif; ?>

                    <?php if ($head): ?><h3 class="timeline-title"><?php echo esc_html($head); ?></h3><?php endif; ?>
                    <?php if ($text): ?><p class="timeline-text"><?php echo esc_html($text); ?></p><?php endif; ?>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (have_rows('about_usps')): ?>
    <section class="about-usps py-5">
        <div class="container text-center">
            <div class="row">
                <?php while (have_rows(about_usps)) : the_row(); ?>
                <div class="col-md-3 mb-4">
                    <?php $icon = get_sub_field('icon'); if ($icon): ?>
                    <img src="<?php echo esc_url($icon['url']);?>" alt="" class="mb-3 icon">
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>