<?php
/**
 Plugin Name: Very Simple Issue Tracker
 Description: Keep track of issues to be done, connect them with a project and list them publicly in a widget and/or table. Allow anonymous users to post issues with a shortcode.
 Author: Hendrik Boone
 Author URI: https://teamblueridge.org
 Version: 1.2
 */

require_once 'class-really-simple-issue-tracker.php';
require_once 'class-issue-type.php';
require_once 'class-status.php';
require_once 'class-priority.php';
require_once 'widgets/widget-issue-list.php';
require_once 'widgets/widget-issue-details.php';
require_once 'shortcodes/issue-table.php';
require_once 'shortcodes/report-issue.php';
require_once 'hooks.php';
$tracker = new ReallySimpleIssueTracker();
