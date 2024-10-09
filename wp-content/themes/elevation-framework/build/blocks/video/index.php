<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;
use ElevationFramework\Lib\Frontend\Settings\Helpers as SettingsHelpers;

/**
 * What Make Us Different Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

function video_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block__video--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'video';
	$className = 'dynamic-assets-load block block__video';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$video_button_position = get_field('video_button_position');
	$video_file = get_field('video_file');

	$video_header = get_field('video_header');
	$video_image = get_field('video_image');
	$video_label_play = get_field('video_label_play');

	$video_switch_content = get_field('video_switch_content');
	$video_vimeo = get_field('video_vimeo');
	$video_youtube = get_field('video_youtube');

	$content_alignment = get_field('content_alignment');
	$container_alignment = get_field('container_alignment');
	$container_width = get_field('container_width');
	$offset_container_width = get_field('offset_container_width');
	if (get_field('padding_disable')) {
		if (!get_field('padding_top')) {
			$pd_top = ' pt-0 ';
		} else {
			$pd_top = '';
		}
		if (!get_field('padding_bottom')) {
			$pd_bottom = ' pb-0 ';
		} else {
			$pd_bottom = '';
		}
	} else {
		$pd_top = '';
		$pd_bottom = '';
	}

?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?> block__video--text">

		<div class="block__container container">
			<div class="block__row row justify-content-<?= esc_html($container_alignment); ?>">
				<div class="block__col col-xl-<?= esc_html($container_width); ?> col-12 offset-xl-<?= esc_html($offset_container_width); ?> <?= $video_switch_content ? 'flex-column-reverse' : ''; ?>">

					<?php if (isset($video_header['editor']) && !empty($video_header['editor']) || isset($video_header['links']) && !empty($video_header['links'])) : ?>
						<div class="block__content <?= esc_html($content_alignment); ?>">
							<?php Helpers::global_content($video_header['editor'], $video_header['links']); ?>
						</div>
					<?php endif; ?>

					<div class="block__video block__video--poster">

						<?php Helpers::global_image($video_image, [
							'class' => 'block__video--poster-img',
							'decorative' => true
						]); ?>

						<?php if ($video_youtube || $video_vimeo || $video_file) : ?>
							<div class="block__video--button float <?= $video_button_position ? $video_button_position : 'bottom-right' ?>">
								<button class="video <?= $video_youtube ? 'youtube' : '' ?><?= $video_vimeo ? 'vimeo' : '' ?><?= $video_file ? 'mp4' : '' ?>" data-video="<?= $video_youtube ? SettingsHelpers::youtube_url($video_youtube, false, true) : '' ?><?= $video_vimeo ? SettingsHelpers::vimeo_url($video_vimeo, true, true) : '' ?><?= $video_file ? $video_file : '' ?>" data-bs-toggle="modal" data-bs-target="#videoTextBlockModal<?= $block['id']; ?>">
									<span class="button-icon"></span>
									<span class="button-label"><?= $video_label_play ? $video_label_play : 'Play'; ?></span>
								</button>
							</div>
						<?php endif; ?>

					</div>

				</div>
			</div>
		</div>

		<div class="modal fade" id="videoTextBlockModal<?= $block['id']; ?>" role="dialog" tabindex="-1" aria-labelledby="videoTextBlockModal<?= $block['id']; ?>Title" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title visually-hidden" id="videoTextBlockModal<?= $block['id']; ?>Title">videoTextBlockModal<?= $block['id']; ?>Title</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="ratio ratio-16x9">
							<video class="ratio ratio-16x9 embed-responsive-item" controls>
								<source type="video/mp4">
								Your browser does not support the video tag.
							</video>
							<iframe class="ratio ratio-16x9 embed-responsive-item" allowfullscreen></iframe>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn-close" data-bs-dismiss="modal"><span class="visually-hidden">Close Popup</span></button>
					</div>
				</div>
			</div>
		</div>

	</div>

<?php
}
