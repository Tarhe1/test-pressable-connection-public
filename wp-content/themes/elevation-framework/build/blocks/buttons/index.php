<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * Buttons Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function buttons_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

  // Create id attribute allowing for custom "anchor" value.
  $id = 'block__buttons-' . $block['id'] . '-' . wp_unique_id();
  if (!empty($block['anchor'])) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $data_id = 'buttons';
  $className = 'dynamic-assets-load block block__buttons';
  if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
  }
  if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
  }

  // Load values and handle defaults.
  $items = get_field('button_items');
  $column_direction = get_field('column_direction');

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
  <?php if ($items) : ?>
    <div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?>">
      <div class="block__container container">
        <div class="block__row row justify-content-<?= esc_html($container_alignment); ?>">
          <div class="block__col col-xl-<?= esc_html($container_width); ?> col-12 offset-xl-<?= esc_html($offset_container_width); ?>">
            <div class="block__content <?= esc_html($content_alignment); ?>">
              <div class="block__buttons <?= esc_html($column_direction) ? 'column' : ''; ?>">
                <?php foreach ($items as $item) : ?>
                  <?= Helpers::global_link($item['links']); ?>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php
}
