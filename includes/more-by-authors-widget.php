<?php

/**
 * Adds Blue Author widget.
 */
class more_by_author extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
                'more_by_author', // Base ID
                'Blue More By Authors Widget', // Name
                array('description' => __('Contextual posts by same author', 'blue review'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        global $post;
        extract($args);
        $howMany = $instance['howMany'];
        $authID = $post->post_author;
        $authorData = array('post__not_in' => array( $post->ID ), 'posts_per_page' => $howMany, 'order' => 'DESC', 'orderby' => 'date', 'author' => $authID);
        $postslist = new WP_Query($authorData);
        $title = apply_filters('widget_title', $instance['title']);
        $who = $instance['who'];
        
        if ($postslist->have_posts()):
        
        echo $before_widget;
        if (!empty($title)):
            echo $before_title . $title . $after_title;
        endif;
        ?><ul class="article">		

            <?php while ($postslist->have_posts()) : $postslist->the_post(); ?>
                        <?php get_template_part('review', 'sidebar'); ?>
                        <?
                    endwhile; ?>
                           </ul><?php
        echo $after_widget;
        endif;
        wp_reset_query();
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['howMany'] = strip_tags($new_instance['howMany']);
        $instance['who'] = strip_tags($new_instance['who']);

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('New title', 'blue review');
        $howMany = isset($instance['howMany']) ? $instance['howMany'] : __('5', 'blue review');
        $who = isset($instance['who']) ? $instance['who'] : __('Default Blue Review Account', 'blue review');
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />

            <label for="<?php echo $this->get_field_id('howMany'); ?>"><?php _e('How Many:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('howMany'); ?>" name="<?php echo $this->get_field_name('howMany'); ?>" type="text" value="<?php echo esc_attr($howMany); ?>" />

        </p>

        <?php
    }

}
?>
