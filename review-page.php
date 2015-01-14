<?php 
$articleClass = ' page';
?>
<article role="article" <?php post_class($articleClass . ' article article--single'); ?>>
    <?php // blue_blog_header(); ?>
    <header class="article--meta">
        <h1 class="article--title"><?php the_title(); ?></h1>
        <h2 class="article--subtitle"><?php (function_exists('the_subtitle')) ? the_subtitle() : "" ?></h2>
        <div class="mobile-only"><h4 class="article--author mobile-only"><?php function_exists('coauthors_posts_link')? coauthors_posts_links() : the_author_posts_link(); ?></h4> | <?php the_date('m.d.Y', '<p class="mobile-only">', '</p>') ?></div>

    </header>
    <div class="article--page-wrap">
        <div class="article--page-gutter">&nbsp;</div>
        <div class="article--content-wrap">
            <div class="article--content">
                    <?php the_content(); ?>
                    <?php wp_link_pages(); ?>
            </div><!--.content-->
            <?php $blue_custom_options = get_option('blue_theme_options');
                if (!empty($blue_custom_options['disclaimer']) && is):
            ?>
            <?php endif; ?>
            <?php
            // If a user ha s filled out their short bio, show a bio on their entries.
            foreach ($post_authors as $post_author): ?>
                <div class="article--author-bio">
                    <?php echo $post_author['shortBio'];?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php comments_template('', true); ?>	
</article> <!--.single-->