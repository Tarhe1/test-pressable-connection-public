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
function statistics_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block__stats--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'statistics';
	$className = 'dynamic-assets-load block__stats';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$stats_editor = get_field('stats_editor');
	$items = get_field('stats_items');
	$links = get_field('stats_links');

	// settings

	$stats_bg = get_field('stats_bg-color');
	$stats_grid = get_field('stats_grid');
	$stats_hr = get_field('stats_hr');
	$stats_layout = get_field('stats_layout');

	$content_alignment = get_field('content_alignment');
	$container_alignment = get_field('container_alignment');
	$container_width = get_field('container_width');
	$offset_container_width = get_field('offset_container_width');

	$global_title_size = get_field('stats_global_title_size');

?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . ' alignfull block__stats--bg-' . esc_html($stats_bg); ?>">

		<div class="block__container container">

			<div class="block__row row justify-content-<?= esc_html($container_alignment); ?>">

				<div class="block__col col-xl-<?= esc_html($container_width); ?> col-12  offset-xl-<?= esc_html($offset_container_width); ?>">

					<div class="block__content <?= esc_html($content_alignment); ?>">

						<div class="block__editor block__editor--color-dark">
							<?= wp_kses_post($stats_editor); ?>
						</div>

						<?php if ($stats_layout == 'stats-icon') : ?>
							<?php if (!empty($links)) : ?>
								<div class="block__buttons <?= esc_html($content_alignment); ?>">
									<?php foreach ($links as $link) : ?>
										<?= Helpers::global_link($link['links']); ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if (!empty($items)) : ?>

							<div class="block__stats<?= '--' . esc_html($stats_layout); ?> counter">

								<?= $stats_hr ? '<hr class="line hr-top dark">' : ''; ?>

								<div class="block__stats-row row row-cols-xl-<?= esc_html($stats_grid); ?> row-cols-md-2 row-cols-1">

									<?php foreach ($items as $item) : ?>

										<div class="col">

											<article class="statistic <?= $content_alignment == 'center' ? 'flex-column' : ''; ?>">

												<?php if (isset($item['icon']) && !empty($item['icon'])) : ?>
													<?= Helpers::global_image($item['icon'], ['echo' => false, 'class' => 'statistic__icon']);
													?>
												<?php endif; ?>

												<div class="statistic__body">

													<h4 class="statistic__body__title <?= esc_html($global_title_size); ?>">

														<?php if (isset($item['before_value']) && !empty($item['before_value'])) : ?>
															<span class="statistic__body__value--before">
																<?= esc_html($item['before_value']); ?>
															</span>
														<?php endif; ?>

														<?php if (isset($item['value']) && !empty($item['value'])) :
															if (fmod($item['value'], 1) !== 0.00) { // your code if its decimals has a value
														?>

																<span class="statistic__body__value numscrollerdec" data-num="<?= esc_html($item['value']); ?>"><?= esc_html($item['value']); ?></span>

															<?php } else { // your code if the decimals are .00, or is an integer
															?>

																<?php if (isset($item['remove_comma']) && !empty($item['remove_comma'])) : ?>

																	<span class="statistic__body__value numscrolleryears" data-num="<?= esc_html($item['value']); ?>"><?= esc_html($item['value']); ?></span>

																<?php else : ?>

																	<span class="statistic__body__value numscroller" data-num="<?= esc_html($item['value']); ?>"><?= esc_html($item['value']); ?></span>

																<?php endif; ?>

														<?php }
														endif; ?>

														<?php if (isset($item['after_value']) && !empty($item['after_value'])) : ?>
															<span class="statistic__body__value--after">
																<?= esc_html($item['after_value']); ?>
															</span>
														<?php endif; ?>

													</h4>

													<?php if (isset($item['description']) && !empty($item['description'])) : ?>
														<div class="statistic__body__excerpt">
															<?= wp_kses_post($item['description']); ?>
														</div>
													<?php endif; ?>

												</div>

											</article>

										</div>

									<?php endforeach; ?>

								</div>

								<?= $stats_hr ? '<hr class="line hr-bottom dark">' : ''; ?>

							</div>

						<?php endif; ?>

						<?php if ($stats_layout == 'stats') : ?>

							<?php if (!empty($links)) : ?>
								<div class="block__buttons <?= esc_html($content_alignment); ?>">
									<?php foreach ($links as $link) : ?>
										<?= Helpers::global_link($link['links']); ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

						<?php endif; ?>

					</div>

				</div>

			</div>

		</div>

	</div>

<?php
}
