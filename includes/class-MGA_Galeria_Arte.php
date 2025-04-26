<?php
class MGA_Galeria_Arte {
    
    private $loader;
    
    public function __construct() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    private function load_dependencies() {
        require_once MGA_PLUGIN_DIR . 'includes/class-MGA_Gallery_Functions.php';
        require_once MGA_PLUGIN_DIR . 'includes/class-MGA_Admin_Settings.php';
        require_once MGA_PLUGIN_DIR . 'includes/class-MGA_Shortcodes.php';
        require_once MGA_PLUGIN_DIR . 'includes/class-MGA_Ajax_Handlers.php';
    }
    
    private function set_locale() {
        load_plugin_textdomain(
            'MGA-mi-galeria-arte',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
    
    private function define_admin_hooks() {
        $admin = new MGA_Admin_Settings();
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_scripts'));
    }
    
    private function define_public_hooks() {
        $public = new MGA_Gallery_Functions();
        add_action('wp_enqueue_scripts', array($public, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($public, 'enqueue_scripts'));
        
        $shortcodes = new MGA_Shortcodes();
        add_shortcode('MGA_galeria_arte', array($shortcodes, 'gallery_shortcode'));
        add_shortcode('MGA_obra_arte', array($shortcodes, 'artwork_shortcode'));
    }
    
    public function run() {
        // Inicializar componentes
        new MGA_Ajax_Handlers();
    }
    
    public static function activate() {
        // Crear tablas personalizadas si es necesario
        MGA_Gallery_Functions::create_tables();
        flush_rewrite_rules();
    }
    
    public static function deactivate() {
        flush_rewrite_rules();
    }
}