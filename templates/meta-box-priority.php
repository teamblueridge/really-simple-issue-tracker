<?php
$priority_types = ReallySimpleIssueTracker_Priority::getDefaultPriorities();
/* @var $type ReallySimpleIssueTracker_Priority */
global $post;
$saved_priority = get_post_meta($post->ID, 'priority', true);
?>

<label>
    <?php _e('Priority', ReallySimpleIssueTracker::HANDLE) ?>:
    <select name="priority">
        <?php foreach($priority_types as $type): ?>
        <option value="<?php echo $type->getId(); ?>" <?php echo $saved_priority == $type->getId() ? 'selected="selected"' : '' ?>><?php echo $type->getName(); ?></option>
        <?php endforeach; ?>
    </select>
    <?php wp_nonce_field('nonce_priority','nonce_priority') ?>
</label>