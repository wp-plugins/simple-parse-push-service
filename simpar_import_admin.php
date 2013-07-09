<?php   

    if($_POST['simpar_hidden'] == 'Y') {  
        //Form data sent 
        $sppsAppName = $_POST['simpar_appName'];  
        update_option('simpar_appName', $sppsAppName); 

        $sppsAppID = $_POST['simpar_appID'];  
        update_option('simpar_appID', $sppsAppID);  
          
        $sppsRestApi = $_POST['simpar_restApi'];  
        update_option('simpar_restApi', $sppsRestApi); 

        $sppsAutoSendTitle = '';
        if (isset($_POST['simpar_autoSendTitle'])) {  
        	update_option('simpar_autoSendTitle', 'true');
        	$sppsAutoSendTitle = ' checked="checked"';
        }
        else
        	update_option('simpar_autoSendTitle', 'false');

        $sppsIncludePostID = '';
        if (isset($_POST['simpar_includePostID'])) {
            update_option('simpar_includePostID', 'true');
            $sppsIncludePostID = ' checked="checked"';
        }
        else
            update_option('simpar_includePostID', 'false');


        $sppsSaveLastMessage = '';
        if (isset($_POST['simpar_saveLastMessage'])) {  
            update_option('simpar_saveLastMessage', 'true');
            $sppsSaveLastMessage = ' checked="checked"';
        }
        else
            update_option('simpar_saveLastMessage', 'false');

        $sppsEnableSound = '';
        if (isset($_POST['simpar_enableSound'])) {  
            update_option('simpar_enableSound', 'true');
            $sppsEnableSound = ' checked="checked"';
        }
        else
            update_option('simpar_enableSound', 'false');
        ?>  
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>  
    <?php
    } else {  
        //Normal page display  
        $sppsAppName   = get_option('simpar_appName');
        $sppsAppID     = get_option('simpar_appID');  
        $sppsRestApi   = get_option('simpar_restApi'); 
       	$sppsAutoSendTitle = '';
       	if (get_option('simpar_autoSendTitle') == 'true') 
       		$sppsAutoSendTitle = ' checked="checked"';
        $sppsSaveLastMessage = '';
        if (get_option('simpar_saveLastMessage') == 'true') 
            $sppsSaveLastMessage = ' checked="checked"';
        $sppsEnableSound = '';
        if (get_option('simpar_enableSound') == 'true') 
            $sppsEnableSound = ' checked="checked"';

        $sppsIncludePostID = '';
        if (get_option('simpar_includePostID') == 'true')
            $sppsIncludePostID = ' checked="checked"';
    }  


    if ($_POST['simpar_push_hidden'] == 'Y') {
    	$msg = $_POST['simpar_push_message'];
    	$badge = $_POST['simpar_push_badge'];

    	if (get_option('simpar_appID') == null || get_option('simpar_restApi') == null || $msg == null)
    	{ 
    		?>
    		<div class="error"><p><strong><?php _e('Fill all Parse.com Account settings, write a message and try again.' ); ?></strong></p></div>
    		<?php
    	}
    	else
    	{
    		include('pushFunctionality.php');
    		echo sendPushNotification(get_option('simpar_appID'), get_option('simpar_restApi'), $msg, $badge);
    	}
    }
?> 





<div class="wrap">  
    <?php    echo "<h2>" . __( 'Simple Parse Push Service', 'simpar_trdom' ) . "</h2>"; ?>  
    <form name="simpar_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
        <input type="hidden" name="simpar_hidden" value="Y">  
        <?php    echo "<h3>" . __( 'Parse.com Push Service - Settings', 'simpar_trdom' ) . " (Parse.com <a href=\"http://parse.com/apps\" target=\"_blank\">Dashboard</a>)</h3>"; ?>  
        
        <p><?php _e("Application name: " ); ?>
        	<input type="text" name="simpar_appName" value="<?php echo $sppsAppName; ?>" size="30">
        </p>  
        <p><i><?php _e("Application ID: " ); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
        	<input type="text" name="simpar_appID" value="<?php echo $sppsAppID; ?>" size="30">
        </p>  
        <p><i><?php _e("REST API Key: " ); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
        	<input type="text" name="simpar_restApi" value="<?php echo $sppsRestApi; ?>" size="30">
        </p> 
        <p>&nbsp;</p>
        <p>
            <input type="checkbox" name="simpar_enableSound" <?php echo $sppsEnableSound; ?> > Enable sound
        </p>
        <p>
        	<input type="checkbox" name="simpar_autoSendTitle" <?php echo $sppsAutoSendTitle; ?> > Send post title with Push Notification as default
        </p>
        <p>
            <input type="checkbox" name="simpar_saveLastMessage" <?php echo $sppsSaveLastMessage; ?> > Remember last used message in posts
        </p>
        <p>
            <input type="checkbox" name="simpar_includePostID" <?php echo $sppsIncludePostID; ?> > Auto include post_ID as extra parameter (you' ll find it in json payload with "post_id" dict name)
        </p>
      
        <p class="submit">  
        <input type="submit" name="Submit" class="button button-primary" value="<?php _e('Update Options', 'simpar_trdom' ) ?>" />  
        </p>  
    </form>

    <hr /> 
    <form name="sendPush_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <h3>Send a Push Notification right now!</h3>
        <input type="hidden" name="simpar_push_hidden" value="Y">  
        <p><i><?php _e("Message:"); ?></i> <input type="text" name="simpar_push_message" size="30"></p>
        <p><i><?php _e("Badge:"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i> <input type="text" name="simpar_push_badge" size="30"> <i>0 or 1 or 2...  "increment" value also works (for iOS)</i></p>

        <p class="submit">  
        <input type="submit" name="Submit" class="button button-action" value="<?php _e('Send Push Notification') ?>" />  
        </p> 
    </form>

    
    <div style="text-align:center; margin-top:10px;">
        If you like this plugin, please consider a donation!  
        <br/>Thank you for your support!<br/>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHZwYJKoZIhvcNAQcEoIIHWDCCB1QCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYCX8x8/gNLnq/bMNjjVMHrA9N8/cllbgYH78yeQEpcStCfv1J2I3m25BzulL/t+tSEZjPpqwoB4kjvolG8DJZcEnfMtPovtjjexZ0gf3GhYIHtpnzCQaRuoRX1EubCq0ra1Sdp4hKCmV0art7amxR6Vn6zS7W32BF2kMb1oSGslxDELMAkGBSsOAwIaBQAwgeQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIESLDMuvwlWWAgcDB3ik9TK+kf3Yh3qKybBz9MY8MJUR2ZixXkD7mU5TcKZ/dMl32Up0ZmVInErv/8gOSrSBpr/EthBMLihNV8O0xjHkR6JTCpD66Y+T5ZY7G7/ZTy2iP0kf3zwPqg0amfq3Ft7nLW04tn/ocWO+uKBfBBx+Kw8aEMAzy1KwCbZiXVYijIuYQMTy6t+X+GswaCiA74TuSCSs5E/Nx0zvqUrBK4C0+DiIuAU5mGKZ0toXH/fDGfD64Y4/+nWWsiwyYENWgggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xMzA2MjIxNTAyMTBaMCMGCSqGSIb3DQEJBDEWBBSLorXU+F3EQQXZIeY66B1R/h6WtTANBgkqhkiG9w0BAQEFAASBgGFrohA3C9CfV7BEo+wmlBgT5B6oVrzX8Uy7EOwZUHqUOaR0mzePHBIVEllc5LYcR2J1CQy7lTIILSreZvipXZgjUE9zsAZiBIEKjNWYP1QgQAsRHbIPer9iTMqL0djUbk03dXyUv3t2qBQVAVXvIF7KDvX3+F7x8s6NQ4kokJk8-----END PKCS7-----
            ">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form> 
    </div>
</div>  