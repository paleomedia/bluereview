<?php $cats = get_the_category();
?>
<?php $size = has_category('feature') ? 'singlepost-thumb' : '';
$firstImage = blue_first_image($post, $size); 
$articleClass = ' article';
if ($firstImage == false): 
    $articleClass .= ' article--no-image';
else:
    $articleClass .= ' article--with-image';
endif; ?>

<article <?php post_class($articleClass); ?>>
    
    <div class="article--meta-container">
        <?php echo $firstImage; ?>
        <?php blue_featured_tag(); ?>

        <div class="article--meta">
            <h1 class="article--title">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title() ?>
                </a>
            </h1>
            <?php if (function_exists('the_subtitle') && (get_the_subtitle() != "")): ?> 
                <h2 class="article--subtitle">
                    <?php the_subtitle(); ?>
                </h2>
            <?php endif ?>
        <?php blue_authors() ?>
        </div>
    </div>
    <div class="article--content">
        <?php the_excerpt(); ?>
        <?php wp_link_pages(); ?>
    </div><!--.content-->
    <?php comments_template( '', true ); ?>	
    <div class="clear"></div>
</article> <!--/article-->