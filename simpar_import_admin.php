<?php  

    /////////////////////////////
    // Working with Parameters //
    /////////////////////////////
    if(isset( $_POST['simpar_hidden'] ) && ( $_POST['simpar_hidden'] == 'Y' )) {  
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


        $sppsDiscardScheduledPosts = '';
        if (isset($_POST['simpar_discardScheduledPosts'])) {
            update_option('simpar_discardScheduledPosts', 'true');
            $sppsDiscardScheduledPosts = ' checked="checked"';
        }
        else
            update_option('simpar_discardScheduledPosts', 'false');


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


        $sppsDoNotIncludeChannel = '';
        if (isset($_POST['simpar_doNotIncludeChannel'])) {  
            update_option('simpar_doNotIncludeChannel', 'true');
            $sppsDoNotIncludeChannel = ' checked="checked"';
        }
        else
            update_option('simpar_doNotIncludeChannel', 'false');

        $sppsPushChannels = trim($_POST['simpar_pushChannels'], " ");
        update_option('simpar_pushChannels', $sppsPushChannels);

        $sppsMetaBoxPriority = $_POST['simpar_metaBoxPriority'];
        update_option('simpar_metaBoxPriority', $sppsMetaBoxPriority);


        if (isset($_POST['simpar_metabox_pt'])) {
            addOrUpdateOption('simpar_metabox_pt', $_POST['simpar_metabox_pt']);
        }
        else {
            delete_option('simpar_metabox_pt');
        }
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

        $sppsDiscardScheduledPosts = '';
        if (get_option('simpar_discardScheduledPosts') == 'true')
            $sppsDiscardScheduledPosts = ' checked="checked"';

        $sppsPushChannels = get_option('simpar_pushChannels');

        $sppsDoNotIncludeChannel = '';
        if (get_option('simpar_doNotIncludeChannel') == 'true') 
            $sppsDoNotIncludeChannel = ' checked="checked"';

        $sppsMetaBoxPriority = get_option('simpar_metaBoxPriority');
        if ($sppsMetaBoxPriority == '') {
            $sppsMetaBoxPriority = 'high';
        }
    }  


    if (isset( $_POST['simpar_push_hidden'] ) && ( $_POST['simpar_push_hidden'] == 'Y' )) {
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
    		echo sendPushNotification(get_option('simpar_appID'), get_option('simpar_restApi'), $msg, $badge, null, get_option('simpar_pushChannels'), $_POST['pushExtraKey'], $_POST['pushExtraValue']);
    	}
    }
?> 





<?php
//////////////////
// Main content //
//////////////////
?>





<div class="wrap">
    
    <div id="icon-options-general" class="icon32"></div>
    <h2>Simple Parse Push Service</h2>
    
    <div id="poststuff">
    
        <div id="post-body" class="metabox-holder columns-2">
        
            <!-- main content -->
            <div id="post-body-content">
                
                <div class="meta-box-sortables ui-sortable">
                    
                    <div class="postbox">
                    
                        <h3><span><?php    echo __( 'Parse.com Push Service - Settings', 'simpar_trdom' ) . " (Parse.com <a href=\"http://parse.com/apps\" target=\"_blank\">Dashboard</a>)"; ?>  </span></h3>
                        <div class="inside">
                            <form name="simpar_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
                                <input type="hidden" name="simpar_hidden" value="Y">  
        
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell"><?php _e("Application name: " ); ?></label></td>
                                        <td><input type="text" name="simpar_appName" value="<?php echo $sppsAppName; ?>" class="regular-text"></td>
                                    </tr>
                                    <tr valign="top" class="alternate">
                                        <td scope="row"><label for="tablecell"><i><?php _e("Application ID: " ); ?></i></label></td>
                                        <td><input type="text" name="simpar_appID" value="<?php echo $sppsAppID; ?>" class="regular-text"></td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell"><i><?php _e("REST API Key: " ); ?></i></label></td>
                                        <td><input type="text" name="simpar_restApi" value="<?php echo $sppsRestApi; ?>" class="regular-text"></td>
                                    </tr>
                                    <tr valign="top" class="alternate">
                                        <td scope="row"><label for="tablecell">Sound</label></td>
                                        <td>
                                            <input type="checkbox" name="simpar_enableSound" <?php echo $sppsEnableSound; ?> > Enable
                                            <p class="description">Enable the default sound for Push Notifications.</p>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">Notification title</label></td>
                                        <td><input type="checkbox" name="simpar_autoSendTitle" <?php echo $sppsAutoSendTitle; ?> > Send post's title as the Push Notification's title
                                            <p class="description">This option is available while you edit a post or create a new one.</p></td>
                                    </tr>
                                    <tr valign="top" class="alternate">
                                        <td scope="row"><label for="tablecell">Notification message</label></td>
                                        <td>
                                            <input type="checkbox" name="simpar_saveLastMessage" <?php echo $sppsSaveLastMessage; ?> > Remember last used message in posts
                                            <p class="description">You can check this option and send a default message (e.g. Check out my new post! ) every time you create a new post.</p>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">Post id</label></td>
                                        <td><input type="checkbox" name="simpar_includePostID" <?php echo $sppsIncludePostID; ?> > Auto include post_ID as extra parameter
                                            <p class="description">See the 'Sample Payload' for more technical info.</p>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">Discard for scheduled</label></td>
                                        <td><input type="checkbox" name="simpar_discardScheduledPosts" <?php echo $sppsDiscardScheduledPosts; ?> > Do not save Push Notification for scheduled posts
                                            <p class="description">If this is disabled, every time you schedule a post for future publish, the appropriate Push Notification (if any) will be saved add Pushed with post's publication. Existing (saved) push notifications won't be affected.</p>
                                        </td>
                                    </tr>
                                </table>

                                <!-- settings - about push channels -->
                                <hr/>
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">Push channels</label></td>
                                        <td><input type="text" name="simpar_pushChannels" placeholder="e.g. news,sports,tennis" value="<?php echo $sppsPushChannels; ?>" class="regular-text">
                                            <p class="description"><strong>Comma</strong> separated and <strong>without</strong> spaces, names for the channels you want to be receiving the notifications. If empty, global broadcast channel (GBC) is selected (GBC is an empty string).</p>
                                        </td>
                                    </tr>
                                    <tr valign="top" class="alternate">
                                        <td scope="row"><label for="tablecell"></label></td>
                                        <td><input type="checkbox" name="simpar_doNotIncludeChannel" <?php echo $sppsDoNotIncludeChannel; ?> > Do not include ANY channel. Send notifications to everyone.</td>
                                    </tr>
                                </table>
                                
                                <!-- settings - meta box -->
                                <hr/>
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell"><?php _e("Meta Box priority " ); ?></label></td>
                                        <td>
                                            <select name="simpar_metaBoxPriority">
                                                <?php
                                                    $priorities = array('high', 'core', 'default', 'low');
                                                    for ($i = 0; $i < 4; $i++) {
                                                        if ($priorities[$i] == $sppsMetaBoxPriority) {
                                                            echo "<option selected value='$priorities[$i]'>$priorities[$i]</option>";
                                                        }
                                                        else {
                                                            echo "<option value='$priorities[$i]'>$priorities[$i]</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <p class="description">The priority for the 'Meta Box' inside the 'edit post' menu.
                                        </td>
                                    </tr>
                                </table>


                                <!-- settings - post types with metabox enabled -->
                                <hr/>
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row">
                                            <label for="tablecell">
                                                <h3><span><?php echo __( 'Post Types with MetaBox enabled', 'simpar_trdom' ) ?></span></h3>
                                            </label>

                                            <?php
                                                $savedPostTypes = get_option('simpar_metabox_pt');
                                           
                                                /* Posts are pre-defined
                                                =================================== */
                                                echo '<input type="checkbox" disabled checked/> Posts <br/>';
                                                echo '<input type="hidden" name="simpar_metabox_pt[]" value="post" />';
                                            
                                                /* Check if pages are selected
                                                ==================================== */
                                                $sppsSavedPage = '';
                                                if (in_array('page', $savedPostTypes))
                                                    $sppsSavedPage = ' checked="checked"';
                                                // die( print_r($savedPostTypes));
                                                echo '<input type="checkbox" name="simpar_metabox_pt[]" value="page"'.$sppsSavedPage.'/> Pages <br/>';
                                           

                                                /* Check for custom types
                                                ==================================== */
                                                $args = array('_builtin' => false, );
                                                $post_types = get_post_types( $args, 'objects' ); 
                                                foreach ( $post_types as $post_type ) {

                                                    $sppsSaved = '';
                                                    if (in_array($post_type->name, $savedPostTypes))
                                                        $sppsSaved = ' checked="checked"';
                                                    echo '<input type="checkbox" name="simpar_metabox_pt[]" value="'.$post_type->name.'" '.$sppsSaved.' />'.$post_type->label.' <br/>';
                                                }
                                            ?>

                                        </td>
                                    </tr>
                                </table>



                                <p class="submit">
                                    <input type="submit" name="Submit" class="button button-primary" value="<?php _e('Update Options', 'simpar_trdom' ) ?>" />
                                </tr>
                            </form>


                        </div> <!-- .inside -->
                    
                    </div> <!-- .postbox -->


                    <div id="sendNow" style="height:40px;"></div>
                    <div class="postbox">
                        <h3><span>Send a Push Notification right now!</span></h3>
                        <div class="inside">
                            <!-- push dashboard -->
                            <form name="sendPush_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                                <input type="hidden" name="simpar_push_hidden" value="Y">  
                                
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell"><i><?php _e("Message:"); ?></i></label></td>
                                        <td><input type="text" name="simpar_push_message" class="regular-text"></td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell"><i><?php _e("Badge:"); ?></i></label></td>
                                        <td><input type="text" name="simpar_push_badge" class="regular-text">
                                            <p class="description"><i>0 or 1 or 2...  "increment" value also works (for iOS)</i></p>
                                        </td>
                                    </tr>
                                </table>
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row"><?php _e("Extra key") ?> <input type="text" name="pushExtraKey" class="regular-text">
                                        <?php _e("Extra value") ?> <input type="text" name="pushExtraValue" class="regular-text"></td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row" >
                                            <p class="description">
                                                With these extra key/value fields, you can add an extra parameter into the push notification payload as in the 'Sample Payload' (with post_id beeing the extra parameter). 
                                                You can find more information about <a href="https://www.parse.com/docs/push_guide#receiving-responding/iOS">Responding to the Payload</a> reading <a href="http://www.parse.com">Parse.com</a>'s <a href="https://www.parse.com/docs/">documentation</a>.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                <p class="submit">  
                                    <input type="submit" name="Submit" class="button button-action" value="<?php _e('Send Push Notification') ?>" />  
                                </p> 
                            </form>
                        </div>
                    </div> <!-- .sent push - box -->
                    
                </div> <!-- .meta-box-sortables .ui-sortable -->
                
            </div> <!-- post-body-content -->
            
            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h3><span>Sample payload</span></h3>
                        <div class="inside">
                            <p style="text-align:justify;">
                           This payload will be received by every iOS device. Similar will be on Android and Windows (Phone) too.<br/> 
                           The thing to <strong>notice</strong> here, is the "post_id" key, which contains a post's id.</p>
<pre> 
{
  "aps":{
    "alert":"alert message",
    "sound":"default"
  },
  "post_id":324
}
</pre>
                        </div>
                    </div>

                    <div class="postbox">
                    
                        <h3><span>Donation</span></h3>
                        <div class="inside">
                            <div style="text-align:center; margin-top:10px;">
                                If you like this plugin, please consider a donation!  
                                <br/>Thank you for your support!<br/>
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                    <input type="hidden" name="cmd" value="_s-xclick">
                                    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
                                    ">
                                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                </form> 
                            </div>
                        </div> <!-- .inside -->
                        
                    </div> <!-- .postbox -->
                    
                </div> <!-- .meta-box-sortables -->
                
            </div> <!-- #postbox-container-1 .postbox-container -->
            
        </div> <!-- #post-body .metabox-holder .columns-2 -->
        
        <br class="clear">
    </div> <!-- #poststuff -->
    
</div> <!-- .wrap -->
