<?php

use ElevationFramework\Lib\Directory\Post;

/**
 * Directory Post Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function directory_post_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block__directory-post--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'directory-post';
	$className = 'dynamic-assets-load block block__directory block__directory-post';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.

	$directory_description = get_field('directory_description');

	$feature_posts = get_field('feature_posts');

	$directory_layout = get_field('directory_layout', 'option');

	$category_layout = get_field('category_layout', 'option');

	$show_sticky_post = get_field('show_sticky_post', 'option');

	$directory_posts = get_field('directory_posts', 'option');

	$disable_filters = get_field('directory_disable_filters');

	$class_directory = $directory_layout;

	if ($directory_layout == 'option-3' && empty($directory_posts)) {
		$class_directory .= ' no-feature-post ';
	}

	if ($category_layout == 'option-1') {
		$class_directory .= ' row-cards row row-cols-xl-3 row-cols-md-2 row-cols-1';
	} else {
		$class_directory .= ' row-cards row row-cols-xl-2 row-cols-md-1 row-cols-1';
	}


?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">

		<div class="block__container container">

			<div class="block__row justify-content-center">

				<?php if ($directory_layout != 'option-3') : ?>
					<div class="block__col col-xl-12 col-12">
						<div class="row justify-content-center">
							<div class="col-xl-6">
								<?= $directory_description; ?>
							</div>
							<div class="col-xl-4">
								<div class="social-header">
									<?php echo do_shortcode('[addtoany]'); ?>
								</div>
							</div>
						</div>
					</div>
					<?php if (!$directory_description) : ?>
						<hr class="line hr-bottom grey">
					<?php endif; ?>

				<?php else : ?>
					<div class="block__col col-xl-12 col-12">
						<div class="social-header">
							<?php echo do_shortcode('[addtoany]'); ?>
						</div>
						<div class="pt-4">
							<?= $directory_description; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<?php

			if ($show_sticky_post) {
				Post::instance()->get_sticky_post();
			}

			?>

			<div class="block__row justify-content-center">

				<div class="block__col col-xl-12 col-12">

					<?php if (is_user_logged_in()) : ?>
						<?php if ($directory_layout == 'option-1') : ?>
							<span class="admin-view h5">Option 1</span>
						<?php elseif ($directory_layout == 'option-2') : ?>
							<span class="admin-view h5">Option 2</span>
						<?php endif; ?>
					<?php endif; ?>

					<?php if (!$disable_filters) : ?>
						<div class="filter">
							<div class="filter__container">
								<div class="filter__row">

									<div class="filter__item">
										<span class="filter__label">Search</span>
										<div class="filter__input filter__input--icon">
											<input type="text" placeholder="Search by Keyword" id="keyword" data-type="filter">
											<button class="search-btn search_directory" type="button"></button>
										</div>
									</div>

									<?php Post::instance()->get_filters(); ?>

									<div class="filter__item filter__item-button">
										<div class="filter__button">
											<button class="cta cta--cta-dark search_directory" id="search_directory" type="button">Search</button>
										</div>
									</div>

									<div class="filter__item filter__item-button">
										<div class="filter__button">
											<button id="clear_filters" type="button" class="visually-hidden">Clear all filters</button>
										</div>
									</div>

								</div>
							</div>
						</div>
					<?php endif; ?>

				</div>

				<div class="block__col filter-results col-xl-12 col-12">
					<div class="label">Displaying Results For:</div>
					<div class="group-results">
					</div>
				</div>

				<?php
				Post::instance()->get_directory_loop($class_directory, $feature_posts); ?>
			</div>

		</div>

	</div>

<?php
}
