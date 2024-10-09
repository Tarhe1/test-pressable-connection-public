<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elevation
 */

use ElevationFramework\Lib\Frontend\Settings\Helpers;
use ElevationFramework\Lib\BlockLibrary\Helpers as BlockHelpers;

?>

<div class="col">
	<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
		<header class="card__header">
			<?php if (has_post_thumbnail()) {
				$image = get_post_thumbnail_id(get_the_ID());
				$alt_image = get_post_meta($image, '_wp_attachment_image_alt', true);
			} else {
				$image = get_template_directory_uri() . '/src/assets/images/default.svg';
				$alt_image = 'This is the default image';
			}

			BlockHelpers::global_image(
				$image,
				[
					'size' => 'medium',
					'alt' => $alt_image,
					'class' => 'card__image'
				]
			);
			?>
		</header>
		<div class="card__body">
			<span class="card__date-author">
				<span class="date"><?= get_the_date('F j, Y') ?></span><span class="author">by <?= get_the_author() ?></span>
			</span>
			<h2 class="card__title h6">
				<a href="<?= esc_url(get_permalink()); ?>" rel="bookmark"><?= the_title(); ?></a>
			</h2>
			<div class="card__excerpt line-clamp-4">
				<?= the_excerpt() ?>
			</div>
			<div class="card__category">
				<?= get_the_category_list(); ?>
			</div>
			<span class="card__date">
				<?= get_the_date('d F Y') ?>
			</span>
		</div>
	</article><!-- #post-<?php the_ID(); ?> -->
</div>