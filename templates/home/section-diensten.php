<section class="home-diensten py-5">
  <div class="container">
    <div class="row g-4">
      <?php if ( have_rows('items') ) : while ( have_rows('items') ) : the_row(); 
        $icon  = get_sub_field('icon'); // kan image of FA class zijn
        $title = get_sub_field('title');
        $desc  = get_sub_field('desc');
        $url   = get_sub_field('url');
      ?>
        <div class="col-12 col-md-6 col-lg-3">
          <div class="dienst-card p-4 h-100" style="background: #016431; color:#fff; border-radius:12px;">
            <div class="dienst-icon mb-3">
              <?php 
              if (is_array($icon) && !empty($icon['url'])) {
                echo '<img src="'.esc_url($icon['url']).'" alt="" style="height:100px;width:auto;">';
              } elseif (is_string($icon) && $icon) {
                echo '<i class="'.esc_attr($icon).'" style="font-size:42px;color:#274D4D;"></i>';
              }
              ?>
            </div>
            <?php if($title): ?><h2 class="h2 mb-2"><?php echo esc_html($title); ?></h3><?php endif; ?>
            <?php if($desc): ?><p class="mb-3"><?php echo esc_html($desc); ?></p><?php endif; ?>
            <?php if($url): ?><a class="btn btn-sm" style="background:#E95C2D;color:#fff;border-radius:8px" href="<?php echo esc_url($url); ?>">Lees meer</a><?php endif; ?>
          </div>
        </div>
      <?php endwhile; endif; ?>
    </div>
  </div>
</section>
