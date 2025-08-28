<?php
// Filters uit query
$filter_dep = isset($_GET['dep']) ? sanitize_text_field($_GET['dep']) : '';
$filter_loc = isset($_GET['loc']) ? sanitize_text_field($_GET['loc']) : '';

// Team ophalen uit options
$team = get_field('team_members', 'option') ?: [];

// Sorteer: featured eerst, dan order, dan naam
usort($team, function($a, $b){
  $fa = !empty($a['featured']); $fb = !empty($b['featured']);
  if ($fa !== $fb) return $fa ? -1 : 1;
  $oa = (int)($a['order'] ?? 100); $ob = (int)($b['order'] ?? 100);
  if ($oa !== $ob) return $oa <=> $ob;
  return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
});

// Verzamel unieke afdelingen/locaties voor filters
$departments = []; $locations = [];
foreach ($team as $m) {
  $dep = $m['department'] ?? '';
  if (is_array($dep)) { foreach ($dep as $d) { if ($d) $departments[$d] = true; } }
  elseif ($dep) { $departments[$dep] = true; }
  $loc = trim($m['location'] ?? '');
  if ($loc) $locations[$loc] = true;
}
ksort($departments, SORT_NATURAL|SORT_FLAG_CASE);
ksort($locations, SORT_NATURAL|SORT_FLAG_CASE);

// Filter de lijst
$filtered = array_filter($team, function($m) use ($filter_dep, $filter_loc){
  $ok = true;
  if ($filter_dep) {
    $dep = $m['department'] ?? '';
    $has = is_array($dep) ? in_array($filter_dep, $dep, true) : ($dep === $filter_dep);
    $ok = $ok && $has;
  }
  if ($filter_loc) {
    $ok = $ok && (trim($m['location'] ?? '') === $filter_loc);
  }
  return $ok;
});
?>

<section class="team-intro py-5">
  <div class="container text-center">
    <h1><?php the_title(); ?></h1>
    <div class="lead"><?php the_content(); ?></div>
  </div>
</section>

<section class="team-filters py-3">
  <div class="container">
    <form class="team-filter-form" method="get" action="">
      <div class="filters-row">
        <label>
          Afdeling:
          <select name="dep">
            <option value="">Alle</option>
            <?php foreach (array_keys($departments) as $d): ?>
              <option value="<?php echo esc_attr($d); ?>" <?php selected($filter_dep, $d); ?>>
                <?php echo esc_html($d); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>

        <label>
          Locatie:
          <select name="loc">
            <option value="">Alle</option>
            <?php foreach (array_keys($locations) as $l): ?>
              <option value="<?php echo esc_attr($l); ?>" <?php selected($filter_loc, $l); ?>>
                <?php echo esc_html($l); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>

        <button class="btn filter-submit" type="submit">Filter</button>
        <?php if ($filter_dep || $filter_loc): ?>
          <a class="btn btn-reset" href="<?php echo esc_url(get_permalink()); ?>">Reset</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</section>

<section class="team-grid-wrap py-4">
  <div class="container">
    <?php if (empty($filtered)) : ?>
      <p><em>Geen teamleden gevonden voor deze selectie.</em></p>
    <?php else: ?>
      <div class="team-grid">
        <?php foreach ($filtered as $m): 
          $photo = $m['photo'] ?? null;
          $name  = $m['name']  ?? '';
          $role  = $m['role']  ?? '';
          $dep   = $m['department'] ?? '';
          $loc   = $m['location'] ?? '';
          $email = $m['email'] ?? '';
          $phone = $m['phone'] ?? '';
          $bio   = $m['bio'] ?? '';
          $lnk   = $m['socials']['linkedin'] ?? '';
          $ig    = $m['socials']['instagram'] ?? '';
        ?>
        <article class="team-card">
          <div class="team-photo">
            <?php if ($photo && !empty($photo['url'])): ?>
              <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($name); ?>">
            <?php else: ?>
              <div class="no-photo" aria-hidden="true"><?php echo strtoupper(mb_substr($name,0,1)); ?></div>
            <?php endif; ?>
          </div>

          <h3 class="team-name"><?php echo esc_html($name); ?></h3>
          <?php if ($role): ?><p class="team-role"><?php echo esc_html($role); ?></p><?php endif; ?>

          <ul class="team-meta">
            <?php if ($dep): ?>
              <li><span class="label">Afdeling:</span>
                <span class="value">
                <?php echo is_array($dep) ? esc_html(implode(', ', $dep)) : esc_html($dep); ?>
                </span>
              </li>
            <?php endif; ?>
            <?php if ($loc): ?><li><span class="label">Locatie:</span> <span class="value"><?php echo esc_html($loc); ?></span></li><?php endif; ?>
            <?php if ($email): ?><li><a href="mailto:<?php echo antispambot($email); ?>">E-mail</a></li><?php endif; ?>
            <?php if ($phone): ?><li><a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>">Bel</a></li><?php endif; ?>
          </ul>

          <?php if ($bio): ?><p class="team-bio"><?php echo esc_html($bio); ?></p><?php endif; ?>

          <?php
			// Globale socials uit Site Instellingen > Ons team > Socials (ACF Repeater: 'socials')
			if ( function_exists('have_rows') && have_rows('socials', 'option') ) : ?>
  			<div class="team-socials">
    			<?php while ( have_rows('socials', 'option') ) : the_row();
      				$url      = esc_url( get_sub_field('url') );                 // bv. https://linkedin.com/...
      				$network  = strtolower( trim( (string) get_sub_field('network') ) ); // bv. linkedin, instagram, facebook, x, tiktok
      				$icon_cls = trim( (string) get_sub_field('icon_class') );    // bv. fab fa-linkedin-in

      				if ( ! $url ) continue;

      				// Bepaal label & icon
      				$label = $network ? ucfirst($network) : 'Social';
      				if ( ! $icon_cls ) {
        				// eenvoudige fallback op basis van 'network'
        				switch ($network) {
          					case 'linkedin': $icon_cls = 'fab fa-linkedin-in'; break;
          					case 'instagram': $icon_cls = 'fab fa-instagram'; break;
          					case 'facebook': $icon_cls = 'fab fa-facebook-f'; break;
          					case 'x': $icon_cls = 'fab fa-x-twitter'; break;
          					case 'tiktok': $icon_cls = 'fab fa-tiktok'; break;
          					case 'youtube': $icon_cls = 'fab fa-youtube'; break;
          					default: $icon_cls = ''; // toon letters als we niks herkennen
        				}
      				}

      				$icon_html = $icon_cls
        			? '<i class="'.esc_attr($icon_cls).'"></i>'
        			: '<span class="abbr">'.esc_html(strtoupper(mb_substr($network ?: 'So', 0, 2))).'</span>';
      			?>
      		<a href="<?php echo $url; ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr($label); ?>">
        		<?php echo $icon_html; ?>
      		</a>
    	<?php endwhile; ?>
  	</div>
	<?php endif; ?>
        </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
