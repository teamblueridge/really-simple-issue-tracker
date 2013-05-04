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
		$instance['spent'] = $new_instance['spent'];
		$instance['assigned'] = $new_instance['assigned'];
	return $instance;
    }

	function form ($instance) {
        $defaults = array(
            'title' => __('Issue Details', ReallySimpleIssueTracker::HANDLE),
            'type' => 'on',
            'status' => 'on',
            'priority' => 'on',
            'spent' => '',
            'assigned' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults ); 
		$type = $instance['type'] ? 'checked="checked"' : '';
		$status = $instance['status'] ? 'checked="checked"' : '';
		$priority = $instance['priority'] ? 'checked="checked"' : '';
		$spent = $instance['spent'] ? 'checked="checked"' : '';
		$assigned = $instance['assigned'] ? 'checked="checked"' : '';

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
			<input class="checkbox" type="checkbox" <?php echo $spent; ?> id="<?php echo $this->get_field_id('spent'); ?>" name="<?php echo $this->get_field_name('spent'); ?>" /> <label for="<?php echo $this->get_field_id('spent'); ?>"><?php _e('Show the spent time', ReallySimpleIssueTracker::HANDLE); ?></label>
			<br/>
			<input class="checkbox" type="checkbox" <?php echo $assigned; ?> id="<?php echo $this->get_field_id('assigned'); ?>" name="<?php echo $this->get_field_name('assigned'); ?>" /> <label for="<?php echo $this->get_field_id('assigned'); ?>"><?php _e('Show the assigned time', ReallySimpleIssueTracker::HANDLE); ?></label>
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

	echo $before_widget;

	?>
	
    <?php if(strlen($instance['title']) > 0 && is_singular('issue') ): ?>
        <div class="widget-title"><?php echo $instance['title'] ?></div>
        <?php endif; ?>

	<?php $post = get_the_ID(); ?>

	<?php if(is_singular('issue') ): ?>
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
            	echo '<li>Status: '.$status_type->getName().'</li>';
            }
	endif; ?>
	
	<?php if($instance['type'] == 'on'):
	$issue = get_post_meta($post, 'issue_type', true);
    $issue_types = ReallySimpleIssueTracker_IssueType::getDefaultIssueTypes();
	if($issue) {
		$issue_type = object;
            foreach($issue_types as $type) {
                if($type->getIssueTypeById($issue))
            	$issue_type = $type;
    			}
            	echo '<li>Type: '.$issue_type->getName().'</li>';
            }
	endif; ?>
	
	<?php if($instance['priority'] == 'on'):
	$priority = get_post_meta($post, 'priority', true);
    if($priority) {echo '<li>Priority: '.$priority.'</li>';}
	endif; ?>
	
	<?php if($instance['spent'] == 'on'):
	$time_spent = get_post_meta($post, 'time_spent', true);
    if($time_spent) {echo '<li>Time spent: '.$time_spent.'</li>';}
	endif; ?>
	
	<?php if($instance['assigned'] == 'on'):
	$original_estimate = get_post_meta($post, 'original_estimate', true);
    if($original_estimate) {echo '<li>Time assigned: '.$original_estimate.'</li>';}
	endif; ?>
	
	</ul>
	<?php endif; ?>

	<?php
	echo $after_widget;
	
	}
	
}

add_action('widgets_init', 'register_issue_details_widget');
function register_issue_details_widget() {
    register_widget('ReallySimpleIssueTracker_DetailsWidget');
};
?>