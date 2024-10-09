<?php

namespace ElevationFramework\Lib\Directory;

use ElevationFramework\Lib\BlockLibrary\Helpers;

class Post extends Directory
{
    protected static $_instance;
    public $post_type = 'post';
    public $label = 'Post';

    public $labels = array();
    public $register = false;
    public $directory = [
        'posts_per_page' =>  12,
        'orderby' => 'date',
        'order' => 'DESC',
    ];
    public $taxonomies = [
        [
            'name' => 'category', // se agrega el prefix de resource
            'label' => 'Category',
            'plural_label' => 'Categories',
        ],
        [
            'name' => 'post_tag', // se agrega el prefix de resource
            'label' => 'Tag',
            'plural_label' => 'Tags',
        ],
    ];


    public function set_custom_args($args)
    {

        if ($this->show_sticky_post) {
            $post__not_in = isset($args['post__not_in']) ? $args['post__not_in'] : [];
            $args['post__not_in'] =  array_merge($post__not_in, get_option('sticky_posts'));
            $args['ignore_sticky_posts'] = 1;
        }
        return $args;
    }

    public function get_sticky_post()
    {
        $this->show_sticky_post = true;

        $sticky_posts = get_option('sticky_posts');

        if (empty($sticky_posts)) {
            return;
        }

        $sticky_posts_args = array(
            'post_type' => 'post',
            'post__in' => $sticky_posts,
        );

        $sticky_posts_query = new \WP_Query($sticky_posts_args);

        $html = '';
        $count_sticky_posts = 0;
        while ($sticky_posts_query->have_posts() && $count_sticky_posts < 1) :
            $sticky_posts_query->the_post();

            $id = get_the_ID();

            if (has_post_thumbnail()) {
                $image = get_the_post_thumbnail_url();
                $thumbnailID = get_post_thumbnail_id($id);
                $altImage = sanitize_text_field(get_post_meta($thumbnailID, '_wp_attachment_image_alt', true));
            } else {
                $image = get_template_directory_uri() . '/src/assets/images/default.svg';
                $altImage = 'This is the default image';
            }
            $title        = get_the_title($id);
            $excerpt      = Helpers::get_excerpt(20, get_the_excerpt($id));
            $categories   = get_the_terms($id, 'categories');
            $permalink    = get_permalink();
            $html .= ' <div class="row-cards pb-0">
							<div class="col sticky_posts">
								<article id="post-' . $id . ' " class="card">
									<header class="card__header">
										<figure class="card__image"><img decoding="async" loading="lazy" src="' . $image . '" alt="' . $altImage . '"></figure>
									</header>
									<div class="card__body">
										<span class="card__date-author">
											<span class="date">' . get_the_date("F j, Y") . '</span><span class="author">by ' . get_the_author() . '</span>
										</span>
										<h2 class="card__title h6"><span>' . $title . '</span></h2>
										<div class="card__excerpt line-clamp-4">' . $excerpt . '</div>
										<div class="card__category">' . get_the_category_list() . '</div>
										<span class="card__date">' . get_the_date("d F Y") . '</span>
									</div>
									<a href="' . $permalink . '" class="stretched-link stretched-link-custom">Read Post</a>
								</article>
							</div>
						</div>';
            $count_sticky_posts++;

        endwhile;
        wp_reset_postdata();

        echo $html;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
