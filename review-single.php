<?php
$articleClass = ' article--single';
if (!has_post_thumbnail()) {
    $articleClass .= ' article--no-image';
} else {
    $articleClass .= ' article--with-image';
}
$post_authors = blue_prep_authors();
?>
<article role="article" <?php post_class($articleClass . ' article'); ?>>
    <?php // blue_blog_header();  ?>
    <header class="article--meta">
        <?php blue_article_featured_image(); ?>

        <h1 class="article--title"><?php the_title(); ?></h1>
        <h2 class="article--subtitle"><?php (function_exists('the_subtitle')) ? the_subtitle() : "" ?></h2>
        <div class="mobile-only"><h4 class="article--author mobile-only"><?php function_exists('coauthors_posts_link') ? coauthors_posts_links() : the_author_posts_link(); ?></h4> | <?php the_date('m.d.Y', '<p class="mobile-only">', '</p>') ?></div>

    </header>
    <?php blue_post_info(); ?>
    <div class="article--wrap">
        <div class="article--content-wrap">
            <div class="article--content">
                <?php the_content(); ?>
                <?php wp_link_pages(); ?>
            </div><!--.content-->
            <?php
            // If a user ha s filled out their short bio, show a bio on their entries.
            foreach ($post_authors as $post_author):
                ?>
                <div class="article--author-bio">
                    <?php echo $post_author['shortBio']; ?>
                </div>
            <?php endforeach; ?>
            <?php if (function_exists('st_makeEntries') && get_option('st_add_to_content') == 'yes'): ?>
                <div class="share">
                    <h4 class="share--heading">Share this Article:</h4>
                    <?php echo st_makeEntries(); ?>
                </div>
            <?php endif; ?>
            <?php
            $blue_custom_options = get_option('blue_theme_options');
            if (!empty($blue_custom_options['disclaimer'])):?>
                <div class="article--disclaimer">
                    <p><?php echo $blue_custom_options['disclaimer']; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php comments_template('', true); ?>	
</article> <!--.single-->