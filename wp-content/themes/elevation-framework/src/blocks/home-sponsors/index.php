<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Homepage - Sponsors Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

function home_sponsors_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block-home-sponsors--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'home-sponsors';
	$className = 'dynamic-assets-load block block-home-sponsors';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$sponsors = get_field('homesponsors_items');
	$sponsors_header = get_field('homesponsors_header');

	// settings

	// $centerSwiperControlClass = ($swiper_center) ? '' : 'w-container';
	$centerSwiperControlClass = 'w-container';

	$content_alignment = get_field('content_alignment');
	$container_alignment = get_field('container_alignment');
	$container_width = get_field('container_width');
	$offset_container_width = get_field('offset_container_width');
	$sponsors_overflow_hidden = get_field('homesponsors_overflow_hidden');
	$visually_hidden = get_field('visually_hidden');

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

	$swiper = get_field('slider_settings');
	$swiper_attrs = Helpers::get_swiper_attrs($swiper, [
		'centered-slides' => false,
		'grab-cursor' => false,
		'slides-per-view' => 2,
		'space-between' => 30,
		'slides-per-view-md' => 3,
		'slides-per-view-lg' => 4,
		'slides-per-view-xl' => 5,
		'slides-per-view-xxl' => 5,
		'slide-to-click-slide' => true,
		'delay' => 7000,
		'disable-on-interaction' => false
	]);

?>
	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?>">
		<div class="block-home-sponsors__container container">

			<div class="block-home-sponsors__row row justify-content-<?= esc_html($container_alignment); ?>">

				<div class="block-home-sponsors__col col-xl-<?= esc_html($container_width); ?> col-12  offset-xl-<?= esc_html($offset_container_width); ?>">

					<?php if (isset($sponsors_header['editor']) && !empty($sponsors_header['editor']) || isset($sponsors_header['links']) && !empty($sponsors_header['links'])) : ?>
						<div class="block-home-sponsors__content block__content <?= esc_html($content_alignment); ?>">
							<?php Helpers::global_content($sponsors_header['editor'], $sponsors_header['links']); ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($sponsors)) : ?>

						<div class="block-home-sponsors--carousel <?= $sponsors_overflow_hidden ? 'overflow-hidden' : '' ?>">

							<div class="block-home-sponsors__swiper" id="swiper-<?= esc_attr($id); ?>" <?php echo esc_attr($swiper_attrs); ?>>

								<div class="swiper-wrapper">

									<?php $post_count = 0; ?>

									<?php foreach ($sponsors as $sponsor) : ?>

										<div class="swiper-slide">

											<?php if (isset($sponsor['link']) && !empty($sponsor['link'])) : ?>

												<a href="<?= esc_url($sponsor['link']['url']); ?>" target="<?= esc_attr($sponsor['link']['target']); ?>">

												<?php endif; ?>

												<?php if (isset($sponsor['image']) && !empty($sponsor['image'])) : ?>

													<?= Helpers::global_image($sponsor['image'], ['class' => 'sponsor__image']) ?>

												<?php endif; ?>

												<?php if (isset($sponsor['title']) && !empty($sponsor['title'])) : ?>
													<div class="sponsor__body <?= $visually_hidden ? 'visually-hidden' : '' ?>">
														<span class="sponsor__body__title h6 <?= $visually_hidden ? 'visually-hidden' : '' ?>"><?= esc_html($sponsor['title']); ?></span>
													</div>
												<?php endif; ?>

												<?php if (isset($sponsor['link']) && !empty($sponsor['link'])) : ?>

												</a>

											<?php endif; ?>

										</div>

										<?php $post_count++; ?>

									<?php endforeach; ?>

								</div>

								<div class="swiper__container-controls <?= esc_html($content_alignment) . ' ' . esc_html($centerSwiperControlClass); ?>">
									<div class="swiper-button-prev"></div>
									<div class="swiper-pagination"></div>
									<div class="swiper-button-next"></div>

									<?php if ($post_count > 3) : ?>
										<button type="button" aria-label="play" class="swiper-button-play-pause">
											<span class="icon" aria-hidden="true"></span>
										</button>
									<?php endif; ?>

								</div>

							</div>

						</div>

					<?php endif; ?>

				</div>

			</div>

		</div>
	</div>

<?php
}
