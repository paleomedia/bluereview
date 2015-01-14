<?php

/**
 * Adds Blue Author widget.
 */
class recent_authors extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
                'recent_author', // Base ID
                'Blue Recent Authors Widget', // Name
                array('description' => __('Most recent authors', 'blue review'),) // Args
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
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $howMany = $instance['howMany'];
        echo $before_widget;
        $args = array(
            'numberposts' => 15,
            'orderby' => 'post_date',
            'order' => 'DESC'
        );
        $posts_array = get_posts($args);
        $authors = array();
        global $post;
        foreach ($posts_array as $authPost) {
            if ($authPost->post_author !== $post->post_author) {
                $authors[$authPost->post_author] = $authPost->post_author;
            }
        }
        wp_reset_query();
        
        $authors = array_slice($authors, 0, $howMany);
        $args = array(
            'include' => $authors,
            'orderby'   => 'post_count',
            'order' => 'desc'
        );
        
        

        $authors = get_users($args);
        if (!empty($authors)) {
            if (!empty($title))
                echo $before_title . $title . $after_title;
            ?>
                <ul class='authors--list'>
                <?php
            // loop through each author
            foreach ($authors as $listAuthor) {
                // get all the user's data
                ?>
                <li class="authors--list-item"><a href="/author/<?php echo $listAuthor->user_nicename; ?>" title="<?php echo $listAuthor->display_name; ?>"><?php echo get_blue_avatar($listAuthor->ID, '56'); ?><br /><?php echo $listAuthor->display_name; ?></a></li>

                <?php
            }
            ?>
                </ul>
<?php
        } else {
            echo 'No authors found';
        }
        ?><?php
        echo $after_widget;
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
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />

            <label for="<?php echo $this->get_field_id('howMany'); ?>"><?php _e('How many authors:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('howMany'); ?>" name="<?php echo $this->get_field_name('howMany'); ?>" type="text" value="<?php echo esc_attr($howMany); ?>" />

        </p>

        <?php
    }

}
?>
