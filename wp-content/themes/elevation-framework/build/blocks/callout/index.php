<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Callout Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function callout_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block__callout--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'callout';
	$className = 'dynamic-assets-load alignfull block block-np block__callout';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.
	$callout_editor = get_field('callout_editor');
	$link = get_field('callout_link');

	// settings
	$callout_bg = get_field('callout_bg-color');

	$callout_btn_side = get_field('callout_btn_side');

	$content_alignment = get_field('content_alignment');
	$container_alignment = get_field('container_alignment');
	$container_width = get_field('container_width');
	$offset_container_width = get_field('offset_container_width');


?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?> <?= 'block--bg-' . esc_html($callout_bg); ?> ">

				<div class="block__container container">

					<div class="block__row row justify-content-<?= esc_html($container_alignment); ?>">

						<div class="block__col col-xl-<?= esc_html($container_width); ?> col-12  offset-xl-<?= esc_html($offset_container_width); ?>">

							<div class="block__content <?= esc_html($content_alignment); ?> <?= $callout_btn_side ? 'flex-row' : '' ?> block__callout--content">

								<div class="block__editor block__editor--color-dark">
									<?= wp_kses_post($callout_editor); ?>
								</div>

								<?php if (isset($link['link_text']) && !empty($link['link_text'])) : ?>
									<div class="block__buttons <?= esc_html($content_alignment); ?>">
										<?= Helpers::global_link($link); ?>
									</div>
								<?php endif; ?>

							</div>

						</div>

					</div>

				</div>

					</div>

				<?php
			}
