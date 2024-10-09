<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Homepage - Impact Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function home_statistics_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block-home-stats--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'home-statistics';
	$className = 'dynamic-assets-load block block-np block-home-stats';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$editor = get_field('homestatistics_editor');
	$stats = get_field('homestatistics_stats');
	$button = get_field('homestatistics_button');

	// settings

	$global_title_size = get_field('homestatistics_global_title_size');

?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">

		<div class="block-home-stats__container container">

			<div class="block-home-stats__row row justify-content-center">

				<div class="block-home-stats__col col-xl-10 col-12">

					<div class="block-home-stats__stats">

						<?php if (!empty($editor)) : ?>
							<div class="block-home-stats__editor block__editor block__editor--color-dark">
								<?= wp_kses_post($editor); ?>
							</div>
						<?php endif; ?>

						<?php if (!empty($stats)) : ?>
							<div class="block-home-stats-row counter">

								<?php foreach ($stats as $statistic) : ?>
									<div class="statistic">

										<div class="statistic__body">

											<div class="statistic__title <?= esc_html($global_title_size); ?>">

												<?php if (isset($statistic['before_value']) && !empty($statistic['before_value'])) : ?>
													<span class="statistic__value--before">
														<?= esc_html($statistic['before_value']); ?>
													</span>
												<?php endif; ?>

												<?php if (isset($statistic['value']) && !empty($statistic['value'])) :
													if (fmod($statistic['value'], 1) !== 0.00) { // your code if its decimals has a value
												?>

														<span class="statistic__value numscrollerdec" data-num="<?= esc_html($statistic['value']); ?>"><?= esc_html($statistic['value']); ?></span>

													<?php } else { // your code if the decimals are .00, or is an integer
													?>

														<?php if (isset($statistic['remove_comma']) && !empty($statistic['remove_comma'])) : ?>

															<span class="statistic__value numscrolleryears" data-num="<?= esc_html($statistic['value']); ?>"><?= esc_html($statistic['value']); ?></span>

														<?php else : ?>

															<span class="statistic__value numscroller" data-num="<?= esc_html($statistic['value']); ?>"><?= esc_html($statistic['value']); ?></span>

														<?php endif; ?>

												<?php }
												endif; ?>

												<?php if (isset($statistic['after_value']) && !empty($statistic['after_value'])) : ?>
													<span class="statistic__value--after">
														<?= esc_html($statistic['after_value']); ?>
													</span>
												<?php endif; ?>

											</div>

											<?php if (isset($statistic['description']) && !empty($statistic['description'])) : ?>
												<div class="statistic__excerpt">
													<p><?= wp_kses_post($statistic['description']); ?></p>
												</div>
											<?php endif; ?>

										</div>

									</div>
								<?php endforeach; ?>

							</div>
						<?php endif; ?>

						<?php if (isset($button['link_text']) && !empty($button['link_text'])) : ?>
							<div class="block-home-stats__buttons block__buttons">
								<?= Helpers::global_link($button); ?>
							</div>
						<?php endif; ?>

					</div>

				</div>

			</div>

		</div>

	</div>

<?php
}
