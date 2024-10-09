<?php
/**
 * Contact us page
 */
return array(
	'title'      => __( 'Contact us', 'elevationweb' ),
	'categories' => array( 'pages', 'templates' ),
	'blockTypes' => array( 'template-parts/content' ),
	'content'    => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
	<div class="wp-block-columns" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:column {"layout":{"type":"constrained","justifyContent":"left"}} -->
	<div class="wp-block-column"><!-- wp:heading -->
	<h2>Get In Touch</h2>
	<!-- /wp:heading -->
	
	<!-- wp:paragraph -->
	<p>More information. 50 Words Max. Fusce non sem nisl. Aenean efficitur egestas ex, eu molestie ante vestibulum eget. Morbi pretium, mi non mattis hendrerit, ligula erat blandit enim, pretium venenatis massa justo in arcu. Donec egestas, nibh sit amet condimentum placerat, urna augue dignissim nunc, molestie efficitur sapien lorem nec diam. In mollis mattis erat.</p>
	<!-- /wp:paragraph -->
	
	<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","right":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70"}}},"backgroundColor":"grey-1","layout":{"type":"default"}} -->
	<div class="wp-block-group has-grey-1-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)"><!-- wp:paragraph -->
	<p><strong>Organization Name Lorem Ipsum</strong><br>Street Address<br>City, State, ZIP<br><br>E-mail: info@mail.org<br>Phone: 123-456-789</p>
	<!-- /wp:paragraph --></div>
	<!-- /wp:group --></div>
	<!-- /wp:column -->
	
	<!-- wp:column -->
	<div class="wp-block-column"><!-- wp:gravityforms/form {"formId":"3"} /--></div>
	<!-- /wp:column --></div>
	<!-- /wp:columns -->
	<!-- wp:elevation/feed-post {"name":"elevation/feed-post","data":{"feed_header_editor":"\u003ch2\u003eContinue Reading\u003c/h2\u003e","_feed_header_editor":"field_63fe6d534d7c4_field_63d97e7fb2d9a","feed_header_links":"","_feed_header_links":"field_63fe6d534d7c4_field_63d97f8e21dd8","feed_header":"","_feed_header":"field_63fe6d404d7c3","feed_taxonomy":["7"],"_feed_taxonomy":"field_63ed075664652","feed_layout":"carousel","_feed_layout":"field_63ed0458a318e","feed_options":"by_terms","_feed_options":"field_63ed068c64650","feed_hr":"0","_feed_hr":"field_63ed0458aa80f","content_alignment":"left","_content_alignment":"field_63fe6e8e5b2da","container_alignment":"start","_container_alignment":"field_63fe72e443701","container_width":"8","_container_width":"field_63fe73545f255","offset_container_width":"0","_offset_container_width":"field_63fe73772406d","padding_disable":"0","_padding_disable":"field_64026f700c418"},"mode":"edit"} /-->',
);