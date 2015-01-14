<?php
add_action('after_setup_theme', 'blue_review_theme_prep');

$do_not_duplicate = array();

function show_me_the_available_functions() {
    $x = get_defined_functions();
    $x = $x['user'];
    asort($x);
    ?><ul style="display:none" ><?php foreach ($x as $y) { ?>

            <?
            print "$y" . "<br/>";
        }
        ?></ul><?php
}

//show_me_the_available_functions();
// more by authors Widget
require_once ( get_template_directory() . '/includes/more-by-authors-widget.php' );

// Twitterers Widget
require_once ( get_template_directory() . '/includes/twitterers-widget.php' );

// Recent Posts Widget
require_once ( get_template_directory() . '/includes/recent-posts-widget.php' );

// Featured Posts Widget
require_once ( get_template_directory() . '/includes/featured-posts-widget.php' );

// Print Edition Widget
require_once ( get_template_directory() . '/includes/print-edition-widget.php' );

// Recent Blog Posts Widget
require_once ( get_template_directory() . '/includes/recent-blog-posts.php' );

// Author profile form
require_once ( get_template_directory() . '/includes/author-meta-form.php' );

// Author list widget
require_once ( get_template_directory() . '/includes/recent-authors.php' );

// Gallery tweaks
require_once ( get_template_directory() . '/includes/gallery-tweaks.php' );

function blue_review_theme_prep() {

// Enable Post Formats
//    register_post_type('dataset', array(
//	'label' => __('dataset'),
//	'singular_label' => __('dataset'),
//	'public' => true,
//	'show_ui' => true,
//	'capability_type' => 'post',
//	'hierarchical' => false,
//	'rewrite' => false,
//	'query_var' => false,
//));
    
//    wp_insert_term( 'post-format-dataset', 'post_format' );

    add_theme_support('post-formats', array('aside', 'audio', 'chat', 'dataset', 'gallery', 'image', 'link', 'quote'));

// Add support for nav menus

    add_theme_support('nav-menus');

// Add support for custom header
    $defaults = array(
        'default-image' => get_template_directory_uri() . '/images/header-tag.jpg',
        'random-default' => false,
        'width' => 950,
        'height' => 152,
        'flex-height' => true,
        'flex-width' => true,
        'default-text-color' => '',
        'header-text' => true,
        'uploads' => true,
        'wp-head-callback' => '',
        'admin-head-callback' => '',
        'admin-preview-callback' => '',
    );
    add_theme_support('custom-header', $defaults);

// Set up featured images
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size( 660, 300, true );

// Add image sizes
    add_image_size('homepage-thumb', 250, 415); 
    add_image_size('singlepost-thumb', 660, 400); 
    add_image_size('featured-thumb', 230, 130); 
    
// Set up sidebars
    register_sidebar(array('name' => 'Homepage Sidebar', 'id' => 'homepage'));
    register_sidebar(array('name' => 'Single Page Sidebar', 'id' => 'single-page'));
    register_sidebar(array('name' => 'Archive Sidebar', 'id' => 'archive'));
    register_sidebar(array('name' => 'Single Post', 'id' => 'single-post'));
    register_sidebar(array('name' => 'Author Page', 'id' => 'author'));
}

// Set up javascript
function add_blue_scripts() {
    
    if( !is_admin()){
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-effects-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_register_script('html5', get_template_directory_uri() . '/js/html5.js');
    wp_enqueue_script('html5');
    wp_register_script('masonryv2', get_template_directory_uri() . '/js/mason/jquery.masonry.min.js');
    wp_enqueue_script('masonryv2');
    wp_register_script('infinity', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js');
    wp_enqueue_script('infinity');
    wp_register_script('masonry-init', get_template_directory_uri() . '/js/script.js');
    wp_enqueue_script('masonry-init');
    wp_register_script('cycle2', get_template_directory_uri() . '/js/jquery.cycle2.min.js');
    wp_enqueue_script('cycle2');
    wp_register_script('select', get_template_directory_uri() . '/js/jquery.customSelect.min.js');
    wp_enqueue_script('select');
    wp_enqueue_style( 'wp-jquery-ui-dialog' );
    }
}

// Create settings for menus

function register_blue_menus() {
    register_nav_menus(
            array(
                'primary-navigation' => __('Primary Navigation'),
                'footer-links' => __('Footer Links')
            )
    );
}

// Set up filters so images work responsively

function remove_blue_size_attribute($html) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Set up filter to modify excerpt as needed

function blue_excerpt_length($length) {
    return 20;
}

// Set up filter for html in bio
remove_filter('pre_user_description', 'wp_filter_kses');

function blue_excerpt_more($more) {
    return ' ...';
}

// Set up filter to add field to image attachments

function add_blue_credit($form_fields, $post) {

  $form_fields["credit"] = array(
    "label" => __("Credit"),
    "input" => "text", 
    "value" => get_post_meta($post->ID, "_credit", true),
    "helps" => __("Photo Credit"),
  );
   return $form_fields;
}

function blue_first_image($post, $size='homepage-thumb'){
    $first_image = ""; 
    $link = get_permalink();
    $title = the_title_attribute(array('echo'=> false));
        if ( has_post_thumbnail($post->ID) ) : 
            $first_image = get_the_post_thumbnail( $post->ID, $size ); 
        else :
            $first_image = get_blue_images( $post->ID, $post->post_content );
        endif; 
        if ($first_image): 
            $image = "<a class='article--image-wrap' href='{$link}' title='{$title}' >";
            $image .= $first_image;
            $image .= '</a>';
            return $image;
        endif; 
        return false;
}

function blue_do_gallery($content){
    if (preg_match_all( '/'. get_shortcode_regex() . '/s', $content, $codes)){
        foreach ($codes[0] as $index => $code){
            if (strpos($code, 'gallery') !== false){
                $content = str_replace($code, '', $content);
                echo do_shortcode($code);
                return $content;
                break;
            }
        }
    }
    return false;
}

function blue_authors(){
    ?><h2 class="article--author">
            <?php if(function_exists('coauthors')): 
                ?> By <?php coauthors_posts_links(); 
            else: 
                ?> By <?php the_author_posts_link(); 
            endif; ?>
        </h2>
<?php
}

// now attach our function to the hook
add_filter("attachment_fields_to_edit", "add_blue_credit", null, 2);

function save_blue_credit($post, $attachment) {
  if( isset($attachment['credit']) ){
    update_post_meta($post['ID'], '_credit', $attachment['credit']);
  }
  return $post;
}
// now attach our function to the hook.
add_filter("attachment_fields_to_save", "save_blue_credit", null , 2);

// Show credit when post is displayed

add_filter('image_send_to_editor','blue_image_to_editor',10,2);

function blue_image_to_editor($html, $id){
        
        $credit = get_post_meta($id, "_credit", true);
        $html .= "<em class='credit'>{$credit}</em><br />";
        
        return $html;
}

function exclude_blue_category($query) {
if ( $query->is_home && $query->is_main_query() ) {
// $query->set('cat', '-28');
}
}

add_filter( 'the_content', 'prefix_insert_post_ads' );

function blue_blog_header() {
    global $post;

    if ( is_single() && ! is_admin() && in_category('blog', $post) ) {
        
        ?>
            <img id="bloghead" src="<? echo get_stylesheet_directory_uri() ?>/images/bloghead.png" alt="blog logo" />
        <?php
    }
}

function prefix_insert_post_ads( $content ) {
    global $post;
//    print_r($post);
    $cats = get_the_category($post->ID);
    $theCat = array();
    foreach ($cats as $cat){
        if ($cat->slug == 'feature' || $cat->slug == 'uncategorized' ){
            
        }
        else{
            $theCat = $cat;
        }
    }
    if (isset($theCat->name) && $theCat->description != ''):
        $categoryNote = '<div class="article--category"><p>' . $theCat->description . '</p></div>';
    endif;
    if ( is_single() && ! is_admin() ) {
        return prefix_insert_after_paragraph( $categoryNote, 1, $content, 25 );
    }
    return $content;
}

 
function prefix_insert_after_paragraph( $insertion, $paragraph_id, $content, $min_words ) {
	$closing_p = '</p>';
	$paragraphs = explode( $closing_p, $content );
	foreach ($paragraphs as $index => $paragraph) {

		if ( trim( $paragraph ) ) {
			$paragraphs[$index] .= $closing_p;
		}

		if ( $paragraph_id == $index + 1 && str_word_count( $paragraph ) > $min_words ) {
			$paragraphs[$index] .= $insertion;
		}
                else { $paragraph_id++; }
	}
	
	return implode( '', $paragraphs );
}

add_filter('pre_get_posts', 'exclude_blue_category');
add_filter('excerpt_more', 'blue_excerpt_more');
add_filter('post_thumbnail_html', 'remove_blue_size_attribute', 10);
add_filter('get_the_excerpt ', 'remove_blue_size_attribute', 10);
add_filter('excerpt_length', 'blue_excerpt_length');


// Tell WordPress about menus

add_action('init', 'register_blue_menus');

// Tell WordPress about Masonry

add_action('wp_enqueue_scripts', 'add_blue_scripts');

// Make images work all of the time
function get_blue_images($post_id, $the_content) {
    $image_tag = '';
    $args = array(
        'numberposts' => 1,
        'order' => 'ASC',
        'post_mime_type' => 'image',
        'post_parent' => $post_id,
        'post_status' => null,
        'post_type' => 'attachment'
    );

    $attachments = get_children($args);


    if ($attachments) {
        $attachment = array_shift($attachments);
        $image_tag = wp_get_attachment_image($attachment->ID, 'thumbnail') ? wp_get_attachment_image($attachment->ID, 'thumbnail') : wp_get_attachment_image($attachment->ID, 'full');
    } else {
        // Try to get the image for the linked image (not attached)
        $match_regex = '~<img [^\>]*\ />~';
        preg_match($match_regex, $the_content, $matches);
        if (isset($matches[0])) {
            $image_tag = array_shift($matches);
        }
    }

    if (!empty($image_tag)) {
        $image_tag = preg_replace('/(width|height)=\"\d*\"\s/', "", $image_tag);
        return $image_tag;
    }
    return false;
}

function register_blue_widgets(){
    register_widget( "more_by_author" );
    register_widget( "Featured_Blue_Posts" );
    register_widget( "Print_Blue_Posts" );
    register_widget( "tweets_by_author" );
    register_widget( "Recent_Blue_Blog_Posts" );
    register_widget( "Recent_Blue_Posts" );
    register_widget( "recent_authors" );
    unregister_widget( "WP_Widget_Recent_Posts" );
}

// register bonus widgets
add_action('widgets_init', 'register_blue_widgets');


add_action('personal_options_update', 'know_more_about_authors');
add_action('edit_user_profile_update', 'know_more_about_authors');

function know_more_about_authors($user_id) {

    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
    update_usermeta($user_id, 'title', wp_strip_all_tags($_POST['title']));
    update_usermeta($user_id, 'avatar', esc_url_raw($_POST['avatar']));
    update_usermeta($user_id, 'shortBio', wp_strip_all_tags($_POST['shortBio']));
    update_usermeta($user_id, 'twitter', wp_strip_all_tags($_POST['twitter']));
    update_usermeta($user_id, 'gplus', esc_url_raw($_POST['gplus']));
    update_usermeta($user_id, 'facebook', esc_url_raw($_POST['facebook']));
    update_usermeta($user_id, 'website', esc_url_raw($_POST['website']));
    $books = array(
        'book1' => array(
            'title' => wp_strip_all_tags($_POST['booktitle1']),
            'url' => esc_url_raw($_POST['bookurl1']),
            'cover' => esc_url_raw($_POST['book1'])
        ),
        'book2' => array(
            'title' => wp_strip_all_tags($_POST['booktitle2']),
            'url' => esc_url_raw($_POST['bookurl2']),
            'cover' => esc_url_raw($_POST['book2'])
        ),
        'book3' => array(
            'title' => wp_strip_all_tags($_POST['booktitle3']),
            'url' => esc_url_raw($_POST['bookurl3']),
            'cover' => esc_url_raw($_POST['book3'])
            ));
    update_usermeta($user_id, 'books', $books);
}

// prep user data for display

function mapping_user($a) {
    return $a[0];
}

;

function blue_author_info($user_id) {
    if ($all_meta_for_user = get_user_meta($user_id)) :
        $user_meta = array_map('mapping_user', get_user_meta($user_id));
        $user_meta['books'] = unserialize($user_meta['books']);
        return $user_meta;

    else:
        return false;
    endif;
}

// Theme options for social bits
require_once ( get_template_directory() . '/blue-options.php' );

// Callback function to insert 'styleselect' into the $buttons array
function blue_mce_buttons_2($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}

// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'blue_mce_buttons_2');

// Callback function to filter the MCE settings
function blue_mce_before_init_insert_formats($init_array) {
    // Define the style_formats array
    $style_formats = array(
        // Each array child is a format with it's own settings
        array(
            'title' => '.pull-sidenote-left',
            'inline' => 'span',
            'classes' => 'pull-sidenote-left',
            'wrapper' => true,
        ),
        array(
            'title' => '.just-the-facts',
            'block' => 'div',
            'classes' => 'just-the-facts',
            'wrapper' => true,
        ),
        array(
            'title' => '.dropcaps',
            'block' => 'p',
            'classes' => 'dropcaps',
            'wrapper' => false,
        ),
        array(
            'title' => '.overflow-left',
            'block' => 'div',
            'classes' => 'overflow-left',
            'wrapper' => true,
        )
    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode($style_formats);

    return $init_array;
}

add_filter('mce_buttons','buts_back');

function buts_back($mce_buttons) {

$pos = array_search('wp_more',$mce_buttons,true);

if ($pos !== false) {

$tmp_buttons = array_slice($mce_buttons, 0, $pos+1);

      $tmp_buttons[] = 'wp_page';

      $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));

  }

return $mce_buttons;

}
function blue_subtitle(){
    global $post;
        if (function_exists('the_subtitle') && (get_the_subtitle() != "")): ?> 
            <h2 class="article--subtitle">
                <?php the_subtitle(); ?>
            </h2>
        <?php endif;
}

add_filter('the_title', 'blue_catch_untitled', 10, 2);
function blue_catch_untitled($title, $id){
    if ($title == ""){
        return 'untitled';
    }
    return $title;
}

// and add editor support for it:
add_editor_style('editor-style.css');

function validate_blue_gravatar($email) {
    // Craft a potential url and test its headers
    $hash = md5(strtolower(trim($email)));
    $uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
    $headers = @get_headers($uri);
    if (!preg_match("|200|", $headers[0])) {
        $has_valid_avatar = FALSE;
    } else {
        $has_valid_avatar = TRUE;
    }
    return $has_valid_avatar;
}

// Attach callback to 'tiny_mce_before_init'
add_filter('tiny_mce_before_init', 'blue_mce_before_init_insert_formats');

function blue_gravatar_filter($avatar, $id_or_email, $size, $default, $alt) {
    if (is_string($id_or_email)){
    $users = get_users('search=' . $id_or_email);
    }
    else {
        if (is_object($id_or_email)){
            return $avatar;
        }
    }
    $userID = is_numeric($id_or_email) ? $id_or_email : $users[0]->ID;
    
	$custom_avatar = get_the_author_meta('avatar', $userID);
	if ($custom_avatar) 
		$return = '<img src="'.$custom_avatar.'" style="width:'.$size.'px; height:'.$size.'px; min-width:' . $size . 'px" alt="'.$alt.'" />';
	elseif ($avatar) 
		$return = $avatar;
	else 
		$return = '<img src="'.$default.'" style="width:'.$size.'px; height:'.$size.'px; min-width:' . $size . '" height="'.$size.'" alt="'.$alt.'" />';
	return $return;
}

add_filter('get_avatar', 'blue_gravatar_filter', 10, 5);
remove_filter('the_excerpt', 'st_add_widget');
remove_filter('the_content', 'st_add_widget');

add_action('show_user_profile', 'get_more_about_authors');
add_action('edit_user_profile', 'get_more_about_authors');

function get_blue_avatar($authorID, $size = 72) {
        if (get_the_author_meta('avatar', $authorID)) {
            $avatar = '<img src="' . get_the_author_meta('avatar', $authorID) . '" class="avatar photo avatar-default" width="' . $size .'" />';
        }
        else{
        $grav_url = "http://www.gravatar.com/avatar/" . 
         md5(strtolower($email)) . "?d=mm&s=" . $size;
        $avatarlink = "<img src='$grav_url' class='avatar photo avatar-default' width='" . $size . "' />";
        $match_regex = '~<img [^\>]*\ />~';
            preg_match($match_regex, $avatarlink, $matches);
            if (isset($matches[0])) {
                $avatar = array_shift($matches);
            }
        }
    return $avatar;
}

add_action( 'add_meta_boxes', 'add_blue_main_tag' );
        function add_blue_main_tag() {
                add_meta_box( 'blue_tag', 'Most Important Tag', 'blue_main_tag', 'post', 'side', 'default' );
                }

            function blue_main_tag( $post ) {
                $blue_main_tag = get_post_meta( $post->ID, '_blue_main_tag', true);
                $tags = get_the_tags($post->ID);
                if ($tags == 0):?>
                    <p>Post needs to be tagged with something to choose the most important tag.</p>
                <?php else:
                foreach ($tags as $tag):
                        ?>
                <input type="radio" name="blue_main_tag" value="<?php echo $tag->term_id; ?>" <?php if ($blue_main_tag == $tag->term_id): ?> checked="checked" <?php endif; ?>> <?php echo $tag->name; ?><br />
                <?php
                endforeach;
                endif;
        }

add_action( 'save_post', 'blue_main_tag_save' );
        function blue_main_tag_save( $post_ID ) {
            global $post;
            if( $post->post_type == "post" ) {
            if (isset( $_POST ) ) {
                update_post_meta( $post_ID, '_blue_main_tag', strip_tags( $_POST['blue_main_tag'] ) );
            }
        }
    }
    function get_blue_main_tag($post_ID){
        $main_tag_id = get_post_meta( $post_ID, '_blue_main_tag', true);
        $main_tag = "";
        if ($main_tag_id != ""){
            $main_tag = get_term($main_tag_id, 'post_tag');
        }
        else{
        $tags = get_the_tags($post_ID);
            if ( $tags != 0 ){
                $main_tag = array_slice($tags, 0, 1);
                $main_tag = $main_tag[0];
            }
        }
        return $main_tag;
    }
    function blue_featured_tag(){
        global $post;
        $tag = get_blue_main_tag($post->ID);
        if($tag != 0): ?>
                <span class="article--main-tag <?php echo $tag->slug ?>"><a href="/tag/<?echo $tag->slug; ?>"><?echo $tag->name; ?></a></span>
        <? endif;
        }
        
    function blue_post_info(){ 
        global $post;
        $post_authors = blue_prep_authors();
        ?>

        <div class="article--information" role="note">
        <div class="article--author-profile vcard">
            <?php $count = count($post_authors);?>
            <?php foreach($post_authors as $key=>$post_author):?>
                <div class='author--avatar-<?php echo $count?>'>
                    <a href="<?php $post_author['link'] ?>"><?php echo get_blue_avatar($key, 84);?></a>
                </div>
            <?php endforeach; ?>

            <h4 class="article--author">
                <?php function_exists('coauthors_posts_links') ? coauthors_posts_links() : the_author_posts_link(); ?>
            </h4>
        </div>
        <div class="article--cats"><?php
            $cats = get_the_category($post->ID);
            foreach ($cats as $key => $cat):
                if (($cat->name == 'Feature') || ($cat->name == 'Uncategorized')):
                    unset($cats[$key]);
                endif;
            endforeach;
            if (count($cats) > 0):
                $cats = array_slice($cats, 0, 3);
                ?>
                <ul class="article--cats-list">
                    <?php foreach ($cats as $cat): ?>
                        <li class="article--category-<?php echo $cat->slug ?>">
                            <a href="/category/<? echo $cat->slug; ?>"><? echo $cat->name; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <span class='article--date'><p><?php echo get_the_time('m.d.Y', $post->ID) ?></p></span>
        </div>
        <div class="article--social">
            <?php if(function_exists('st_makeEntries')): echo st_makeEntries(); endif; ?>
        </div>
        <div class="article--post-tags">
            <?php the_tags('Tags: ', ', ', ''); ?>
        </div>
    </div><?php
    }
    
    function blue_prep_authors(){
        global $post;
        $post_auth = function_exists('get_coauthors') ? get_coauthors(): array(get_the_author());
        foreach ($post_auth as $post_author):
            $post_authors[$post_author->ID] = blue_author_info($post_author->ID);
            $post_authors[$post_author->ID]['display-name'] = get_the_author_meta('display_name', $post_author->ID);
            $post_authors[$post_author->ID]['email'] = get_the_author_meta('email', $post_author->ID);
            $post_authors[$post_author->ID]['shortBio'] = get_the_author_meta('shortBio', $post_author->ID);
            $post_authors[$post_author->ID]['link'] = get_author_posts_url($post_author->ID);
        endforeach;
        return $post_authors;
    }
    
    function blue_article_featured_image(){
        global $post;
        ?>
        <?php
        if (has_post_thumbnail()) :
            ?>
            <div class="article--featured-image"> 
                <?php
                $featuredimage = get_post_thumbnail_id(get_the_ID());
                $featuredpost = get_post($featuredimage);
                $credit = get_post_meta($featuredimage, "_credit", true);
                $caption = $featuredpost->post_excerpt;
                the_post_thumbnail();
                if ((!empty($credit)) || (!empty($caption))): ?>
                    <div class='article--featured-image_caption-wrap'>
                        <p class='article--featured-image_caption'>
                            <em class='article--featured-image_credit'>
                                <?php if (!empty($credit)) {echo $credit;} ?>
                            </em>
                            <?php if (!empty($caption)) {echo $caption;} ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php
    }
    
    function blue_author_sort($a, $b) {
        if ($a->display_name == $b->display_name) return 0;
        return (strcasecmp($a->display_name, $b->display_name));
    }
    
    ?>