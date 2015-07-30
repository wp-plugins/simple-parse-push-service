<?php
/**
 * @package Simple_Parse_Push_Service
 * @version 1.3.5
 */
/*
Plugin Name: Simple Parse Push Service
Plugin URI: http://wordpress.org/plugins/simple-parse-push-service/
Description: This is a simple implementation for Parse.com Push Service (for iOS, Android, Windows, Windows Phone or any other devices may add). You can send a push notification via admin panel or with a post update/creation. In order to use this plugin you MUST have an account with Parse.com and cURL ENABLED.
Author: Tsolis Dimitris - Sotiris
Version: 1.3.5
Author URI: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
 * Global variables
 *
 */
$scheduledPosts = array();

if (!defined('SPPS_VERSION')) define('SPPS_VERSION', '1.3.5');

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
    	global $scheduledPosts;
    	$scheduledPosts = get_option('simpar_scheduled_message_options');
    	if ($scheduledPosts == '') {
    		$scheduledPosts = array();
    	}


    	$sppsMetaBoxPriority = get_option('simpar_metaBoxPriority');
        if ($sppsMetaBoxPriority == '') {
            $sppsMetaBoxPriority = 'high';
        }

        /* 
         * Enable meta box and the appropriate hooks
         * for each post type available
         ======================================================== */
        
        $savedPostTypes = get_option('simpar_metabox_pt');

        if ( count( $savedPostTypes ) == 0) { // bug fix: admin area is really slow...
        	$savedPostTypes[] = 'post';
        	addOrUpdateOption('simpar_metabox_pt', $savedPostTypes);
        	$savedPostTypes = get_option('simpar_metabox_pt');
        } else {
        	// distincts the post-types array
        	// remaining from the bug above that was filling the array with the same data
			$savedPostTypes = array_unique($savedPostTypes);
        }

        foreach ($savedPostTypes as $postType) {
        	add_meta_box( 
		        'simpar_tid_post',
		        'Simple Parse Push Notification',
		        'simpar_boxcontent',
		        $postType,
		        'side',
		        $sppsMetaBoxPriority
		    );


			add_action('publish_'.$postType, 'simpar_send_post');
        }
    	
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

    $sendToChannelsChecked = '';
    if (get_option('simpar_sendToChannels') == 'true') {
    	$sendToChannelsChecked = ' checked="checked"';
    }

	echo '<label for="simpar_pushText">';
		_e("Alert Message", 'simpar_context');
	echo '</label><br/>';
	echo '<input id="simpar_pushText" type="text" name="simpar_pushText" value="'.$sppsLastMessage.'" size=\"30\"><br/>';

	echo '<label for="simpar_pushBadge">';
		_e("Badge(0 or 1... 'increment' also works (for iOS))", 'simpar_context');
	echo '</label><br/>';
	echo '<input id="simpar_pushBadge" type="text" name="simpar_pushBadge" value"'.__("", 'simpar_context').'" size=\"10\"<br/><br/>';

	echo '<input id="simpar_titleCheckBox" type="checkbox" name="simpar_titleCheckBox"'.$checked.'>&nbsp;Send title as message (ignore the textbox above).<br/><br/>';
	echo '<input id="simpar_includePostIDCheckBox" type="checkbox" name="simpar_includePostIDCheckBox"'.$includePostIDChecked.'>&nbsp;Include postID as extra param.<br/><br/>';

	echo '<label for="simpar_activate">';
       _e("Activate Push Notifications for this post ? ", 'simpar_context' );
	echo '</label><br/>';
	echo '<select id="simpar_activate" name="simpar_activate"><option value="0">'.__("No", 'simpar_context' ).'</option><option value="1"'.$selected.'>'.__("Yes", 'simpar_context' ).'</option></select>';
}

/////////////
// Helpers //
/////////////
function addOrUpdateOption($option_name, $value) {
	if ( get_option( $option_name ) !== false ) {
		if ( get_option( $option_name ) !== $value)
	    	update_option( $option_name, $value );
	} else {
	    add_option( $option_name, $value );
	}
}

function indexForScheduledPost($post_ID) {
	global $scheduledPosts;
	$scheduledPosts = get_option( 'simpar_scheduled_message_options' );
	$index = -1;
    for ($i=0; $i < count( $scheduledPosts ); $i++) { 
    	$tmpArray = $scheduledPosts[$i];
    	if ( $tmpArray['post_id'] == $post_ID) {
    		return $i;
    	}
    }
    return $index;
}

function removeScheduledPost($post_ID) {
	$index = indexForScheduledPost($post_ID);

	global $scheduledPosts;
	$scheduledPosts = get_option( 'simpar_scheduled_message_options' );

    if ($index > -1) {
    	$tmpArray = $scheduledPosts[$index];

    	// remove the scheduled push...
    	unset( $scheduledPosts[$index] );
    	$scheduledPosts = array_values( $scheduledPosts );
    	// ...and save update the cached array
		addOrUpdateOption( 'simpar_scheduled_message_options', $scheduledPosts );
		$scheduledPosts = get_option( 'simpar_scheduled_message_options' );
    }
}

function metaboxParamsFilter($post_ID) {
	$returnArray = array('message' => '',
						 'badge' => '',
						 'post_id' => null);
	$message = null;
	if ( isset($_REQUEST['simpar_pushText']) )
		$message = $_REQUEST['simpar_pushText'];

	if ( !isset($_POST['simpar_nonce']) OR !wp_verify_nonce( $_POST['simpar_nonce'], plugin_basename(__FILE__) ) OR !intval($_POST['simpar_activate']) OR ($message == null && !isset($_POST['simpar_titleCheckBox'])))
		return null;

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
	
	$badge = '';
	if ( isset( $_REQUEST['simpar_pushBadge'] ) ) 
		$badge = $_REQUEST['simpar_pushBadge'];

	$returnArray['message'] = $message;
	$returnArray['badge']   = $badge;
	$returnArray['post_id'] = $incPostID;
	return $returnArray;
}

////////////////////////////
// send push notification //
////////////////////////////
function simpar_send_post($post_ID) {
	if ( !isset( $_POST['simpar_pushText'] ) ) {
		// if false, this post is published automatically and not the time user hit 'publish'
		return;
	}

	$values = metaboxParamsFilter($post_ID);
	if ($values == null)
		return $post_ID;

	include('pushFunctionality.php');
	sendPushNotification(get_option('simpar_appID'), get_option('simpar_restApi'), $values['message'], $values['badge'], $values['post_id'], get_option('simpar_pushChannels'));

    return $post_ID;
}

function simpar_future_to_publish($post) {

	$validPostTypes = get_option('simpar_metabox_pt');
	if (!in_array($post->post_type, $validPostTypes)) {
		return;
	}

    global $scheduledPosts;
	$scheduledPosts = get_option( 'simpar_scheduled_message_options' );
    $index = indexForScheduledPost($post->ID);

    if ($index > -1) {
    	$tmpArray = $scheduledPosts[$index];
		include('pushFunctionality.php');
		sendPushNotification(get_option('simpar_appID'), get_option('simpar_restApi'), $tmpArray['message'], $tmpArray['badge'], $post->ID, get_option('simpar_pushChannels'));

    	// remove the scheduled push...
    	unset( $scheduledPosts[$index] );
    	$scheduledPosts = array_values( $scheduledPosts );
    	// ...and save update the cached array
		addOrUpdateOption( 'simpar_scheduled_message_options', $scheduledPosts );
		$scheduledPosts = get_option( 'simpar_scheduled_message_options' );
    }
}

function simpar_save_post($new_status, $old_status, $post) {
	if ( $old_status == 'draft' && $new_status == 'publish' ) {
		simpar_send_post($post->ID);
    }

	if ( get_option('simpar_discardScheduledPosts') == 'true' )
		return; // disabled by user


	$values = metaboxParamsFilter($post->ID);
	if ($values == null)
		return $post->ID;

	global $scheduledPosts;


	$posttime = strtotime($post->post_date); // date to be published
	$currtime = time();						 // NOW
	$diff = $posttime - $currtime;			 // difference (if diff > 0 then the post is scheduled to be published)

	if ($new_status != 'future' || ($new_status == 'new' && $diff <= 0)) { 
		// this means that every check needed, made in 'publish_post' function
		// no need to cache any options for future publish
		return $post->ID;
	}

	$validPostTypes = get_option('simpar_metabox_pt');
	if (!in_array($post->post_type, $validPostTypes)) {
		return;
	}


	$scheduledPostsInfo = array('message'      => $values['message'],
								'badge'        => $values['badge'],
								'post_type'    => $post->post_type,
								'post_id'      => $post->ID,
								'last_updated' => time());
	$index = indexForScheduledPost($post->ID);
	$scheduledPosts = get_option('simpar_scheduled_message_options');
	if ( $index == -1) {
		$scheduledPosts[] = $scheduledPostsInfo;
	}
	else {
		$scheduledPosts[$index] = $scheduledPostsInfo;
	}
	addOrUpdateOption( 'simpar_scheduled_message_options', $scheduledPosts );
	$scheduledPosts = get_option( 'simpar_scheduled_message_options' );
}

//////////////////////////
// admin, settings menu //
//////////////////////////
function simpar_admin() {
	include('simpar_import_admin.php');
}

function simpar_submenu() {
	include('simpar_import_pending_notf.php');
}

function simpar_admin_actions() {  

    add_menu_page("Simple Parse Push Service", "Simple Parse Push Service", 'manage_options', "Simple-Parse-Push-Service", "simpar_admin");
	$pending_notf_page = add_submenu_page( "Simple-Parse-Push-Service", "Settings", "Settings", "manage_options", "Simple-Parse-Push-Service", "simpar_admin" );
	$pending_notf_page = add_submenu_page( "Simple-Parse-Push-Service", "Pending Notifications", "Pending Notifications", "manage_options", "spps_pending_notifications", "simpar_submenu" );
	add_action( "admin_head-{$pending_notf_page}", 'my_admin_head_script' );

	/* 
	 * enqueue javascript to make 'postbox'-es 
	 * act like the dashboard 'postbox'-es
	 * ======================================================== */
	wp_enqueue_script("dashboard");
} 


/*
 * Additional javascript or css
 * ============================================ */
function my_admin_head_script() { 
	wp_enqueue_script( 'admin-pending-notf-js', plugin_dir_url( __FILE__ ).'js/pendingNotificationsAdmin.js' );
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
	delete_option('simpar_metaBoxPriority');
 	delete_option('simpar_doNotIncludeChannel');
 	delete_option('simpar_pushChannels');
 	delete_option('simpar_scheduled_message_options');
 	delete_option('simpar_hide_warning');
 	delete_option('simpar_discardScheduledPosts');
 	delete_option('simpar_metabox_pt');
    /*Remove any other options you may add in this plugin and clear any plugin cron jobs */
}
  

////////////////////////
// register functions //
////////////////////////
add_action('admin_init', 'simpar_admin_init', 1);
add_action('admin_menu', 'simpar_admin_actions');  
add_action('future_to_publish', 'simpar_future_to_publish');
add_action( 'transition_post_status', 'simpar_save_post', 10, 3 );
register_uninstall_hook(__FILE__, 'simpar_plugin_on_uninstall');


?>
