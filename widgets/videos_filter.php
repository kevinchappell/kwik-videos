<?php
/**
 * Widget Name: Videos Filter
 * Description: A widget that allows you to filter videos on the video archive and category pages .
 * Version: 0.1
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'videos_filter_load_widgets' );

/**
 * Register our widget.
 * 'Videos_Filter_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function videos_filter_load_widgets() {
	register_widget( 'Videos_Filter_Widget' );
}

/**
 * Custom Category Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Videos_Filter_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Videos_Filter_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_videos_filter', 'description' => esc_html__('Filter videos on archive and category pages.', 'kwik') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'videos-filter-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'videos-filter-widget', esc_html__('Kwik Videos: Filter', 'kwik'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		if(isset($_GET['sortby'])) $sortby = $_GET['sortby'];

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$sortby_title = $instance['sortby_title'];

		?>
		<form id="video_filter" class="clearfix" action="javascript:void(0)">   

        <label id="vid_type_label" for="video_type"><?php _e('Types','kwik'); ?>: </label><?php kv_get_types_select(); ?>    
        
        
<!--           
        <label for="publish_year" class="publish_year"><?php _e('Year','kwik'); ?>:</label><?php kv_get_years_select('videos'); ?>
        
       --> 
        <label class="sortby" for="sortby"><?php _e('Sort by','kwik'); ?>:</label>
        <select id="sortby" class="no_display" name="sortby">
            <option value="title" <?php echo ($sortby == 'title' ? 'selected="selected"' : '') ?>><?php _e('Title','kwik'); ?></option>
            <option value="date" <?php echo ($sortby == 'date' ? 'selected="selected"' : '') ?>><?php _e('Date','kwik'); ?></option>
        </select>
        <span title="date" class="vid_sort"><?php _e('Date','kwik'); ?></span>
        <span title="name" class="vid_sort"><?php _e('Title','kwik'); ?></span>        
        <span title="comments" class="vid_sort"><?php _e('Most Commented','kwik'); ?></span>
        
        <label id="vid_order_label"><?php _e('Order','kwik'); ?>:</label>
        <select id="order" class="no_display" name="order">
            <option value="ASC"><?php _e('&#9650; Ascend','kwik'); ?></option>
            <option value="DESC" selected="selected"><?php _e('&#9660; Descend','kwik'); ?></option>            
        </select>        
        
        
        <span class="vid_order ASC" title="ASC">&#9650;</span><span class="vid_order DESC" title="DESC">&#9660;</span>
        
        
       
    </form>
	
	<?php

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		/* No need to strip tags for dropdowns and checkboxes. */
		$instance['sortby_title'] = $new_instance['sortby_title'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => esc_html__('Video Filter', 'kwik'), 'sortby_title' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        <!-- Widget Title: Text Input -->	
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e('Widget Title:', 'kwik'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
        
        <p>
        <strong><?php esc_html_e('Allow Sorting by...', 'kwik'); ?></strong><br/>			
			<label for="<?php echo $this->get_field_id( 'sortby_title' ); ?>"><?php esc_html_e('Title:', 'kwik'); ?></label>
			<input id="<?php echo $this->get_field_id( 'sortby_title' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'sortby_title' ); ?>" value="<?php echo $instance['sortby_title']; ?>" <?php ($user['is_logged_in'] ? 'checked="checked" ' : ' '); ?> />

		</p>
                        
<?php
	}
	
	
}


