<?php 
/**
 * This file contains functions which create output for events regardless of the theme
 *
 * @since 1.0.0
 *
 * @package    MP Stacks SocialGrid
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Add the View Event button to the_content
 *
 * @since 1.6
 *
 * @param content String containing the post content
 * @return void
 */
function mp_eventgrid_event_button($content) {
  	
	//Set up post variables
	$post_id = get_the_ID();
	$sg_type = mp_core_get_post_meta( $post_id, 'sg_type' );
	$sg_author = mp_core_get_post_meta( $post_id, 'sg_author' );
	$sg_permalink = mp_core_get_post_meta( $post_id, 'sg_permalink' );
	$sg_unique_id = mp_core_get_post_meta( $post_id, 'sg_unique_id' );
	
	$link = get_permalink();
	
	$iframe_url = add_query_arg( array( 'mp_eventgrid_iframe' => true ), $link );
	
	$button_html = '<p><a href="' . $iframe_url . '" class="button mp-stacks-iframe-height-match-lightbox-link">' . __( 'View Full Event Details', 'mp_stacks_eventgrid' ) . '</a></p>';
	
  return $button_html . $content;
}

/**
 * If we are showing an mp_eventgrid_post, make the page style
 *
 * @since 1.6
 *
 * @param void
 * @return void
 */
function mp_stacks_eventgrid_post_output(){
		
	global $wp_query;
	
	//If we are not supposed to show an mp_eventgrid_post, get out of here
	if( !isset( $wp_query->query['post_type'] ) || ( isset( $wp_query->query['post_type'] ) && $wp_query->query['post_type'] != 'mp_event' ) || is_admin() ){
		return;
	}
	
	//If this post is NOT loading in a lightbox/iframe, get out of here. This style is for lightboxes/iframes only.
	if ( !isset( $_GET['mp_eventgrid_lightbox'] ) && !isset( $_GET['mp_eventgrid_iframe'] ) ){
		
		add_filter( 'the_content', 'mp_eventgrid_event_button' );
		
		return;	
	}
	
	
	//Set up post variables
	$post_id = get_the_ID();
							
	?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo( 'charset' ); ?>" />
            <meta name=viewport content="width=device-width, initial-scale=1">
            <title><?php wp_title( '|', true, 'right' ); ?></title>
            <link rel="profile" href="//gmpg.org/xfn/11" />
            <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
            
            <!-- Google Fonts -->
            <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
            
            <!-- Font Awesome -->  
            <link href='<?php echo MP_STACKS_PLUGIN_URL . 'includes/fonts/font-awesome/css/font-awesome.css?ver=' . MP_STACKS_VERSION ;?>' rel='stylesheet' type='text/css'>
            
			<!-- Jquery From WordPress -->
			<script type='text/javascript' src='<?php bloginfo( 'wpurl' ); ?>/wp-includes/js/jquery/jquery.js'></script>
                        
            <!--[if lt IE 9]>
            <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
            <![endif]-->
					
            <style type="text/css">
				
				body{
					font-size: 1.2rem;
					margin:0px;	
					font-family: 'Montserrat', 'Helvetica Neue', Arial,Helvetica, sans-serif;
					background-color:transparent;	
					box-sizing:border-box;
				}
				*{
					box-sizing:border-box;	
				}
				a{
					color:#3f729b;	
					text-decoration:none;
				}
				p{
					margin:0;	
				}
				iframe{
					border: none;	
				}
				.outer-container{
					display:table;
					width:100%;
					background-color:#000;
					position:relative;
				}
				#media-side{
					display:table-cell;
					vertical-align: top;
					color:#fff;
					padding:0px;
				}
				#media-side > *{
					float:left;
					font-size:30%;
					display:inline-block
				}
				#info-side{
					display:table-cell;
					vertical-align: top;
					background-color:#f8f8f8;	
					position:relative;
				}
				#description-container{
					white-space: pre-line;	
				}
				
				@media (max-width: 900px) {
					.outer-container{
						display:inline-block;
						width:100%;
						background-color:#000;
						position:relative;
					}
					#media-side{
						display:inline-block;
						vertical-align: top;
						color:#fff;
						width:100%!important;
						overflow:hidden;
					}
					#media-side > *{
						max-width:100%;
					}
					#info-side{
						display:inline-block;
						width:100%;
						vertical-align: top;
						background-color:#f8f8f8;	
					}
					.content-block{
						height:auto!important;	
						min-height:100px;
					}
					#comment-form-container{
						position:absolute;
						top:0;
					}
					#comments-container{
						margin-top:50px!important;	
						height:auto!important;	
					}
					#description-container, #comments-container{
						white-space: pre-line;	
						height:auto!important;	
					}
				}
				.white-block{
					padding:12px;
					background-color:#fff;
					border-bottom: 1px solid #ddd;
					box-shadow: 0 1px 1px rgba(0,0,0,.06);
				}
				/* Featured Image and Title Areas */
				#featured-image,
				#featured-image *,
				#title{
					display:inline-block;
					vertical-align:middle;	
					max-height:200px;
					overflow:hidden;
				}
				/* Event Details Area */
				.event-detail-container{
					display:table;
					margin-top:20px;
				}
				.event-detail-cell{
					display:table-cell;
					vertical-align:middle;	
				}
				.event-detail-icon{
					padding-right:5px;	
					font-size:25px;
					line-height:25px;
					width:25px;
					height:25px;
					text-align:center;
					display:table-cell;
					color:#4e5665;
					vertical-align:middle;
					padding-top:1px;
				}
				.event-detail-icon:before{
					vertical-align:top;	
				}
				.event-detail-main{
					line-height:1.2;
					font-size:13px;
					color:#4e5665;
				}
				.event-detail-sub{
					font-family: 'Open Sans', 'Helvetica Neue', Arial,Helvetica, sans-serif;
					font-size:12px;
					line-height: 14px;
					color:#81868a;
					display:block;
				}
				
				/* Date Event Details CSS */
				#date{
					margin-top:5px;	
				}
				.end-time-cell{
					padding-left:20px;	
				}
				.startdate,
				.enddate{
					font-size:13px;
					color:#4e5665;
					display:block;
				}
				
				/* Address Area */
				#address{
					margin-top:10px;
				}
				.address-marker-icon{
					margin-right:5px;	
					font-size:13px;
					line-height:13px;
					display:inline-block;
					color:#4e5665;
					vertical-align:top;
				}
				
				/* Views Area Buttons */
				#views{
					font-size:65%;	
					color:#81868a;
					display:inline-block;
				}
				#view-comments,
				#view-description{
					border-right: 1px solid #ddd;	
				}
				h1.text{
					font-size:20px;
					font-family: 'Open Sans', 'Helvetica Neue', Arial,Helvetica, sans-serif;	
					font-weight:normal;
					margin:2px 0px 4px 0px;
				}
				#edit-post{
					position:absolute;
					top:0;
					right:0;
					font-size:60%;
					padding:12px;	
				}
				
				/*Links Block */
				#links-block{
					font-size:60%;
					display:table;
					width:100%;
				}
				.links-block-item{
					display:table-cell;
					padding:12px;
					border-bottom: 1px solid #ddd;
					box-shadow: 0 1px 1px rgba(0,0,0,.06);
					background-color:#fff;
					text-align:center;	
					cursor:pointer;
					overflow:hidden;
				}
				.links-block-item.selected{
					background-color:transparent;
					border-bottom: none;
					box-shadow: none;
					box-shadow: inset -1px 0px 0px 0px rgba(0,0,0,.06);
				}
				
				/*Content Block - where comments and the description are shown */
				.content-block{
					display:none;
					font-family: 'Open Sans', 'Helvetica Neue', Arial,Helvetica, sans-serif;	
					font-weight:normal;
					font-size:0.8rem;
					position:relative;
					width:100%;
				}
				.content-block.selected{
					display:inline-block;	
					float:left;
				}
				
				/*Description Block */
				#description-container{
					padding:20px 12px 12px 12px;
					overflow-y:scroll;	
					overflow-x:hidden;
				}
				
				/*Comment Block */
				#comments-container{
					padding:20px 12px 12px 12px;
					overflow-y:scroll;	
					overflow-x:hidden;
				}
				
				/*Comment Block */
				.comment{
					margin-bottom:10px;	
					display: inline-block;
					width: 100%;
				}
				.comment-avatar-container{
					display: inline-block;
					margin-right:8px;
				}
				.comment-avatar img{
					box-shadow: inset 0 0 0 1px rgba(0,0,0,.3);	
					float:left;
				}
				.comment-info-side-container{
					display:inline-block;
					max-width:80%;
					vertical-align:top;
				}
				.comment-author{
					margin-bottom:2px;
					font-weight:700;
					float:left;
					display:inline-block;
				}
				.comment-date{
					font-size: 80%;
					color: #81868a;
					font-weight:normal;
					display:inline-block;
					vertical-align: middle;
					margin-left:5px;
				}
				/* Clear Fix */
				.clearedfix:after {
					content: ".";
					visibility: hidden;
					display: block;
					height: 0;
					clear: both;
				}
				
				/*Comment Form Container*/
				#comment-form-container{
					height:50px;
					padding:5px;
					background-color:#fff;
					width:100%;
					box-sizing: border-box;
					display:inline-block;
					border-top: 1px solid #ddd;
					box-shadow: 0px -1px 1px 0px rgba(0,0,0,.06);
					float: left;
				}
				#comment{
					font-size:0.8rem;
					padding:10px;
					border: 1px solid #ccc;
					-webkit-box-sizing: border-box;
					-moz-box-sizing: border-box;
					-ms-box-sizing: border-box;
					box-sizing: border-box;
					-webkit-border-radius: 3px;
					border-radius: 3px;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
					-moz-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.05);	
					width:84%;
					float:left;
				}
				#submit{
					width:15%	
				}
				input[type=submit], .button{
					display:inline-block;
					white-space:nowrap;
					font-size:0.8rem;
					padding:10px;
					border: 1px solid #ccc;
					margin-left:1%;
					-webkit-box-sizing: border-box;
					-moz-box-sizing: border-box;
					-ms-box-sizing: border-box;
					box-sizing: border-box;
					-webkit-border-radius: 3px;
					border-radius: 3px;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
					-moz-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.05);	
					
					background-color: #fafafa;
					background-image: -webkit-gradient(linear,left top,left bottom,from(#fafafa),to(#eee));
					background-image: -webkit-linear-gradient(top,#fafafa,#eee);
					background-image: -moz-linear-gradient(top,#fafafa,#eee);
					background-image: -o-linear-gradient(top,#fafafa,#eee);
					background-image: -ms-linear-gradient(top,#fafafa,#eee);
					background-image: linear-gradient(top,#fafafa,#eee);
					filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#fafafa', EndColorStr='#eeeeee');
					background-position: 50% 50%;

				}
				#login-box{
					display:none;
					position:absolute;
					background:#fff;
					padding:20px;
					color:#000;	
					width:80%;
					height:80%;
					top:10%;
					left:10%;
					z-index:99999999999;
					-webkit-box-shadow: 0px 0px 288px 118px rgba(0,0,0,0.75);
					-moz-box-shadow: 0px 0px 288px 118px rgba(0,0,0,0.75);
					box-shadow: 0px 0px 288px 118px rgba(0,0,0,0.75);
				}
				#login-box-close{
					cursor:pointer;
					position:absolute;
					right:0;
					top:0;
					font-size:13px;
					padding:10px	
				}
				#login-box-inner-table{
					display:table;
					width: 100%;
					height: 100%;
				}
				#login-box-inner-table-cell{
					display:table-cell;
					vertical-align:middle;	
				}
				#login-box-header{
					text-align:center;
				}
				#login-box-left-side{
					width: 50%;
					float: left;
					min-height: 10px;
					padding:20px;
				}
				#login-box-right-side{
					width: 50%;
					float: left;	
					padding:20px;
					text-align:center;
				}
				#log-in-services-list{
					list-style: none;
					padding: 0; 
				}
				#log-in-services-list li{
					margin-bottom:20px;	
					font-size: 15px;
				}
				input{
					font-size:0.8rem;
					padding:10px;
					border: 1px solid #ccc;
					-webkit-box-sizing: border-box;
					-moz-box-sizing: border-box;
					-ms-box-sizing: border-box;
					box-sizing: border-box;
					-webkit-border-radius: 3px;
					border-radius: 3px;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
					-moz-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.05);	
					width:84%;
					float:left;	
				}
				#log-in-using-email-title, #email, #password, #display-name{
					margin-bottom:10px;	
				}
				#sign-up{
					margin-left:0;	
					width: inherit;
				}
				#mp-core-mobile-back-btn-container{
					display:none;	
				}
				@media (max-width: 600px) {
					#mp-core-mobile-back-btn-container{
						display:block;	
					}
				}
	
			</style>
            
             <script>
				function mp_core_go_back() {
					window.history.back();
				}
			</script>

        </head>
        
        <body>
        
            <div id="mp-core-mobile-back-btn-container" style="background-color:#25272a; color:#fff; width=100%; font-size:45px;">
                <div id="mp-core-back-btn-icon" onclick="mp_core_go_back()" style="padding:20px 10px 30px 20px; line-height: 1px;">&lsaquo;</div>
            </div>       
                         
            <div class="outer-container">   
            	
                <div id="login-box">
                	<div id="login-box-close"><?php echo __( 'Close', 'mp_stacks_eventgrid' ); ?></div>
                	<div id="login-box-inner-table">
                    	<div id="login-box-inner-table-cell">
                            <div id="login-box-header">
                                
                                <div id="login-box-header-message">
                                
                                </div>
                                
                                <div id="login-box-header-comment">
                                
                                </div>
                                
                            </div>
                           
                            <div id="login-box-left-side">
                            	
                                <div id="log-in-using-email-title">
                                	<?php echo __( 'Log In using Email', 'mp_stacks_eventgrid' ); ?>
                                </div>
                                
                                <div id="wp-signin-container">
                                	<?php echo mp_stacks_eventgrid_wp_login_form(); ?>
                            	</div>
                                
                            </div>
                            
                            <div id="login-box-right-side">
                                
                                <div id="log-in-services">
                                	
                                    <ul id="log-in-services-list">
                                    
                                    </ul>
                                    
                                </div>
                                
                            </div>
                    	</div>
                    </div>
                </div>
                
                <div id="info-side"> 
                
                	<?php 
						
						//Get the title and description of this post
						$post_title = get_the_title( $post_id );
						
						$post_object = get_post( $post_id );
						$post_description = $post_object->post_content;
						
					?>
                        	
                
                    <div id="title-block" class="white-block">
                    	
                        <input id="post-id" type="hidden" value="<?php echo $post_id; ?>" />
                         
						<?php 
						
						if ( current_user_can( 'edit_posts' ) ){?>
                        	<div id="edit-post"><a href="<?php echo get_edit_post_link( $post_id ); ?>" target="_blank"><?php echo __( 'Edit this WP Post', 'mp_stacks_eventgrid' ); ?></a></div><?php
						}
																		
						?>
                        
                        <div id="featured-image">
                        	<img src="<?php echo mp_core_the_featured_image( $post_id, 50 ); ?>" width="23px" />
                        </div>
                                                
                        <div id="title">
							<span>
                                <h1 class="text"><?php echo $post_title; ?></h1>
                            </span>
                        </div>     
							
                        <div id="date">
                        	
                            <?php //DATE:                            
							//Get the format by which dates and times should be shown based on WP defaults
							$date_format = get_option( 'date_format' );
							$time_format = get_option( 'time_format' );
							
							//Get start DATE info
							$the_start_date = mp_events_get_mp_event_start_date( $post_id );

							//Get start TIME info
							$the_start_time = mp_core_get_post_meta( $post_id, 'event_start_time' );
							
							//Get END date info
							$the_end_date = mp_events_get_mp_event_end_date( $post_id );
							
							//Get END Time info
							$the_end_time = mp_core_get_post_meta( $post_id, 'event_end_time', 'no_time_entered' );
							
							if ( $the_end_time == 'no_time_entered' ){
								$the_end_time = NULL;
							}
	
                            ?>		
                            
                            <time class="timestamp" datetime="<?php echo date( 'c', $the_start_date_timestamp ); ?>">
                                
                                <div class="event-detail-container">
                                	<div class="event-detail-cell">
                                    	<div class="event-detail-icon clock-icon fa-clock-o"></div>
                                    </div>
                                    <div class="event-detail-cell">
                                        <div class="startdate event-detail-main"><?php echo __( 'Starts: ', 'mp_stacks_eventgrid' ) . $the_start_date; ?></div>
                                        <div class="starttime event-detail-sub"><?php echo $the_start_time; ?></div>
                                    </div>
                                
                                
									<?php //If there is an end date set
                                    if ( !empty( $the_end_date ) ){ ?>
                                        
                                        <div class="event-detail-cell end-time-cell">
                                            <div class="event-detail-icon clock-icon fa-clock-o"></div>
                                        </div>
                                    	<div class="event-detail-cell">
                                            <div class="enddate event-detail-main"><?php echo __( 'Ends: ', 'mp_stacks_eventgrid' ) . $the_end_date; ?></div>
                                            <?php //If there is an end time set
                                            if ( !empty( $the_end_time ) ){ ?>
                                                <div class="endtime event-detail-sub"><?php echo $the_end_time; ?></div>
                                            <?php } ?>
										</div> 
								    <?php } ?>
                            	</div>
                            </time>
                        </div>
                        
                        <?php 
						
						//Get the Event Address
						$address = mp_core_get_post_meta( $post_id, 'event_address', 'no_address' );
						
						//If an Address has been entered, show it here
						if ( $address != 'no_address' ){ ?>
                            <div id="address">
                                <div class="event-detail-container">
                                    <div class="event-detail-cell">
                                        <div class="event-detail-icon fa-map-marker"></div>
                                    </div>
                                    <div class="event-detail-cell">
                                        <div class="event-detail-main" itemprop="adr"><?php echo $address; ?></div>
                                        
                                        <?php 
                                        /*
                                        //For now the map is pretty useless and makes it bad UX on small devices. If someone requests it we'll put this back
                                        <div class="event-detail-sub">
                                            <a class="view-map-link" href="https://www.google.com/maps/place/<?php echo str_replace( ' ', '+', trim(preg_replace('/\s+/', ' ', $address ) ) ); ?>" target="_blank"> <?php echo __( 'View Map', 'mp_stacks_eventgrid' ); ?></a>
                                        </div>
                                        */ 
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php } 
						
						//Tickets: If the CTC Plugin is active
						if ( class_exists( 'Church_Theme_Content' ) ){
							
							//There is no ticketing information in the CTC plugin
							$tickets = 'no_ticket_info';
							
						}
						//Tickets: If no CTC Plugin, use MP Events
						else{
							
							//Get the Event's Ticket Purchase Link
							$tickets = mp_core_get_post_meta( $post_id, 'event_ticket_url', 'no_ticket_info' );
							
						}
						
						//If an Ticket Info has been entered, show it here
						if ( $tickets != 'no_ticket_info' ){ ?>
                            <div id="tickets">
                                <div class="event-detail-container">
                                    <div class="event-detail-cell">
                                        <div class="event-detail-icon fa-ticket"></div>
                                    </div>
                                    <div class="event-detail-cell">
                                        <div class="event-detail-main"><?php echo $tickets; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div id="links-block">
                        
                        <div id="view-description" class="links-block-item selected">
                             <?php echo __( 'Notes', 'mp_stacks_eventgrid' ); ?>
                        </div>
                        
                     
                        <?php 
						//Check if comments are enabled for this event post.
						if ( comments_open() ){ ?>
                        <div id="view-comments" class="links-block-item">
                             <?php echo __( 'Discussion', 'mp_stacks_eventgrid' ); ?>
                        </div>
                        <?php } ?>

                    </div>
                    
                    <div class="content-block description selected">
                        
                        <div id="description-container"><?php echo do_shortcode( $post_description ); ?></div>
                        
                    </div>
					
                     <?php 
					//Check if comments are enabled for this event post.
					if ( comments_open() ){ ?>
                    <div class="content-block comments">
                        
                        <div id="comments-container">
                            <?php echo __( 'Loading Discusson...', 'mp_stacks_eventgrid' ); ?>
                       </div>
                        
                       <div id="comment-form-container">
                           <form action="<?php bloginfo( 'wpurl' ); ?>/wp-comments-post.php" method="post" id="commentform" class="comment-form">
                               <input type="text" id="comment" name="comment" placeholder="<?php echo __( 'Leave a comment...', 'mp_stacks_eventgrid' ); ?>" aria-required="true"></textarea>
                               <input name="submit" type="submit" id="submit" value="Post">
                               <input type="hidden" name="comment_post_ID" value="<?php echo $post_id; ?>" id="comment_post_ID">
                               <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                           </form>
                       </div>
                    
                    </div>
                    <?php } ?>
                    
                </div>
                
                <div id="media-side"> 
                <?php 
				
				//Get the popup Media Content URLs
                $video_value = mp_core_get_post_meta( $post_id, 'event_video' );
				$featured_image_attachment_id = get_post_thumbnail_id( $post_id );
				$featured_image_details = wp_get_attachment_image_src( $featured_image_attachment_id, 'full' );
				$image_value = $featured_image_details[0];
				
				//If a video has been entered
				if ( !empty( $video_value ) ){
					//If the video is a youtube video, add custom formatting to the URL
					if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_value, $match)) {
						$youtube_video_id = $match[1];
						$video_value = 'https://www.youtube.com/embed/' . $youtube_video_id . '?modestbranding=1&showinfo=0&rel=0&wmode=transparent&autohide=1&autoplay=1&fs=1';
					}
					//If this is a vimeo video
					elseif ( strpos( $video_value, 'vimeo' ) ) {
						//Get json from vimeo about this video using Curl 
						$curl = curl_init('http://vimeo.com/api/oembed.json?url=' . $video_value );
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl, CURLOPT_TIMEOUT, 30);
						curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
						$vimeo_info_array = curl_exec($curl);
						curl_close($curl);
			
						//Json decode Vimeo info array
						$vimeo_info_array = json_decode($vimeo_info_array, true);
						
						$video_id = $vimeo_info_array['video_id'];
						
						//Create iframe with settings for vimeo
						$video_value = '//player.vimeo.com/video/' . $video_id . '?portrait=0&badge=0&color=ff9933&autoplay=1';
					}
						
					$content_url = $video_value;
					
					//Attempt to wrap the content in an HTML tag
					$args = array(
						'autoplay_videos' => true
					);
						
					$content_html = mp_core_wrap_media_url_in_html_tag( $content_url, $args );
					
				}
				//If only a featured image exists for content
				elseif( !empty( $image_value ) ){
										
					//Only show the featured image if its width is greater than 500px
					//if ( $featured_image_details[1] > 500 ){
						$content_html = '<img src="' . $image_value . '" style="width:100%;"/>';
					//}
				}
							
				//If we were able to wrap the content, show it
				if ( !empty ( $content_html ) && trim( $content_html ) ){
					
					echo $content_html;
					
					$media_to_show = true;
                  
                }else{
					$media_to_show = false;	
				}?>
                
                </div>
                
                <script type="text/javascript">
				
					jQuery(document).ready(function($){
						
						//We resize the comments area on a loop 50 times because the heights are wrong until the content ( IE YouTube Video etc) loads:
						var content_set_loop_counter = 1;
						
						<?php if ( $media_to_show ){ ?>
							
							var set_content_size = setInterval( function(){
															
								//SIZE LEFT SIDE
								
								//Set the width of the left side based on the aspect ratio of its content
								var left_content_width = $( '#media-side > *' ).css( 'width' ).replace('px', '');
								var left_content_height = $( '#media-side > *' ).css( 'height' ).replace('px', ''); 
															
								//Get the ratio of height - to width
								var height_ratio = left_content_width / left_content_height;
								
								//If the loaded content is wider than it is high
								if ( height_ratio > 1 ){
									$( '#media-side' ).css( 'width', '800px' );
									$( '#media-side *' ).css( 'width', '100%' );
								}
								//If the loaded content is higher than it is wide - or a square
								else if( height_ratio < 1 ){
									
									if( height_ratio < 0.2 ){									
										$( '#media-side' ).css( 'width', '50px' );
									}
									else if( height_ratio < 0.5 ){									
										$( '#media-side' ).css( 'width', '300px' );
									}
									else if( height_ratio < 0.8 ){									
										$( '#media-side' ).css( 'width', '360px' );
									}
									else{
										$( '#media-side' ).css( 'width', '450px' );
									}
									
								}
								//If the loaded content/image is totally square
								else{
									$( '#media-side' ).css( 'width', '571px' );
								}
							 
								//SIZE RIGHT SIDE
								 
								//Get the height of the right side
								var left_side_height = $( '#media-side > *' ).css( 'height' ).replace('px', '');
								
								//Get the height of the title block
								var title_block_height = $( '#title-block' ).css( 'height' ).replace('px', '');
								var links_block_height = $( '#links-block' ).css( 'height' ).replace('px', '');	
								var comment_form_container_height = $( '#comment-form-container' ).length > 0 ? $( '#comment-form-container' ).css( 'height' ).replace('px', '') : 0;		
									
								//Set the height of the content area by subtracting the height of the title area and the comment form area
								if ( $( '#comment-form-container' ).length > 0 ){
									$( '#comments-container' ).css( 'height', left_side_height - title_block_height - comment_form_container_height - links_block_height);
								}
								
								//Set the height of the content area by subtracting the height of the title area and the comment form area
								$( '#description-container' ).css( 'height', left_side_height - title_block_height - links_block_height );
								
								content_set_loop_counter = content_set_loop_counter + 1;
		
								if ( content_set_loop_counter >= 50 ){
									clearInterval( set_content_size );
								}
							}, 100 );
						
						<?php } ?>
						
						
						//When the 'Description' button is clicked
						$( '#view-description' ).on( 'click', function( event ){
							event.preventDefault();
							$( '#view-comments' ).removeClass( 'selected' );
							$( '.comments' ).removeClass( 'selected' );
							$( '#view-description' ).addClass( 'selected' );
							$( '.description' ).addClass( 'selected' );
							
							//Trigger the event which resizes the lightbox to match the height of its content - since the height has now changed
							parent.mp_stacks_mfp_match_height_trigger();
						});
								
												
					});
					
					//This function allows us to grab URL variables
					function mp_stacks_eventgrid_getQueryVariable(variable){
						   var query = window.location.search.substring(1);
						   var vars = query.split("&");
						   for (var i=0;i<vars.length;i++) {
								   var pair = vars[i].split("=");
								   if(pair[0] == variable){return pair[1];}
						   }
						   return(false);
					}
					
					function fnSelect(objId)
					{
					fnDeSelect();
					if (document.selection)
					{
					var range = document.body.createTextRange();
					range.moveToElementText(document.getElementById(objId));
					range.select();
					}
					else if (window.getSelection)
					{
					var range = document.createRange();
					range.selectNode(document.getElementById(objId));
					window.getSelection().addRange(range);
					}
					}
					function fnDeSelect()
					{
					if (document.selection)
					document.selection.empty();
					else if (window.getSelection)
					window.getSelection().removeAllRanges();
					}
				
				</script>   
        </body>
	</html>
    <?php
	
	die();

}
add_action( 'wp', 'mp_stacks_eventgrid_post_output' );

/**
 * This function returns the wp login form for the eventgrid
 *
 * @since    1.0.0
 * @link     
 * @see      function_name()
 * @param    void
 * @return   void
 */ 
function mp_stacks_eventgrid_wp_login_form(){
	
	return '<form id="wp-signin">
                                
		<input id="email" type="email" placeholder="Your Email Address" />
		
		<input id="password" type="password" placeholder="Password" />
		
		<div class="clearedfix"></div>
		
		<input id="sign-up" type="submit" value="' . __( 'Log In/Sign Up' ) . '" />
	
	</form>';
									
}