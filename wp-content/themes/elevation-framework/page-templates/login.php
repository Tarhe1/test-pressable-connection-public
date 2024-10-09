<?php

/*
Template Name: Login
*/

?>

<?php get_header(); ?>

<main id="primary" class="site-main">

	<div class="container">

		<div class="row justify-content-center">

			<div class="col-md-12 content-page">

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-content">

						<div class="login-top-link">
							<span>Don’t have an account yet?</span>
							<a href="/register" class="pmpro_btn">Sign Up/Apply</a>
						</div>

						<?php
						if (has_custom_logo()) {
							$custom_logo_id  = get_theme_mod('custom_logo');
							$logo            = wp_get_attachment_image_src($custom_logo_id, 'full');
							$logo_header     = $logo[0];
							$width_logo      = $logo[1];
							$height_logo     = $logo[2];
							$alt_logo_header = 'Logo/Branding';
						} else {
							$logo_header     = get_template_directory_uri() . '/src/assets/images/logo.svg';
							$alt_logo_header = 'Logo/Branding';
							$width_logo      = '165';
							$height_logo     = '80';
						}
						?>

						<div class="login-site-logo">
							<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
								<img decoding="async" loading="lazy" src="<?php echo $logo_header; ?>" alt="<?php echo $alt_logo_header; ?>" class="navbar-brand-desktop" width="<?php echo $width_logo; ?>" height="<?php echo $height_logo; ?>">
							</a>
						</div>

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