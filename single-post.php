<?php get_header(); ?>
<section id="content" class="single-post main--content">
        <?php
        if (have_posts()) : while (have_posts()) : the_post();
            global $do_not_duplicate;
            $do_not_duplicate[] = $post->ID;
        ?>
        <?php get_template_part('review', 'single') ?>
            <?php
            endwhile;
            else:
                 get_template_part('review', '404');
        endif;
        ?>
</section><!--/#content-->
<?php get_sidebar('post'); ?>
<?php get_footer(); ?>