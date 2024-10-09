<?php

/*
Template Name: Contact Us
*/

?>

<?php get_header(); ?>

<main id="primary" class="site-main">

	<div class="container">

		<div class="row justify-content-center">

			<div class="col-md-12 col-lg-12 content-page">

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-content">
						<?php
						the_content();
						?>
					</div><!-- .entry-content -->

				</article><!-- #post-<?php the_ID(); ?> -->

			</div>

		</div>

	</div>

</main><!-- #main -->

<?php get_footer(); ?>