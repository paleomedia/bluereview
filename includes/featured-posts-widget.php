<?php

/**
 * Featured Posts widget class
 *
 * @since 2.8.0
 */

class Featured_Blue_Posts extends WP_Widget {

    function __construct() {
        
        $widget_ops = array('classname' => 'widget_featured_blue_entries', 'description' => __("The featured posts on your site, displayed better"));
        parent::__construct('featured-blue-posts', __('Blue Featured Posts'), $widget_ops);
        $this->alt_option_name = 'widget_featured_blue_entries';

        add_action('save_post', array(&$this, 'flush_widget_cache'));
        add_action('deleted_post', array(&$this, 'flush_widget_cache'));
        add_action('switch_theme', array(&$this, 'flush_widget_cache'));
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('widget_featured_blue_posts', 'widget');

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

        $title = apply_filters('widget_title', empty($instance['title']) ? __('') : $instance['title'], $instance, $this->id_base);
        if (empty($instance['number']) || !$number = absint($instance['number']))
            $number = 3;
        if (empty($instance['tag']) || !$tag = absint($instance['tag']))
            $tag = 18;
        global $post;
        global $do_not_duplicate;
        $do_not_duplicate[] = $post->ID;
        $r = new WP_Query(apply_filters('widget_posts_args', array('tag_id'=>$tag, 'posts_per_page' => $number, 'post__not_in' => $do_not_duplicate, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'orderby' => 'rand')));
        if ($r->have_posts()) :
            ?>
            <?php echo $before_widget; ?>
            <?php if ($title) echo $before_title . $title . $after_title; ?>
                <div class="featured--sidebar clearfix">
                    <?php while ($r->have_posts()) : $r->the_post(); ?>
                        <?php get_template_part('review', 'index'); ?>
                    <?php endwhile; ?>
                </div>
            <?php echo $after_widget; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_featured_blue_posts', $cache, 'widget');
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['tag'] = (int) $new_instance['tag'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['widget_featured_blue_entries']))
            delete_option('widget_featured_blue_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_featured_blue_posts', 'widget');
    }

    function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $the_tag = isset($instance['tag']) ? absint($instance['tag']) : 18;
        $the_posts = isset($instance['posts']) ? esc_attr($instance['posts']) : '';
        $tags = get_tags();
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        <p><label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e('What tag to feature?'); ?></label><br />
            <?php foreach ($tags as $tag): ?>
                <?php if ($tag->count >= $number): ?>
                    <input id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" <?php if ($the_tag == $tag->term_id): ?>checked="checked"<?php endif; ?> type="radio" value="<?php echo $tag->term_id; ?>" size="3" /> <?php echo $tag->name; ?><br />
                <?php endif; ?>
            <?php endforeach; ?>
        </p>
        <?php
    }

}
