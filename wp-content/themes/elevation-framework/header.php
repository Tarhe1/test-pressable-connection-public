<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package elevation
 */

use ElevationFramework\Lib\Admin\Menu\WalkerNav;
use ElevationFramework\Lib\BlockLibrary\Helpers;
use ElevationFramework\Lib\Frontend\Settings\Helpers as SettingsHelpers;

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<?php
$nav_class = '';
$header_class = '';
$menu_type = '';

$header_logodark = get_field('header_logodark', 'option');
$header_logodarkshort = get_field('header_logodarkshort', 'option');


$header_transparent = get_field('header_transparent', get_the_ID());

$header_transparent = $header_transparent ? true : false;

$body_classes = [];

if ($header_transparent && !is_search()) {
    $body_classes[] = 'header__transparent';
}

?>

<body <?php body_class($body_classes); ?>>
	<?php SettingsHelpers::body_open(); ?>
	<div id="page" class="site">
		
			<a class="skiplink cta cta__blue visually-hidden-focusable" href="#primary"><?php esc_html_e('Skip to content', 'elevation'); ?></a>
			<header class="header<?= esc_attr($header_class); ?>">
				<div class="header__section-search">
					<div class="header__container container-fluid container-xl">
						<div class="header__search">
							<form method="get" id="header__search-form" class="header__search-form" action="/">
								<label class="visually-hidden" for="searchInput">Search for:</label>
								<input type="text" value="" name="s" id="searchInput" placeholder="Search here...">
								<input type="submit" id="searchSubmit" value="Search">
							</form>
						</div>
					</div>
				</div>
				<div class="header__section">
					<span class="header__section--fixed-bg"></span>
					<div class="header__container container">
						<nav class="header__nav navbar navbar-expand-xl<?= esc_attr($nav_class); ?>">
						
							<div class="header__top-menu">
								<span class="header__top-menu--bg"></span>
								<?php
								wp_nav_menu([
									'theme_location'  => 'menu-2',
									'container' => false,
									'fallback_cb' => '__return_false',
									'items_wrap' => '<ul id="%1$s" class="nav navbar-nav %2$s header__top-menu-list" aria-label="Menu">%3$s</ul>',
									'depth' => 1,
									'walker' => WalkerNav::instance()
								]);
								?>
								<button id="open-search">
									<span class="label visually-hidden">Search</span>
								</button>
							</div>
						
							<div class="header__navbar">
								<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="header__logo navbar-brand">
								<?php Helpers::global_image($header_logodark, ['loading' => 'eager', 'class' => 'header_logodark header__logo']); ?>
								<?php Helpers::global_image($header_logodarkshort, ['loading' => 'eager', 'class' => 'header_logodarkshort header__logo']); ?>
									<span class="visually-hidden"><?php bloginfo('name'); ?></span>
								</a>

								<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#NavDropdown" aria-controls="NavDropdown" aria-expanded="false" aria-label="Toggle navigation">
									<span class="navbar-toggler-icon"></span>
									<span class="visually-hidden">Menu</span>
								</button>
							</div>

							<div class="header__bottom-menu">
								<div class="collapse navbar-collapse" id="NavDropdown">

									<div class="wrapper-collapse">
										<?php
										wp_nav_menu([
											'theme_location'  => 'menu-1',
											'container' => false,
											'fallback_cb' => '__return_false',
											'items_wrap' => '<ul id="%1$s" class="nav navbar-nav %2$s" aria-label="Menu">%3$s</ul>',
											'depth' => 2,
											'walker' => WalkerNav::instance()
										]);
										?>

										<div class="mobile-wrapper">
											<?php
											wp_nav_menu([
												'theme_location'  => 'menu-2',
												'container' => false,
												'fallback_cb' => '__return_false',
												'items_wrap' => '<ul id="%1$s" class="nav navbar-nav %2$s header__top-menu-list" aria-label="Menu">%3$s</ul>',
												'depth' => 1,
												'walker' => WalkerNav::instance()
											]);
											?>

											<button id="open-search-mobile">
												<span class="label visually-hidden">Search</span>
											</button>
										</div>
										
									</div>
				
								</div>
							</div>

						</nav>
					</div>
				</div>

			</header><!-- #site-header -->

		