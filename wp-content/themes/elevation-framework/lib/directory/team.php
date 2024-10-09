<?php

namespace ElevationFramework\Lib\Directory;

class Team extends Directory
{
    protected static $_instance;
    public $post_type = 'team';
    public $label = 'Teams';

    public $labels = array();
    public $directory = [
        'posts_per_page' =>  12,
    ];

    public $taxonomies = [
        [
            'name' => 'team_categories', // se agrega el prefix de resource
            'label' => 'Team Category',
            'plural_label' => 'Teams Category',
            'type' => 'custom',
            'in_modal' => false /// esto es para que no aparezca en el modal
        ]
    ];

    public function __construct()
    {
        parent::__construct();
    }


    public function previus_content($total_post, $total_post_page, $sortByRaw)
    {
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
