<?php
function issue_table_style() {
	wp_enqueue_style('really-simple-issue-tracker-table-style', plugin_dir_url(__FILE__).'css/issue-table.css');
}
add_action('wp_enqueue_scripts', 'issue_table_style');

function issue_table() { 
	$querytype = 'post_type="issue"';
	echo '<table id="issue-table"><thead><tr><th>Date</th><th>Type</th><th>Status</th><th>Title</th><th>Project</th><th>For</th><th>Priority</th><th>Spent</th><th>Assigned</th></tr></thead>';
	echo '<tbody>';
	query_posts($querytype);

	while ( have_posts() ) : the_post();
	$post = get_the_ID();
    echo '<tr><td>';
    echo get_the_date();

    $issue = get_post_meta($post, 'issue_type', true);
    $issue_types = ReallySimpleIssueTracker_IssueType::getDefaultIssueTypes();
	if($issue) {
		$issue_type = object;
            foreach($issue_types as $type) {
                if($type->getIssueTypeById($issue))
            	$issue_type = $type;
    			}
            	echo '<td>'.$issue_type->getName().'</td>';
            }

    $status = get_post_meta($post,'issue_status', true);
    $status_types = ReallySimpleIssueTracker_Status::getDefaultStatusTypes();
        if($status) {
            $status_type = object;
            foreach($status_types as $type) {
                if($type->getStatusTypeById($status))
            	$status_type = $type;
    			}
            	echo '<td class="'.$status_type->getId().'">'.$status_type->getName().'';
            }
    echo '</td><td>';
?>
    <a href=<?php the_permalink() ?>><?php the_title() ?></a>
<?php
    echo '</td><td>';
    the_taxonomies(array('template' => '<div style="display:none;">%s</div> %l')); 
	echo '</td><td>';

    $assigned = get_user_meta(get_post_meta($post, 'assigned_to', true), 'nickname', true);
    	if($assigned) {echo $assigned;}
    	else {echo __('Unassigned',ReallySimpleIssueTracker::HANDLE);}

	$priority = get_post_meta($post, 'priority', true);
    $priority_types = ReallySimpleIssueTracker_Priority::getDefaultPriorities();
	if($priority) {
		$priority_type = object;
            foreach($priority_types as $type) {
                if($type->getPriorityById($priority))
            	$priority_type = $type;
    			}
            	echo '</td><td>'.$priority_type->getName().'';
            }
    echo '</td><td>';
    $time_spent = get_post_meta($post, 'time_spent', true);
    	if($time_spent) {echo $time_spent;}
    echo '</td><td>';
    $original_estimate = get_post_meta($post,'original_estimate', true);
        if($original_estimate) {echo $original_estimate;}
    echo '</td></tr>';
	
	endwhile;
	echo '</tbody></table>';
	wp_reset_query(); 
}
add_shortcode('really-simple-issue-tracker', 'issue_table');
?>