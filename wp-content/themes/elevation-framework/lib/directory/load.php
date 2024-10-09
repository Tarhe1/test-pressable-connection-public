<?php

namespace  ElevationFramework\Lib\Directory;

class Load
{
    protected static $_instance;

    public function __construct()
    {
        Post::instance();
        Team::instance();

        add_filter('request', [$this, 'elevation_change_term_request'], 1, 1);
        add_filter('term_link', [$this, 'elevation_term_permalink'], 10, 3);
        add_action('template_redirect', [$this, 'elevation_old_term_redirect']);
    }

    public function response()
    {
    }

    public function elevation_change_term_request($query)
    {

        $tax_name = 'team_categories'; // specify you taxonomy name here, it can be also 'category' or 'post_tag'

        // Request for child terms differs, we should make an additional check
        $name = "";
        if (isset($query['attachment']) && $query['attachment']) :
            $include_children = true;
            $name = $query['attachment'];
        elseif (isset($query['name']) && $query['name']) :
            $include_children = false;
            $name = $query['name'];
        endif;


        $term = get_term_by('slug', $name, $tax_name); // get the current term to make sure it exists

        if (isset($name) && $term && !is_wp_error($term)) : // check it here

            if ($include_children) {
                unset($query['attachment']);
                $parent = $term->parent;
                while ($parent) {
                    $parent_term = get_term($parent, $tax_name);
                    $name = $parent_term->slug . '/' . $name;
                    $parent = $parent_term->parent;
                }
            } else {
                unset($query['name']);
            }

            $query[$tax_name] = $name; // for another taxonomies

        endif;

        return $query;
    }


    public function elevation_old_term_redirect()
    {

        $taxonomy_name = 'team_categories';
        $taxonomy_slug = 'team_categories';

        // exit the redirect function if taxonomy slug is not in URL
        if (strpos($_SERVER['REQUEST_URI'], $taxonomy_slug) === FALSE)
            return;

        if ((is_category() && $taxonomy_name == 'category') || (is_tag() && $taxonomy_name == 'post_tag') || is_tax($taxonomy_name)) :

            wp_redirect(site_url(str_replace($taxonomy_slug, '', $_SERVER['REQUEST_URI'])), 301);
            exit();

        endif;
    }

    public function elevation_term_permalink($url, $term, $taxonomy)
    {

        $taxonomy_name = 'team_categories'; // your taxonomy name here
        $taxonomy_slug = 'team_categories'; // the taxonomy slug can be different with the taxonomy name (like 'post_tag' and 'tag' )

        // exit the function if taxonomy slug is not in URL
        if (strpos($url, $taxonomy_slug) === FALSE || $taxonomy != $taxonomy_name) return $url;

        $url = str_replace('/' . $taxonomy_slug, '', $url);

        return $url;
    }


    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
