<?php 
class PandoraTweets extends WP_Widget {

    var $widget_cssclass;
	var $widget_description;
	var $widget_idbase;
	var $widget_name;
    
	function PandoraTweets() {

		/* Widget variable settings. */
		$this->widget_cssclass = 'twitter';
		$this->widget_description = __( 'Display latest tweets.', PLSH_THEME_DOMAIN );
		$this->widget_idbase = 'pandora_tweets';
		$this->widget_name = __( 'Pandora Twitter feed', PLSH_THEME_DOMAIN );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		/* Create the widget. */
		$this->WP_Widget('pandora_tweets', $this->widget_name, $widget_ops);

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	function widget($args, $instance) 
    {
		$cache = wp_cache_get('pandora_tweets', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Pandora Twitter feed', PLSH_THEME_DOMAIN) : $instance['title'], $instance, $this->id_base);
		$widget_id = isset( $instance['widget_id'] ) ? $instance['widget_id'] : false;
        $color = isset( $instance['color'] ) ? $instance['color'] : false;
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
        
            <div class="items">
                <a class="twitter-timeline" data-tweet-limit="2" data-link-color="#FF2D6D" data-theme="<?php echo $color; ?>" data-chrome="noheader nofooter noscrollbar noborders transparent" data-dnt="true" width="300" height="230" href="https://twitter.com/" data-widget-id="<?php echo $widget_id; ?>">Tweets</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>

		<?php echo $after_widget; ?>
        <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('pandora_tweets', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) 
    {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['color'] = strip_tags($new_instance['color']);
		$instance['widget_id'] = $new_instance['widget_id'];

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['pandora_tweets']) )
			delete_option('pandora_tweets');

		return $instance;
	}

	function flush_widget_cache() 
    {
		wp_cache_delete('pandora_tweets', 'widget');
	}

	function form( $instance ) 
    {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$widget_id = isset( $instance['widget_id'] ) ? esc_attr( $instance['widget_id'] ) : '';
        $color = isset( $instance['color'] ) ? esc_attr( $instance['color'] ) : 'dark';
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', PLSH_THEME_DOMAIN ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'widget_id' ); ?>"><?php _e( 'Widget id:', PLSH_THEME_DOMAIN ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'widget_id' ); ?>" name="<?php echo $this->get_field_name( 'widget_id' ); ?>" type="text" value="<?php echo $widget_id; ?>" /></p>
        
        <p>
            <input type="radio" id="lightcolor" name="<?php echo $this->get_field_name( 'color' ); ?>" value="light" <?php if($color == 'light') echo 'checked'; ?> />
            <label for="lightcolor"><?php _e( 'Light', PLSH_THEME_DOMAIN ); ?></label>
        </p>
        <p>
            <input type="radio" id="darkcolor" name="<?php echo $this->get_field_name( 'color' ); ?>" value="dark" <?php if($color == 'dark') echo 'checked'; ?> />
            <label for="darkcolor"><?php _e( 'Dark', PLSH_THEME_DOMAIN ); ?></label>
        </p>
        
<?php
	}
}