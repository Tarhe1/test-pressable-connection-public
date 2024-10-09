<?php
use ElevationFramework\Lib\BlockLibrary\Helpers;
/**
 * Homepage - Banner Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function home_banner_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block-home-banner--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'home-banner';
	$className = 'dynamic-assets-load block block-np block-home-banner';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}
	// Load values and handle defaults.

	if (get_field('homebanner_image')) {
		$homebanner_image = get_field('homebanner_image')['sizes']['hd'];
		$alt_homebanner_image = get_field('homebanner_image')['alt'];
	}else{
		$homebanner_image = get_template_directory_uri() . '/src/assets/images/home-banner.webp';
		$alt_homebanner_image = 'Default background decorative image';
	}

	$homebanner_image_retina = get_field('homebanner_image_retina');

	$homebanner_image_tablet = get_field('homebanner_image_tablet');
	if ($homebanner_image_tablet) {
		$homebanner_image_tablet = get_field('homebanner_image_tablet')['sizes']['hd'];
	}

	$homebanner_image_mobile = get_field('homebanner_image_mobile');
	if ($homebanner_image_mobile) {
		$homebanner_image_mobile = get_field('homebanner_image_mobile')['sizes']['hd'];
	}
  

	$homebanner_editor = get_field('homebanner_editor');
	$homebanner_button = get_field('homebanner_button');

?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">

		<picture class="block-home-banner__banner-wrapping">
			<?php if ($homebanner_image_retina) : ?>
				<source media="(max-width:320px)" srcset="<?= $homebanner_image_mobile; ?>">
				<source media="(max-width:768px)" srcset="<?= $homebanner_image_tablet; ?>">
			<?php endif; ?>
			<img src="<?= $homebanner_image; ?>" alt="<?= $alt_homebanner_image; ?>" width="1440" height="808" fetchpriority="high" decoding="async">
		</picture>

		<div class="block-home-banner__container container">
			<div class="block-home-banner__row row justify-content-center">

				<?php if ($homebanner_editor || isset($homebanner_button['link']) && !empty($homebanner_button['link'])) : ?>
					<div class="block-home-banner__col col-xl-8 col-lg-10">
						<div class="block-home-banner__content block__content">

							<div class="block-home-banner__editor block__editor block__editor--color-light">        
								<?= $homebanner_editor; ?>
							</div>

							<?php if (isset($homebanner_button['link_text']) && !empty($homebanner_button['link_text'])) : ?>
								<div class="block-home-banner__buttons block__buttons">
									<?= Helpers::global_link($homebanner_button); ?>
								</div>
							<?php endif; ?>
							
						</div>
					</div>
				<?php endif; ?>

			</div>
		</div>

	</div>

<?php
}
