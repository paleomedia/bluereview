<?php get_header(); ?>
<section id="content" class="single-page main--content">
        <?php
        if (have_posts()) : while (have_posts()) : the_post();
                ?>
                <?php get_template_part('review', 'page') ?>
                <?php
            endwhile;
        endif;
        ?>
</section><!--/#content-->
<?php get_sidebar('single'); ?>
<?php get_footer(); ?>