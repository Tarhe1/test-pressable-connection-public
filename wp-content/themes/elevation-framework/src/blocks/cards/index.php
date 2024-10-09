<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

// use ElevationFramework\Lib\BlockLibrary\Load;

/**
 * Cards Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function cards_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block__cards--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'cards';
	$className = 'dynamic-assets-load block block__cards';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.
	$cards_header = get_field('cards_header');
	$items = get_field('cards_items');

	//settings
	$cards_grid = get_field('cards_grid');
	$cards_layout = get_field('cards_layout');
	$cards_hr = get_field('cards_hr');
	$global_title_size = get_field('global_title_size');

	$cards_different_borders = get_field('cards_diferent_borders');

	$cards_initial_border = get_field('cards_initial_border');
	$cards_hover_border = get_field('cards_hover_border');

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
				<div class="block__col col-xl-<?= esc_html($container_width); ?> col-12 offset-xl-<?= esc_html($offset_container_width); ?>">

					<?php if (isset($cards_header['editor']) && !empty($cards_header['editor']) || isset($cards_header['links']) && !empty($cards_header['links'])) : ?>
						<div class="block__content <?= esc_html($content_alignment); ?>">
							<?php Helpers::global_content($cards_header['editor'], $cards_header['links']); ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($items)) : ?>

						<div class="block__cards block__cards<?= '--' . esc_html($cards_layout); ?>">

							<?= $cards_hr ? '<hr class="line hr-top dark">' : ''; ?>

							<div class="block__cards-row row row-cols-xl-<?= esc_html($cards_grid); ?> row-cols-md-2 row-cols-1">

								<?php foreach ($items as $item) : ?>

									<div class="col">

										<?php if ($cards_different_borders) : ?>

											<article class="card" <?php if (isset($item['color']) && !empty($item['color'])) : ?> style="border-top-color: <?= esc_attr($item['color']); ?>" <?php endif; ?>>

											<?php else : ?>

												<article class="card">

												<?php endif; ?>

												<?php if (isset($item['link']) && !empty($item['link'])) : ?>
													<?php $target = $item['link']['target'] ? $item['link']['target'] : '_self'; ?>
													<a href="<?= esc_url($item['link']['url']); ?>" target="<?php echo esc_attr($target); ?>">

													<?php endif; ?>

													<?php if (isset($item['image']) && !empty($item['image'])) : ?>

														<?= Helpers::global_image($item['image'], ['echo' => false, 'class' => 'card__image--hover-effect']); ?>

													<?php endif; ?>

													<div class="card__body">

														<?php if ($cards_layout == 'icons-hover' || $cards_layout == 'icons-static') : ?>
															<?php if (isset($item['icon']) && !empty($item['icon'])) : ?>
																<?php Helpers::global_image($item['icon'], ['is_figure' => false, 'class' => 'card__body__icon']); ?>
															<?php endif; ?>
														<?php endif; ?>

														<?php if (isset($item['title']) && !empty($item['title'])) : ?>
															<h2 class="card__body__title <?= esc_html($global_title_size); ?>">
																<?= esc_html($item['title']); ?>
															</h2>
														<?php endif; ?>

														<?php if (isset($item['description']) && !empty($item['description'])) : ?>
															<?php if ($cards_layout == 'icons-static') : $limit_excerpt = "";
															elseif ($cards_layout == 'icons-hover-2') : $limit_excerpt = "line-clamp-3";
															else : $limit_excerpt = "line-clamp-4";
															endif; ?>
															<div class="card__body__excerpt <?= $limit_excerpt; ?>">
																<p><?= wp_kses_post($item['description']); ?></p>
															</div>
														<?php endif; ?>

														<?php if (isset($item['link']) && !empty($item['link'])) : ?>
															<span class="card__body__button">
																<?php if ($cards_layout == 'image-hover' || $cards_layout == 'image-icons-hover') : ?>
																	<span class="cta cta--simple-tertiary-light"><?= esc_html($item['link']['title']); ?>
																		<span class="visually-hidden">Read more about <?php echo esc_html($item['title']) ?> </span>
																	</span>
																<?php elseif ($cards_layout == 'icons-hover-2') : ?>
																	<span class="square-arrow">
																		<span class="visually-hidden">Read more about <?php echo esc_html($item['title']) ?> </span>

																	</span>
																<?php elseif ($cards_layout == 'icons-static') : ?>
																	<span class="cta cta--simple-tertiary-secondary"><?= esc_html($item['link']['title']); ?>
																		<span class="visually-hidden">Read more about <?php echo esc_html($item['title']) ?> </span>
																	</span>
																<?php endif; ?>
															</span>
														<?php endif; ?>

													</div>

													<?php if (isset($item['link']) && !empty($item['link'])) : ?>

													</a>

												<?php endif; ?>

												</article>

									</div>

								<?php endforeach; ?>

							</div>

							<?= $cards_hr ? '<hr class="line hr-bottom dark">' : ''; ?>

						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>

		<?php if ($cards_layout == 'icons-hover-2') : ?>
			<style>
				.block__cards--icons-hover-2 .card {
					border-top-color: <?= esc_attr($cards_initial_border); ?>;
				}

				.block__cards--icons-hover-2 .card:hover {
					border-top-color: <?= esc_attr($cards_hover_border); ?>;
				}
			</style>
		<?php endif; ?>

	</div>

<?php
}
