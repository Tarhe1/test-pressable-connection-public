<?php
if (is_category()) :
	$id = get_queried_object();
else :
	global $id;
endif;

if (get_field('banner_image_hide', $id)) return;
/* Content */
$banner_image = get_template_directory_uri() . '/src/assets/images/interior-banner.webp';
$alt_banner_image = 'Default image for pages';

if (get_field('banner_image', $id)) {
	$banner_image = get_field('banner_image', $id)['sizes']['hd'];
	$alt_banner_image = get_field('banner_image', $id)['alt'];
}

$banner_image_retina = get_field('banner_image_retina', $id);

$banner_image_tablet = get_field('banner_image_tablet', $id);
if ($banner_image_tablet) {
	$banner_image_tablet = get_field('banner_image_tablet', $id)['sizes']['hd'];
	$banner_alt_image_tablet = sanitize_text_field(get_field('banner_image_tablet', $id)['alt']);
}

$banner_image_mobile = get_field('banner_image_mobile', $id);
if ($banner_image_mobile) {
	$banner_image_mobile = get_field('banner_image_mobile', $id)['sizes']['hd'];
	$banner_alt_image_mobile = sanitize_text_field(get_field('banner_image_mobile', $id)['alt']);
}

$banner_title = get_the_title($id);
$banner_editor = get_field('banner_editor', $id);
$banner_button = get_field('banner_button', $id);
if ($banner_button) {
	$banner_button_url = $banner_button['url'];
	$banner_button_title = $banner_button['title'];
	$banner_button_target = $banner_button['target'] ? $banner_button['target'] : '_self';
}

/* Settings */
$banner_container_width = get_field('banner_container_width', $id);
$banner_block_position = get_field('banner_block_position', $id);
$banner_content_alignment = get_field('banner_content_alignment', $id);
$banner_hide_page_title = get_field('banner_hide_page_title', $id);
// $banner_mask_image = get_field('banner_mask_image', $id);
$banner_image_position = get_field('banner_image_position', $id);
$banner_custom = get_field('banner_custom', $id);

?>

<section class="interior-banner <?= $banner_custom ? 'custom-banner' : '' ?>">

	<div class="banner-wrapping">
		<div aria-hidden="true" class="top-mask"></div>
		<div aria-hidden="true" class="bottom-mask"></div>
		<picture>
			<?php if ($banner_image_retina) : ?>
				<source media="(max-width:767px)" srcset="<?= $banner_image_mobile; ?>">
				<source media="(max-width:1200px)" srcset="<?= $banner_image_tablet; ?>">
			<?php endif; ?>
			<img fetchpriority="high" decoding="async" src="<?= $banner_image; ?>" alt="<?= $alt_banner_image; ?>" class="<?= $banner_image_position; ?>">
		</picture>
	</div>

	<div class="container">
		<div class="row justify-content-<?= $banner_block_position; ?>">
			<div class="banner-col col-12 col-xl-<?= $banner_container_width ? $banner_container_width : '12'; ?>">
				<div class="banner-container block__editor block__editor--color-light <?= $banner_content_alignment; ?>">

					<?php if (!$banner_hide_page_title) : ?>
						<?php if (is_404()) : ?>
							<h1>Error</h1>
						<?php elseif (is_category()) : ?>
							<?php $categoryInformation = get_queried_object(); ?>
							<h1><?= $categoryInformation->name; ?></h1>
						<?php elseif (is_search()) : ?>
							<h1>Search</h1>
						<?php elseif (is_singular('tribe_events') || is_post_type_archive('tribe_events')) : ?>
							<h1>Events</h1>
						<?php elseif (is_singular('team') || is_post_type_archive('team')) : ?>
							<h1>Our Team</h1>
						<?php else : ?>
							<h1><?= $banner_title; ?></h1>
						<?php endif; ?>
					<?php endif; ?>

					<?= $banner_editor; ?>

					<?php if ($banner_button) : ?>
						<div class="btn-container mt-4">
							<a href="<?= esc_url($banner_button_url); ?>" class="cta cta--cta-dark" target="<?= esc_attr($banner_button_target); ?>" role="button"><?= esc_html($banner_button_title); ?></a>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</section>