<?php
global $post;

$status_types = ReallySimpleIssueTracker_Status::getDefaultStatusTypes();
$saved_status = get_post_meta($post->ID, 'issue_status', true);

$issue_types = ReallySimpleIssueTracker_IssueType::getDefaultIssueTypes();
$saved_type = get_post_meta($post->ID, 'issue_type', true);

$priority_types = ReallySimpleIssueTracker_Priority::getDefaultPriorities();
$saved_priority = get_post_meta($post->ID, 'priority', true);

$all_authors = get_users();
$saved_assignee = get_post_meta($post->ID, 'assigned_to', true);

$original_estimate = get_post_meta($post->ID, 'original_estimate', true);
$time_spent = get_post_meta($post->ID, 'time_spent', true);
?>

<p><label>
    <?php _e('Status', ReallySimpleIssueTracker::HANDLE) ?>:
    <select name="issue_status">
        <?php foreach($status_types as $type): ?>
        <option value="<?php echo $type->getId(); ?>" <?php echo $saved_status == $type->getId() ? 'selected="selected"' : '' ?>><?php echo $type->getName(); ?></option>
        <?php endforeach; ?>
    </select>
    <?php wp_nonce_field('nonce_issue_status','nonce_issue_status') ?>
</label></p>

<p><label>
    <?php _e('Type', ReallySimpleIssueTracker::HANDLE) ?>:
    <select name="issue_type">
        <?php foreach($issue_types as $type): ?>
            <option value="<?php echo $type->getId(); ?>" <?php echo $saved_type == $type->getId() ? 'selected="selected"' : '' ?>><?php echo $type->getName(); ?></option>
        <?php endforeach; ?>
    </select>
    <?php wp_nonce_field('nonce_issue_type','nonce_issue_type') ?>
</label></p>

<p><label>
    <?php _e('Priority', ReallySimpleIssueTracker::HANDLE) ?>:
    <select name="priority">
        <?php foreach($priority_types as $type): ?>
        <option value="<?php echo $type->getId(); ?>" <?php echo $saved_priority == $type->getId() ? 'selected="selected"' : '' ?>><?php echo $type->getName(); ?></option>
        <?php endforeach; ?>
    </select>
    <?php wp_nonce_field('nonce_priority','nonce_priority') ?>
</label></p>

<p><label>
    <?php _e('Assigned to', ReallySimpleIssueTracker::HANDLE) ?>:
    <select name="assigned_to">
        <option value="">
            -- <?php _e('Unassigned', ReallySimpleIssueTracker::HANDLE)?> --
        </option>
        <?php foreach($all_authors as $author): ?>
            <option value="<?php echo $author->ID ?>" <?php echo $saved_assignee == $author->ID ? 'selected="selected"' : '' ?>><?php echo $author->user_nicename ?></option>
        <?php endforeach; ?>
    </select>
    <?php wp_nonce_field('nonce_assigned_to','nonce_assigned_to') ?>
</label></p>

<p><label>
        <?php _e('Estimated time', ReallySimpleIssueTracker::HANDLE) ?>:
        <input type="text" name="original_estimate" size="8" placeholder="ex: 1h 30m" value="<?php echo $original_estimate != '' ? $original_estimate : '' ?>"/>
        <?php wp_nonce_field('nonce_original_estimate','nonce_original_estimate') ?>
    </label></p>

<p><label>
        <?php _e('Spent time', ReallySimpleIssueTracker::HANDLE) ?>:
        <input type="text" name="time_spent" size="8" placeholder="ex: 45m" value="<?php echo $time_spent != '' ? $time_spent : '' ?>"/>
        <?php wp_nonce_field('nonce_time_spent','nonce_time_spent') ?>
    </label></p>