<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Custom Callout Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function customcallout_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block-custom-callout--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'custom-callout';
	$className = 'dynamic-assets-load block-custom-callout';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.
	$callout_image = get_field('customcallout_image');
	$callout_editor = get_field('customcallout_editor');
	$link = get_field('customcallout_link');

	// settings
	$callout_layout = get_field('customcallout_layout');
	$callout_image_effect = get_field('customcallout_image_effect');

	$content_alignment = get_field('content_alignment');
	$container_alignment = get_field('container_alignment');

	$container_width = get_field('container_width');
	$offset_container_width = get_field('offset_container_width');

	$container_width_lg = get_field('container_width_lg');
	$offset_container_width_lg = get_field('offset_container_width_lg');

?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?> <?= 'block-custom-callout--type-' . esc_html($callout_layout); ?>">
		
		<div class="block-custom-callout__background">
			<?php if (!$callout_image_effect) : ?>
				<?php Helpers::global_image($callout_image, ['class' => 'block-custom-callout--background-behind', 'echo' => true, 'decorative' => true]); ?>
			<?php else : ?>
				<figure class="block-custom-callout--background-fixed" style="background-image: url('<?= esc_attr($callout_image['image']['url']); ?>');"></figure>
			<?php endif; ?>
		</div>

		<div class="block-custom-callout__container container">
			<div class="block-custom-callout__row row justify-content-<?= esc_html($container_alignment); ?>">

				<div class="block-custom-callout__col col-xl-<?= esc_html($container_width); ?> offset-xl-<?= esc_html($offset_container_width); ?> col-lg-<?= esc_html($container_width_lg); ?> offset-lg-<?= esc_html($offset_container_width_lg); ?> col-12">
					<div class="block-custom-callout__content block__content <?= esc_html($content_alignment); ?>">

						<div class="block-custom-callout__editor block__editor block__editor--color-light">
							<?= wp_kses_post($callout_editor); ?>
						</div>

						<?php if (isset($link['link_text']) && !empty($link['link_text'])) : ?>
							<div class="block-custom-callout__buttons block__buttons <?= esc_html($content_alignment); ?>">
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
