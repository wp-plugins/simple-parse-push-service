<?php
/**
 * @package Simple_Parse_Push_Service
 * @version 1.0.1
 */
/*
Plugin Name: Simple Parse Push Service
Plugin URI: http://wordpress.org/plugins/simple-parse-push-service/
Description: This is a simple implementation for Parse.com Push Service (for iOS, Android, Windows 8 or any other devices may add). You can send a push notification via admin panel or with a post update/creation. In order to use this plugin you MUST have an account with Parse.com and cURL ENABLED.
Author: Tsolis Dimitris - Sotiris
Version: 1.0.1
Author URI: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('SPPS_VERSION')) define('SPPS_VERSION', '1.0.1');

/////////////////////////////////////////////////////////
// fuctions for 'send push notifications on edit' menu //
/////////////////////////////////////////////////////////
function simpar_admin_init() {
    
    if ( !function_exists('curl_version') ) {

        function simpar_curl_warning() {
            echo "<div id='simpar-curl-warning' class='updated fade'><p><strong>".__("cURL is NOT installed on this server. cURL is necessary for 'Simple Parse Push Service' plugin in order to work.", 'simpar_context')."</strong> </p></div>";
        }
        add_action('admin_notices', 'simpar_curl_warning'); 
        
        return; 
    } else if ( get_option('simpar_appID') == null || get_option('simpar_restApi') == null) {
        
        function simpar_appname_warning() {
            echo "<div id='simpar-warning' class='updated fade'><p><strong>".sprintf(__("'Simple Parse Push Service %s' plugin needs to be configured.", 'simpar_context'), SPPS_VERSION) ."</strong> ".sprintf(__('Please go to <a href="%s">Simple Parse Push Service admin menu</a> to configure your Parse Account keys.', 'simpar_context'), get_bloginfo('url').'/wp-admin/options-general.php?page=Simple-Parse-Push-Service')."</p></div>";
        }
        add_action('admin_notices', 'simpar_appname_warning'); 
        
        return; 
    } else {
    	add_meta_box( 
	        'simpar_tid_post',
	        'Simple Parse Push Notification',
	        'simpar_boxcontent',
	        'post',
	        'side',
	        'high'
	    );
    }
}

function simpar_boxcontent() {
	wp_nonce_field( plugin_basename(__FILE__), 'simpar_nonce' );
	  
	$selected = '';
	$checked = '';
	if (get_option('simpar_autoSendTitle') == 'true') {
		$selected = ' selected="selected"';
		$checked  = ' checked="checked"';
	}

	$sppsLastMessage = '';
    if (get_option('simpar_saveLastMessage') == 'true') {
        $sppsLastMessage = get_option('simpar_lastMessage');
        $selected = ' selected="selected"';
    }

    $includePostIDChecked  = '';
    if (get_option('simpar_includePostID') == 'true') {
    	$includePostIDChecked  = ' checked="checked"';
    }

	echo '<label for="simpar_pushText">';
		_e("Alert Message", 'simpar_context');
	echo '</label><br/>';
	echo '<input id="simpar_pushText" type="text" name="simpar_pushText" value="'.$sppsLastMessage.'" size=\"30\"><br/>';

	echo '<label for="simpar_pushBadge">';
		_e("Badge(0 or 1... 'increment' also works (for iOS))", 'simpar_context');
	echo '</label><br/>';
	echo '<input id="simpar_pushBadge" type="text" name="simpar_pushBadge" value"'.__("", 'simpar_context').'" size=\"10\"<br/><br/>';

	echo '<input id="simpar_titleCheckBox" type="checkbox" name="simpar_titleCheckBox"'.$checked.'>&nbsp;Send title as message.<br/><br/>';
	echo '<input id="simpar_includePostIDCheckBox" type="checkbox" name="simpar_includePostIDCheckBox"'.$includePostIDChecked.'>&nbsp;Include postID as extra param.<br/><br/>';

	echo '<label for="simpar_activate">';
       _e("Activate Push Notifications for this post ? ", 'simpar_context' );
	echo '</label><br/>';
	echo '<select id="simpar_activate" name="simpar_activate"><option value="0">'.__("No", 'simpar_context' ).'</option><option value="1"'.$selected.'>'.__("Yes", 'simpar_context' ).'</option></select>';
}

////////////////////////////
// send push notification //
////////////////////////////
function simpar_send_post($post_ID) {
	$message = $_REQUEST['simpar_pushText'];
	if ( !wp_verify_nonce( $_POST['simpar_nonce'], plugin_basename(__FILE__) ) OR !intval($_POST['simpar_activate']) OR ($message == null && !isset($_POST['simpar_titleCheckBox'])))
		return $post_ID;

	if (get_option('simpar_saveLastMessage') == 'true') 
		update_option('simpar_lastMessage', $message);
	else
		update_option('simpar_lastMessage', '');

	if (isset($_POST['simpar_titleCheckBox'])) {
		$message = html_entity_decode(get_the_title($post_ID),ENT_QUOTES,'UTF-8');
	}

	$incPostID = null;
	if (isset($_POST['simpar_includePostIDCheckBox']))
		$incPostID = $post_ID;
	
	$badge = $_REQUEST['simpar_pushBadge'];	
	include('pushFunctionality.php');
	sendPushNotification(get_option('simpar_appID'), get_option('simpar_restApi'), $message, $badge, $incPostID);


    return $post_ID;
}

//////////////////////////
// admin, settings menu //
//////////////////////////
function simpar_admin() {
	include('simpar_import_admin.php');
}

function simpar_admin_actions() {  
      add_menu_page("Simple Parse Push Service", "Simple Parse Push Service", 1, "Simple-Parse-Push-Service", "simpar_admin");
}  

////////////////////////////////////////
// on (un)install/(de)activate plugin //
////////////////////////////////////////
function simpar_plugin_on_uninstall(){
    //Remove the all options
    delete_option('simpar_appName');
	delete_option('simpar_appID');
	delete_option('simpar_restApi');
	delete_option('simpar_autoSendTitle');
	delete_option('simpar_saveLastMessage');
	delete_option('simpar_enableSound');
	delete_option('simpar_lastMessage');
	delete_option('simpar_includePostID');
 
    /*Remove any other options you may add in this plugin and clear any plugin cron jobs */
}
  

////////////////////////
// register functions //
////////////////////////
add_action('admin_init', 'simpar_admin_init', 1);
add_action('admin_menu', 'simpar_admin_actions');  
add_action('publish_post', 'simpar_send_post');
register_uninstall_hook(__FILE__, 'simpar_plugin_on_uninstall');


?>
