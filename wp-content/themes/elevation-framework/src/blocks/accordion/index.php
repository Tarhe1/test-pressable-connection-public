<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Accordion Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function accordion_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

  // Create id attribute allowing for custom "anchor" value.
  $id = 'component__accordion-' . $block['id'] . '-' . wp_unique_id();
  if (!empty($block['anchor'])) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $data_id = 'accordion';
  $className = 'dynamic-assets-load component component__accordion';
  if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
  }
  if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
  }

  // Load values and handle defaults.
  $accordion_header = get_field('accordion_header');
  $items = get_field('accordion_items');

  // Settings
  $accordion_layout = get_field('accordion_layout');

  $content_alignment = get_field('content_alignment');
  $container_alignment = get_field('container_alignment');
  $container_width = get_field('container_width');
  $offset_container_width = get_field('offset_container_width');
  $open_firts = get_field('open_firts');
  $always_open = get_field('always_open');

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
          <div class="component__content <?= esc_html($content_alignment); ?>">
           
            <?php if (isset($accordion_header['editor']) && !empty($accordion_header['editor']) || isset($accordion_header['links']) && !empty($accordion_header['links'])) : ?>
              <?php Helpers::global_content($accordion_header['editor'], $accordion_header['links']); ?>
            <?php endif; ?>
            
          </div>
          <?php if ($items) : ?>
            <?php
            $accodion_id = 'accordion-' . $id;
            ?>
            <div class="component__accordion">
              <div class="accordion <?= 'accordion--' . esc_html($accordion_layout); ?>" id="<?= esc_attr($accodion_id); ?>">
                <?php foreach ($items as $index => $item) : ?>
                  <?php
                  $heading = 'heading-' . $accodion_id . '-' . $index;
                  $collapse = 'collapse-' . $accodion_id . '-' . $index;
                  $button_classes = ' collapsed';
                  $accordion_classes = '';
                  $aria_expanded = 'false';
                  if ($open_firts) {
                    if ($index == 0) :
                      $button_classes = '';
                      $accordion_classes = ' show';
                      $aria_expanded = 'true';
                    endif;
                  }
                  ?>
                  <div class="accordion__item">
                    <h2 class="accordion__header <?= esc_attr($item['heading_size']); ?>" id="<?= esc_attr($heading); ?>">
                      <button class="accordion__button <?= esc_attr($button_classes); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= esc_attr($collapse); ?>" aria-expanded="<?= esc_attr($aria_expanded); ?>" aria-controls="<?= esc_attr($collapse); ?>">
                        <?php if (isset($item['title']) && !empty($item['title'])) : ?>
                          <?= esc_html($item['title']); ?>
                        <?php endif; ?>
                      </button>
                    </h2>
                    <div id="<?= esc_attr($collapse); ?>" class="accordion__collapse collapse<?= esc_attr($accordion_classes); ?>" aria-labelledby="<?= esc_attr($heading); ?>" <?php if (!$always_open) : ?> data-bs-parent="#<?= esc_attr($accodion_id); ?>" <?php endif; ?>>

                      <div class="accordion__body">

                        <?php if (isset($item['show_image']) && !empty($item['show_image'])) : ?>
                          <div class="accordion__grid<?= $item['switch_image'] ? ' reverse' : ''; ?>">
                            <div class="col-image" style="flex-basis: <?= $item['image_size'] . '%'; ?>">
                              <?= Helpers::global_image($item['image']); ?>
                            </div>
                            <div class="col-content">
                            <?php endif; ?>

                            <?php if (isset($item['description']) && !empty($item['description'])) : ?>
                              <?= wp_kses_post($item['description']); ?>
                            <?php endif; ?>

                            <?php if (isset($item['link']['link_text']) && !empty($item['link']['link_text'])) : ?>
                              <div class="block__buttons">
                                <?= Helpers::global_link($item['link']); ?>
                              </div>
                            <?php endif; ?>

                            <?php if (isset($item['show_image']) && !empty($item['show_image'])) : ?>
                            </div>
                          </div>
                        <?php endif; ?>

                      </div>

                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php
}
