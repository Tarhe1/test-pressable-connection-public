<?php

namespace ElevationFramework\Lib\Directory;

use ElevationFramework\Lib\Directory\Controller\ApiController;

class Directory extends ApiController
{
    // Propiedades de la clase padre
    public $post_type = 'resource';
    public $register = true;
    public $label = 'Resource';
    public $labels = array();
    public $args = array();
    public $description = 'Post Type Description';
    public $taxonomies = [];
    public $directory = [
        'per_page' => 4,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    ];
    public $show_sticky_post = false;

    public function __construct()
    {
        parent::__construct();

        if ($this->register) {
            add_action('after_switch_theme', [$this, 'rewrite_flush']);
            add_action('init', [$this, 'add_post_type']);
        }

        $this->set_directory();
    }

    public function add_post_type()
    {
        $exclude_default_post_types = get_field('exclude_default_post_types', 'option');
        $enabled_post_type = is_array($exclude_default_post_types) ? !in_array($this->post_type, $exclude_default_post_types) : true;
        if ($enabled_post_type) {
            $args = $this->get_args();
            register_post_type($this->post_type, $args);

            $this->set_taxonomies();
        }
    }

    public function rewrite_flush()
    {
        $this->add_post_type();
        flush_rewrite_rules();
    }


    public function get_labels()
    {
        $defaults = array(
            'name'                  => _x($this->label, 'Post Type General Name', 'elevation'),
            'singular_name'         => _x($this->label, 'Post Type Singular Name', 'elevation'),
            'menu_name'             => __($this->label, 'elevation'),
            'name_admin_bar'        => __($this->label, 'elevation'),
            'archives'              => __($this->label . ' Archives', 'elevation'),
            'attributes'            => __($this->label . ' Attributes', 'elevation'),
            'parent_item_colon'     => __('Parent ' . $this->label, 'elevation'),
            'all_items'             => __('All ' . $this->label, 'elevation'),
            'add_new_item'          => __('Add New ' . $this->label, 'elevation'),
            'add_new'               => __('Add New ', 'elevation'),
            'new_item'              => __('New ' . $this->label, 'elevation'),
            'edit_item'             => __('Edit ' . $this->label, 'elevation'),
            'update_item'           => __('Update ' . $this->label, 'elevation'),
            'view_item'             => __('View ' . $this->label, 'elevation'),
            'view_items'            => __('View ' . $this->label, 'elevation'),
            'search_items'          => __('Search in ' . $this->label, 'elevation'),
            'not_found'             => __('Not found', 'elevation'),
            'not_found_in_trash'    => __('Not found in Trash', 'elevation'),
            'featured_image'        => __('Featured Image', 'elevation'),
            'set_featured_image'    => __('Set featured image', 'elevation'),
            'remove_featured_image' => __('Remove featured image', 'elevation'),
            'use_featured_image'    => __('Use as featured image', 'elevation'),
            'insert_into_item'      => __('Insert into ' . $this->label, 'elevation'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'elevation'),
            'items_list'            => __($this->label . ' list', 'elevation'),
            'items_list_navigation' => __($this->label . ' list navigation', 'elevation'),
            'filter_items_list'     => __('Filter items list', 'elevation'),
        );

        return wp_parse_args($this->labels, $defaults);
    }

    public function get_args()
    {

        $default = array(
            'label'                 => __($this->label, 'elevation'),
            'description'           => __($this->description, 'elevation'),
            'labels'                => $this->get_labels(),
            'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'author'),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 21,
            'menu_icon'             => 'dashicons-groups',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rest_base'             => $this->post_type,
            'taxonomies' => array()
        );

        $args = wp_parse_args($this->args, $default);

        if (!empty($this->taxonomies)) {
            $taxes = [];

            foreach ($this->taxonomies as $tax) {
                $taxes[] = $tax['name'];
            }

            $args['taxonomies'] = $taxes;
        }


        return $args;
    }

    public function set_taxonomies()
    {
        if (!empty($this->taxonomies)) {
            foreach ($this->taxonomies as $tax) {
                $tax_args = $this->get_taxonomy_args($tax);
                register_taxonomy($tax['name'], array($this->post_type), $tax_args);
            }
        }
    }

    public function get_taxonomy_label($tax)
    {
        $name = $tax['label'];
        $plural_name = $tax['plural_label'];
        $labels = array(
            'name'                       => _x($name, 'Taxonomy General Name', 'elevation'),
            'singular_name'              => _x($plural_name, 'Taxonomy Singular Name', 'elevation'),
            'menu_name'                  => __($name, 'elevation'),
            'all_items'                  => __('All Items', 'elevation'),
            'parent_item'                => __('Parent Item', 'elevation'),
            'parent_item_colon'          => __('Parent Item:', 'elevation'),
            'new_item_name'              => __('New Item Name', 'elevation'),
            'add_new_item'               => __('Add New Item', 'elevation'),
            'edit_item'                  => __('Edit Item', 'elevation'),
            'update_item'                => __('Update Item', 'elevation'),
            'view_item'                  => __('View Item', 'elevation'),
            'separate_items_with_commas' => __('Separate items with commas', 'elevation'),
            'add_or_remove_items'        => __('Add or remove items', 'elevation'),
            'choose_from_most_used'      => __('Choose from the most used', 'elevation'),
            'popular_items'              => __('Popular Items', 'elevation'),
            'search_items'               => __('Search Items', 'elevation'),
            'not_found'                  => __('Not Found', 'elevation'),
            'no_terms'                   => __('No items', 'elevation'),
            'items_list'                 => __('Items list', 'elevation'),
            'items_list_navigation'      => __('Items list navigation', 'elevation'),
        );

        if (!empty($tax['args']['labels'])) {
            return wp_parse_args($tax['args']['labels'], $labels);
        }

        return $labels;
    }

    public function get_taxonomy_args($tax)
    {
        $labels = $this->get_taxonomy_label($tax);
        $not_labels = array(
            'labels',
        );
        if (empty($tax['args'])) {
            return $this->get_default_tax_args($tax, $labels);
        }

        $args = $this->get_default_tax_args($tax, $labels);

        return  wp_parse_args(array_diff_key($tax['args'], array_flip($not_labels)), $args);
    }

    public function get_default_tax_args($tax, $labels)
    {
        return array(
            'hierarchical'               => true,
            'labels'                     => $labels,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_rest' => true,
            'show_in_quick_edit '        => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite' => array(
                'slug' => $this->post_type . '/' .  str_replace($this->post_type . '_', "", $tax['name']),
                'with_front' => false, // don't prepend static prefix from post permalink
            ),
        );
    }

    //  probar parse de array
    public function get_filters_default($tax)
    {

        $default_filters = array(
            'type' => 'dropdown',
            'show' => true,
            'is_checkbox' => true,
            'in_modal' => true,
        );

        // filtros defaults
        return wp_parse_args($tax, $default_filters);
    }

    public function get_filters()
    {

        $filters = $this->taxonomies;

        $html = '';
        foreach ($filters as $tax) {
            $tax = $this->get_filters_default($tax);
            $terms = get_terms(array(
                'taxonomy' => $tax['name'],
                'hide_empty' => false,
            ));
            // var_dump($tax);
            if ($tax['show'] && !empty($terms)) {
                $html .= '<div class="filter__item"><span class="filter__label">' . $tax['label'] . '*</span>';

                if ($tax['type'] == 'checkbox') {
                    $html .=  $this->get_checkbox($tax);
                } elseif ($tax['type'] == 'boolean') {
                    $html .= $this->get_boolean($tax);
                } else {
                    $html .= $this->get_dropdown($tax);
                }

                $html .= '</div>';
            }
        }

        echo $html;
    }


    public function get_modal_filters()
    {
        $filters = $this->taxonomies;

        $html = '';
        foreach ($filters as $tax) {
            $tax = $this->get_filters_default($tax);
            if ($tax['in_modal'] && $tax['show']) {
                $html .= '<div class="filter__item">';
                $html .=  $this->get_checkbox($tax, true);
                $html .= '</div>';
            }
        }
        echo $html;
    }

    public function get_dropdown($tax)
    {
        $slug = $tax['name'];
        $ID = $slug;

        $values = get_terms([
            'taxonomy'   => $slug,
            'hide_empty' => true,
        ]);

        $html = '';

        if ($tax['is_checkbox']) {

            $html .= '<div class="multiselect" id="' . $ID . '" data-type="filter">
                <div class="multiselect__selectBox">
                  <div aria-expanded="false" class="select" aria-haspopup="group" role="combobox" tabindex="0">All</div>
                </div>
                <div class="multiselect__checkboxes" role="group" tabindex="-1">
                <ul class="multiselect__options">';
            foreach ($values as $value) {
                $html .= '
                      <div class="form-check">
                        <label class="form-check-label" for="checkbox-' . $value->slug . '">' . ucfirst($value->name) . '</label>
                        <input class="form-check-input" name="checkbox-' . $slug . '" type="checkbox" id="checkbox-' . $value->slug . '" value="' . $value->slug . '">
                      </div>';
            }
            $html .= '
                  </ul>
                </div>
              </div>';
        } else {

            $html .= '<select class="filter__select" id="' . $ID . '" name="' . $ID . '"  data-type="filter">
                                      <option value="">Search by ' . $tax['label']  . '</option>';
            foreach ($values as $value) :
                $html .= '<option value="' . $value->slug . '">' . ucfirst($value->name) . '</option>';
            endforeach;
            $html .= '</select>';
        }

        return $html;
    }

    public function get_checkbox($tax = null, $is_modal = false)
    {

        $slug = $tax['name'];
        $values = get_terms([
            'taxonomy'   => $slug,
            'hide_empty' => true,
        ]);

        $html = '';
        if ($is_modal) {
            $html .= '<div class="group-filter" data-filter="' .  $slug  . '">
            <div class="group-header">
                <span class="field-label h6">Filter by ' . $this->label . ' </span>
                <button class="cta-filter select" type="button" id="modalSelectAll-' . $slug . '">Select All</button>
                <button class="cta-filter unselect" type="button" id="modalUnselectAll-' . $slug . '">Unselect All</button>
            </div>';

            foreach ($values as $value) {
                $html .= '
              <div class="form-check form-check-inline">
                <label class="form-check-label" for="checkbox-' . $value->slug . '">' . ucfirst($value->name) . '</label>
                <input class="form-check-input" name="checkbox-' . $slug . '" type="checkbox" data-target="checkbox-' . $value->slug . '" value="' . $value->slug . '">
              </div>';
            }
            $html .= '</div>';
        } else {

            $html .= '<div class="group-filter" id="' . $slug . '" data-type="filter">
            <div class="group-header">
                <span class="field-label h6">Filter by ' . $this->label . ' </span>
                <button class="cta-filter select" type="button" id="selectAll-' . $slug . '">Select All</button>
                <button class="cta-filter unselect" type="button" id="unselectAll-' . $slug . '">Unselect All</button>
            </div>';

            foreach ($values as $value) {
                $html .= '
              <div class="form-check form-check-inline">
                <label class="form-check-label" for="checkbox-' . $value->slug . '">' . ucfirst($value->name) . '</label>
                <input class="form-check-input" name="checkbox-' . $slug . '" type="checkbox" data-value="checkbox-' . $value->slug . '" id="checkbox-' . $value->slug . '" value="' . $value->slug . '">
              </div>';
            }
            $html .= '</div>';
        }

        return $html;
    }

    public function get_boolean($mainTitle, $id, $slug, $title)
    {
        // $mainTitle, $id, $slug, $title
        $html = '';
        $html .= '<div class="group-filter">
            <div class="group-header">
                <span class="field-label h6">' . $mainTitle . '</span>
            </div>
            <div class="form-check form-switch">
                <label class="form-check-label" for="' . $id . '">' . $title . '</label>
                <input class="form-check-input" name="' . $id . '"type="checkbox" id="' . $slug . '">
            </div>
        </div>';

        return $html;
    }

    public function set_directory()
    {
        $defaults = [
            'total' => 12,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        ];

        $this->directory =  wp_parse_args($this->directory, $defaults);
    }


    public function previus_content($total_post, $total_post_page, $sortByRaw)
    {
        $options = [
            'date-DESC' => 'DATE [DESC]',
            'date-ASC' => 'DATE [ASC]',
            'title-ASC' => 'TITLE [ASC]',
            'title-DESC' => 'TITLE [DESC]',
        ];
        $h = '<div class="not-col block__header-results-short">
        <span class="total__results h6">Viewing ' . $total_post_page . ' of ' . $total_post . ' Results</span>
          <div class="sort__results">
            <select class="sort__results--select" name="sortBy" id="sortBy" data-type="filter">';
        foreach ($options as $key => $value) :
            $selected = $sortByRaw == $key ? 'selected' : '';
            $h .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        endforeach;
        $h .=   '</select>
          </div>
        </div>';

        return $h;
    }


    public function after_content()
    {
    }

    public function get_directory_loop($classname = '', $feature_posts = [], $posts_per_page = null)
    {
        $featured_posts_ids = is_array($feature_posts) ? array_column($feature_posts, 'post') : explode(',', $feature_posts);
        $feature_posts_attr = '';
        if (!empty($featured_posts_ids)) {
            $feature_posts_attr = ' data-feature_posts="' . implode(',', $featured_posts_ids) . '"';
        }
        $html = '<div class="' . $classname . '" id="data-container-directory"  data-post-type="' . $this->post_type . '"' . $feature_posts_attr . '>';
        $html .= $this->get_directory('', $feature_posts, $posts_per_page);
        $html .= '</div>';

        echo $html;
    }

    public function set_custom_args($args)
    {
        return $args;
    }

    public function get_directory($classname = '',  $feature_posts = [], $posts_per_page = null)
    {

        $this->show_sticky_post = get_field('show_sticky_post', 'option');

        $paged = 1;
        $tax_query = [];
        if (isset($_GET['actualPage'])) {
            $paged  = sanitize_text_field($_GET['actualPage']);
        }

        $args = array(
            'post_type'           => $this->post_type,
            'paged'               => (int) $paged,
        );

        if ($posts_per_page) {
            $args['posts_per_page'] = $posts_per_page;
        }

        if (empty($feature_posts) && isset($_GET['feature_posts'])) {
            $feature_posts  = sanitize_text_field($_GET['feature_posts']);
        }

        if (!empty($feature_posts)) {
            // Check if the feature_posts is an array or a string
            $featured_posts_ids = is_array($feature_posts) ? array_column($feature_posts, 'post') : explode(',', $feature_posts);
            // If it is a string, convert it to an array with this structure ['post' => $post_id]
            $feature_posts = is_array($feature_posts) ? $feature_posts : array_map(function ($post) {
                return ['post' => $post];
            }, explode(',', $feature_posts));

            // Exclude the featured posts from the main query
            $args['post__not_in'] = $featured_posts_ids;
            $args['posts_per_page'] = $posts_per_page - 1;
        }

        if (isset($_GET['search'])) {
            $args['s'] = $_GET['search'];
        }

        foreach ($this->taxonomies as $value) {
            if (isset($_GET[$value['name']])) {
                $taxesString = sanitize_text_field($_GET[$value['name']]);
                $taxes = explode(',', $taxesString);
                $tax_query[] = array(array(
                    'taxonomy' => $value['name'],
                    'field'    => 'slug',
                    'terms'    => $taxes,
                ));
            }
        }

        $args = wp_parse_args($this->directory, $args);
        $sortByRaw = isset($_GET['sortBy']) ? $_GET['sortBy'] : false;
        if ($sortByRaw) {
            $sortBy = explode('-', $_GET['sortBy']);
            if (count($sortBy) == 2) {
                $args['orderby'] = $sortBy[0];
                $args['order']   = $sortBy[1];
            }
        }

        $args = $this->set_custom_args($args);

        if (!empty($tax_query) && count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }

        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        $html = '';

        $the_query = new \WP_Query($args);

        $total_post = $the_query->found_posts;
        $total_post_page = $the_query->post_count;

        $html .= $this->previus_content($total_post, $total_post_page, $sortByRaw);

        $count = 0;
        $feature_count = 0;

        if ($the_query->have_posts()) :

            while ($the_query->have_posts()) : $the_query->the_post();
                require ELEVATION_THEME_DIR . '/lib/directory/view/single-' . $this->post_type . '.php';
                $count++;

                if (!empty($feature_posts) && $count == 6) {
                    require ELEVATION_THEME_DIR . '/lib/directory/view/feature-post.php';
                    $feature_count++;
                }

            endwhile;

            if (!empty($feature_posts) && isset($feature_posts[1]) && $count >= 12) {
                require ELEVATION_THEME_DIR . '/lib/directory/view/feature-post.php';
            }

            wp_reset_postdata();
            if ($total_post > $args['posts_per_page']) :
            $html .=  '
        <nav class="navigation pagination" aria-label="' . $this->post_type . '"> 
          <h2 class="screen-reader-text">Posts navigation</h2>
          <div class="nav-links">';
            $html .=
                paginate_links(array(
                    'total' => $the_query->max_num_pages,
                    'current' => max(1, $paged),
                ));
            $html .= '
          </div>
        </nav>';
        endif;

            $html .= $this->after_content();

        else :
            $html .= '<h5>No entries found. Please try another combination.</h5>';
        endif;

        return $html;
    }
}
