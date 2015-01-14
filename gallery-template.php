<?php
/*
Template Name Posts: Gallery in Header
*/
?>

<?php get_header(); ?>
<section id="content" class="single-post main--content">
        <?php
        if (have_posts()) : while (have_posts()) : the_post();?>
        <?php get_template_part('review', 'gallery') ?>
            <?php
            endwhile;
            else:
                 get_template_part('review', '404');
        endif;
        ?>
</section><!--/#content-->
<?php get_sidebar('post'); ?>
<?php get_footer(); ?>