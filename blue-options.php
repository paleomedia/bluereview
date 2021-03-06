<?php

add_action( 'admin_init', 'blue_options_init' );
add_action( 'admin_menu', 'blue_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function blue_options_init(){
	register_setting( 'blue_options', 'blue_theme_options', 'blue_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function blue_options_add_page() {
	add_theme_page( __( 'Theme Options', 'blue review' ), __( 'Theme Options', 'blue review' ), 'edit_theme_options', 'blue_review_theme_options', 'blue_options_do_page' );
}

/**
 * Create the options page
 */
function blue_options_do_page() {
	global $select_options, $radio_options;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'blue review' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'blue review' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'blue_options' ); ?>
			<?php $options = get_option( 'blue_theme_options' ); ?>

			<table class="form-table">

				<?php
				/**
				 * A sample checkbox option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Show Facebook icon', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[facebookYN]" name="blue_theme_options[facebookYN]" type="checkbox" value="1" <?php checked( '1', $options['facebookYN'] ); ?> />
						<label class="description" for="blue_theme_options[facebookYN]"><?php _e( 'Show Facebook icon in header', 'blue review' ); ?></label>
					</td>
				</tr>
				
                                <tr valign="top"><th scope="row"><?php _e( 'Show Twitter icon', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[twitterYN]" name="blue_theme_options[twitterYN]" type="checkbox" value="1" <?php checked( '1', $options['twitterYN'] ); ?> />
						<label class="description" for="blue_theme_options[twitterYN]"><?php _e( 'Show Twitter icon in header', 'blue review' ); ?></label>
					</td>
				</tr>
				
                                <tr valign="top"><th scope="row"><?php _e( 'Show google+ icon', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[gPlusYN]" name="blue_theme_options[gPlusYN]" type="checkbox" value="1" <?php checked( '1', $options['gPlusYN'] ); ?> />
						<label class="description" for="blue_theme_options[gPlusYN]"><?php _e( 'Show google+ icon in header', 'blue review' ); ?></label>
					</td>
				</tr>
				
                                <tr valign="top"><th scope="row"><?php _e( 'Show RSS', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[rssYN]" name="blue_theme_options[rssYN]" type="checkbox" value="1" <?php checked( '1', $options['rssYN'] ); ?> />
						<label class="description" for="blue_theme_options[rssYN]"><?php _e( 'Show RSS icon in header', 'blue review' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * A sample text input option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Twitter Handle', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[twitterHandle]" class="regular-text" type="text" name="blue_theme_options[twitterHandle]" value="<?php esc_attr_e( $options['twitterHandle'] ); ?>" />
						<label class="description" for="blue_theme_options[twitterHandle]"><?php _e( 'Twitter handle to which header icon should link', 'blue review' ); ?></label>
					</td>
				</tr>
                                
				<tr valign="top"><th scope="row"><?php _e( 'Facebook URL', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[facebookURL]" class="regular-text" type="text" name="blue_theme_options[facebookURL]" value="<?php esc_attr_e( $options['facebookURL'] ); ?>" />
						<label class="description" for="blue_theme_options[facebookURL]"><?php _e( 'Facebook profile URL to which icon should link', 'blue review' ); ?></label>
					</td>
				</tr>
                                
				<tr valign="top"><th scope="row"><?php _e( 'RSS URL', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[rssURL]" class="regular-text" type="text" name="blue_theme_options[rssURL]" value="<?php esc_attr_e( $options['rssURL'] ); ?>" />
						<label class="description" for="blue_theme_options[rssURL]"><?php _e( 'URL for the rss icon', 'blue review' ); ?></label>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e( 'Google Plus', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[gPlus]" class="regular-text" type="text" name="blue_theme_options[gPlus]" value="<?php esc_attr_e( $options['gPlus'] ); ?>" />
						<label class="description" for="blue_theme_options[gPlus]"><?php _e( 'google+ profile URL to which icon should link', 'blue review' ); ?></label>
					</td>
				</tr>

                                <tr valign="top"><th scope="row"><?php _e( 'Google Calendar', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[gCal]" class="regular-text" type="text" name="blue_theme_options[gCal]" value="<?php esc_attr_e( $options['gCal'] ); ?>" />
						<label class="description" for="blue_theme_options[gCal]"><?php _e( 'URL to Google Calendar for events', 'blue review' ); ?></label>
					</td>
				</tr>

                                <tr valign="top"><th scope="row"><?php _e( 'Footer Text', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[footText]" class="regular-text" type="text" name="blue_theme_options[footText]" value="<?php esc_attr_e( $options['footText'] ); ?>" />
						<label class="description" for="blue_theme_options[footText]"><?php _e( 'Text for footer', 'blue review' ); ?></label>
					</td>
				</tr>
                                
                                <tr valign="top"><th scope="row"><?php _e( 'Footer Image URL', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[footImg]" class="regular-text" type="text" name="blue_theme_options[footImg]" value="<?php esc_attr_e( $options['footImg'] ); ?>" />
						<label class="description" for="blue_theme_options[footImg]"><?php _e( 'Image for footer', 'blue review' ); ?></label>
					</td>
                                
                                <tr valign="top"><th scope="row"><?php _e( 'Footer Image link URL', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[footImgURL]" class="regular-text" type="text" name="blue_theme_options[footImgURL]" value="<?php esc_attr_e( $options['footImgURL'] ); ?>" />
						<label class="description" for="blue_theme_options[footImgURL]"><?php _e( 'Link for footer image', 'blue review' ); ?></label>
					</td>
                                
                                <tr valign="top"><th scope="row"><?php _e( 'Article Disclaimer', 'blue review' ); ?></th>
					<td>
						<input id="blue_theme_options[disclaimer]" class="regular-text" type="text" name="blue_theme_options[disclaimer]" value="<?php esc_attr_e( $options['disclaimer'] ); ?>" />
						<label class="description" for="blue_theme_options[disclaimer]"><?php _e( 'Disclaimer for the end of the article', 'blue review' ); ?></label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'blue review' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function blue_theme_options_validate( $input ) {
	global $select_options, $radio_options;

	// Facebook YN
	if ( ! isset( $input['facebookYN'] ) )
		$input['facebookYN'] = null;
	$input['facebookYN'] = ( $input['facebookYN'] == 1 ? 1 : 0 );

        // Twitter YN
	if ( ! isset( $input['twitterYN'] ) )
		$input['twitterYN'] = null;
	$input['twitterYN'] = ( $input['twitterYN'] == 1 ? 1 : 0 );

        // RSS YN
	if ( ! isset( $input['rssYN'] ) )
		$input['rssYN'] = null;
	$input['rssYN'] = ( $input['rssYN'] == 1 ? 1 : 0 );

	// Facebook User
	$input['facebookURL'] = esc_url_raw( $input['facebookURL'] );

	// rss url
	$input['rssURL'] = esc_url_raw( $input['rssURL'] );

	// gPlus User
	$input['gPlus'] = esc_url_raw( $input['gPlus'] );

        // Twitter User
	$input['twitterURL'] = esc_textarea( $input['twitterURL'] );

        // GCal link
	$input['gCal'] = esc_url_raw( $input['gCal'] );

        // foot img link
	$input['footImg'] = esc_url_raw( $input['footImg'] );

        // foot img link url
	$input['footImgURL'] = esc_url_raw( $input['footImgURL'] );

        // foot text link
	$input['footText'] = wp_kses_post( $input['footText'] );

        // discliamer
	$input['disclaimer'] = esc_textarea( $input['disclaimer'] );

//	// Our select option must actually be in our array of select options
//	if ( ! array_key_exists( $input['selectinput'], $select_options ) )
//		$input['selectinput'] = null;
//
//	// Our radio option must actually be in our array of radio options
//	if ( ! isset( $input['radioinput'] ) )
//		$input['radioinput'] = null;
//	if ( ! array_key_exists( $input['radioinput'], $radio_options ) )
//		$input['radioinput'] = null;
//
//	// Say our textarea option must be safe text with the allowed tags for posts
//	$input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );

	return $input;
}