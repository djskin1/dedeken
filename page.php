<?php get_header();

$layout = get_field('page_layout') ?: 'standaard';

switch ($layout) {

    case 'home':
        include locate_template('templates/pages/home_page.php');
        break;
    
    case 'about':
        include locate_template('templates/pages/about_page.php');
        break;
    
    case 'team':
        include locate_template('templates/pages/team_page.php');
        break;

    case 'contact':
        include locate_template('templates/pages/contact_page.php');
        break;

    case 'diensten':
        include locate_template('templates/pages/diensten_page.php');
        break;

    case 'standaard':
        default:
        ?>
    <section class="page-content py-5">    
        <div class="container">
            <?php while(have_posts()): the_post(); the_content(); endwhile; ?>
        </div>
    </section>
    <?php
    break;
}
get_footer(); ?>
