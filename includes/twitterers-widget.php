<?php

/**
 * Adds Blue Author Twitter widget.
 */
class tweets_by_author extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
                'tweets_by_author', // Base ID
                'Blue Author Tweets Widget', // Name
                array('description' => __('Contextual tweets by post/page author Or not.', 'blue review'),) // Args
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
        extract($instance);
        $themeOptions = get_option('blue_theme_options');
        $fallback = $themeOptions['twitterHandle'];
        $howMany = $instance['howMany'];
        if ($instance['show'] == 'site'):
            $author = $fallback;
        else:
            if ($instance['fallback'] == 'default'):
                $author = get_the_author_meta('twitter', $post->post_author) ? get_the_author_meta('twitter', $post->post_author) : $fallback;
            else:
                $author = str_replace('@', '', get_the_author_meta('twitter', $post->post_author));
            endif;
        endif;
        $author = '';
        if ($author != ""):
            $title = apply_filters('widget_title', $instance['title']);
            $title .= ' by @' . $author;
            $model = new twitterFeedHelper();
            $tweets = get_transient($author . 'tweets');

            // $data = empty($tweets) ? $model->getData(array('user' => $author, 'display_name' => 0, 'count' => $howMany, 'consumer_key' => $consumerKey, 'consumer_secret' => $consumerSecret, 'access_token' => $accesKey, 'access_secret' => $accessSecret)) : unserialize($tweets);
            echo $before_widget;
            if (!empty($title)):
                echo $before_title . $title . $after_title;
            endif;
            if (empty($consumerKey) || empty($consumerSecret) || empty($accesKey) || empty($accessSecret)):
                ?><p>Some configuration options need to be set before Twitter will allow access. Please adjust the settings on this widget.</p><?php
            else:
            ?>
            <ul><?php foreach ($data->tweets as $text): ?>
                    <li class="tweet"><?php echo $text->text; ?></li>
                <?php endforeach; ?>
            </ul>
            <p class="notes">
                <a class="" href="http://twitter.com/<?php echo $author; ?>">Follow @<?php echo $author; ?> on twitter</a>
            </p>
            <?php
            endif;
            echo $after_widget;
        endif;
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
        if (is_numeric($new_instance['howMany'])): $instance['howMany'] = strip_tags($new_instance['howMany']);
        endif;
        $instance['consumerKey'] = strip_tags($new_instance['consumerKey']);
        $instance['consumerSecret'] = strip_tags($new_instance['consumerSecret']);
        $instance['accessKey'] = strip_tags($new_instance['accessKey']);
        $instance['accessSecret'] = strip_tags($new_instance['accessSecret']);
        $instance['fallback'] = strip_tags($new_instance['fallback']);
        $instance['show'] = strip_tags($new_instance['show']);

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
        extract($instance);
        $blue_custom_options = get_option('blue_theme_options');
        $title = isset($instance['title']) ? $instance['title'] : __('Recent Tweets', 'blue review');
        $howMany = isset($instance['howMany']) ? $instance['howMany'] : __('5', 'blue review');
        $fallback = isset($instance['fallback']) ? $instance['fallback'] : __($blue_custom_options_['twitter'], 'blue review');
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />

            <label for="<?php echo $this->get_field_id('consumerKey'); ?>"><?php _e('Consumer Key:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('consumerKey'); ?>" name="<?php echo $this->get_field_name('consumerKey'); ?>" type="text" value="<?php echo esc_attr($consumerKey); ?>" />

            <label for="<?php echo $this->get_field_id('consumerSecret'); ?>"><?php _e('Consumer Secret:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('consumerSecret'); ?>" name="<?php echo $this->get_field_name('consumerSecret'); ?>" type="text" value="<?php echo esc_attr($consumerSecret); ?>" />

            <label for="<?php echo $this->get_field_id('accessKey'); ?>"><?php _e('Access Key:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('accessKey'); ?>" name="<?php echo $this->get_field_name('accessKey'); ?>" type="text" value="<?php echo esc_attr($accessKey); ?>" />

            <label for="<?php echo $this->get_field_id('accessSecret'); ?>"><?php _e('Access Secret:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('accessSecret'); ?>" name="<?php echo $this->get_field_name('accessSecret'); ?>" type="text" value="<?php echo esc_attr($accessSecret); ?>" />

            <label for="<?php echo $this->get_field_id('howMany'); ?>"><?php _e('How Many:', 'blue review'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('howMany'); ?>" name="<?php echo $this->get_field_name('howMany'); ?>" type="text" value="<?php echo esc_attr($howMany); ?>" />

            <label for="<?php echo $this->get_field_id('show'); ?>"><?php _e('What feed?'); ?></label>
            <select name="<?php echo $this->get_field_name('show'); ?>" id="<?php echo $this->get_field_id('show'); ?>" class="widefat">
                <option value="author"<?php selected($instance['show'], 'author'); ?>><?php _e('Show contextual author'); ?></option>
                <option value="site"<?php selected($instance['show'], 'site'); ?>><?php _e('Show default'); ?></option>
            </select>
            <label for="<?php echo $this->get_field_id('fallback'); ?>"><?php _e('If contextual author is missing'); ?></label>
            <select name="<?php echo $this->get_field_name('fallback'); ?>" id="<?php echo $this->get_field_id('fallback'); ?>" class="widefat">
                <option value="hide"<?php selected($instance['fallback'], 'hide'); ?>><?php _e('Hide Widget'); ?></option>
                <option value="default"<?php selected($instance['fallback'], 'default'); ?>><?php _e('Show default'); ?></option>
            </select>
        </p>

        <?php
    }

}

require_once dirname(__FILE__) . '/../lib/twitteroauth/twitteroauth.php';
require_once dirname(__FILE__) . '/../lib/twitter-text/Autolink.php';
require_once dirname(__FILE__) . '/../lib/twitter-text/Extractor.php';
require_once dirname(__FILE__) . '/../lib/twitter-text/HitHighlighter.php';

class twitterFeedHelper {

    private $data;
    private $cacheTransient = 'blue_tweet_cache';

    public function getData($options) {
        $params = new AnyUserParams($options);
        $twitterConnection = new TwitterOAuth(
                trim($params->get('consumer_key', '')), // Consumer Key
                trim($params->get('consumer_secret', '')), // Consumer secret
                trim($params->get('access_token', '')), // Access token
                trim($params->get('access_secret', '')) // Access token secret
        );
        $author = ($params->get('user'));
        $twitterData = $twitterConnection->get(
                'search/tweets', array(
            'q' => trim($params->get('user', 'reviewblue')),
            'count' => trim($params->get('count', 5)),
                )
        );
        if (!isset($twitterData->errors))
            $twitterData = $twitterData->statuses;

        // if there are no errors
        if (!isset($twitterData->errors)) {
            $tweets = array();
            foreach ($twitterData as $tweet) {
                $tweetDetails = new stdClass();
                $tweetDetails->text = Twitter_Autolink::create($tweet->text)->setNoFollow(false)->addLinks();
                $tweets[] = $tweetDetails;
            }
            $data = new stdClass();
            $data->tweets = $tweets;
            $this->data = $data;

            if (empty($data->tweets[0]->text))
                return '';

            set_transient($author . 'tweets', serialize($this->data), 15 * MINUTE_IN_SECONDS);

            return $data;
        }
        else {
            return $this->getCache();
        }
    }

    private function setCache() {
        set_transient($this->cacheTransient, serialize($this->data), 15 * MINUTE_IN_SECONDS);
    }

    private function getCache() {
        $cache = get_transient($this->cacheTransient);
        if (!empty($cache))
            return unserialize($cache);
        return false;
    }

    // parse time in a twitter style
    private function getTime($date) {
        $timediff = time() - strtotime($date);
        if ($timediff < 60)
            return $timediff . 's';
        else if ($timediff < 3600)
            return intval(date('i', $timediff)) . 'm';
        else if ($timediff < 86400)
            return round($timediff / 60 / 60) . 'h';
        else
            return date_i18n('M d', strtotime($date));
    }

}

class AnyUserParams {

    private $params;

    public function __construct($params) {
        $this->params = $params;
    }

    public function get($param, $default = '') {
        if (isset($this->params[$param]))
            return $this->params[$param];
        return $default;
    }

}