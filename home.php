<?php get_header(); ?>

<section id="content" class="front list-page main--content">
    <?php 
    global $do_not_duplicate;
    // Get the first post for the featured posts area
    $args = array('category_name'=>'feature', 'posts_per_page'=>1, 'post__in'  => get_option( 'sticky_posts' ));
    $my_query = new WP_Query( $args );
        if ($my_query->have_posts()) : ?>
            <div id="featured-articles" class="featured--primary">
            <?php
            while ($my_query->have_posts()) : $my_query->the_post();
                $do_not_duplicate[$post->ID] = $post->ID; ?>
                <?php get_template_part('review', 'overlayed'); ?>
            <?php endwhile; ?>
            </div><!--/.featured-primary-->
        <?php endif;?>

    <?php
    // Get the next two posts for the featured posts area
    $args = array( 'category_name'=>'feature', 'posts_per_page'=>2, 'post__not_in'=>$do_not_duplicate );
    $my_query = new WP_Query($args);
    if ($my_query->have_posts()) :
        ?>
        <div class="featured--secondary clearfix">
        <?php while ($my_query->have_posts()) : $my_query->the_post();
            $do_not_duplicate[$post->ID] = $post->ID; ?>
            <div class="featured--secondary-wrap">
                <?php get_template_part('review', 'index'); ?>
            </div>
        <?php endwhile; ?>
        </div><!--/.featured-secondary-->
    <?php endif;?>
    <?php // And we're done with featured posts. ?>
    <div id="articles" class="article--list">
        <?php if (have_posts()) : 
            while (have_posts()) : the_post();
                if (in_array($post->ID, $do_not_duplicate)):
                    continue;
                endif; ?>
                <?php get_template_part('review', 'overlayed') ?>
            <?php endwhile;
            else: get_template_part('review', '404');
        endif; ?>
    </div><!--/#articles -->
    <div id="nextPrev"><?php print (previous_posts_link('« Newer Entries', 0)); ?><?php print (next_posts_link('Older Entries »', 0)); ?></div>
</section><!--/#content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>