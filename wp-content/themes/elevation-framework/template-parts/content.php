<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elevation
 */

use ElevationFramework\Lib\Frontend\Settings\Helpers;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if (is_singular()) :
			// Hide category and tag text for pages.
			if ('post' === get_post_type()) {
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list(esc_html__(' | ', 'elevation'));
				if ($categories_list) {
					/* translators: 1: list of categories. */
					printf('<span class="cat-links">' . esc_html__('%1$s', 'elevation') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
			the_title('<h1 class="entry-title">', '</h1>');
		else :
			the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
		endif;

		if ('post' === get_post_type() && !is_singular()) :
		?>
			<div class="entry-meta">
				<?php
				Helpers::posted_on();
				Helpers::posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<div class="container">
			<?php if (is_singular()) : ?>
				<?php Helpers::post_thumbnail(); ?>
				<?php
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'elevation'),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post(get_the_title())
					)
				);

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__('Pages:', 'elevation'),
						'after'  => '</div>',
					)
				);
				?>
			<?php else :  ?>
				<?php the_excerpt() ?>
			<?php endif; ?>
		</div>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->