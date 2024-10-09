	<?php

	/**
	 * The template for displaying all single posts
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
	 *
	 * @package elevation
	 */

	use ElevationFramework\Lib\BlockLibrary\Helpers;

	global $id;

	get_header();

	$categories = get_the_terms($id, 'resources_categories');
	$tags = get_the_terms($id, 'resources_tags');
	$types = get_the_terms($id, 'resources_types');

	$link_resource = get_field('link_resource', $id);
	$video_youtube = get_field('youtube', $id);
	$downloadable = get_field('downloadable', $id);
	$video_image = get_field('video_poster', $id);


	$show_authors = get_field('sr_show_authors', 'option');
	$custom_author = get_field('sr_custom_author', $id);
	$custom_author_name = get_field('sr_custom_author_name', $id);
	$custom_author_description = get_field('sr_custom_author_description', $id);


	$directory_url = get_field('sr_directory_url', 'option');
	if (empty($directory_url)) {
		$directory_url = site_url();
	}
	$directory_url_label = get_field('sr_directory_url_label', 'option');

	?>

	<main id="primary" class="site-main container">
		<?php
		while (have_posts()) :
			the_post();
		?>

			<article class="post post__single">
				<div class="row">
					<div class="col-xl-8 offset-xl-1">

						<div class="post__breadcrumbs aioseo-breadcrumbs" id="breadcrumbs">
							<span class="aioseo-breadcrumb">
								<a href="<?php echo site_url(); ?>">Home</a>
							</span>

								<span class="aioseo-breadcrumb-separator">»</span>
								<span class="aioseo-breadcrumb">
									<a href="<?= $directory_url; ?>"><?= !empty($directory_url_label) ? esc_html($directory_url_label) : 'Resources Directory'; ?></a>
								</span>

							<span class="aioseo-breadcrumb-separator">»</span>

							<?php the_category('<span class="aioseo-breadcrumb-separator">»</span>', 'multiple', $id); ?>

							<span class="aioseo-breadcrumb">
								<?= get_the_title($id); ?>
							</span>
						</div>

						<div class="post__title">
							<h1><?= get_the_title($id); ?></h1>
						</div>

						<?php if ($categories || $tags || $types) : ?>

							<div class="post__flex-row">

								<?php if ($categories) : ?>
									<span class="post__category"><?= $categories[0]->name; ?></span>
								<?php endif; ?>

								<?php if ($types) : ?>
									<span class="post__category"><?= $types[0]->name; ?></span>
								<?php endif; ?>

								<?php if ($tags) : ?>
									<div class="post__tags">
										<?php foreach ($tags as $tag) : ?>
											<span><?php echo $tag->name; ?></span>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

							</div>

						<?php endif; ?>

						<?php if (has_post_thumbnail($id)) : ?>

							<?php if (isset($downloadable['file_url'])) : ?>
								<figure class="post__image post__image--pdf ratio ratio-16x9">
								<?php else : ?>
									<figure class="post__image ratio ratio-16x9">
									<?php endif; ?>
									<?= the_post_thumbnail('full'); ?>
									</figure>

								<?php else : ?>

									<?php if ($video_youtube) : ?>
										<div class="post__video block__video">
											<div class="block__video block__video--poster">

												<?php if (isset($video_image['image']) && !empty($video_image['image'])) : ?>
													<?php Helpers::global_image(
														$video_image,
														[
															'class' => 'block__video--poster-img',
															'decorative' =>  true
														]
													); ?>
												<?php else : ?>
													<?php Helpers::global_image(
														get_template_directory_uri() . "/src/assets/images/default-2.svg",
														[
															'class' => 'block__video--poster-img',
															'decorative' =>  true
														]
													); ?>
												<?php endif; ?>

												<?php if ($video_youtube) : ?>
													<div class="block__video--button float bottom-left">
														<button class="video <?= $video_youtube ? 'youtube' : '' ?>" data-video="<?= $video_youtube ? Helpers::youtube_url($video_youtube, false, true) : '' ?>" data-bs-toggle="modal" data-bs-target="#videoTextBlockModal-<?= $id; ?>">
															<span class="button-icon"></span>
															<span class="button-label">Watch <br> the Video</span>
														</button>
													</div>
												<?php endif; ?>

											</div>
											<div class="modal fade" id="videoTextBlockModal-<?= $id; ?>" role="dialog" tabindex="-1" aria-labelledby="videoTextBlockModal-<?= $id; ?>-Title" aria-hidden="true">
												<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title visually-hidden" id="videoTextBlockModal-<?= $id; ?>Title">videoTextBlockModal-<?= $id; ?>-Title</h5>
															<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<div class="ratio ratio-16x9">
																<video class="ratio ratio-16x9 embed-responsive-item" controls>
																	<source src="" type="video/mp4">
																	Your browser does not support the video tag.
																</video>
																<iframe class="ratio ratio-16x9 embed-responsive-item" src="" allowfullscreen></iframe>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn-close" data-bs-dismiss="modal"><span class="visually-hidden">Close Popup</span></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endif; ?>

								<?php endif; ?>

								<div class="post__content">
									<?php the_content($id); ?>
								</div>

								<?php if ($link_resource) : ?>
									<div class="post__buttons">
										<a href="<?php echo $link_resource; ?>" target="_blank" rel="noopener noreferrer" class="cta cta--cta-dark-arrow-right">External Resource</a>
									</div>
								<?php endif; ?>

								<?php if (has_post_thumbnail()) : ?>

									<?php if ($video_youtube) : ?>
										<div class="post__video block__video">
											<div class="block__video block__video--poster">

												<?php if (isset($video_image['image']) && !empty($video_image['image'])) : ?>
													<?php Helpers::global_image(
														$video_image,
														[
															'class' => 'block__video--poster-img',
															'decorative' =>  true
														]
													); ?>
												<?php else : ?>
													<?php Helpers::global_image(
														get_template_directory_uri() . "/src/assets/images/default-2.svg",
														[
															'class' => 'block__video--poster-img',
															'decorative' =>  true
														]
													); ?>
												<?php endif; ?>

												<?php if ($video_youtube) : ?>
													<div class="block__video--button float bottom-left">
														<button class="video <?= $video_youtube ? 'youtube' : '' ?>" data-video="<?= $video_youtube ? Helpers::youtube_url($video_youtube, false, true) : '' ?>" data-bs-toggle="modal" data-bs-target="#videoTextBlockModal-<?= $id; ?>">
															<span class="button-icon"></span>
															<span class="button-label">Watch <br> the Video</span>
														</button>
													</div>
												<?php endif; ?>
											</div>
											<div class="modal fade" id="videoTextBlockModal-<?= $id; ?>" role="dialog" tabindex="-1" aria-labelledby="videoTextBlockModal-<?= $id; ?>-Title" aria-hidden="true">
												<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title visually-hidden" id="videoTextBlockModal-<?= $id; ?>Title">videoTextBlockModal-<?= $id; ?>-Title</h5>
															<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<div class="ratio ratio-16x9">
																<video class="ratio ratio-16x9 embed-responsive-item" controls>
																	<source src="" type="video/mp4">
																	Your browser does not support the video tag.
																</video>
																<iframe class="ratio ratio-16x9 embed-responsive-item" src="" allowfullscreen></iframe>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn-close" data-bs-dismiss="modal"><span class="visually-hidden">Close Popup</span></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endif; ?>

								<?php endif; ?>

								<?php if ($show_authors) : ?>

									<?php if ($custom_author && ($custom_author_name || $custom_author_description)) : ?>

										<div class="post__author">

											<div class="post__author--body">
												<span class="post__author--title h6"><?= $custom_author_name; ?></span>
												<p><?= $custom_author_description; ?></p>
											</div>

										</div>

									<?php else : ?>

										<div class="post__author">

											<div class="post__author--body">
												<span class="post__author--title h4"><?= get_the_author($id); ?></span>
												<?php
												$author_id = get_the_author_meta($id);
												$author_description = get_the_author_meta('description', $author_id);
												echo '<p>' . $author_description . '</p>';
												?>
											</div>

										</div>

									<?php endif; ?>

								<?php endif; ?>

					</div>

					<?php if (isset($downloadable['editor']) && !empty($downloadable['editor']) || isset($downloadable['file_url']) && !empty($downloadable['file_url'])) : ?>
						<div class="col-xl-10 offset-xl-1">
							<div class="block__downloadable block__row row justify-content-start">
								<div class="block__col col-xl-12 col-12">
									<?php if ($downloadable['editor']) : ?>
										<div class="block__editor">
											<?php echo $downloadable['editor']; ?>
										</div>
									<?php endif; ?>
									<?php if ($downloadable['file_url']) : ?>
										<div class="block__buttons">
											<a href="<?php echo esc_url($downloadable['file_url']); ?>" class="cta cta--cta-dark-download-left" target="_blank" download rel="noopener noreferrer"><?php echo esc_html($downloadable['label_button']); ?></a>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>

				</div>

				<div class="addtoany-float">
					<?php echo do_shortcode('[addtoany]'); ?>
				</div>

			</article>

		<?php
		endwhile; // End of the loop.
		?>

		<?php
		$category_layout   = get_field('directory_layout', 'option');
		$related_resources = get_field('related_resources', $id);

		if ($related_resources) : ?>
			<div class="row">
				<div class="col-xl-10 offset-xl-1 position-relative">

					<?php
					$terms = get_the_terms($id, 'resources_categories', 'string');
					$term_ids = wp_list_pluck($terms, 'term_id');
					$second_query = new WP_Query(array(
						'post_type' => 'resources',
						'tax_query' => array(
							array(
								'taxonomy' => 'resources_categories',
								'field'    => 'id',
								'terms'    => $term_ids,
								'operator' => 'IN'
							)
						),
						'posts_per_page'      => 9,
						'ignore_sticky_posts' => 1,
						'orderby'             => 'date',
						'post__not_in'        => array($id)
					));

					$post_count = $second_query->post_count;

					if ($second_query->have_posts()) : ?>

						<?php // if (is_user_logged_in()) :
						?>
						<div class="block__buttons options-views">
							<button id="option1" class="cta cta--cta-red">Option 1</button>
							<button id="option2" class="cta cta--cta-red">Option 2</button>
							<button id="option3" class="cta cta--cta-red">Option 3</button>
						</div>
						<?php // endif; 
						?>

						<div class="block block__feed--carousel <?= $category_layout; ?>">
							<div class="block__title">
								<h2>More from the <?= esc_html($terms[0]->name); ?></h2>
							</div>
							<div class="swiper__feed" id="swiper-single-resource">



								<div class="swiper-wrapper">
									<?php
									while ($second_query->have_posts()) : $second_query->the_post();
										$permalink   = get_permalink($post->ID);
										$title       = get_the_title($post->ID);
										$description = get_the_excerpt($post->ID);
										$categories  = get_the_terms($post->ID, 'resources_categories');
										if (has_post_thumbnail()) {
											$image = get_post_thumbnail_id($post->ID);
											$alt_image = sanitize_text_field(get_post_meta($image, '_wp_attachment_image_alt', true));
										} else {
											$image = get_template_directory_uri() . '/src/assets/images/default.svg';
											$alt_image = 'default image';
										}
										$card_image_change = ' ';
										$downloadable = get_field('downloadable', $post->ID);
										if (isset($downloadable['editor']) && !empty($downloadable['editor']) || isset($downloadable['file_url']) && !empty($downloadable['file_url'])) {
											$card_image_change = ' card__image--pdf';
										}
										$video_youtube = get_field('youtube', $post->ID);
										if (isset($video_youtube) && !empty($video_youtube)) {
											$card_image_change = ' card__image--video';
										}
									?>

										<article class="card swiper-slide">
											<?php Helpers::global_image($image, ['size' => 'medium', 'class' => 'card__image card__image--hover-effect' . $card_image_change]); ?>

											<div class="card__body">
												<?php if (isset($title) && !empty($title)) : ?>
													<div class="card__body__title h6 line-clamp-2">
														<span>
															<?= esc_html($title); ?>
														</span>
													</div>
												<?php endif; ?>
												<?php if (isset($description) && !empty($description)) : ?>
													<div class="card__body__excerpt line-clamp-4">
														<p><?= wp_kses_post($description); ?></p>
													</div>
												<?php endif; ?>
											</div>

											<?php if (isset($categories) && !empty($categories)) : ?>
												<div class="card__footer">
													<?php // foreach ($categories as $category) : 
													?>
													<span><?= esc_html($categories[0]->name); ?></span>
													<?php // endforeach; 
													?>
												</div>
											<?php endif; ?>

											<a class="stretched-link stretched-link-custom" href="<?= esc_url($permalink); ?>" target="_self">Read More</a>

										</article>

									<?php endwhile; ?>

								</div>
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
						</div>

						<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
						<script>
							const swiper = new Swiper('.swiper__feed', {
								// Optional parameters
								centeredSlides: false,
								grabCursor: true,
								spaceBetween: 30,
								slideToClickedSlide: true,

								// Rotation
								autoplay: {
									delay: 7000,
									disableOnInteraction: false,
								},

								// Navigation arrows
								navigation: {
									nextEl: '.swiper-button-next',
									prevEl: '.swiper-button-prev',
								},

								pagination: {
									el: '.swiper-pagination',
									clickable: true,
								},

								breakpoints: {
									320: {
										slidesPerView: 1,
									},
									576: {
										slidesPerView: 1,
									},
									768: {
										slidesPerView: 2,
									},
									992: {
										slidesPerView: 3,
									},
								}
							});

							<?php if ($post_count > 3) { ?>

								swiper.autoplay.stop();

								const playPauseButton = swiper.el.querySelector('.swiper-button-play-pause');

								if (playPauseButton) {
									playPauseButton.addEventListener('click', function(e) {
										const arialLabel = playPauseButton.getAttribute('aria-label');
										if (arialLabel == 'play') {
											swiper.autoplay.start();
											playPauseButton.setAttribute('aria-label', 'pause');
										} else {
											swiper.autoplay.stop();
											playPauseButton.setAttribute('aria-label', 'play');
										}
									});
								}

							<?php } ?>
						</script>

						<script>
							// Obtiene los botones por sus identificadores
							const option1Button = document.getElementById('option1');
							const option2Button = document.getElementById('option2');
							const option3Button = document.getElementById('option3');

							function changeOption(option) {
								const block = document.querySelector('.block__feed--carousel');
								block.classList.remove('option-1', 'option-2', 'option-3');
								block.classList.add(option);
							}

							document.addEventListener('DOMContentLoaded', function() {
								const block = document.querySelector('.block__feed--carousel');
								if (block.classList.contains('option-1')) {
									option1Button.classList.add('active');
								} else if (block.classList.contains('option-2')) {
									option2Button.classList.add('active');
								} else if (block.classList.contains('option-3')) {
									option3Button.classList.add('active');
								}
							});
							option1Button.addEventListener('click', function() {
								changeOption('option-1');
								option1Button.classList.toggle('active');
								option2Button.classList.remove('active');
								option3Button.classList.remove('active');
							});

							option2Button.addEventListener('click', function() {
								changeOption('option-2');
								option1Button.classList.remove('active');
								option2Button.classList.toggle('active');
								option3Button.classList.remove('active');
							});

							option3Button.addEventListener('click', function() {
								changeOption('option-3');
								option1Button.classList.remove('active');
								option2Button.classList.remove('active');
								option3Button.classList.toggle('active');
							});
						</script>
					<?php endif; ?>

				</div>
			</div>
		<?php endif; ?>

	</main> <!-- #main -->

	<?php

	get_sidebar();
	get_footer();
