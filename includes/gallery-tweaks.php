<?php function blue_gallery_display($attr) {
	global $post;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'orderby'    => 'menu_order ASC, ID ASC',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
	), $attr));

	$id = intval($id);
	$attachments = get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby={$orderby}");

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link($id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	
	$output = apply_filters('gallery_style', "
		<style type='text/css'>
			.gallery {
				margin: auto;
			}
			.gallery-item {
				float: left;
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;			}
			.gallery img {
				border: 2px solid #cfcfcf;
			}
			.gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->
                <div class='gallery--wrap'>
		<div class='gallery'>");
	foreach ( $attachments as $id => $attachment ) {
	//$link = wp_get_attachment_link($id);
		
		$a_img = wp_get_attachment_url($id);
	// Attachment page ID
		$att_page = get_attachment_link($id);
	// Returns array
		$img = wp_get_attachment_image_src($id, $size);
                $credit = get_post_meta($id, "_credit", true);
		$img = $img[0];
	// If no caption is defined, set the title and alt attributes to title
		$title = strip_tags($attachment->post_excerpt);
		$imgTitle = $attachment->post_title;
		
		
		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon'>
			
					<a href=\"$a_img\" title=\"$title\" class=\"thickbox\" rel=\"gallery-$post->ID\">
					<img src=\"$img\" alt=\"$title\" />
					</a><em class='credit'>{$credit}</em>	
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='gallery-caption'>
                                    <h3 class='gallery-heading'>{$imgTitle}</h3>
                                    {$attachment->post_excerpt}
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n
		</div>\n";

	return $output;
}
remove_shortcode('gallery');
add_shortcode('gallery', 'blue_gallery_display');

?>