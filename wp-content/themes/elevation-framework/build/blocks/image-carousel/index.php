<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Image Carousel Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function image_carousel_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

  // Create id attribute allowing for custom "anchor" value.
  $id = 'block__image-carousel' . $block['id'] . '-' . wp_unique_id();
  if (!empty($block['anchor'])) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $data_id = 'image-carousel';
  $className = 'dynamic-assets-load block block__image-carousel';
  if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
  }
  if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
  }

  // Load values and handle defaults.
  $carousel_header = get_field('carousel_header');
  $images = get_field('image_gallery');

  $image_filter = get_field('image_filter');
  $filter_color = get_field('filter_color');

  $content_alignment = get_field('content_alignment');
  $container_overflow_hidden = get_field('container_overflow_hidden');


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

  $swiper = get_field('slider_settings');
  $swiper_attrs = Helpers::get_swiper_attrs($swiper, [
    'centered-slides' => false,
    'grab-cursor' => false,
    'slides-per-view' => 1,
    'space-between' => 30,
    'slides-per-view-md' => 2,
    'slides-per-view-lg' => 2,
    'slides-per-view-xl' => 'auto',
    'slides-per-view-xxl' => 'auto',
    'slide-to-click-slide' => true,
    'delay' => 7000,
    'disable-on-interaction' => true,
    'loop-additional-slides' => 1
  ]);


?>
  <div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?><?php if (is_array($images) && count($images) > 3) : echo esc_attr(' block__image--carousel');
                                                                                                                                                            endif; ?>">
    <div class="block__container swiper__container--carousel container row-gap-2">
      <div class="block__row row justify-content-start">
        <div class="block__col col-xl-12 col-12">

          <?php if (isset($carousel_header['editor']) && !empty($carousel_header['editor']) || isset($carousel_header['links']) && !empty($carousel_header['links'])) : ?>
            <div class="block__content <?= esc_html($content_alignment); ?>">
              <?php Helpers::global_content($carousel_header['editor'], $carousel_header['links']); ?>
            </div>
          <?php endif; ?>

          <?php if ($images) : ?>
            <div class="block__carousel <?= $container_overflow_hidden ? 'overflow-hidden' : '' ?>">

              <div class="swiper__images" id="swiper-<?= esc_attr($id); ?>" <?php echo esc_attr($swiper_attrs); ?>>
                <div class="swiper-wrapper">

                  <?php foreach ($images as $image) :
                    // Image variables.
                    $caption_img = $image['caption'];
                  ?>
                    <div class="carousel swiper-slide">
                      <figure class="carousel__image--size-2 <?= esc_attr($image_filter) ? 'mask' : '' ?>">
                        <?php Helpers::global_image(['image' => $image], ['is_figure' => false]); ?>

                        <?php if (isset($caption_img) && !empty($caption_img)) : ?>
                          <figcaption class="carousel__caption line-clamp-2">
                            <p><?= esc_html($image['caption']); ?></p>
                          </figcaption>
                        <?php endif; ?>

                      </figure>
                    </div>
                  <?php endforeach; ?>

                </div>

                <div class="swiper__container-controls">
                  <div class="swiper__container-buttons">
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                  </div>
                  <div class="swiper-scrollbar"></div>
                </div>

              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php if ($filter_color) : ?>
      <style>
        <?= '#' . esc_attr($id) . ' '; ?>div[class*="swiper__container--carousel"] .carousel figure.mask::after {
          background-color: <?= esc_attr($filter_color); ?>;
        }
      </style>
    <?php endif; ?>

  </div>
<?php
}
