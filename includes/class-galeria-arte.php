<?php
class Galeria_Arte {
    public function __construct() {
        // Constructor
    }

    public function run() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once MGA_PLUGIN_DIR . 'includes/gallery-functions.php';
        require_once MGA_PLUGIN_DIR . 'includes/admin-settings.php';
        require_once MGA_PLUGIN_DIR . 'includes/shortcodes.php';
        require_once MGA_PLUGIN_DIR . 'includes/ajax-handlers.php';
    }

    private function set_locale() {
        load_plugin_textdomain(
            'mi-galeria-arte',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    private function define_admin_hooks() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        // Otros hooks para admin
    }

    private function define_public_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));
        // Otros hooks para frontend
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_style(
            'mga-admin-css',
            MGA_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            MGA_VERSION,
            'all'
        );
        
        wp_enqueue_script(
            'mga-admin-js',
            MGA_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'jquery-ui-sortable'),
            MGA_VERSION,
            true
        );
    }

    public function enqueue_public_scripts() {
        // CSS responsivo
        wp_enqueue_style(
            'mga-styles',
            MGA_PLUGIN_URL . 'assets/css/styles.css',
            array(),
            MGA_VERSION,
            'all'
        );
        
        // Media queries para diferentes tamaños
        wp_enqueue_style(
            'mga-responsive',
            MGA_PLUGIN_URL . 'assets/css/responsive.css',
            array('mga-styles'),
            MGA_VERSION,
            'screen and (max-width: 1200px)'
        );
        
        // Scripts responsivos
        wp_enqueue_script(
            'mga-scripts',
            MGA_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery', 'imagesloaded'),
            MGA_VERSION,
            true
        );
        
        // Pasar variables a JS para responsive
        wp_localize_script('mga-scripts', 'mga_vars', array(
            'breakpoints' => array(
                'mobile' => 480,
                'tablet' => 768,
                'desktop' => 1024
            )
        ));
    }
}