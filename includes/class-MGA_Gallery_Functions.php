<?php
class MGA_Gallery_Functions {
    
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'MGA_galleries';
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            gallery_data longtext NOT NULL,
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
    
    public function enqueue_styles() {
        wp_enqueue_style(
            'MGA-styles',
            MGA_PLUGIN_URL . 'assets/css/MGA-styles.css',
            array(),
            MGA_VERSION,
            'all'
        );
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            'MGA-scripts',
            MGA_PLUGIN_URL . 'assets/js/MGA-frontend.js',
            array('jquery', 'imagesloaded'),
            MGA_VERSION,
            true
        );
        
        wp_localize_script('MGA-scripts', 'MGA_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('MGA_ajax_nonce')
        ));
    }
    
    public static function get_gallery($gallery_id) {
        // Implementación mejorada para evitar conflictos
        $gallery = get_post_meta($gallery_id, '_MGA_gallery_data', true);
        
        if (!$gallery) {
            return array(
                'images' => array(),
                'settings' => self::get_default_settings()
            );
        }
        
        return wp_parse_args($gallery, array(
            'images' => array(),
            'settings' => self::get_default_settings()
        ));
    }
    
    public static function get_default_settings() {
        return array(
            'columns' => 4,
            'gutter' => '15px',
            'lightbox' => true,
            'lazy_load' => true,
            'hover_effect' => 'fade',
            'responsive_breakpoints' => array(
                'mobile' => 480,
                'tablet' => 768,
                'desktop' => 1024
            )
        );
    }
}