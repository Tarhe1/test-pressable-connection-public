<?php

/*
Template Name: Member Portal
*/
?>

<?php get_header();  ?>

<?php if (!is_user_logged_in()) {

	wp_redirect(wp_login_url());
} ?>

<section class="account-settings">
	<div class="container">
		<div class="row">
			<div class="col">
				<h3>Account Settings</h3>
			</div>
		</div>
	</div>
</section>

<main id="primary" class="site-main">

	<div class="container">

		<div class="row justify-content-center">

			<div class="col-md-12 col-lg-10 content-page">

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

<?php get_footer();  ?>