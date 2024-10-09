<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Stats Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function text_photo_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{
	// Create id attribute allowing for custom "anchor" value.
	$id = 'block__textphoto--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'text-photo';
	$className = 'dynamic-assets-load block block__textphoto';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$textphoto_image = get_field('textphoto_image');
	$textphoto_image_link = get_field('textphoto_image_link');
	$textphoto_image_link_open_new_tab = get_field('textphoto_image_link_open_new_tab');
	$textphoto_editor = get_field('textphoto_editor');
	$items = get_field('textphoto_buttons');
	$textphoto_caption = get_field('textphoto_caption');

	$contentDistribution = get_field('content_distribution');
	$text = get_field('text_col');
	$textII = get_field('text_col_II');
	$textIII = get_field('text_col_III');
	$textIV = get_field('text_col_IV');
	$textV = get_field('text_col_V');
	$textVI = get_field('text_col_VI');

	// settings

	$textphoto_layout = get_field('textphoto_layout');
	$textphoto_switch = get_field('textphoto_switch');
	$textphoto_image_contain = get_field('textphoto_image_contain');
	$textphoto_image_contain_type = get_field('textphoto_image_contain_type');
	$textphoto_show_caption = get_field('textphoto_show_caption');
	$container_background = get_field('container_background');
	$background_color = get_field('background_color');
	$row_alignment = get_field('row_alignment');
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

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?>">

		<div class="block__container container">

			<div class="block__row row justify-content-<?= esc_html($container_alignment); ?>">

				<div class="block__col <?= $container_background ? 'bg-box block__col' . $background_color : ' '; ?> col-xl-<?= esc_html($container_width); ?> col-12  offset-xl-<?= esc_html($offset_container_width); ?>">

					<?php if ($textphoto_layout == 'type2') : ?>
						<?php if ($textphoto_image_contain) : ?>
							<div class="block__row has-image row <?= esc_html($textphoto_switch) ? 'block__row--reverse' : ''; ?> row-cols-xl-2 row-cols-1 align-items-<?= esc_html($row_alignment) . ' ' . ($textphoto_image_contain_type); ?>">
						<?php else : ?>
							<div class="block__row has-image row <?= esc_html($textphoto_switch) ? 'block__row--reverse' : ''; ?> row-cols-xl-2 row-cols-1 align-items-<?= esc_html($row_alignment); ?>">
						<?php endif; ?>
					<?php endif; ?>

						<?php if ($textphoto_editor) : ?>

							<div class="block__content <?= esc_html($content_alignment); ?>">

								<div class="block__editor block__editor--color-dark">
									<?= wp_kses_post($textphoto_editor); ?>
								</div>

								<?php if (!empty($items)) : ?>
									<div class="block__buttons <?= esc_html($content_alignment); ?>">
										<?php foreach ($items as $item) : ?>
											<?= Helpers::global_link($item['textphoto_link']); ?>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

							</div>

						<?php endif; ?>

						<?php if ($textphoto_layout == 'type2' || $textphoto_image) : ?>

							<aside class="block__figure">
								<?php if ($textphoto_image_link) : ?>
									<?php
									$attrs = $textphoto_image_link_open_new_tab ? 'target=_blank rel=noopener' : '';
									?>
									<a href="<?php echo esc_url($textphoto_image_link);
												echo esc_attr($attrs) ?> ">
									<?php endif; ?>
									<?php Helpers::global_image($textphoto_image, ['class' => 'block__photo', 'decorative' => false]); ?>
									<?php if ($textphoto_show_caption) : ?>
										<div class="block__caption">
											<?= wp_kses_post($textphoto_caption); ?>
										</div>
									<?php endif; ?>
									<?php if ($textphoto_image_link) : ?>
									</a>
								<?php endif; ?>
							</aside>

						</div>

					<?php endif; ?>

					<?php if ($textphoto_layout == 'type3') : ?>

						<?php if ($contentDistribution == '1' || $contentDistribution == NULL) : ?>

							<div class="block__row row row-cols-xl-1 row-cols-1">
								<?php if (isset($text['editor']) && !empty($text['editor']) || isset($text['links']) && !empty($text['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($text['editor'], $text['links']); ?>
									</div>
								<?php endif; ?>
							</div>

						<?php elseif ($contentDistribution == '2') : ?>

							<div class="block__row row row-cols-xl-2 row-cols-md-2 row-cols-1">
								<?php if (isset($text['editor']) && !empty($text['editor']) || isset($text['links']) && !empty($text['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($text['editor'], $text['links']); ?>
									</div>
								<?php endif; ?>
								<?php if (isset($textII['editor']) && !empty($textII['editor']) || isset($textII['links']) && !empty($textII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textII['editor'], $textII['links']); ?>
									</div>
								<?php endif; ?>
							</div>

						<?php elseif ($contentDistribution == '3') : ?>

							<div class="block__row row row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-1">

								<?php if (isset($text['editor']) && !empty($text['editor']) || isset($text['links']) && !empty($text['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($text['editor'], $text['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textII['editor']) && !empty($textII['editor']) || isset($textII['links']) && !empty($textII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textII['editor'], $textII['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textIII['editor']) && !empty($textIII['editor']) || isset($textIII['links']) && !empty($textIII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textIII['editor'], $textIII['links']); ?>
									</div>
								<?php endif; ?>

							</div>

						<?php elseif ($contentDistribution == '4') : ?>

							<div class="block__row row row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-1">

								<?php if (isset($text['editor']) && !empty($text['editor']) || isset($text['links']) && !empty($text['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($text['editor'], $text['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textII['editor']) && !empty($textII['editor']) || isset($textII['links']) && !empty($textII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textII['editor'], $textII['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textIII['editor']) && !empty($textIII['editor']) || isset($textIII['links']) && !empty($textIII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textIII['editor'], $textIII['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textIV['editor']) && !empty($textIV['editor']) || isset($textIV['links']) && !empty($textIV['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textIV['editor'], $textIV['links']); ?>
									</div>
								<?php endif; ?>

							</div>

						<?php elseif ($contentDistribution == '5') : ?>

							<div class="block__row row row-cols-xl-5 row-cols-lg-3 row-cols-md-2 row-cols-1">

								<?php if (isset($text['editor']) && !empty($text['editor']) || isset($text['links']) && !empty($text['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($text['editor'], $text['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textII['editor']) && !empty($textII['editor']) || isset($textII['links']) && !empty($textII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textII['editor'], $textII['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textIII['editor']) && !empty($textIII['editor']) || isset($textIII['links']) && !empty($textIII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textIII['editor'], $textIII['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textIV['editor']) && !empty($textIV['editor']) || isset($textIV['links']) && !empty($textIV['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textIV['editor'], $textIV['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textV['editor']) && !empty($textV['editor']) || isset($textV['links']) && !empty($textV['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textV['editor'], $textV['links']); ?>
									</div>
								<?php endif; ?>

							</div>

						<?php elseif ($contentDistribution == '6') : ?>

							<div class="block__row row row-cols-xl-6 row-cols-lg-3 row-cols-md-2 row-cols-1">

								<?php if (isset($text['editor']) && !empty($text['editor']) || isset($text['links']) && !empty($text['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($text['editor'], $text['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textII['editor']) && !empty($textII['editor']) || isset($textII['links']) && !empty($textII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textII['editor'], $textII['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textIII['editor']) && !empty($textIII['editor']) || isset($textIII['links']) && !empty($textIII['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textIII['editor'], $textIII['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textIV['editor']) && !empty($textIV['editor']) || isset($textIV['links']) && !empty($textIV['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textIV['editor'], $textIV['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textV['editor']) && !empty($textV['editor']) || isset($textV['links']) && !empty($textV['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textV['editor'], $textV['links']); ?>
									</div>
								<?php endif; ?>

								<?php if (isset($textVI['editor']) && !empty($textVI['editor']) || isset($textVI['links']) && !empty($textVI['links'])) : ?>
									<div class="col">
										<?php Helpers::global_content($textVI['editor'], $textVI['links']); ?>
									</div>
								<?php endif; ?>

							</div>

						<?php endif; ?>

					<?php endif; ?>

				</div>

			</div>

		</div>

	</div>

<?php
}
