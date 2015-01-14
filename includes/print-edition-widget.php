<?php

/**
 * Print Posts widget class
 *
 * @since 2.8.0
 */

class Print_Blue_Posts extends WP_Widget {

    function __construct() {
        
        $widget_ops = array('classname' => 'widget_print_edition_entries', 'description' => __("The print edition feature"));
        $control_ops = array('width' => 350, 'height' => 350);
        parent::__construct('print-blue-posts', __('Blue Print Posts'), $widget_ops, $control_ops);
        $this->alt_option_name = 'widget_print_edition_entries';

        add_action('save_post', array(&$this, 'flush_widget_cache'));
        add_action('deleted_post', array(&$this, 'flush_widget_cache'));
        add_action('switch_theme', array(&$this, 'flush_widget_cache'));
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('widget_print_edition_posts', 'widget');

        if (!is_array($cache))
            $cache = array();

        if (!isset($args['widget_id']))
            $args['widget_id'] = $this->id;

        if (isset($cache[$args['widget_id']])) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();
        extract($args);
        extract($instance);
        $title = apply_filters('widget_title', empty($instance['title']) ? __('') : $instance['title'], $instance, $this->id_base);
        $head = empty($instance['head']) ? __('') : $instance['head'];
        $image = empty($instance['image']) ? '' : esc_url_raw($instance['image']);
        if (empty($instance['tag'])){
            $tag = '';
        }
        if (empty($instance['posts'])){
            $posts = false;
        }
        if (empty($instance['postTitles'])){
            $postTitles = false;
        }
        $subhead = empty($instance['subhead']) ? '' : wp_kses_post($instance['subhead']);
        $lastList = empty($instance['lastList']) ? '' : wp_kses_post($instance['lastList']);
        $r = new WP_Query(apply_filters('widget_posts_args', array('post__in' => $posts, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true)));
        if ($posts != false && $r->have_posts()) :
            ?>
                <?php echo $before_widget; ?>
                <?php if ($title) echo $before_title . $title . $after_title; ?>
                    <div class="print--sidebar clearfix">
                        <?php if ($head):  ?>
                            <h1 class="print--title"><a href='/tag/<?php echo $tag; ?>'><?php echo $head; ?></a></h1>
                        <?php endif; ?>
                        <?php if ($subhead): ?>
                            <h3 class='print--subhead'><a href='/tag/<?php echo $tag; ?>'><?php echo $subhead; ?></a></h3>
                        <?php endif; ?>
                        <?php if ($image != ""): ?>
                        <a href="/tag/<?php echo $tag; ?>">
                            <img src ='<?php echo $image; ?>' alt='TBR Print' />
                        </a>
                        <?php endif; ?>
                        <?php if($r->have_posts()): ?>
                        <ul class='print--list'>
                            <?php while ($r->have_posts()) : $r->the_post(); ?>
                            <li class='print--item'>
                                <a href='<?php the_permalink(); ?>'>
                                    <?php echo isset($postTitles[get_the_ID()]) ? $postTitles[get_the_ID()] : get_the_title(); ?>
                                </a>
                            <?php endwhile; ?>
                            <?php if (isset($lastList)): ?><li class="print--item"><a href='/tag/<?php echo $tag; ?>'><?php echo $lastList; ?></a></li><?endif; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                <?php echo $after_widget; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_print_edition_posts', $cache, 'widget');
    }

    function update($new_instance, $old_instance) {
        print_r($new_instance);
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['head'] = strip_tags($new_instance['head']);
        $instance['image'] = strip_tags($new_instance['image']);
        $instance['tag'] = strip_tags($new_instance['tag']);
        $instance['subhead'] = wp_kses_post($new_instance['subhead']);
        $instance['lastList'] = wp_kses_post($new_instance['lastList']);
        $instance['posts'] = $new_instance['posts'];
        $instance['postTitles'] = $new_instance['postTitles'];

        $this->flush_widget_cache();

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['widget_print_edition_entries']))
            delete_option('widget_print_edition_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_print_edition_posts', 'widget');
    }

    function form($instance) {
       
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $subhead = isset($instance['subhead']) ? wp_kses_post($instance['subhead']) : '';
        $head = isset($instance['head']) ? esc_attr($instance['head']) : '';
        $image = isset($instance['image']) ? esc_url_raw($instance['image']) : '';
        $tag = isset($instance['tag']) ? esc_attr($instance['tag']) : '';
        $lastList = isset($instance['lastList']) ? wp_kses_post($instance['lastList']) : '';
        $posts = isset($instance['posts']) ? $instance['posts'] : array();
        $postTitles = isset($instance['postTitles']) ? $instance['postTitles'] : array();
        $q = new WP_Query(apply_filters('widget_posts_args', array('tag'=>$tag, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true)));
        $queryPosts = array();
        while ($q->have_posts()): 
            $q->the_post();
            $id = get_the_ID();
            $queryPosts[$id]['id'] = $id;
            $queryPosts[$id]['title'] = get_the_title();
        endwhile;
        
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('head'); ?>"><?php _e('Header:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('head'); ?>" name="<?php echo $this->get_field_name('head'); ?>" type="text" value="<?php echo $head; ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('subhead'); ?>"><?php _e('Subhead:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('subhead'); ?>" name="<?php echo $this->get_field_name('subhead'); ?>" type="text" value="<?php echo $subhead; ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e('Tag (slug):'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo $tag; ?>" /></p>
        <label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e('Posts to show:'); ?></label><br />
        <?php if (isset($tag) && $tag != ""): ?>
            <?php foreach ($queryPosts as $post):
                $checked = false;?>
               <p> <?php $preppedTitle = isset($postTitles[$post['id']]) ? $postTitles[$post['id']] : $post['title']; ?>
                <input id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts') . '[' . $post['id'] . ']'; ?>" type="checkbox" value="<?php echo $post['id']; ?>" <?php if (in_array($post['id'], $posts)): $checked = true; ?> checked='checked' <?php endif; ?> /> <?php echo $post['title']; ?><br />
                <?php if ($checked): ?>
                    <label for="<?php echo $this->get_field_id('postTitles'); ?>"><?php _e('Title for list:'); ?></label><br />
                    <textarea class="widefat" id="<?php echo $this->get_field_id('postTitles'); ?>" name="<?php echo $this->get_field_name('postTitles') . '[' . $post['id'] . ']'; ?>" rows="2" columns="8" ><?php echo $preppedTitle; ?></textarea></p>
               <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
        <span>Input a tag, then you can choose the posts</span>
        <?php endif; ?>
        <p><label for="<?php echo $this->get_field_id('lastList'); ?>"><?php _e('Last item on list (More on... ):'); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id('lastList'); ?>" name="<?php echo $this->get_field_name('lastList'); ?>" rows="3" columns="10" ><?php echo htmlspecialchars($lastList); ?></textarea></p>
    <?php
    }

}
