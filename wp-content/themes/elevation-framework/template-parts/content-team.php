<?php

/**
 * Template part for displaying resources
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elevation
 */
use ElevationFramework\Lib\Frontend\Settings\Helpers;

global $id;

$categories = get_the_terms( $id, 'team_categories' );

$position = get_field('position');
$email    = get_field('email');
$phone    = get_field('phone');
$website  = get_field('website');
$social   = get_field('social_networks');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('col-card col show'); ?>>
	<div class="card">

		<?php Helpers::post_thumbnail('md'); ?>

		<div class="card__body">
			<div class="card__body__title h5">
				<a class="card__body__title--link" type="button" href="<?= esc_url(get_permalink()); ?>" target="_self">
					<?= wp_kses_post(get_the_title()); ?>
				</a>
			</div>

			<?php if ($categories) : ?>
				<span class="card__body__category">
					<?= $categories[0]->name; ?>
				</span>
			<?php endif; ?>

			<?php if($position) :?>
				<span class="card__body__position h6"><?php echo $position; ?></span>
			<?php endif; ?>

			<div class="card__body__excerpt">
				<p><?= wp_kses_post(the_excerpt()); ?></p>
			</div>

			<hr class="line card__body__divider">

			<div class="card__body__wrapper">

				<?php if ($email || $phone) : ?>
					<div class="card__body__contact">
						<?php if (isset($email) && !empty($email)) : ?>
							<div class="card__body__contact--email">
								<a href="mailto:<?= esc_html($email); ?>"><?= esc_html($email); ?></a>
							</div>
						<?php endif; ?>
						<?php if (isset($phone) && !empty($phone)) : ?>
							<div class="card__body__contact--phone">
								<?= wp_kses_post($phone); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<hr class="line card__body__divider">

				<?php if (have_rows( 'social_network' )) : ?>
					<div class="card__body__social">
						<ul role="list" class="social-network">
							<?php while ( have_rows( 'social_network' )) : the_row();
								$url = get_sub_field('url');
								$icon = get_sub_field('icon');
								$email = get_sub_field('email');
							?>
							
								<li>
									<?php if (isset($email) && !empty($email)) : ?>
										<a href="mailto:<?= esc_html($email); ?>" target="_blank" class="dark-icon" rel="noopener noreferrer">
									<?php else : ?>
										<a href="<?= esc_html($url); ?>" target="_blank" class="dark-icon" rel="noopener noreferrer">
									<?php endif; ?>
											<span class="icon--<?= esc_html($icon); ?>"></span>
											<span class="visually-hidden"><?= esc_html($icon); ?></span>
										</a>
								</li>

							<?php endwhile; ?>
						</ul>
					</div>

				<?php endif; ?>

			</div>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->