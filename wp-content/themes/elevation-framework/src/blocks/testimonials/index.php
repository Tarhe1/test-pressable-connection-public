<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * What Make Us Different Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function testimonials_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

  // Create id attribute allowing for custom "anchor" value.
  $id = 'component__testimonials-' . $block['id'] . '-' . wp_unique_id();
  if (!empty($block['anchor'])) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $data_id = 'testimonials';
  $className = 'dynamic-assets-load component component__testimonials';
  if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
  }
  if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
  }

  // Load values and handle defaults.
  $testimonials_header = get_field('testimonials_header');
  $testimonials = get_field('testimonials_items');
  $swiper = get_field('swiper_settings');
  $swiper_attrs = Helpers::get_swiper_attrs($swiper);

  $content_alignment = get_field('content_alignment');
  $container_alignment = get_field('container_alignment');
  $container_width = get_field('container_width');
  $offset_container_width = get_field('offset_container_width');
  if (get_field('padding_disable')) {
    if (!get_field('padding_top')) {
      $pd_top = ' pt-0 ';
    } else {
      $pd_top = '';
    }
    if (!get_field('padding_bottom')) {
      $pd_bottom = ' pb-0 ';
    } else {
      $pd_bottom = '';
    }
  } else {
    $pd_top = '';
    $pd_bottom = '';
  }

?>
  <div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?>">

    <div class="component__container container">

      <div class="component__row row justify-content-<?= esc_html($container_alignment); ?>">

        <div class="component__col col-xl-<?= esc_html($container_width); ?> col-12 offset-xl-<?= esc_html($offset_container_width); ?>">

          <?php if (isset($testimonials_header['editor']) && !empty($testimonials_header['editor']) || isset($testimonials_header['links']) && !empty($testimonials_header['links'])) : ?>
            <div class="component__content <?= esc_html($content_alignment); ?>">
              <?php Helpers::global_content($testimonials_header['editor'], $testimonials_header['links']); ?>
            </div>
          <?php endif; ?>

          <?php if ($testimonials) : ?>

            <div class="swiper__testimonials component__swiper swiper__container--testimonials" id="swiper-<?= esc_attr($id); ?>" <?php echo esc_attr($swiper_attrs) ?>>



              <div class="swiper-wrapper">

                <?php $post_count = 0; ?>

                <?php foreach ($testimonials as $testimonial) : ?>

                  <?php if (isset($testimonial['image']) && !empty($testimonial['image'])) : ?>
                    <div class="testimonial has-image swiper-slide">
                    <?php else : ?>
                      <div class="testimonial swiper-slide">
                      <?php endif; ?>

                      <?php if (isset($testimonial['image']) && !empty($testimonial['image'])) : ?>
                        <?php Helpers::global_image($testimonial['image'], ['class' => 'testimonial__image']); ?>
                      <?php endif; ?>

                      <div class="testimonial__content">
                        <?php if (isset($testimonial['quote']) && !empty($testimonial['quote'])) : ?>
                          <div class="testimonial__content--quote">
                            <p><?= esc_html($testimonial['quote']); ?></p>
                          </div>
                        <?php endif; ?>
                        <div class="testimonial__content--cite">
                          <?php if (isset($testimonial['name']) && !empty($testimonial['name'])) : ?>
                            <span class="name"><?= esc_html($testimonial['name']); ?></span>
                          <?php endif; ?>
                          <?php if (isset($testimonial['position']) && !empty($testimonial['position'])) : ?>
                            <span class="other"><?= esc_html($testimonial['position']); ?></span>
                          <?php endif; ?>
                        </div>
                      </div>
                      </div>

                      <?php $post_count++; ?>

                    <?php endforeach; ?>

                    </div>

                    <div class="swiper__container-controls">
                      <div class="swiper-button-prev"></div>
                      <div class="swiper-pagination"></div>
                      <div class="swiper-button-next"></div>

                      <?php if ($post_count > 3) : ?>
                        <button type="button" aria-label="play" class="swiper-button-play-pause">
                          <span class="icon" aria-hidden="true"></span>
                        </button>
                      <?php endif; ?>

                    </div>

              </div>

            <?php endif; ?>


            </div>

        </div>

      </div>

    </div>

  <?php
}
