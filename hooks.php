<?php
/**
 * Save post meta-box actions
 * ----------------------------
 */
add_action('save_post','save_meta_box_issue_details');
function save_meta_box_issue_details($post_id) {
    if(count($_POST) > 0) {
        if(isset($_POST['assigned_to']) && wp_verify_nonce($_POST['nonce_assigned_to'],'nonce_assigned_to') && $_POST['assigned_to'] != '') {
            update_post_meta($post_id, 'assigned_to', $_POST['assigned_to']);
        } else {
            delete_post_meta($post_id, 'assigned_to');
        }
        if(isset($_POST['priority']) && wp_verify_nonce($_POST['nonce_priority'],'nonce_priority') && $_POST['priority'] != '') {
            update_post_meta($post_id, 'priority', $_POST['priority']);
        } else {
            delete_post_meta($post_id, 'priority');
        }
        if(isset($_POST['issue_type']) && wp_verify_nonce($_POST['nonce_issue_type'],'nonce_issue_type')) {
            update_post_meta($post_id, 'issue_type', $_POST['issue_type']);
        } else {
            delete_post_meta($post_id, 'issue_type');
        }
        if(isset($_POST['issue_status']) && wp_verify_nonce($_POST['nonce_issue_status'],'nonce_issue_status')) {
            update_post_meta($post_id, 'issue_status', $_POST['issue_status']);
        } else {
            delete_post_meta($post_id, 'issue_status');
        }
        if(isset($_POST['original_estimate']) && wp_verify_nonce($_POST['nonce_original_estimate'],'nonce_original_estimate')) {
            update_post_meta($post_id, 'original_estimate', $_POST['original_estimate']);
        } else {
            delete_post_meta($post_id, 'original_estimate');
        }

        if(isset($_POST['time_spent']) && wp_verify_nonce($_POST['nonce_time_spent'],'nonce_time_spent'))  {
            update_post_meta($post_id, 'time_spent', $_POST['time_spent']);
        } else {
            delete_post_meta($post_id, 'time_spent');
        }
    }
}