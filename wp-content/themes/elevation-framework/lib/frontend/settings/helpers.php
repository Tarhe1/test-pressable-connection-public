<?php

namespace  ElevationFramework\Lib\Frontend\Settings;

class Helpers
{

    protected static $_instance;

    public function __construct()
    {
    }

    public static function posted_on()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        // if (get_the_time('U') !== get_the_modified_time('U')) {
        // 	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        // }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'elevation'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

    public static function posted_by()
    {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'elevation'),
            /* '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'*/
            '<span class="author vcard">' . esc_html(get_the_author()) . '</span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public static function entry_footer()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'elevation'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'elevation') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'elevation'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'elevation') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if (is_singular('post') && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'elevation'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'elevation'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }

    public static function post_thumbnail($size = 'post-thumbnail', $class = null)
    {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            $arr = [];
            if ($class) {
                $arr['class'] = $class;
            }
?>
            <div class="post-thumbnail card__image card__image--hover-effect">
                <?php the_post_thumbnail($size, $arr); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="card__link" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                $arr = [
                    'alt' => the_title_attribute(
                        [
                            'echo' => false,
                        ]
                    ),
                ];
                if ($class) {
                    $arr['class'] = $class;
                }
                the_post_thumbnail($size, $arr);
                ?>
            </a>

<?php
        endif; // End is_singular().
    }

    public static function determine_url($link, $anchor, $type)
    {
        $urlSrc =
            ($type == 'internal' || $type == 'internal-anchor') ? $link['internal_link'] : ($type == 'external' ? $link['external_link'] : ($type == 'file' ? $link['file'] : $link['internal_taxonomy']));
        if ($type == 'internal-taxonomy') {
            if ($urlSrc) {
                $urlSrc = get_term_link($urlSrc);
            } else {
                $urlSrc = '';
            }
        }
        if ($anchor != '') {
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if ($urlSrc == $actual_link) {
                $urlSrc = '';
            } else {
                $urlSrc = rtrim($urlSrc, '/');
            }
        }
        return $urlSrc;
    }

    public static function body_open()
    {
        do_action('wp_body_open');
    }

    public static function site_estimated_reading_time($content = '', $wpm = 250)
    {
        $clean_content = strip_shortcodes($content);
        $clean_content = strip_tags($clean_content);
        $word_count = str_word_count($clean_content);
        $time = ceil($word_count / $wpm);
        return $time;
    }



    public static function get_primary_taxonomy_name($post_id, $taxonomy)
    {
        $prm_term = '';
        if (class_exists('WPSEO_Primary_Term')) {
            $wpseo_primary_term = new WPSEO_Primary_Term($taxonomy, $post_id);
            $prm_term = $wpseo_primary_term->get_primary_term();
        }
        if (!is_object($wpseo_primary_term) || empty($prm_term)) {
            $term = wp_get_post_terms($post_id, $taxonomy);
            if (isset($term) && !empty($term)) {
                return $term[0];
            } else {
                return '';
            }
        }
        return get_term($wpseo_primary_term->get_primary_term());
    }

    // get first page with specific template
    public function get_page_by_template($template = '')
    {
        $args = array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $template
        );
        $pages = get_pages($args);

        return $pages[0]->ID;
    }

    public static function vimeo_url($url = '', $loop = false, $only_controls = false)
    {
        if (strpos($url, 'vimeo.com/') !== false) {
            $params = '?autoplay=1&autopause=0&title=0';
            if ($loop) {
                $params = '?autoplay=1&loop=1&autopause=0&title=0&controls=0&dnt=1&muted=1';
                if ($only_controls) {
                    $params = '?autoplay=1&autopause=0&title=0&controls=1&dnt=1&muted=0';
                }
            }
            //it is Vimeo video
            $videoId = explode("vimeo.com/", $url)[1];
            if (strpos($videoId, '&') !== false) {
                $videoId = explode("&", $videoId)[0];
            }
            //if its needed autoplay you must add => &muted=1
            return 'https://player.vimeo.com/video/' . $videoId . $params;
        }
        return false;
    }

    // Change youtube regular url for embeded url
    public static function youtube_url($url = '', $loop = false, $only_controls = false)
    {
        if (strpos($url, 'youtu.be/') !== false) {
            $videoId = explode("youtu.be/", $url)[1];
            $params = '?autoplay=1&origin=' . get_the_permalink();
            if ($loop) {
                $params = '?playlist=' . $videoId . '&autoplay=1&mute=1&loop=1&origin=' . get_the_permalink();
                if ($only_controls) {
                    $params = '?playlist=' . $videoId . '&autoplay=1&mute=0&controls=1&origin=' . get_the_permalink();
                }
            }
            return 'https://www.youtube-nocookie.com/embed/' . $videoId . $params;
        } elseif (strpos($url, 'youtube.com/watch?v=') !== false) {
            $parts = parse_url($url);
            parse_str($parts['query'], $query);
            $videoId = $query['v'];
            $params = '?autoplay=1&origin=' . get_the_permalink();
            if ($loop) {
                $params = '?playlist=' . $videoId . '&autoplay=1&mute=1&loop=1&origin=' . get_the_permalink();
            }
            return 'https://www.youtube-nocookie.com/embed/' . $videoId . $params;
        } else {
            return $url;
        }

        return false;
    }

    public function post_excerpt($limit, $excerpt = null)
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

    public function hex_to_rgba($color, $opacity = false)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
