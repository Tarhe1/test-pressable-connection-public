<?php

namespace ElevationFramework\Lib\BlockLibrary\Controls;

class Styles
{
    protected static $_instance;

    public $paths_json = ELEVATION_THEME_DIR . '/lib/block-library/controls/json';

    public function __construct()
    {
        add_action('acf/input/admin_footer', [$this, 'admin_colors_footer']);
        add_action('acf/input/admin_head', [$this,  'acf_admin_head']);
    }

    public function get_theme_colors($mce4 = false)
    {
        // Get colors palette registerd in theme support
        $color_palette_json =  wp_remote_get(ELEVATION_THEME_DIR . '/theme.json');
        $color_palette = wp_remote_retrieve_body($color_palette_json);

        if (!empty($color_palette)) {
            // Get each 'color' value (hex code)
            $colors_json = json_decode($color_palette);
            $colors = $colors_json->settings->color->palette;

            if ($mce4) {
                $mce4_colors = [];
                foreach ($colors as $color) {
                    $mce4_colors = array_merge($mce4_colors, [str_replace('#', '', $color->color), $color->slug]);
                }

                return [json_encode($mce4_colors), count($colors)];
            } else {
                $color_codes = array_column($colors, 'color');
                return $color_codes;
            }
        } else {

            return false;
        }
    }

    public function admin_colors_footer()
    {
        // Get colors palette registerd in theme support
        $colors = $this->get_theme_colors();

        if ($colors) {
?>
            <script id="custom-acf-colors">
                if (window.acf) {
                    acf.add_filter('color_picker_args', function(args, $field) {
                        args.palettes = <?php echo json_encode($colors); ?>;
                        return args;
                    });
                    const reSizeColorPicker = () => {
                        jQuery('.iris-palette').css({
                            'height': '20px',
                            'width': '20px',
                            'margin-left': '',
                            'margin-right': '3px',
                            'margin-top': '3px'
                        });

                        //Nr of palette-areas? This is need if you have several
                        //palettes on one page (else the palette-box would have an
                        //unnessary large height)
                        var paletteareasCount = jQuery('.acf-color-picker').length ? jQuery('.acf-color-picker').length : 1;
                        var paletteCount = Math.ceil(jQuery('.iris-palette').length / paletteareasCount);

                        var paletteRowCount = Math.ceil(paletteCount / 8);

                        jQuery('.iris-strip, .iris-square').css('height', 'calc(100% - ' + paletteRowCount * 23 + 'px)');
                        jQuery('.iris-picker').css({
                            'height': 185 + (paletteRowCount * 23) + 'px',
                            'padding-bottom': '15px'
                        });
                    };


                    const colorPinckerInterval = setInterval(() => {
                        if (document.querySelector('.wp-picker-container')) {
                            reSizeColorPicker();
                            clearInterval(colorPinckerInterval);
                        }
                    }, 100);


                }
            </script>
        <?php
        }
    }

    public function acf_admin_head()
    {
        ?>
        <style>
            .acf-input .menu-preview {
                overflow: auto;
                height: 70vh;
                margin: 0;
            }

            .acf-input .menu-preview img {
                max-width: 100%;
            }

            .acf-field-message .acf-label {
                display: none;
            }

            .acf-range-wrap input[type="number"] {
                min-width: 4.5em !important;
            }

            .acf-block-body .acf-fields>.acf-field {
                width: 100%;
            }

            .acf-fields>.acf-tab-wrap {
                width: 100%;
            }

            .acf-editor-wrap iframe {
                min-height: 100px;
            }

            .ml-auto {
                margin-left: auto;
            }

            /** FF*/
            input[type="range"]::-moz-range-progress {
                background-color: rgba(102, 45, 145, 1);
            }

            input[type="range"]::-moz-range-track {
                background-color: rgba(0, 0, 0, 0.2);
            }

            /* IE*/
            input[type="range"]::-ms-fill-lower {
                background-color: rgba(102, 45, 145, 1);
            }

            input[type="range"]::-ms-fill-upper {
                background-color: rgba(0, 0, 0, 0.2);
            }

            .acf-switch {
                border-color: rgba(102, 45, 145, 1) !important;
            }

            .acf-button-group label:hover {
                border-color: rgba(102, 45, 145, 1);
                background-color: rgba(102, 45, 145, 0.1);
                transition: all 0.4s ease;
            }

            .acf-button-group label:not(.selected) {
                color: rgba(102, 45, 145, 1);
            }

            .acf-switch:hover .acf-switch-slider,
            .acf-switch.-focus .acf-switch-slider {
                border-color: rgba(102, 45, 145, 1);
            }

            .acf-switch:hover,
            .acf-switch.-focus {
                color: rgba(102, 45, 145, 1);
            }

            .acf-switch .acf-switch-on {
                text-shadow: rgba(102, 45, 145, 1) 0 1px 0;
            }

            .acf-switch.-on .acf-switch-slider {
                border-color: rgba(102, 45, 145, 1);
            }

            .wp-core-ui .button-primary:not(.save) {
                font-size: 17px;
            }

            .wp-core-ui .button-primary {
                background: rgba(102, 45, 145, 1) !important;
                border-color: rgba(102, 45, 145, 1) !important;
                color: #ffffff !important;
            }

            .wp-core-ui .button-primary.focus,
            .wp-core-ui .button-primary.hover,
            .wp-core-ui .button-primary:focus,
            .wp-core-ui .button-primary:hover {
                background: rgba(102, 45, 145, 0.8) !important;
                border-color: rgba(102, 45, 145, 0.8) !important;
                transition: all 0.2s ease;
            }

            .acf-switch.-on,
            .acf-button-group label.selected {
                background: rgba(102, 45, 145, 1);
                border-color: rgba(102, 45, 145, 1);
            }

            .acf-field-repeater table.acf-table tbody tr.acf-row>td.acf-row-handle.order:not(.acf-fields) {
                background: linear-gradient(0deg, rgba(0, 244, 237, 1) 0%, rgba(102, 45, 145, 1) 10%);
            }

            .acf-field-repeater table.acf-table tbody tr.acf-row>td.acf-fields>.acf-repeater .acf-input .acf-table table.acf-repeater tbody .acf-row-number.order {
                background: linear-gradient(0deg, rgba(0, 0, 0, 1) 0%, rgba(102, 45, 145, 1) 10%);
            }

            .acf-row-number {
                color: #fff;
                font-size: 30px;
                font-weight: bold;
            }

            .acf-button-group {
                flex-wrap: wrap;
                gap: 8px;
            }

            .acf-button-group label {
                flex: 0;
            }

            @media (min-width: 1620px) {
                .interface-complementary-area {
                    width: 450px;
                }

                .block-editor .edit-post-sidebar .acf-field.acf-accordion .acf-input.acf-accordion-content .acf-fields>.acf-field:first-child {
                    border-top-style: solid;
                }

                .block-editor .edit-post-sidebar .acf-field.acf-accordion .acf-input.acf-accordion-content .acf-fields>.acf-field {
                    margin: 0;
                    padding: 15px;
                    border-width: 1px !important;
                }
            }

            .acf-block-component:not(.acf-block-panel) .acf-block-fields {
                display: flex;
                flex-wrap: wrap;
            }

            .acf-block-panel .acf-block-fields.acf-fields {
                flex-direction: column;
            }

            .acf-block-component.acf-block-body .acf-field.acf-accordion .acf-input.acf-accordion-content>.acf-fields {
                display: flex;
                flex-wrap: wrap;
            }

            #acf-field-group-options .field-group-settings-tab {
                flex: 1;
            }

            .flex-3 {
                flex: 1 0 calc(100%/3);
                width: calc(100%/3) !important;
            }

            .flex-2 {
                flex: 1 0 calc(100%/2);
                width: calc(100%/2) !important;
            }

            .flex-1 {
                flex: 1;
            }
        </style>
<?php
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
