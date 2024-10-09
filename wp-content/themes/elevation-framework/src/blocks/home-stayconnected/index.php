<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Homepage - Stay Connected Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function home_stayconnected_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block-home-stayconnected--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'home-stayconnected';
	$className = 'dynamic-assets-load block block-home-stayconnected';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$editor = get_field('homestayconnected_editor');
	$social_feed = get_field('homestayconnected_socialfeed');

	if(!empty($editor) || !empty($social_feed)):

?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">

		<div class="block-home-stayconnected__container container">

			<?php if (!empty($editor)) : ?>
				<div class="block-home-stayconnected__row row justify-content-center">
					<div class="block-home-stayconnected__col col-xl-10 col-12 offset-xl-1">
						<div class="block-home-stayconnected__content">
							<div class="block-home-stayconnected__editor block__editor block__editor--color-dark">
								<?= wp_kses_post($editor); ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if (!empty($social_feed)) : ?>
				<div class="block-home-stayconnected__social-feed">
					<?= do_shortcode($social_feed); ?>
				</div>
			<?php endif; ?>

		</div>

	</div>

	<?php endif; ?>
<?php
}
