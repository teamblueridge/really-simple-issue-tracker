<?php
$AnonUserName = 'Anonymous'; // name of anonymous user
$PostStatus = 'draft'; // 'publish' or 'draft'

function report_issue_style() {wp_register_style('report-issue-style', plugin_dir_url(__FILE__).'report-issue.css');}
add_action('wp_enqueue_scripts', 'report_issue_style');

function report_issue() {

wp_enqueue_style('report-issue-style');

global $AnonUserName;
global $PostStatus;

if($_POST) {

if(username_exists($AnonUserName)){$UserID = username_exists($AnonUserName);}
else {$UserID = wp_create_user($AnonUserName, wp_generate_password(20, false), 'no@email.com' );}

$my_issue = array(
       		'post_title' => $_POST['Title'],
       		'post_content' => $_POST['Message'],
       		'post_status' => $PostStatus,
       		'post_author' => $UserID,
			'post_type' => 'issue',
			'comment_status' => 'open',
       	);

if (empty ($_POST['URL'])) {$PostID = wp_insert_post($my_issue);

update_post_meta($PostID, 'issue_status', 'open');

//later we add a link back to the issue table, impossible now because no template yet

if ($PostStatus == 'publish') {echo 'You can follow the issue status<a href="'.get_permalink($PostID).'">here</a>.';}
else {echo 'Moderation in progress, issue will be added soon.';}
}

}
else {

global $post;

$issue_types = ReallySimpleIssueTracker_IssueType::getDefaultIssueTypes();
$saved_type = get_post_meta($post->ID, 'issue_type', true);

$priority_types = ReallySimpleIssueTracker_Priority::getDefaultPriorities();
$saved_priority = get_post_meta($post->ID, 'priority', true);
?>

<div id="respond"><form method="post" onSubmit="if (this.Title.value == '') {return false;}">

<p>
    <?php _e('Type', ReallySimpleIssueTracker::HANDLE) ?>:
    <select name="issue_type">
        <?php foreach($issue_types as $type): ?>
            <option value="<?php echo $type->getId(); ?>" <?php echo $saved_type == $type->getId() ? 'selected="selected"' : '' ?>><?php echo $type->getName(); ?></option>
        <?php endforeach; ?>
    </select> -
    <?php wp_nonce_field('nonce_issue_type','nonce_issue_type') ?>
    <?php _e('Priority', ReallySimpleIssueTracker::HANDLE) ?>:
    <select name="priority">
        <?php foreach($priority_types as $type): ?>
        <option value="<?php echo $type->getId(); ?>" <?php echo $saved_priority == $type->getId() ? 'selected="selected"' : '' ?>><?php echo $type->getName(); ?></option>
        <?php endforeach; ?>
    </select>
    <?php wp_nonce_field('nonce_priority','nonce_priority') ?>
</p>
<p class="comment-form-author"><input type="text" name="Title" placeholder="Issue title" size="30"></p>
<p class="comment-form-url"><input type="text" name="URL" placeholder="URL" size="30"></p>
<p class="comment-form-comment"><textarea name="Message" cols="45" rows="8" placeholder="Issue content"></textarea></p>
<input type="submit" name="submit" value="Submit"></form></div>

<?php
// add an email input field to allow issue notifications by mail. Integrate that in the settings.

}

}
add_shortcode('report-issue', 'report_issue');
?>