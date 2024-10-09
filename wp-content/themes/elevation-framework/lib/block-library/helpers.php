<?php

namespace ElevationFramework\Lib\BlockLibrary;

class Helpers
{
    protected static $_instance;

    public function __construct()
    {
        add_action('wp_head', [$this, 'elevation_avoid_fouc_css'], 1);
        add_filter('style_loader_tag', [$this, 'preload_styles'], 10, 4);
        add_filter('script_loader_tag', [$this, 'defer_scripts'], 10, 3);
    }

    public static function global_image_($image, $echo = true, $class = 'media', $decorative = null, $is_figure = true, $isLazyForce = false)
    {
        if (is_array($image)) {
            if ($image['image']) {
                $image1x  = $image['image'] ? $image['image']['url'] : '';
                $imageAlt = $image['image'] && $image['image']['alt'] ? ' alt="' . $image['image']['alt'] . '"' : ' alt=""';
                $width = $image['image'] && $image['image']['width'] ? $image['image']['width'] : '200';
                $height = $image['image'] && $image['image']['height'] ? $image['image']['height'] : '100';
                $isLazy   = isset($image['is_lazy']) ? $image['is_lazy'] : false;
                $lazyAttr = $isLazy != null && !$isLazy ? 'auto' : 'lazy';
                $lazyAttr = $isLazyForce ? "lazy" : $lazyAttr;
                $classes  = $isLazy ? '' : '';
                $retina   = $image['add_retina'];
                $image2x  = $retina ? ($image['image_2x'] ? $image['image_2x']['url'] : '') : '';
                $src_set  = $isLazy ? 'srcset="' : 'srcset="';
                $decoding = $lazyAttr == "lazy" ? ' decoding="async"' : "";
                if ($decorative) {
                    $imageAlt = ' alt=""';
                }
                $figure = $is_figure ? 'figure' : 'span';

                $imageObj = '<' . $figure . ' class="' . $class . '"><img' . $imageAlt . $decoding .  ' loading="' . $lazyAttr . '" ' . $classes . ' ' . ($isLazy ? '' : '') . 'src="' . $image['image']['url'] . '"' . ($retina == 1 ? $src_set . ($image1x != '' ? $image1x . ' 1x,' : '') . ($image2x != '' ? $image2x . ' 2x' : '') . '"' : '') . ' width="' . $width . '" height="' . $height . '"></' . $figure . '>';

                if (empty($image1x)) {
                    $imageObj = false;
                }

                if ($echo) {
                    echo $imageObj;
                } else {
                    return $imageObj;
                }
            } else {
                return '';
            }
        }
    }

    /**
     * Get the image from ACF.
     * 
     * @since 1.0.0
     * 
     * @param array $image The ACF field with the image.
     * @param array $args The arguments to customize the image. Default is null.
     *               Optional. Attributes for the image markup. Default is empty.
     *              Supported keys:
     *              - 'echo'        (bool) Whether to echo the image HTML markup directly (true) or return it (false). Default is `true`
     *              - 'class'       (string) The CSS class attribute for styling the figure tag, img will also get the class + "-image". Default is `media`
     *              - 'decorative'  (bool) Whether the image is purely decorative and should be ignored by assistive technologies such as screen readers. Default is `false`.
     *              - 'is_figure'   (bool) Whether the image is wrapped in a figure tag or a span tag. Default is `true`.
     *              - 'loading'     (string) This overrides the loading attribute from the image. Default is `null`, accepted value `eager`.
     *              - 'size'        (string|array) Image size. Accepts any registered image size name, or an array containing width and height values in pixels (in that order). Default: `full`
     *              - 'alt'         (string) Override the alt attribute from image. Default is `null`.
     * 
     * @return string This returns an image with the provided ACF. If there isn't one, it returns an empty string.
     */
    public static function global_image($image, $args = null)
    {
        if (empty($image)) {
            return '';
        }

        $is_url = false;
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $is_url = true;
        } elseif (is_numeric($image)) {
            $image_id = $image;
        } elseif (isset($image['image']['ID'])) {
            $image_id = $image['image']['ID'];
        } else {
            return '';
        }

        $defaults = [
            'echo' => true,
            'class' => 'media',
            'decorative' => null,
            'is_figure' => true,
            'loading' => null,
            'size' => 'full',
            'alt' => null,
            'icon' => false
        ];

        // Combinar los argumentos proporcionados con los valores predeterminados
        $args = wp_parse_args($args, $defaults);

        // Extraer los argumentos como variables locales
        extract($args);

        $image_args = [
            'class' => $class . '-image',
            'decoding' => 'async',
        ];

        $is_lazy   = isset($image['is_lazy']) && $image['is_lazy'] === null ? true : (isset($image['is_lazy']) && $image['is_lazy'] == false ? false : true);

        if ($is_lazy) {
            $image_args['loading'] = 'lazy';
        }
        if ($loading) {
            $image_args['loading'] = $loading;
        }
        if ($decorative) {
            $image_args['alt'] = '';
        }

        if ($alt) {
            $image_args['alt'] = $alt;
        }

        $figure = $is_figure ? 'figure' : 'span';

        if ($is_url) {
            // If $attachment_id_or_url is a URL, directly print the image by URL
            $img = sprintf('<img src="%s" alt="%s" class="%s" loading="%s" decoding="async">', esc_url($image), esc_attr(isset($image['alt']) && $image_args['alt'] ? $image_args['alt'] : ''), esc_attr($image_args['class']), esc_attr($image_args['loading']));
        } else {
            // Get the attachment image HTML markup
            $img = wp_get_attachment_image($image_id, $size, $icon, $image_args);
        }

        $image_component = '<' . $figure . ' class="' . $class . '">' . $img . '</' . $figure . '>';

        if ($echo) {
            echo $image_component;
        } else {
            return $image_component;
        }
    }

    public static function global_link($link)
    {
        if (is_array($link) && $link['link_text']) {
            $type = $link['link_type'];
            $newTab = '';
            if ($type == 'pages') {
                $href = $link['page'];
            }
            if ($type == 'external') {
                $href = $link['external_url'];
                $newTab = ' target="_blank" rel="noopener noreferrer"';
            }
            if ($type == 'file') {
                $href = $link['file']['url'];
                $newTab = ' target="_blank" download rel="noopener noreferrer"';
            }
            if ($type == 'internal') {
                $href = $link['internal_url'];
                $newTab = ' target="_self" rel="noopener noreferrer"';
            }
            if ($type == 'with-anchor') {
                $anchor = '#' . $link['anchor'];
                $href .= $anchor;
            }

            echo '<a href="' . $href . '" class="cta cta--' . $link['link_style'] . '"'  . $newTab . '>' . $link['link_text'] . '</a>';
        }
    }

    public static function global_content($editor, $links, $args = null)
    {

        $defaults = [
            'class' => '',
        ];

        $args = wp_parse_args($args, $defaults);

        extract($args);


        if (!empty($editor)) {
            $classString = 'block__editor';
            if (!empty($class)) {
                $classString .= ' ' . $class;
            }

            echo '<div class="' . $classString . '">';
            echo $editor;
            echo '</div>';
        }

        if (!empty($links) && count($links) > 0) {
            echo '<div class="block__buttons">';
            foreach ($links as $link) :
                self::global_link($link);
            endforeach;
            echo '</div>';
        }
    }

    // Excerpt for ACF blocks
    public static function get_blocks_excerpt($post_id)
    {
        $excerpt = get_the_excerpt($post_id);

        if (!$excerpt) {
            $blocks = parse_blocks(get_the_content($post_id));
            $excerpt = '';
            foreach ($blocks as $block) {
                $excerpt .= render_block($block);
            }
            $excerpt = wp_strip_all_tags($excerpt);
        }

        return wp_trim_words($excerpt, 50, '...');
    }

    public static function get_excerpt($limit, $excerpt = null)
    {

        if ($excerpt == null) {
            $excerpt = explode(' ', get_the_excerpt(), $limit);
        } else {
            $excerpt = explode(' ', $excerpt, $limit);
        }

        if (count($excerpt) >= $limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt) . '...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }

        $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);

        return $excerpt;
    }

    public static function get_swiper_attrs($swiper, $default_values = [])
    {
        $attributes = [
            'loop',
            'centered-slides',
            'grab-cursor',
            'slides-per-view',
            'space-between',
            'space-between-md',
            'slides-per-view-md',
            'space-between-lg',
            'slides-per-view-lg',
            'space-between-xl',
            'slides-per-view-xl',
            'space-between-xxl',
            'slides-per-view-xxl',
            'slide-to-click-slide',
            'delay',
            'disable-on-interaction',
            'loop-additional-slides'
        ];


        // If $swiper is an array of ACF fields, get the actual array
        $swiper = $swiper['swiper_settings'] ?? $swiper;

        // Remove empty, 0 or "0" values from $swiper
        $swiper = self::array_filter_false($swiper);

        // Merge the arrays, overwriting default values with values from $swiper
        if (!empty($default_values) & !empty($swiper)) {
            $swiper = array_merge($default_values, $swiper);
        } elseif (empty($swiper)) {
            $swiper = $default_values;
        }

        $swiper_attrs = '';
        foreach ($attributes as $attribute) {
            if (isset($swiper[$attribute]) && $swiper[$attribute] !== '') {
                $attr = $swiper[$attribute] === false ? '0' : $swiper[$attribute];
                $swiper_attrs .= ' data-' . $attribute . '=' . $attr . '';
            }
        }

        return $swiper_attrs;
    }


    public static function array_filter_false($array)
    {
        if (!is_array($array))  return $array;

        foreach ($array as $key => $value) {
            if ($value === "" || $value === 0 || $value === '0') {
                unset($array[$key]);
            }
        }
        return $array;
    }

    public function preload_styles($html, $handle, $href, $media)
    {
        if (!is_admin()) {
            if ($handle == 'addtoany' && is_front_page()) return;
            if (str_contains($href, '/build/blocks/') || str_contains($href, '/build/assets/')) return;
            if ($handle !== "elevation-critical-css") {
                $html = '<link rel="preload" href="' . $href . '" as="style" id="' . $handle . '" media="' . $media . '" onload="this.onload=null;this.rel=\'stylesheet\'">'
                    . '<noscript>' . $html . '</noscript>';
            }
        }
        return $html;
    }


    public function elevation_avoid_fouc_css()
    {
        echo '<style id="avoid-fouc-css">body{opacity:0;transition:opacity 0.2s ease-in 0.2s;}</style><noscript><style id="reverse-avoid-fouc-css">body{opacity:1!important;}</style></noscript>';
    }

    public function defer_scripts($tag, $handle, $source)
    {
        $exclude = ['/wp-includes/', '/woocommerce'];

        foreach ($exclude as $excluded) {
            if (str_contains($source, $excluded)) {
                return $tag;
            }
        }

        if (is_admin() || $handle == 'akismet-frontend' || $handle == 'elevation-jquery') {
            return $tag;
        }

        if (($handle == 'addtoany-jquery' || $handle == 'addtoany-core') && is_front_page()) {
            return;
        }

        if (str_contains($source, '/build/blocks/') || str_contains($source, '/build/assets/')) {
            return;
        }

        // Add defer attribute to the scripts or type module to elevation-custom-directory-script
        $type_or_defer = $handle == 'elevation-custom-directory-script' ? 'type="module"' : 'defer="defer"';

        if ($handle !== 'elevation-critical-scripts') {
            $tag = '<script ' . $type_or_defer . ' src="' . $source . '" id="' . $handle . '"></script>';
        }

        return $tag;
    }

    public function block_excerpt($post_id)
    {
        $excerpt = get_the_excerpt($post_id);

        if (!$excerpt) {
            $blocks = parse_blocks(get_the_content($post_id));
            $excerpt = '';
            foreach ($blocks as $block) {
                $excerpt .= render_block($block);
            }
            $excerpt = wp_strip_all_tags($excerpt);
        }

        return wp_trim_words($excerpt, 50, '...');
    }


    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
