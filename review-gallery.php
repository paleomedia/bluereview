<?php 
$articleClass = ' article--single';
if (has_post_thumbnail()){
    $articleClass .= ' article--no-image';
}
else{
    $articleClass .= ' article--with-image';
}
$post_authors = blue_prep_authors();
$content = get_the_content();
?>
<article role="article" <?php post_class($articleClass . ' article'); ?>>
    <?php // blue_blog_header();  ?>
    <header class="article--meta">
         <?php $content = blue_do_gallery($content); ?>
        <h1 class="article--title"><?php the_title(); ?></h1>
        <h2 class="article--subtitle"><?php (function_exists('the_subtitle')) ? the_subtitle() : "" ?></h2>
        <div class="mobile-only"><h4 class="article--author mobile-only"><?php function_exists('coauthors_posts_link') ? coauthors_posts_links() : the_author_posts_link(); ?></h4> | <?php the_date('m.d.Y', '<p class="mobile-only">', '</p>') ?></div>

    </header>
    <?php blue_post_info(); ?>
    <div class="article--content-wrap">
    <div class="article--content">
        <?php
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        echo $content;
        ?>
            <?php wp_link_pages(); ?>
    </div>
        <div id="author-description" class="author-head article--author-bio notes" role="note">
                <?php
                // If a user ha s filled out their short bio, show a bio on their entries.
                foreach ($post_authors as $post_author):
                    ?>
                <div class="authorbio">
    <?php echo $post_author['shortBio']; ?>
                </div>
    <?php endforeach; ?>
        </div><!--.content-->
    </div>
    <footer>  
    </footer>
<?php comments_template('', true); ?>	
</article> <!--.single-->