<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package elevation
 */

use ElevationFramework\Lib\BlockLibrary\Helpers;

get_header('', [
	'header_type' => 'transparent'
]);
$title = get_field('four_o_four_heading', 'option');
$subtitle = get_field('four_o_four_subheading', 'option');
$description = get_field('four_o_four_description', 'option');
$link =  get_field('four_o_four_go_back_link', 'option');
?>

<main id="primary" class="site-main">
	<section class="error-404 not-found">
		<div class="container">
			<header class="error-404__header">
				<h1 class="error-404__title"><?= esc_html($title); ?></h1>
				<h2 class="error-404__subtitle h3"><?= esc_html($subtitle); ?></h1>
			</header><!-- .page-header -->

			<div class="error-404__content">
				<p class="body-1"><?= esc_html($description); ?></p>
				<?php if ($link['link_text'] == '' || empty($link)) : ?>
					<a href="<?php echo home_url(); ?> " class="cta cta--cta-dark">Back</a>

				<?php else : ?>
					<?= Helpers::global_link($link); ?>
				<?php endif; ?>
			</div><!-- .page-content -->
		</div>
	</section><!-- .error-404 -->
</main><!-- #main -->

<?php
get_footer();
