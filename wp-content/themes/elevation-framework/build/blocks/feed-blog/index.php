<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Feed Blog Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function feed_blog_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block-feed-blog--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'feed-blog';
	$className = 'dynamic-assets-load block block-feed-blog';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$feed_header = get_field('feed_header');
	$feed_options = get_field('feed_options');

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

	$swiper_attrs = Helpers::get_swiper_attrs( [
		'centered-slides' => false,
		'grab-cursor' => false,
		'space-between' => 30,
		'slides-per-view' => 1,
		'slides-per-view-md' => 1,
		'slides-per-view-lg' => 2,
		'slides-per-view-xl' => 1.7,
		'slide-to-click-slide' => true,
		'delay' => 7000,
		'disable-on-interaction' => true
	]);

?>
	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?>">
		<div class="block-feed-blog__container container">

			<div class="block-feed-blog__row row justify-content-center">
				<div class="block-feed-blog__col col-xl-10 col-12">
					
					<?php if ($feed_header) : ?>
						<div class="block-feed-blog__content">
							<div class="h3"><?php echo $feed_header['title']; ?></div>
							<?= Helpers::global_link($feed_header['button']); ?>
						</div>
					<?php endif; ?>

					<div class="block-feed-blog__swiper" id="swiper-<?= esc_attr($id); ?>" <?php echo esc_attr($swiper_attrs); ?>>
						<div class="swiper-wrapper">
	
							<?php if ($feed_options == 'by_terms') : ?>
	
								<?php
								global $post;
								$post_type = 'post';
								$taxonomy  = 'category';
								$term_ids  = get_field('feed_taxonomy');
	
								if ($term_ids) {
									$args = array(
										'post_type'      => $post_type,
										'posts_per_page' => 12,
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
										'posts_per_page' => 12,
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
										$date = get_the_date('m F Y');
										if (has_post_thumbnail()) {
											$image = get_post_thumbnail_id($post->ID);
											$alt_image = sanitize_text_field(get_post_meta($image, '_wp_attachment_image_alt', true));
										} else {
											$image = get_template_directory_uri() . '/src/assets/images/default.svg';
											$alt_image = 'default image';
										}
								?>
	
										
										<div class="card swiper-slide">
						
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

													<?php if (isset($date) && !empty($date)) : ?>
														<div class="card__date">
															<?= esc_html($date); ?>
														</div>
													<?php endif; ?>

													<?php if (isset($title) && !empty($title)) : ?>
														<div class="card__title line-clamp-2 h6">
															<span>
																<?= esc_html($title); ?>
															</span>
														</div>
													<?php endif; ?>

													<?php if (isset($description) && !empty($description)) : ?>
														<div class="card__excerpt line-clamp-3">
															<p><?= wp_kses_post($description); ?></p>
														</div>
													<?php endif; ?>

													<?php if (isset($categories) && !empty($categories)) : ?>
														<span class="card__category">
															<?php foreach ($categories as $category) : 
															?>
															<span><?= esc_html($category->name); ?></span>
															<?php endforeach; 
															?>
														</span>
													<?php endif; ?>

												</div>

												<a href="<?= esc_url($permalink); ?>" target="_self" class="stretched-link stretched-link-custom">Read Post <?= esc_html($title) ?></a>
										</div>

									<?php $post_count++; ?>
								<?php endwhile; ?>
							<?php endif; ?>
	
							<?php elseif ($feed_options == 'by_posts') : ?>
	
								<?php $featured_posts = get_field('feed_post_object');
								if ($featured_posts) :
									$post_count = 0;
									foreach ($featured_posts as $featured_post) :
										$permalink = get_permalink($featured_post->ID);
										$title = get_the_title($featured_post->ID);
										$description = get_the_excerpt($featured_post->ID);
										$categories = get_the_terms($featured_post->ID, 'category');
										$date = get_the_date('m F Y');
										if (has_post_thumbnail()) {
											$image = get_post_thumbnail_id($featured_post->ID);
											$alt_image = sanitize_text_field(get_post_meta($image, '_wp_attachment_image_alt', true));
										} else {
											$image = get_template_directory_uri() . '/src/assets/images/default.svg';
											$alt_image = 'default image';
										} ?>
	
										
										<div class="card swiper-slide">
	
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

												<?php if (isset($date) && !empty($date)) : ?>
													<div class="card__date">
														<?= esc_html($date); ?>
													</div>
												<?php endif; ?>
	
												<?php if (isset($title) && !empty($title)) : ?>
													<div class="card__title h6 line-clamp-2">
														<span>
															<?= esc_html($title); ?>
														</span>
													</div>
												<?php endif; ?>

												<?php if (isset($description) && !empty($description)) : ?>
													<div class="card__excerpt line-clamp-3">
														<p><?= wp_kses_post($description); ?></p>
													</div>
												<?php endif; ?>
	
												<?php if (isset($categories) && !empty($categories)) : ?>
													<span class="card__category">
														<?php foreach ($categories as $category) : 
														?>
														<span><?= esc_html($category->name); ?></span>
														<?php endforeach; 
														?>
													</span>
												<?php endif; ?>
	
											</div>
	
											<a href="<?= esc_url($permalink); ?>" target="_self" class="stretched-link stretched-link-custom">Read Post <?= esc_html($title) ?></a>
	
										</div>
	
										<?php $post_count++; ?>
									<?php endforeach; ?>
								<?php endif; ?>
	
							<?php endif; ?>
	
						</div>

						<div class="swiper__container-controls">
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
			</div>
	
		</div>
	</div>
<?php
}
