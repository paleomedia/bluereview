<?php get_header(); ?>
<section id="content" class="list-page main--content article--list">
    <h1 class='page--title'><?php single_term_title() ?></h1>
    <div id="articles" class='article--list'>
        <?php
        if (have_posts()) : while (have_posts()) : the_post();
                ?>
                <?php get_template_part('review', 'overlayed') ?>
            <?php
            endwhile;
            else:
                get_template_part('review', '404');
        endif;
        ?>
    </div><!--/#articles -->
    <div id="nextPrev"><?php print (previous_posts_link('« Newer Entries', 0)); ?><?php print (next_posts_link('Older Entries »', 0)); ?></div>
</section><!--/#content-->
<?php get_sidebar('archive'); ?>
<?php get_footer(); ?>