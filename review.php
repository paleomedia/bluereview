<?php 
$articleClass = ' article';
$size = has_category('feature') ? 'singlepost-thumb' : '';
$firstImage = blue_first_image($post, $size); 
if ($firstImage == false): 
    $articleClass .= ' article--no-image';
else:
    $articleClass .= ' article--with-image';
endif; ?>

<article <?php post_class($articleClass); ?>>
    
    <div class="article--meta-container">
        <?php echo $firstImage; ?>
        
        <?php blue_featured_tag(); ?>
    </div>
    <div class="article--meta">
        <h1 class="article--title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title() ?>
            </a>
        </h1>
        <?php blue_subtitle(); ?>
        <?php blue_authors(); ?>
    </div>
    <div class="article--content">
        <?php the_excerpt(); ?>
        <?php wp_link_pages(); ?>
    </div><!--.content-->
    <div class="clear"></div>
</article> <!--/article-->