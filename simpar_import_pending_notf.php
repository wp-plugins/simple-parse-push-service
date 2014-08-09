<?php
	global $scheduledPosts;
	$scheduledPosts = get_option('simpar_scheduled_message_options');


    if (isset( $_POST['simpar_delete_hidden'] ) && ( $_POST['simpar_delete_hidden'] == 'Y' )) { 

        if (isset($_POST['simpar_scheduled_posts'])) {
            foreach ($_POST['simpar_scheduled_posts'] as $post_ID) {

                $index = indexForScheduledPost($post_ID);

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
            ?>
            <div class="updated"><p><strong><?php _e( 'Push notifications removed from queue.' ); ?></strong></p></div>
            <?php
        }

    }
    else if (isset( $_POST['simpar_hide_warning'] ) && ( $_POST['simpar_hide_warning'] == 'Y' )) { 
        update_option( 'simpar_hide_warning', 'hide' );
    }
?>



<div class="wrap">
    
    <div id="icon-options-general" class="icon32"></div>
    <h2>Simple Parse Push Service - Pending Notifications</h2>

    <div id="poststuff">
    
        <div id="post-body" class="metabox-holder columns-2">
        
            <!-- main content -->
            <div id="post-body-content">
                
                <div class="meta-box-sortables ui-sortable">

                    <?php 
                    if ( get_option( 'simpar_hide_warning' ) == 'hide' ) {
                        echo '<div id="my_div" class="postbox closed">';
                    }
                    else {
                        echo '<div id="my_div" class="postbox">';
                    }
                    ?>  
                        <h3 class="hndle">
                            <span>WARNING!!! (click to toggle)</span>
                        </h3>
                        <div class="inside">
                            <p>
                                Wordpress, in order to publish a scheduled post, runs a pseudo-cron job WHEN a user visits the site. Any user, any page (admin page or a page a visitor sees).<br/>
                                SO, if for example you have scheduled a post for publish at 16:00 and this website doesn't have a hit until 16:50, the post will actually be published at 16:50 and the Push Notification will be sent at 16:50.<br/>
                                Keep that in mind!
                            </p>
                            <form id="simpar_warning_form" name="simpar_warning_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"> 
                                <input type="hidden" name="simpar_hide_warning" value="Y">  
                                <p class="submit">
                                    <a id="warning_form_submit" href="#" class="button button-secondary"><?php _e('Close me', 'simpar_trdom' ) ?></a> 
                                </p>
                            </form>
                        </div> <!-- .inside -->
                    
                    </div> <!-- .postbox -->

                    <div class="scheduled-posts-queue">

                        <?php   
                        if ( count( $scheduledPosts ) == 0) {
                            echo '<p>Awesome! There aren\'t any pending Push Notifications!</p>';
                        }
                        else {
                        ?>
                            <p><?php echo __('Total notifications: ', 'simpar_trdom' ).'<strong>'.count( $scheduledPosts );?></strong></p>
                            <form name="simpar_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
                                <input type="hidden" name="simpar_delete_hidden" value="Y">  
                                <!--    $scheduledPostsInfo = array('message'      => $values['message'],
                                                                'badge'        => $values['badge'],
                                                                'post_type'    => $post->post_type,
                                                                'post_id'      => $post->ID,
                                                                'last_updated' => time()); -->
                                <table class="widefat">
                                    <thead>
                                        <tr>
                                            <th class="check-column">
                                                <input id="selectall" type="checkbox" />
                                            </th>
                                            <th><strong>Message</strong></th>
                                            <th><strong>Badge</strong></th>
                                            <th><strong>Post Type</strong></th>
                                            <th><strong>Post ID</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 0;
                                            foreach ($scheduledPosts as $tmpPost) {
                                                if ($i % 2 != 0)
                                                    echo '<tr>';
                                                else
                                                    echo '<tr class="alternate">';

                                                echo '  <td>';
                                                echo '  <input type="checkbox" class="simpar_ckb" name="simpar_scheduled_posts[]" value="'.$tmpPost['post_id'].'" />';
                                                echo '  </td>';
                                                echo '  <td>'.$tmpPost['message'].'</td>';
                                                echo '  <td>'.$tmpPost['badge'].'</td>';
                                                echo '  <td>'.$tmpPost['post_type'].'</td>';
                                                echo '  <td><a href="'.get_edit_post_link($tmpPost['post_id']).'">'.$tmpPost['post_id'].'</a></td>';
                                                echo '</tr>';
                                                $i++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                
                                <p class="submit">
                                    <a id="simpar_delete_ask" href="#" class="button button-secondary ">Remove from queue</a> 
                                    <p id='simpar_delete_confirm' style="display: none;">
                                        Are you sure? &nbsp;
                                        <input id="simpar_delete_confirm" type="submit" name="Submit" class="button button-primary" value="<?php _e('Yes, remove them from queue', 'simpar_trdom' ) ?>" />
                                        <a id="simpar_delete_deny" href="#" class="button button-secondary">No, cancel this action</a> 
                                    </p>
                                </tr>
                            </form>


                        <?php
                        }
                        ?>
                    </div> <!-- .scheduled-posts-queue -->
                    
                </div> <!-- .meta-box-sortables .ui-sortable -->
                
            </div> <!-- post-body-content -->
            
            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                
                <div class="meta-box-sortables">
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

<script type="text/javascript">

</script>