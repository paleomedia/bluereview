<?php get_header(); ?>
<section class="author-page author main--content">
    <div class="author--content-area">
    <?php if (have_posts()) : the_post() ?>
        <?php
        $post_author = blue_author_info(get_the_author_meta('ID'));
        $post_author['display-name'] = get_the_author_meta('display_name');
        $post_author['email'] = get_the_author_meta('email');
        $post_author['ID'] = get_the_author_meta('ID');
        ?>
        <div id="list-authors">
            <?php
            $args = array(
                'orderby' => 'post_count',
                'order' => 'DESC',
            );

            $authors = get_users($args);
            $list_authors = $authors;
            if (!empty($list_authors)): ?>
                <h2 class="authors--list-heading">The Blue Review Writers</h2>
                <div class="authors--select">
                    <?php usort($list_authors, 'blue_author_sort'); ?>
                    <select class="authors--select-list">
                        <option class="authors--select-heading">
                            Find an Author:
                        </option>
                        <?php foreach ($list_authors as $author): ?>
                            <?php $total = count_user_posts($author->ID); ?>
                            <?php if ((get_the_author() != $author->display_name) && ($total > 0)):?>
                                <option class="authors--select-item" value="/author/<?php echo $author->user_nicename; ?>">
                                    <?php echo $author->display_name; ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach;?>
                    </select>
                </div>
                <ul class='authors--list'>
                    <?php
                    // loop through each author
                    foreach ($authors as $author):
                        $total = count_user_posts($author->ID);
                        if ((get_the_author() != $author->display_name) && ($total > 0)):
                            // get all the user's data
                            ?>
                            <li class="authors--list-item">
                                <a href="/author/<?php echo $author->user_nicename; ?>" title="<?php echo $author->display_name; ?>">
                                    <span class="authors--list-image"><?php echo get_blue_avatar($author->ID, 64); ?></span>
                                </a>
                            </li>
                        <?php endif;
                    endforeach; ?>
                </ul>
            <?php else :
                echo 'No authors found';
            endif; ?>
            </div>
            <div class="author--info" >
                <div class="author--profile" rel="me">
                    <?php echo get_blue_avatar($post_author['ID'], '200'); ?>
                    <?php if ($post_author['twitter'] || $post_author['email'] || $post_author['gplus'] || $post_author['facebook'] || $post_author['website']):
                        ?>
                    <div class="author-box">
                        <ul class="author--social">
                            <li class="notes author--social-link">Contact:</li>
                            <?php if ($post_author['twitter']) : ?>
                                <li class="twitter author--social-link">
                                    <a class="author--social-anchor" href="http://twitter.com/<?php echo $post_author['twitter']; ?>" title="Follow <?php echo $post_author['display-name']; ?> on Twitter"></a>
                                </li>
                            <?php endif;
                            if ($post_author['facebook']) : ?>
                                <li class="facebook author--social-link">
                                    <a class="author--social-anchor" href="<?php echo $post_author['facebook']; ?>" title="Like <?php echo $post_author['display-name']; ?> on facebook"></a>
                                </li>
                            <?php endif;
                            if ($post_author['gplus']) : ?>
                                <li class="gplus author--social-link">
                                    <a class="author--social-anchor" href="<?php echo $post_author['gplus']; ?>" title="Follow <?php echo $post_author['display-name']; ?> on Google Plus" /></a>
                                </li>
                            <?php endif;
                            if ($post_author['email']) : ?>
                                <li class="email author--social-link">
                                    <a class="author--social-anchor" href="mailto:<?php echo $post_author['email']; ?>" title="email <?php echo $post_author['display-name']; ?>"></a>
                                </li>
                            <?php endif; ?>
                            <? if ($post_author['website']) :
                                ?>
                                    <li class="web author--social-link">
                                        <a class="author--social-anchor" href="<?php echo $post_author['website']; ?>" title="Visit <?php echo $post_author['display-name']; ?>'s site"></a>
                                    </li>
                            <?php endif; ?> 
                            </ul>
                    </div>
                    <div class="author--publications-wrap">                            
                        <ul class="author--publications">
                        <?php if (!empty($post_author['books'])): foreach($post_author['books'] as $book):
                            if ((!empty($book['url'])) || (!empty($book['title'])) || (!empty($book['cover']))):?>
                                <li class="author--publications-item">
                                    <?php if ((!empty($book['url'])) || (!empty($book['cover'])) || (!empty($book['title']))) :
                                        if (!empty($book['url'])):?>
                                            <a href="<?php echo $book['url']; ?>">
                                        <? endif; ?>
                                        <?php if (!empty($book['cover'])) : ?>
                                            <img class="author--publications-item-image" src="<?php echo $book['cover']; ?>" />
                                        <?php else: ?>
                                            <img class="author--publications-item-image" src="<?php echo get_template_directory_uri() ?>/images/pub.jpg" />
                                        <?php endif; ?>
                                        <?php if (!empty($book['title'])) : ?>
                                            <p class="author--publications-item-description"><?php echo $book['title']; ?></p>
                                            <?php if (!empty($book['url'])) : ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                            <? endforeach;
                            endif; ?>
                        </ul>
                        </div>
                        <div class="clear" id="author--tweets">
                            <?php
                            $author_tweets = $post_author['twitter'];
                            if ($author_tweets != ""):
                                $title = apply_filters('widget_title', $instance['title']);
                                $jsonurl = "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=" . $author_tweets . "&count=3&nclude_entities=false&include_rts=false&exclude_replies=true&trim_user=true";
                                $json = file_get_contents($jsonurl, 0, null, null);
                                $json_output = (array)json_decode($json);
                                if (!empty($json_output)):
                                    ?>
                                    <p class="notes">author's latest tweets</p>
                                    <ul>
                                        <?php foreach ($json_output as $text): ?>
                                            <li class="tweet"><?php echo $text->text ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <p>
                                        <a href="http://twitter.com/<?php echo $post_author['twitter']; ?>" title="Follow <?php echo $post_author['display-name']; ?> on Twitter">Follow <?php echo $post_author['display-name']; ?> on Twitter</a>
                                    </p>
                                <?php endif;
                            endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- .author-info -->
            <div class="author--summary">
                <div id="author-description" class="author-head">
                    <h1 class="author--name"><span class="vcard"><?php the_author() ?></span></h1>
                    <h2 class="author--title"><?php echo $post_author['title']; ?></h2>
                    <div class="author--bio"> 
                        <?php
                        // If a user ha s filled out their description, show a bio on their entries.
                        echo wp_kses_post($post_author['description']);
                        ?>
                    </div>
                </div>
                <div class="author--articles">
                    <h3 class="author--article-heading">Articles from this author</h3>
                    <?php /* Start the Loop */ ?>
                    <?php
                    /* Since we called the_post() above, we need to
                     * rewind the loop back to the beginning that way
                     * we can run the loop properly, in full.
                     */
                    rewind_posts(); $i=0;
                    ?>
                    <?php if (have_posts()) : ?>
                                    <ul class="article"><?php
                        while ((have_posts()) && $i < 4 ) : the_post(); ?>
                            <?php get_template_part('review', 'sidebar'); $i++ ?>
                            <?
                        endwhile; ?>
                        </ul><?php
                    endif;
                    ?>
                <?php else : ?>
        <?php get_template_part('review', '404'); ?>
    <?php endif; ?>
                </div></div><div class="clear"></div></div>
    </section><!-- #page -->
    <?php get_sidebar('author'); ?>
    <?php get_footer(); ?>