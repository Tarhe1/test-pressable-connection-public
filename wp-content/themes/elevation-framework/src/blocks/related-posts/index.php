<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Related Posts Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function related_posts_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block__related--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'related-posts';
	$className = 'dynamic-assets-load block block__related';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$related_header = get_field('related_header');
	$items = get_field('related_items');
	$related_grid = get_field('related_grid');
	$related_layout = get_field('related_layout');
	$related_options = get_field('related_options');
	$related_hr = get_field('related_hr');

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

	$swiper = get_field('slider_settings');
	$swiper_attrs = Helpers::get_swiper_attrs($swiper, [
		'centered-slides' => false,
		'grab-cursor' => false,
		'slides-per-view' => 1,
		'space-between' => 30,
		'slides-per-view-md' => 2,
		'slides-per-view-lg' => 3,
		'slides-per-view-xl' => 3,
		'slides-per-view-xxl' => 3,
		'slide-to-click-slide' => true,
		'delay' => 7000,
		'disable-on-interaction' => false
	]);


?>
	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?>">
		<div class="block__container container">
			<div class="block__row row justify-content-<?= esc_html($container_alignment); ?>">
				<div class="block__col col-xl-<?= esc_html($container_width); ?> col-12 offset-xl-<?= esc_html($offset_container_width); ?>">

					<?php if (isset($related_header['editor']) && !empty($related_header['editor']) || isset($related_header['links']) && !empty($related_header['links'])) : ?>
						<div class="block__content <?= esc_html($content_alignment); ?>">
							<?php Helpers::global_content($related_header['editor'], $related_header['links']); ?>
						</div>
					<?php endif; ?>

					<div class="block__related <?= 'block__related--' . esc_html($related_layout); ?>">

						<?php if (is_user_logged_in()) : ?>
							<?php if ($related_options == "by_manually") : ?>
								<span class="admin-view h5">By Manually</span>
							<?php elseif ($related_options == 'by_terms') : ?>
								<span class="admin-view h5">By Category</span>
							<?php elseif ($related_options == 'by_posts') : ?>
								<span class="admin-view h5">By Post Object</span>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ($related_layout == "grid") : ?>

							<div class="block__related-row row row-cols-xl-<?= $related_grid ? esc_html($related_grid) : '3'; ?> row-cols-md-2 row-cols-1">

							<?php else : ?>

								<div class="swiper__feed swiper__container--feed" id="swiper-<?= esc_attr($id); ?>" <?php echo esc_attr($swiper_attrs); ?>>



									<div class="swiper-wrapper">

									<?php endif; ?>

									<?php if ($related_options == "by_manually") : ?>

										<?php if (!empty($items)) : ?>

											<?php $post_count = 0; ?>

											<?php foreach ($items as $item) : ?>

												<?php if ($related_layout == "grid") : ?>
													<div class="col">
														<article class="card">
														<?php else : ?>
															<article class="card swiper-slide">
															<?php endif; ?>

															<?php if (isset($item['link']) && !empty($item['link'])) : ?>
																<?php if (isset($item['image']) && !empty($item['image'])) : ?>
																	<?= Helpers::global_image(
																		$item['image'],
																		[
																			'echo' => false,
																			'class' => 'card__image card__image--hover-effect'
																		]
																	); ?>
																<?php endif; ?>
															<?php else : ?>
																<?php if (isset($item['image']) && !empty($item['image'])) : ?>
																	<?= Helpers::global_image(
																		$item['image'],
																		[
																			'echo' => false,
																			'class' => 'card__image'
																		]
																	); ?>
																<?php endif; ?>
															<?php endif; ?>
															<div class="card__body">
																<?php if (isset($item['title']) && !empty($item['title'])) : ?>
																	<h3 class="card__title line-clamp-2 h6">
																		<span><?= esc_html($item['title']); ?></span>
																	</h3>
																<?php endif; ?>
																<?php if (isset($item['description']) && !empty($item['description'])) : ?>
																	<div class="card__excerpt line-clamp-4">
																		<p><?= wp_kses_post($item['description']); ?></p>
																	</div>
																<?php endif; ?>
																<?php if (isset($item['categories']) && !empty($item['categories'])) : ?>
																	<div class="card__category">
																		<?php foreach ($item['categories'] as $category) : ?>
																			<?php if (isset($category['category']) && !empty($category['category'])) : ?>
																				<span><?= esc_html($category['category']); ?></span>
																			<?php endif; ?>
																		<?php endforeach; ?>
																	</div>
																<?php endif; ?>
															</div>

															<?php if (isset($item['link']) && !empty($item['link'])) : ?>
																<?php $target = $item['link']['target'] ? $item['link']['target'] : '_self'; ?>
																<a href="<?= esc_url($item['link']['url']); ?>" target="<?= esc_attr($target); ?>" class="stretched-link stretched-link-custom">Read Post <?= esc_html($item['title']); ?></a>
															<?php endif; ?>

															<?php if ($related_layout == "grid") : ?>
															</article>
													</div>
												<?php else : ?>
													</article>
												<?php endif; ?>

												<?php $post_count++; ?>

											<?php endforeach; ?>
										<?php endif; ?>

									<?php elseif ($related_options == 'by_terms') : ?>

										<?php
										global $post;
										$post_type = 'post';
										$taxonomy  = 'category';
										$term_ids  = get_field('related_taxonomy');

										if ($term_ids) {
											$args = array(
												'post_type'      => $post_type,
												'posts_per_page' => -1,
												'tax_query'      => array(
													array(
														'taxonomy' => $taxonomy,
														'field'    => 'term_id',
														'terms'    => $term_ids,
														'operator' => 'IN',
													),
												),
											);
										} else {
											$args = array(
												'post_type'      => $post_type,
												'posts_per_page' => -1,
											);
										}

										$posts = new WP_Query($args);

										$post_count = $posts->post_count;

										if ($posts->have_posts()) :
											while ($posts->have_posts()) : $posts->the_post();
												$permalink = get_permalink($post->ID);
												$title = get_the_title($post->ID);
												$description = get_the_excerpt($post->ID);
												$categories = get_the_terms($post->ID, 'category');
												$date = get_the_date('F m');
												if (has_post_thumbnail($post->ID)) {
													$image = get_post_thumbnail_id($post->ID);
													$alt_image = sanitize_text_field(get_post_meta($image, '_wp_attachment_image_alt', true));
												} else {
													$image = get_template_directory_uri() . '/src/assets/images/default.svg';
													$alt_image = 'default image';
												}
										?>

												<?php if ($related_layout == "grid") : ?>
													<div class="col">
														<article class="card">
														<?php else : ?>
															<article class="card swiper-slide">
															<?php endif; ?>
															<?= Helpers::global_image(
																$image,
																[
																	'size' => 'medium',
																	'alt' => $alt_image,
																	'echo' => false,
																	'class' => 'card__image card__image--hover-effect'
																]
															); ?>

															<div class="card__body">
																<?php if (isset($title) && !empty($title)) : ?>
																	<h3 class="card__title line-clamp-2 h6">
																		<span>
																			<?= esc_html($title); ?>
																		</span>
																	</h3>
																<?php endif; ?>
																<?php if (isset($description) && !empty($description)) : ?>
																	<div class="card__excerpt line-clamp-4">
																		<p><?= wp_kses_post($description); ?></p>
																	</div>
																<?php endif; ?>

																<?php if (isset($categories) && !empty($categories)) : ?>
																	<div class="card__category">
																		<?php foreach ($categories as $category) : ?>
																			<span><?= esc_html($category->name); ?></span>
																		<?php endforeach; ?>
																	</div>
																<?php endif; ?>
															</div>

															<a href="<?= esc_url($permalink); ?>" target="_self" class="stretched-link stretched-link-custom">Read Post <?= esc_html($title) ?></a>

															<?php if ($related_layout == "grid") : ?>
															</article>
													</div>
												<?php else : ?>
													</article>
												<?php endif; ?>

												<?php $post_count++; ?>

											<?php endwhile; ?>
										<?php endif; ?>

									<?php elseif ($related_options == 'by_posts') : ?>

										<?php $featured_posts = get_field('related_post_object');
										if ($featured_posts) :

											$post_count = 0;

											foreach ($featured_posts as $featured_post) :
												$permalink = get_permalink($featured_post->ID);
												$title = get_the_title($featured_post->ID);
												$description = get_the_excerpt($featured_post->ID);
												$categories = get_the_terms($featured_post->ID, 'category');
												$date = get_the_date('m F Y');
												if (has_post_thumbnail($featured_post->ID)) {
													$image = get_post_thumbnail_id($featured_post->ID);
													$alt_image = sanitize_text_field(get_post_meta($image, '_wp_attachment_image_alt', true));
												} else {
													$image = get_template_directory_uri() . '/src/assets/images/default.svg';
													$alt_image = 'default image';
												} ?>

												<?php if ($related_layout == "grid") : ?>
													<div class="col">
														<article class="card">
														<?php else : ?>
															<article class="card swiper-slide">
															<?php endif; ?>
															<?= Helpers::global_image(
																$image,
																[
																	'size' => 'medium',
																	'alt' => $alt_image,
																	'echo' => false,
																	'class' => 'card__image card__image--hover-effect'
																]
															); ?>

															<div class="card__body">
																<?php if (isset($title) && !empty($title)) : ?>
																	<h3 class="card__title h6">
																		<span>
																			<?= esc_html($title); ?>
																		</span>
																	</h3>
																<?php endif; ?>
																<?php if (isset($description) && !empty($description)) : ?>
																	<div class="card__excerpt line-clamp-4">
																		<p><?= wp_kses_post($description); ?></p>
																	</div>
																<?php endif; ?>

																<?php if (isset($categories) && !empty($categories)) : ?>
																	<div class="card__category">
																		<?php foreach ($categories as $category) : ?>
																			<span><?= esc_html($category->name); ?></span>
																		<?php endforeach; ?>
																	</div>
																<?php endif; ?>
															</div>

															<a href="<?= esc_url($permalink); ?>" target="_self" class="stretched-link stretched-link-custom">Read Post <?= esc_html($title) ?></a>

															<?php if ($related_layout == "grid") : ?>
															</article>
													</div>
												<?php else : ?>
													</article>
												<?php endif; ?>

												<?php $post_count++; ?>
											<?php endforeach; ?>
										<?php endif; ?>


									<?php endif; ?>

									<?php if ($related_layout == "grid") : ?>
									</div>
								<?php else : ?>
								</div>

								<?= $related_hr ? '<hr class="line hr-bottom">' : ''; ?>

								<div class="swiper__container-controls center">
									<div class="swiper-button-prev"></div>
									<div class="swiper-pagination"></div>
									<div class="swiper-button-next"></div>

									<?php if ($post_count > 3) { ?>
										<button type="button" aria-label="play" class="swiper-button-play-pause">
											<span class="icon" aria-hidden="true"></span>
										</button>
									<?php } ?>

								</div>

							</div>
						<?php endif; ?>


					</div>

				</div>
			</div>
		</div>
	</div>
<?php
}
