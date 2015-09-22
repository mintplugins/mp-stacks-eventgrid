<?php
/**
 * This file contains the ajax callback functions involved in the mp stacks + eventgrid
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Features
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
* Short Description
*
* @since    1.0.0
* @link     http://mintplugins.com/doc/
* @see      function_name()
* @param    array $args See link for description.
* @return   void
*/

/**
 * This function logs a user into WordPress via ajax
 *
 * @since    1.0.0
 * @link     
 * @see      function_name()
 * @param    void
 * @return   void
 */ 
function mp_stacks_eventgrid_email_log_in_via_ajax(){

	
	//If the passed in email is blank, use the one we stored in the php session
	if ( empty( $_POST['mp_stacks_eventgrid_user_email'] ) ){
		$user_email = $_SESSION['user_email'];
		$user_password = $_SESSION['user_password'];
	}
	//If the user email is not blank, use the passed in email address
	else{
		$_SESSION['user_email'] = $_POST['mp_stacks_eventgrid_user_email'];
		$_SESSION['user_password'] = $_POST['mp_stacks_eventgrid_user_password'];
		$user_email = $_POST['mp_stacks_eventgrid_user_email'];
		$user_password = $_POST['mp_stacks_eventgrid_user_password'];
	}
	
	$user = get_user_by( 'login', $user_email );
	
	//If this user is NOT already registered in WP
	if ( is_wp_error( $user ) || empty( $user ) ){
		
		//Check if the user has submitted a Display Name (step 2)
		if ( empty( $_POST['mp_stacks_eventgrid_user_display_name'] ) ){
					
			//We don't have a Display Name yet so get that from the user
			echo json_encode( array(
					'user_signup' => '<div id="log-in-using-email-title">
											' . __( 'Enter the Display Name you wish to use.', 'mp_stacks_eventgrid' ) . '
										</div>
                                
										<form id="wp-signin">
											
											<input id="display-name" type="text" placeholder="Display Name" />
																						
											<div class="clearedfix"></div>
											
											<input id="sign-up" type="submit" value="' . __( 'Complete Sign Up' ) . '	" />
										
										</form>',
				)
			);
			
			die();
			
		}
		
		//Create the new user - usernames are username_mp_stacks_eventgrid_service. Example: me_mp_stacks_eventgrid_instagram
		$user_id = wp_create_user( $user_email, $user_password, $user_email );
		//Add the user's name
		$user_id = wp_update_user( array( 'ID' => $user_id, 'first_name' => $_POST['mp_stacks_eventgrid_user_display_name'], 'nickname' => $_POST['mp_stacks_eventgrid_user_display_name'], 'display_name' => $_POST['mp_stacks_eventgrid_user_display_name'], 'user_url' => get_bloginfo( 'wp_url' ) ) );
		
		//Get our newly created user object
		$user = get_user_by( 'login', $user_email );
		
		//Notify the user we signed them up
		wp_new_user_notification( $user_id, $user_password );
	}
	
	//Sign the user in
	$creds['user_login'] = $user->user_login;
	$creds['user_password'] = $user_password;
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
	if ( is_wp_error($user) ){

		$error_message = $user->get_error_message();
		
		//If the password was wrong
		if ( strpos( $error_message, 'The password you entered for the username' ) !== false ){
			echo json_encode( array(
				'error' => true,
				'error_message' => __( 'Wrong username/password.', 'mp_stacks_eventgrid' ) . '<br /><a target="_blank" href="' . wp_lostpassword_url() . '">' . __( 'Forgot your password?', 'mp_stacks_eventgrid' ) . '</a>',
				'login_form_html' => mp_stacks_eventgrid_wp_login_form()
				)
			);
		}
		//If there another error
		else{
			
			echo json_encode( array(
				'error' => true,
				'error_message' => $user->get_error_message(),
				'login_form_html' => mp_stacks_eventgrid_wp_login_form()
				)
			);
		}
		
		die();
	}
					
	wp_set_current_user( $user->ID, $user->user_login );
	
	//Now that we've logged the user in, redirect and post their comment	
	if ( is_user_logged_in() ) {
		
		//This will trigger the mp_sg_post_comment_via_ajax jquery trigger which will post the user's comment
		echo json_encode( array(
			'user_signed_in' => true
			)
		);
		
		die();
		
	}	
	//If the user was not properly logged in
	else{
		
		echo json_encode( array(
			'error' => true,
			'error_message' => __( 'Wrong username/password.', 'mp_stacks_eventgrid' ) . '<a target="_blank" href="' . wp_lostpassword_url( $redirect ) . '">' . __( 'Forgot your password?', 'mp_stacks_eventgrid' ) . '</a>',
			'login_form_html' => mp_stacks_eventgrid_wp_login_form()
			)
		);
		
		die();
		
	}
		
}
add_action( 'wp_ajax_mp_stacks_eventgrid_email_log_in_via_ajax', 'mp_stacks_eventgrid_email_log_in_via_ajax' );
add_action( 'wp_ajax_nopriv_mp_stacks_eventgrid_email_log_in_via_ajax', 'mp_stacks_eventgrid_email_log_in_via_ajax' );



//Ajax comment posting 
function mp_stacks_eventgrid_comment_via_ajax(){
	
	//Start up the PHP session if we haven't already
	if( !session_id() )
        session_start();
	
	//Get the URL of the page and store it so we can redirect the user back to it after the user is authenticated
	$_SESSION['mp_stacks_eventgrid_comment_parent_page_url'] = $_POST['mp_stacks_eventgrid_comment_parent_page_url'];
	$_SESSION['mp_stacks_eventgrid_comment_page_url'] = $_POST['mp_stacks_eventgrid_comment_page_url'];
		
	//If the passed comment is empty, check if there is a comment in the SESSION variable - which is typed by the user before they authenticated themselves
	if ( empty( $_POST['mp_stacks_eventgrid_comment_text'] ) ){
		
		//If there is no comment in the SESSION, this page was refreshed after a comment was posted so just die already. lol.
		if ( empty( $_SESSION['mp_stacks_eventgrid_comment_text'] ) ){
			
			echo __( 'Leave a comment...', 'mp_stacks_eventgrid' ); 
			die();	
		}
	
		//Get the comment from the prior-to-authentication SESSION
		$comment_text = $_SESSION['mp_stacks_eventgrid_comment_text'];
		//Reset the SESSION comment to be blank
		$_SESSION['mp_stacks_eventgrid_comment_text'] = NULL;
	}
	//Get the passed-from-ajax comment and store it in the session so we can post it after the user is authenticated
	else{
		$_SESSION['mp_stacks_eventgrid_comment_text'] = $_POST['mp_stacks_eventgrid_comment_text'];
		$comment_text = $_POST['mp_stacks_eventgrid_comment_text'];
	}
	
	//If this comment is empty
	if ( empty( $comment_text ) ){
		//Send an error to the console
		echo __( 'Oops! The User didn\'t enter a comment', 'mp_stacks_eventgrid' );
		die();
	}
	
	//Get the eventgrid local post id
	$post_id = $_POST['mp_stacks_eventgrid_post_id'];
	
	//If this user is not logged in
	if ( !is_user_logged_in() ) {
		
		//Send back the variables that allow them to log in
		echo json_encode( array(
			'header_message' => __( 'Log in to post your comment', 'mp_stacks_eventgrid' ),
			'comment' =>  $comment_text,
			'log_in_services' =>  apply_filters( 'mp_stacks_eventgrid_login_and_comment_services', array() ),
		));
		
		die();
	}
	
	//Get the current user to use them as the author
	$current_user = wp_get_current_user();
	
	//Post the comment to WordPress
	
	//Get the current timestamp
	$time = current_time('mysql');
	
	//Set up the data for a new WP comment
	$data = array(
		'comment_post_ID' => $post_id,
		'comment_author' => $current_user->user_login,
		'comment_author_email' => $current_user->user_email,
		'comment_author_url' => $current_user->user_url,
		'comment_content' => $comment_text,
		'comment_type' => '',
		'comment_parent' => 0,
		'user_id' => $current_user->ID,
		'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
		'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
		'comment_date' => $time,
	);
		
	//Insert the new comment into WP
	$new_comment_id = wp_new_comment($data);
	
	//Get the comment object
	$comment = get_comment( $new_comment_id );
	
	//Set up the args to echo the successful comment	
	$args = array(
		'user_id' => $current_user->ID,
		'comment_timestamp' => $comment->comment_date,
		'comment_text' => $comment->comment_content,
	);
	
	//Echo the successfull comment array back to the ajax
	mp_stacks_eventgrid_format_and_output_successful_comment( $args );
	
}
add_action( 'wp_ajax_mp_stacks_eventgrid_comment_via_ajax', 'mp_stacks_eventgrid_comment_via_ajax' );
add_action( 'wp_ajax_nopriv_mp_stacks_eventgrid_comment_via_ajax', 'mp_stacks_eventgrid_comment_via_ajax' );

function mp_stacks_eventgrid_format_and_output_successful_comment( $args ){
	
	$default_args = array(
		'user_id' => NULL,
		'comment_timestamp' => NULL,
		'comment_text' => NULL,
	);
	
	//This allows us to actually get the display_name for the comment
	$comment_author_data = get_userdata( $args['user_id'] );
	
	//If this user has an image that came from their 3rd party service, use that:
	$mp_sg_profile_picture = get_user_meta( $args['user_id'], 'mp_sg_profile_picture', true );
	if ( !empty( $mp_sg_profile_picture ) ){
		$avatar = '<img src="' . $mp_sg_profile_picture . '" width="40px" />';
	}
	else{
		$avatar = mp_core_get_avatar( $args['user_id'], 40 ); 
	}
	
	//Output the json array back to the local-page so that we can display the new comment.
	echo json_encode( array(
		'successfully_posted_comment' => true,
		'new_comment' => '<div class="comment">
							<div class="comment-avatar-container">
								<div class="comment-avatar">
									' . $avatar . '
								</div>
							</div>
							<div class="comment-info-side-container">
								<div class="comment-author">' . $comment_author_data->display_name . '</div>
								<div class="comment-date">' . mp_core_time_ago( $args['comment_timestamp'] ) . '</div>
                                <div class="clearedfix"></div>
								<div class="comment-content">' . $args['comment_text'] . '</div>
							</div>
						</div>',
		)
	);
	
	//Reset the SESSION comment to be blank
	$_SESSION['mp_stacks_eventgrid_comment_text'] = NULL;
	
	die();
}

//This function loads in comments using ajax - this will help speed up the initial page load showing the actual content.
function mp_stacks_eventgrid_load_comments_via_ajax(){
	
	$post_id = $_POST['mp_stacks_eventgrid_post_id'];
	
	//Get comments from WordPress
	$comments = get_comments( 'post_id=' . $post_id );		
		
	//Add a timestamp key to each comment
	foreach ( $comments as $comment ){
		
		//If the comment_date is already a timestamp (passed from the api of instagram etc)
		if ( is_numeric( $comment->comment_date ) && (int)$comment->comment_date == $comment->comment_date ){
			$comment->timestamp = $comment->comment_date;
		}
		//If it is not already a timestamp, str_to_time it
		else{
			$comment->timestamp = strtotime( $comment->comment_date );
		}

	}
	
	//Sort the comments by date via timestamp
	$comments = mp_core_array_to_object( mp_core_array_sort_by_key( $comments, 'timestamp', SORT_DESC ) );   
								
	//If there are comments
	if ( $comments ){
		foreach($comments as $comment) {?>
			
		   
			<div class="comment">
				<div class="comment-avatar-container">
					<div class="comment-avatar">
						<?php 
						//If there is an author image in the comment array for this comment, use that
						if ( isset( $comment->user_img ) ){
							echo '<img src="' . $comment->user_img . '" width="40px" />';
						}
						//If there is not an author image in the comment array for this comment,
						else{
							
							//See if this user has an image saved in their WP User account
							$mp_sg_profile_picture = get_user_meta( $comment->user_id, 'mp_sg_profile_picture', true );
						
							//Else, if this user has an image that came from their 3rd party service, use that:
							if ( !empty( $mp_sg_profile_picture ) ){
								echo '<img src="' . $mp_sg_profile_picture . '" width="40px" />';
							}
							//Otherwise, use their image from Gravatar
							else{
								echo mp_core_get_avatar( $comment->user_id, 40 ); 
							}
						}?>
					</div>
				</div>
				
				<div class="comment-info-side-container">
				
					<div class="comment-author"><?php 
						
							//If there is a display name for the author in the array for this comment, use that
							if ( isset( $comment->user_display_name ) ){
								echo $comment->user_display_name;
							}
							//Otherwise use their WordPress display name
							else{
								$comment_author_data = get_userdata( $comment->user_id );
								if ( isset( $comment_author_data->display_name ) ){
									echo $comment_author_data->display_name;
								}
								else{
									echo __( 'The user who posted this comment may have been deleted', 'mp_stacks_eventgrid');
								}
							}
																		
						?></div>
					<div class="comment-date"><?php echo mp_core_time_ago( (int)$comment->timestamp );?></div>
					<div class="clearedfix"></div>
					<div class="comment-content"><?php echo is_string( $comment->comment_content ) ? $comment->comment_content : ''; ?></div>
				</div>
			</div>
			
	   <?php } 
	}
	//If there are no comments to show
	else{ 
		echo '<div id="no-comments">' . __( 'No comments posted on ' . get_bloginfo( 'wpurl' ) . ' about this post yet.', 'mp_stacks_eventgrid' ) . '</div>';
	}
	
	die();                             	
}
add_action( 'wp_ajax_mp_stacks_eventgrid_load_comments_via_ajax', 'mp_stacks_eventgrid_load_comments_via_ajax' );
add_action( 'wp_ajax_nopriv_mp_stacks_eventgrid_load_comments_via_ajax', 'mp_stacks_eventgrid_load_comments_via_ajax' );