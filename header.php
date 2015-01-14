<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <title><?php is_home() ? bloginfo('description') : wp_title(''); ?> | <?php bloginfo('name'); ?> </title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300|Carrois+Gothic+SC' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <meta name="viewport" content="width=device-width" /> 
        <link rel="alternate" type="application/rss+xml" title="The Blue Review" href="<?php echo bloginfo('rss2_url'); ?>" />
        <LINK REL="apple-touch-icon-precomposed" HREF="/apple-touch-icon.png" />

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <header id="header" class="meta constrain" role="banner">
            <div id="sticky-menu" class="meta--sticky-menu clearfix">
                <div id="site-info" class="constrain relative">
                    <a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home" <?php if (get_option(display_header_text())): ?> class="text" <?php endif; ?>><?php
                        $header_image = get_header_image();
                        if (!empty($header_image)) :
                            ?><img src="<?php echo get_template_directory_uri() ?>/images/header-notag.jpg" class="meta--image-small" alt="<?php bloginfo('name'); ?>" />
                            <img src="<?php echo esc_url($header_image); ?>" class="meta--image-logo" alt="<?php bloginfo('name'); ?>" />
                        <?php endif; ?><span<?php if (!get_option(display_header_text())) : ?> class="hide-text"><?php
                        endif;
                        bloginfo('name');
                        ?></span></a>
                    <h2 id="subhead" <?php if (!get_option(display_header_text())) : ?> class="hide-text"><?php
                        endif;
                        bloginfo('description');
                        ?></h2>
                    <div id="menu-mobile" class="mobile-only meta--mobile"><h2 style="font-size:1.2em;">Menu</h2></div>
                    <div id='full-menu'>
                        <?php wp_nav_menu(array('container' => 'nav', 'container_class' => 'primary-nav meta--menu', 'theme_location' => 'primary-navigation', 'fallback_cb' => 'wp_page_menu')); ?>
                        <nav id="head-social" class="meta--head-social">
                        <ul>
                            <?php $blue_custom_options = get_option('blue_theme_options'); ?>
                            <?php if (($blue_custom_options['twitterYN'] == true) && ($blue_custom_options['twitterHandle'] != false)): ?>
                                <li class="social tw"><a href="http://twitter.com/<?php echo $blue_custom_options['twitterHandle'] ?>"></a></li><?php endif; ?>
                            <?php if (($blue_custom_options['facebookYN'] == true) && ($blue_custom_options['facebookURL'] != false)): ?>
                                <li class="social fb"><a href="<? echo $blue_custom_options['facebookURL']; ?>"></a></li><?php endif; ?>
                            <?php if (($blue_custom_options['gPlusYN'] == true) && ($blue_custom_options['gPlus'] != false)): ?>
                                <li class="social gplus"><a href="<? echo $blue_custom_options['gPlus']; ?>"></a></li><?php endif; ?>
                            <?php if (($blue_custom_options['rssYN'] == true) && ($blue_custom_options['rssURL'] != false)): ?>
                                <li class="social rss"><a href="<?php echo $blue_custom_options['rssURL']; ?>"></a></li><?php endif; ?>
                                <li class="social bsu"><a href="http://boisestate.edu"></a></li>
                                <li class="social search"><?php get_search_form(); ?></li>
                            </ul>
                        </nav>
                        <div class="meta--search">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </header><!--/#header-->
    <div id="main" class="constrain main--content-wrap">
<?php
// .constrain and body closed in index.php  ?>