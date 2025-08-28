<?php
$bg      = get_sub_field('bg');
$title   = get_sub_field('title');
$btn_txt = get_sub_field('btn_text');
$btn_url = get_sub_field('btn_url');
$bg_url  = $bg ? esc_url($bg['url']) : '';
?>
<section class="home-cta" style="position:relative;min-height:40vh;">
  <?php if($bg_url): ?>
    <div style="position:absolute;inset:0;background:url('<?php echo $bg_url; ?>') center/cover;"></div>
  <?php endif; ?>
  <div style="position:absolute;inset:0;background:#274D4D;opacity:.65;"></div>
  <div class="container" style="position:relative;z-index:1;color:#fff;padding:60px 0;text-align:center;">
    <?php if($title): ?><h2 class="mb-3"><?php echo esc_html($title); ?></h2><?php endif; ?>
    <?php if($btn_txt && $btn_url): ?>
      <a class="btn btn-lg" style="background:#E95C2D;color:#fff;border-radius:10px" href="<?php echo esc_url($btn_url); ?>"><?php echo esc_html($btn_txt); ?></a>
    <?php endif; ?>
  </div>
</section>
