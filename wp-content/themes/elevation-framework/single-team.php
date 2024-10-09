<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package elevation
 */

use ElevationFramework\Lib\BlockLibrary\Helpers;

global $id;

get_header();

$categories = get_the_terms($id, 'team_categories');

$title       = get_the_title($id);
$description = get_the_content($id);
$position    = get_field('position', $id);
$email       = get_field('email', $id);
$phone       = get_field('phone', $id);
$website     = get_field('website', $id);
$social      = get_field('social_network', $id);

?>

<main id="primary" class="site-main container">
  <?php
  while (have_posts()) :
    the_post();
  ?>

    <div class="team__modal">

      <div class="team__modal__profile">

        <?php if (has_post_thumbnail($id)) : ?>
          <?php Helpers::global_image(get_post_thumbnail_id($id), ['class' => 'team__modal__image ratio ratio-1x1']); ?>
        <?php else : ?>
          <?php Helpers::global_image(get_template_directory_uri() . '/src/assets/images/bios2.jpg', ['class' => 'team__modal__image ratio ratio-1x1']); ?>
        <?php endif; ?>

        <?php if ($email || $phone || $social) : ?>

          <div class="team__modal__body">

            <?php if ($email || $phone) : ?>

              <span class="team__modal__contact">
                <?php if (isset($email) && !empty($email)) : ?>
                  <div class="team__modal__contact--email">
                    <a href="mailto:<?= esc_html($email); ?>"><?= esc_html($email); ?></a>
                  </div>
                <?php endif; ?>

                <?php if (isset($phone) && !empty($phone)) : ?>
                  <div class="team__modal__contact--phone">
                    <?= wp_kses_post($phone); ?>
                  </div>
                <?php endif; ?>
              </span>

            <?php endif; ?>

            <?php if (have_rows('social_network', $id)) : ?>
              <ul class="card__body__social social-network">
                <?php while (have_rows('social_network', $id)) : the_row();
                  $link = get_sub_field('url');
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
            <?php endif; ?>

          </div>

        <?php endif; ?>

      </div>

      <div class="team__modal__info">

        <?php if (isset($title) && !empty($title)) : ?>
          <span class="team__modal__title h4">
            <?= esc_html($title); ?>
          </span>
        <?php endif; ?>

        <?php if (isset($position) && !empty($position)) : ?>
          <span class="team__modal__position"><?= wp_kses_post($position); ?></span>
        <?php endif; ?>

        <?php if (isset($description) && !empty($description)) : ?>
          <div class="team__modal__content">
            <?= wp_kses_post($description); ?>
          </div>
        <?php endif; ?>

        <?php if (isset($button) && !empty($button)) : ?>
          <div class="team__modal__button">
            <a href="<?= esc_url($button_url); ?>" target="<?= esc_attr($button_target); ?>" class="cta cta--cta-primary"><?= esc_html($button_title); ?></a>
          </div>
        <?php endif; ?>

      </div>

    </div>

  <?php
  endwhile; // End of the loop.
  ?>

</main>

<?php get_footer();

?>