<?php
class ReallySimpleIssueTracker_DetailsWidget extends WP_Widget {

    private $plugin_url;

    public function __construct() {
        $this->plugin_url = plugin_dir_url(__FILE__);
        parent::__construct(false, __('Issue Details', ReallySimpleIssueTracker::HANDLE), array('description'=> __('Displays issue details on a single issue page.', ReallySimpleIssueTracker::HANDLE)));
    }
	
	function update ($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['type'] = $new_instance['type'];
		$instance['status'] = $new_instance['status'];
		$instance['priority'] = $new_instance['priority'];
		$instance['time'] = $new_instance['time'];
	return $instance;
    }

	function form ($instance) {
        $defaults = array(
            'title' => __('Issue Details', ReallySimpleIssueTracker::HANDLE),
            'type' => 'on',
            'status' => 'on',
            'priority' => 'on',
            'time' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults ); 
		$type = $instance['type'] ? 'checked="checked"' : '';
		$status = $instance['status'] ? 'checked="checked"' : '';
		$priority = $instance['priority'] ? 'checked="checked"' : '';
		$time = $instance['time'] ? 'checked="checked"' : '';

        ?>
        
        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', ReallySimpleIssueTracker::HANDLE) ?>:
            	<input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title') ?> " value="<?php echo $instance['title'] ?>" size="20">
        	</label>
    	</p>
    	<p>
			<input class="checkbox" type="checkbox" <?php echo $type; ?> id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" /> <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Show the type', ReallySimpleIssueTracker::HANDLE); ?></label>
			<br/>
			<input class="checkbox" type="checkbox" <?php echo $status; ?> id="<?php echo $this->get_field_id('status'); ?>" name="<?php echo $this->get_field_name('status'); ?>" /> <label for="<?php echo $this->get_field_id('status'); ?>"><?php _e('Show the status', ReallySimpleIssueTracker::HANDLE); ?></label>
			<br/>
			<input class="checkbox" type="checkbox" <?php echo $priority; ?> id="<?php echo $this->get_field_id('priority'); ?>" name="<?php echo $this->get_field_name('priority'); ?>" /> <label for="<?php echo $this->get_field_id('priority'); ?>"><?php _e('Show the priority', ReallySimpleIssueTracker::HANDLE); ?></label>
			<br/>
			<input class="checkbox" type="checkbox" <?php echo $time; ?> id="<?php echo $this->get_field_id('time'); ?>" name="<?php echo $this->get_field_name('time'); ?>" /> <label for="<?php echo $this->get_field_id('time'); ?>"><?php _e('Show the time', ReallySimpleIssueTracker::HANDLE); ?></label>
		</p>

	<?php
	}
	
	function widget ($args, $instance) {
	/**
         * Global variables results in code error in PHPStorm
         * if you don't declare them like this
         * @var string $before_widget
         * @var string $after_widget
         * @var string $before_title
         * @var string $after_title
         */
	extract($args);
	
	if(is_singular('issue') ):

	echo $before_widget;
	
	if(strlen($instance['title']) > 0): ?>
        <div class="widget-title"><?php echo $instance['title'] ?></div>
    <?php endif; ?>

	<?php $post = get_the_ID(); ?>

	<ul>
	
	<?php if($instance['status'] == 'on'):
	$status = get_post_meta($post,'issue_status', true);
    $status_types = ReallySimpleIssueTracker_Status::getDefaultStatusTypes();
        if($status) {
            $status_type = object;
            foreach($status_types as $type) {
                if($type->getStatusTypeById($status))
            	$status_type = $type;
    			}
            	echo '<li>Status: <strong>'.$status_type->getName().'</strong></li>';
            }
	endif;
	
	if($instance['type'] == 'on'):
	$issue = get_post_meta($post, 'issue_type', true);
    $issue_types = ReallySimpleIssueTracker_IssueType::getDefaultIssueTypes();
	if($issue) {
		$issue_type = object;
            foreach($issue_types as $type) {
                if($type->getIssueTypeById($issue))
            	$issue_type = $type;
    			}
            	echo '<li>Type: <strong>'.$issue_type->getName().'</strong></li>';
            }
	endif;
	
	if($instance['priority'] == 'on'):
	$priority = get_post_meta($post, 'priority', true);
    $priority_types = ReallySimpleIssueTracker_Priority::getDefaultPriorities();
	if($priority) {
		$priority_type = object;
            foreach($priority_types as $type) {
                if($type->getPriorityById($priority))
            	$priority_type = $type;
    			}
            	echo '<li>Priority: <strong>'.$priority_type->getName().'</strong></li>';
            }
	endif;
	
	if($instance['time'] == 'on'):
	$time_spent = get_post_meta($post, 'time_spent', true);
    if($time_spent) {echo '<li>Time spent: <strong>'.$time_spent.'</strong></li>';}
	$original_estimate = get_post_meta($post, 'original_estimate', true);
    if($original_estimate) {echo '<li>Time assigned: <strong>'.$original_estimate.'</strong></li>';}
    
    if($original_estimate && $time_spent) {
    $original_estimate_minutes = ReallySimpleIssueTracker::convertEstimatedTimeStringToMinutes($original_estimate);
	$time_remaining = $original_estimate_minutes - ReallySimpleIssueTracker::convertEstimatedTimeStringToMinutes($time_spent);
	$time_remaining = $time_remaining < 0 ? 0 : $time_remaining;
	$time_remaining = ReallySimpleIssueTracker::convertMinutesToEstimatedTimeString($time_remaining);
	echo '<li>Time left: <strong>'.$time_remaining.'</strong></li>';}

	endif; ?>
	
	</ul>

	<?php
	echo $after_widget;
	endif;

	}
	
}

add_action('widgets_init', 'register_issue_details_widget');
function register_issue_details_widget() {
    register_widget('ReallySimpleIssueTracker_DetailsWidget');
};
?>