<?php

/*
Template Name: Mega Menu 2
*/

use ElevationFramework\Lib\Admin\Menu\WalkerNav;
use ElevationFramework\Lib\BlockLibrary\Helpers;

?>

<?php get_header();

$logo = get_field('header_logo_dark', 'option');

?>

<a class="skiplink cta cta__blue visually-hidden-focusable" href="#primary"><?php esc_html_e('Skip to content', 'elevation'); ?></a>

<header class="header mega-menu-active mega-menu-type-2">

	<section class="header__section-search">
		<div class="header__container container">
			<div class="header__search">
				<form method="get" id="header__search-form" class="header__search-form" action="/">
					<label class="visually-hidden" for="searchInput">Search for:</label>
					<input type="text" value="" name="s" id="searchInput" placeholder="Search here...">
					<input type="submit" id="searchSubmit" value="Search">
				</form>
			</div>
		</div>
	</section>

	<section class="header__section">

		<span class="header__section--fixed-bg"></span>

		<div class="header__container container">

			<nav class="header__nav navbar navbar-expand-xl">

				<div class="header__navbar">

					<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="header__logo navbar-brand">
						<?php Helpers::global_image($logo); ?>
						<span class="visually-hidden"><?php bloginfo('name'); ?></span>
					</a>

					<?php
					$header_mobile_cta = get_field('header_mobile_cta', 'option');
					if ($header_mobile_cta) :
						$link_url = $header_mobile_cta['url'];
						$link_title = $header_mobile_cta['title'];
						$link_target = $header_mobile_cta['target'] ? $header_mobile_cta['target'] : '_self';
					?>
						<div class="header__mobile">
							<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" class="cta cta--cta-red"><?php echo esc_html($link_title); ?></a>
						</div>
					<?php endif; ?>

					<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#NavDropdown" aria-controls="NavDropdown" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
						<span class="visually-hidden">Menu</span>
					</button>

				</div>

				<div class="header__bottom-menu">
					<div class="collapse navbar-collapse" id="NavDropdown">
						<div class="wrapper-collapse">

							<?php

							if (get_field('header_mega_menu', 'option')) {
								$depth = 1;
							} else {
								$depth = 3;
							}

							wp_nav_menu([
								'theme_location'  => 'menu-type-2',
								'container' => false,
								'fallback_cb' => '__return_false',
								'items_wrap' => '<ul id="%1$s" class="nav navbar-nav %2$s" aria-label="Menu">%3$s</ul>',
								'depth' => $depth,
								'walker' => WalkerNav::instance()
							]);

							?>

							<button id="open-search">
								<span class="label visually-hidden">Search</span>
							</button>

						</div>
					</div>
				</div>

			</nav>

		</div>

	</section>

	<?php
	$mega_menu_file = get_template_directory() . '/inc/mega-menu-2.php';
	if (file_exists($mega_menu_file)) {
		include $mega_menu_file;
	}
	?>

</header><!-- #site-header -->

<?php
get_template_part('template-parts/content', 'banner');
?>

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