<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

if (has_post_thumbnail()) {
	$image = get_the_post_thumbnail_url();
	$thumbnailID = get_post_thumbnail_id(get_the_ID());
	$altImage = sanitize_text_field(get_post_meta($thumbnailID, '_wp_attachment_image_alt', true));
} else {
	$image = get_template_directory_uri() . '/src/assets/images/default.svg';
	$altImage = 'This is the default image';
}
$title        = get_the_title();
$excerpt      = wp_strip_all_tags(Helpers::get_excerpt(20));
$categories   = get_the_terms(get_the_ID(), 'category');
$permalink    = get_permalink();

$html .= '
<div class="col">
	<article id="post-' . get_the_ID() . '" class="card">
		<header class="card__header">
			<figure class="card__image"><img decoding="async" loading="lazy" src="' . $image . '" alt="' . $altImage . '"></figure>
		</header >
		<div class="card__body">
			<span class="card__date-author">
				<span class="date">' . get_the_date("F j, Y") . '</span>
				<span class="author">by ' . get_the_author() . '</span>
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
			<span class="card__date">' . get_the_date("d F Y") . '</span>
		</div>
		<a href="' . $permalink . '" class="stretched-link stretched-link-custom">Read Post</a>	
	</article>
</div>';
