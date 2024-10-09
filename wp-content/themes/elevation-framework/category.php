<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elevation
 */

get_header(
  '',
  [
    'header_type' => 'transparent'
  ]
);

$term = get_queried_object();

// Banner default
$banner_post = get_field('banner_post', 'option');
$content_post = get_field('content_post', 'option');

//
$directory_posts = get_field('directory_posts', 'option');
$above_content_option  = get_field('above_content_option ', 'option');
$below_content_option   = get_field('below_content_option  ', 'option');

// Campos que menejan layout
$directory_layout = get_field('directory_layout', 'option');
$layout_rows = get_field('category_layout', 'option'); // Cantidad de row de las cards
$content_alignment = get_field('directory_content_alignment', 'option');
$container_alignment = get_field('directory_container_alignment', 'option');
$container_width = get_field('directory_container_width', 'option');
$directory_image = get_field('directory_image', 'option');

// Campos especificos de cada categoria
$category_banner = get_field('category_banner', $term);
$category_content = get_field('category_content', $term);
$category_background_image = get_field('category_background_image', $term);
$above_content = get_field('category_info_above', $term);
$below_content = get_field('category_info_below', $term);
$hide_banner = get_field('banner_hidden', $term);

if ($banner_post) : ?>
  <?php if ($directory_image && !$category_banner) :
    $background_image    = $directory_image['sizes']['hd'];
    $altbackground_image = $directory_image['alt'];
  endif; ?>

  <?php if ($category_banner && $category_background_image) :
    $background_image    = $category_background_image['sizes']['hd'];
    $altbackground_image = $category_background_image['alt'];
  endif
  ?>

  <?php if (!$hide_banner) : ?>
    <section class="interior-banner category alignfull">
      <?php if ($directory_image != null || $category_background_image != null) : ?>
        <div class="banner-wrapping">
          <div class="bottom-mask"></div>
          <?php Helpers::global_image($category_background_image); ?>
        </div>
      <?php endif; ?>
      <div class="container">
        <div class="row justify-content-<?= $container_alignment; ?>">
          <div class="col-12 col-xl-<?= $container_width ? $container_width : '12'; ?>">
            <div class="banner-container <?= $content_alignment; ?>">
              <?php if ($category_banner) : ?>
                <?php echo $category_content; ?>
              <?php else : ?>
                <?php echo $content_post; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php endif; ?>
<?php endif; ?>

<main id="primary" class="site-main container<?php if (!$hide_banner) echo ' with-banner'; ?>">
  <?php if (have_posts()) : ?>

    <header class="page-header">
      <div class="row justify-content-center">
        <div class="col-xl-10">
          <div class="row">
            <div class="col-xl-7">
              <?php
              if ($hide_banner) {
                the_archive_title('<h1 class="page-title">', '</h1>');
                the_archive_description('<div class="archive-description">', '</div>');
              }
              ?>
            </div>
            <div class="col-xl-5">
              <div class="social-header">
                <?php echo do_shortcode('[addtoany]'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php if ($directory_layout == 'option-3' && !$hide_banner) : ?>
        <section class="pt-4">
          <?php
          the_archive_description('<div class="archive-description">', '</div>');
          ?>
        </section>
      <?php endif; ?>
    </header><!-- .page-header -->


    <?php

    if ($above_content_option && !$above_content) {
      echo apply_filters('the_content', get_post_field('post_content', $above_content_option));
    }

    if ($above_content) {
      echo apply_filters('the_content', get_post_field('post_content', $above_content));
    }
    ?>


    <div class="<?= $directory_layout; ?> <?= $directory_layout == 'option-3' && empty($directory_posts) ?  'no-feature-post' : '' ?> row-cards row row-cols-xl-<?= $layout_rows == 'option-1' ? '3 row-cols-md-2' : '2 row-cols-md-1'; ?> row-cols-1">
    <?php
    /* Start the Loop */
    $c = 1;
    while (have_posts()) :
      the_post();
      /*
              * Include the Post-Type-specific template for the content.
              * If you want to override this in a child theme, then include a file
              * called content-___.php (where ___ is the Post Type name) and that will be used instead.
              */

      get_template_part('template-parts/content-posts', get_post_type());

      if ($directory_layout == 'option-3' && !empty($directory_posts)) {
        echo get_sticky_post($directory_posts, $c);
      }

      $c++;
    endwhile;

    the_posts_pagination();

    echo '<span class="border-line"></span></div>';

  else :

    get_template_part('template-parts/content', 'none');

    echo '</div>';

  endif;
    ?>

    <?php

    if ($below_content_option && !$below_content) {
      echo apply_filters('the_content', get_post_field('post_content', $below_content_option));
    }
    if ($below_content) {
      echo apply_filters('the_content', get_post_field('post_content', $below_content));
    }
    ?>

    <?php
    $show_block = get_field('display_optional_block', 'option');
    $category_optional_block = get_field('category_optional_block', 'option');
    if ($show_block) : ?>
      <div class="block-bottom-category">
        <div class="row justify-content-center">
          <div class="col-xl-10">

            <?php if ($category_optional_block != NULL) : ?>
              <div class="block-bottom-category__content">
                <?php Helpers::global_content($category_optional_block['editor'], $category_optional_block['links']); ?>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    <?php endif; ?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
