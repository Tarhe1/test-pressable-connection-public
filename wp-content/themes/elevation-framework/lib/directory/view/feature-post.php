<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

$post_id = isset($feature_posts[$feature_count]['post']) ? $feature_posts[$feature_count]['post'] : false;

if (!$post_id || !$html) return;

$post = get_post($post_id);

if (has_post_thumbnail($post_id)) {
	$image = get_the_post_thumbnail_url($post_id);
	$thumbnailID = get_post_thumbnail_id($post_id);
	$altImage = sanitize_text_field(get_post_meta($thumbnailID, '_wp_attachment_image_alt', true));
} else {
	$image = get_template_directory_uri() . '/src/assets/images/default.svg';
	$altImage = 'This is the default image';
}
$title        = $post->post_title;
$excerpt      = wp_strip_all_tags(Helpers::get_excerpt(20, $post->post_content));
$categories   = get_the_terms($post_id, 'category');
$permalink    = get_permalink($post_id);
$html .= '
<div class="col feature-post">
	<article id="post-' . $post_id  . '" class="card">
		<header class="card__header">
			<figure class="card__image"><img decoding="async" loading="lazy" src="' . $image . '" alt="' . $altImage . '"></figure>
		</header >
		<div class="card__body">
			<span class="card__date-author">
				<span class="date">' . get_the_date("F j, Y") . '</span><span class="author">by ' . get_the_author() . '</span>
			</span>
			<h3 class="card__title h6"><span>' . $title . '</span></h3>
			<div class="card__excerpt line-clamp-4"><p>' . $excerpt . '</p></div>';


$html .= '<div class="card__category">';
if (!empty($categories)) :
	$html .= '<div class="card__category"><ul class="post-categories">';
	foreach ($categories as $category) :
		$html .= '<li><span>' . $category->name . '</span></li>';
	endforeach;
	$html .= '</ul></div>';
endif;
$html .=  '</div>';

$html .= '
			<span class="card__date">' . get_the_date("d F Y", $post_id) . '</span>
		</div>
		<a href="' . $permalink . '" class="stretched-link stretched-link-custom">Read Post</a>	
	</article>
</div>';

return $html;
