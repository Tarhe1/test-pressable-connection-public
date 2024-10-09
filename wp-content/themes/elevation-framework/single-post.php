<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package elevation
 */

use ElevationFramework\Lib\BlockLibrary\Helpers;

global $id, $post;

setup_postdata($post);

get_header('single', [
	'header_type' => 'transparent',
]);

$custom_author = get_field('custom_author', $id);
$custom_author_name = get_field('custom_author_name', $id);
$custom_author_description = get_field('custom_author_description', $id);

$show_authors = get_field('show_authors', 'option');

$blog_url = get_field('blog_url', 'option');
if (empty($blog_url)) {
	$blog_url = site_url();
}
$blog_label = get_field('blog_label', 'option');

?>

<main id="primary" class="site-main container">
	<?php
	while (have_posts()) :
		the_post(); ?>

		<article class="post post__single">
			<div class="post__breadcrumbs aioseo-breadcrumbs" id="breadcrumbs">
				<span class="aioseo-breadcrumb">
					<a href="<?= $blog_url; ?>"><?= !empty($blog_label) ? esc_html($blog_label) : 'Blog Directory'; ?></a>
				</span>
				<span class="aioseo-breadcrumb-separator">»</span>
				<span class="aioseo-breadcrumb">
					<?php the_category('<span class="aioseo-breadcrumb-separator-custom">/</span>', 'multiple', $id); ?>
				</span>
			</div>

			<div class="post__row row justify-content-center">
				<div class="post__col col-xl-8">
					<div class="post__title">
						<h1><?= get_the_title($id); ?></h1>
					</div>
					<div class="post__flex-row">
						<?php if ($show_authors) : ?>
							<?php if ($custom_author && $custom_author_name) : ?>
								<span class="post__date"><?= $custom_author_name; ?> <span aria-hidden="true" class="post__date__separator">/</span></span>
							<?php else : ?>
								<span class="post__date"><?= get_the_author() ?> <span aria-hidden="true" class="post__date__separator">/</span></span>
							<?php endif; ?>
						<?php endif; ?>
						<span class="post__date"><?= get_the_date('d F Y') ?></span>
					</div>
				</div>

				<?php if (has_post_thumbnail($id)) : ?>
					<div class="post__col col-xl-10">
						<figure class="post__image ratio ratio-16x9">
							<?= the_post_thumbnail('full'); ?>
						</figure>
					</div>
				<?php endif; ?>

				<div class="post__col col-xl-8">
					<div class="post__content">
						<?= the_content(); ?>
					</div>


					<?php if ($show_authors) : ?>
						<?php if ($custom_author && ($custom_author_name || $custom_author_description)) : ?>

							<div class="post__author">

								<div class="post__author--body">
									<span class="post__author--title h4"><?= $custom_author_name; ?></span>
									<p><?= $custom_author_description; ?></p>
								</div>

							</div>

						<?php else : ?>

							<div class="post__author">

								<div class="post__author--body">
									<span class="post__author--title h4"><?= get_the_author() ?></span>
									<?php
									$author_id = get_the_author_meta('ID');
									$author_description = get_the_author_meta('description', $author_id);
									if (!empty($author_description)) {
										echo '<p>' . $author_description . '</p>';
									}
									?>
								</div>

							</div>

						<?php endif; ?>
					<?php endif; ?>

				</div>


				<?php if (comments_open() || get_comments_number()) : ?>
					<div class="post__col col-xl-10">
						<?= comments_template(); ?>
					</div>
				<?php endif; ?>


			</div>
			<div class="addtoany-float">
				<?php echo do_shortcode('[addtoany]'); ?>
			</div>
		</article>

	<?php
	endwhile; // End of the loop.

	$category_layout = get_field('category_layout', 'option');
	$post_type = 'post';
	$taxonomy  = 'category';
	$terms = get_the_terms($post->ID, $taxonomy);
	$post_id = get_the_ID();

	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => 12,
		'post__not_in'   => array($post_id),
	);
	if (is_array($terms) && !empty($terms)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $terms[0]->term_id,
				'operator' => 'IN',
			),
		);
	}
	$posts = new WP_Query($args);
	$post_count = $posts->post_count;

	if ($posts->have_posts()) : ?>
		<div class="block block__feed--carousel pt-0 <?= $category_layout == 'option-2' ? 'layout-x2' : ''; ?>">
			<div class="block__title">
				<h2>Related Posts</h2>
			</div>
			<div class="swiper__feed" id="swiper-single-post">

				<div class="swiper-wrapper">
					<?php
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
						<article class="card swiper-slide">
							<a class="card__image card__link" href="<?= esc_url($permalink); ?>" target="_self">
								<?php Helpers::global_image($image, ['is_figure' => false, 'size' => 'medium', 'class' => 'card__image card__image--hover-effect']); ?>
							</a>
							<div class="card__body">
								<?php if (isset($title) && !empty($title)) : ?>
									<div class="card__body__title h6">
										<a href="<?= esc_url($permalink); ?>" target="_self"><?= esc_html($title); ?></a>
									</div>
								<?php endif; ?>
								<?php if (isset($description) && !empty($description)) : ?>
									<div class="card__body__excerpt line-clamp-4">
										<p><?= wp_kses_post($description); ?></p>
									</div>
								<?php endif; ?>
								<?php if (isset($categories) && !empty($categories)) : ?>
									<span class="card__body__category">
										<?php foreach ($categories as $category) : ?>
											<span><?= esc_html($category->name); ?></span>
										<?php endforeach; ?>
									</span>
								<?php endif; ?>
							</div>
							<?php if (isset($date) && !empty($date)) : ?>
								<div class="card__footer">
									<span><?= esc_html($date); ?></span>
								</div>
							<?php endif; ?>
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
				slideToClickedSlide: true,
				spaceBetween: 30,

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

				<?php if ($category_layout == 'option-2') : ?>
					loop: false,
					slidesPerView: 1,
					breakpoints: {
						768: {
							slidesPerView: 1,
							grid: {
								rows: 1,
								fill: "row",
							},
						},
						992: {
							slidesPerView: 2,
							grid: {
								rows: 2,
								fill: "row",
							},
						},
						1200: {
							slidesPerView: 2,
							grid: {
								rows: 2,
								fill: "row",
							},
						},
						1400: {
							slidesPerView: 2,
							grid: {
								rows: 2,
								fill: "row",
							},
						},
					},
				<?php else : ?>
					loop: true,
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
				<?php endif; ?>
			});

			swiper.autoplay.pause();

			const playPauseButton = swiper.el.querySelector('.swiper-button-play-pause');

			if (playPauseButton) {
				playPauseButton.addEventListener('click', function(e) {
					const arialLabel = playPauseButton.getAttribute('aria-label');
					if (arialLabel == 'play') {
						swiper.autoplay.resume();
						playPauseButton.setAttribute('aria-label', 'pause');
					} else {
						swiper.autoplay.pause();
						playPauseButton.setAttribute('aria-label', 'play');
					}
				});
			}
		</script>

	<?php endif; ?>

</main><!-- #main -->

<?php

get_sidebar();
get_footer();
