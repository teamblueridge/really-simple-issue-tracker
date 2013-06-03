<?php
class ReallySimpleIssueTracker_ListWidget extends WP_Widget {

    private $plugin_url;

    public function __construct() {
        $this->plugin_url = plugin_dir_url(__FILE__);
        parent::__construct(false, __('Issue List', ReallySimpleIssueTracker::HANDLE), array('description'=> __('Displays a customizable list of registered issues.', ReallySimpleIssueTracker::HANDLE)));
    }

    function update ($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['list_length'] = $new_instance['list_length'];
        $instance['assignee'] = $new_instance['assignee'];
        $instance['project'] = $new_instance['project'];
        $instance['status'] = $new_instance['status'];
        return $instance;
    }

    function form ($instance) {
        $defaults = array(
            'title' => __('Issue list', ReallySimpleIssueTracker::HANDLE),
            'list_length' => 10,
            'assignee' => 0,
            'project' => 0,
            'status' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
?>

    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>:
            <input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title') ?> " value="<?php echo $instance['title'] ?>" size="20">
        </label>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('list_length'); ?>"><?php _e('List length ', ReallySimpleIssueTracker::HANDLE) ?>:
            <select name="<?php echo $this->get_field_name('list_length'); ?>">
                <?php for($i=1; $i<=10; $i++): ?>
                <option value="<?php echo $i ?>" <?php echo $i == $instance['list_length'] ? 'selected="selected"' : ''?>><?php echo $i ?></option>
                <?php endfor; ?>
            </select>
            &nbsp;<?php echo __('issue(s)',ReallySimpleIssueTracker::HANDLE) ?>
        </label>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('assignee'); ?>"><?php _e('Assigned to ', ReallySimpleIssueTracker::HANDLE) ?>:
            <select name="<?php echo $this->get_field_name('assignee'); ?>">
                <option value="">-- <?php _e('Select user', ReallySimpleIssueTracker::HANDLE) ?> --</option>
                <?php foreach(get_users() as $user): ?>
                <option value="<?php echo $user->ID ?>" <?php echo $user->ID == $instance['assignee'] ? 'selected="selected"' : ''?>><?php echo $user->user_nicename ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('project'); ?>"><?php _e('Project', ReallySimpleIssueTracker::HANDLE) ?>:
            <select name="<?php echo $this->get_field_name('project'); ?>">
                <option value="">-- <?php _e('Select project', ReallySimpleIssueTracker::HANDLE) ?> --</option>
                <?php foreach(get_terms('project','hide_empty=0') as $project): ?>
                <option value="<?php echo $project->term_id ?>" <?php echo $project->term_id == $instance['project'] ? 'selected="selected"' : ''?>><?php echo $project->name ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('status'); ?>"><?php _e('Status', ReallySimpleIssueTracker::HANDLE) ?>:
            <select name="<?php echo $this->get_field_name('status'); ?>">
                <option value="">-- <?php _e('Select status', ReallySimpleIssueTracker::HANDLE) ?> --</option>
                <?php foreach(ReallySimpleIssueTracker_Status::getDefaultStatusTypes() as $status_type): ?>
                <option value="<?php echo $status_type->getId() ?>" <?php echo $status_type->getId() == $instance['status'] ? 'selected="selected"' : ''?>><?php echo $status_type->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </label>
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

        $querystr = 'post_type="issue"';

        // Only display posts assigned to specific user
        if($instance['assignee'] != 0)
            $querystr .= '&meta_key=assigned_to&meta_value='.$instance['assignee'];

        // Only display posts with a specific status
        if($instance['status'] != '')
            $querystr .= '&meta_key=issue_status&meta_value='.$instance['status'];

        // Limit number of posts according to desired list length
        if($instance['list_length'] != 0)
            $querystr .= '&showposts='.$instance['list_length'];

        echo $before_widget;
        ?>
    <?php if(strlen($instance['title']) > 0): ?>
        <div class="widget-title"><?php echo $instance['title'] ?></div>
        <?php endif; ?>
    <ul>
        <?php query_posts($querystr);
        if (have_posts()) :
            while (have_posts()) : the_post();
                $post = get_post(get_the_ID()); ?>
                <li>
						<a href="<?php echo get_post_permalink($post->ID) ?>">
                            <?php echo $post->post_title ?></a>    
                </li>
                <?php endwhile; ?>
        </ul>
        <?php else : ?>
            <p>
                    <?php _e('List empty', ReallySimpleIssueTracker::HANDLE); ?>
            </p>
            <?php
        endif;
        wp_reset_query();
        ?>

    <?php
        echo $after_widget;
    }
}

// Register the widget
add_action('widgets_init', 'register_issue_list_widget');
function register_issue_list_widget() {
    register_widget('ReallySimpleIssueTracker_ListWidget');
};
