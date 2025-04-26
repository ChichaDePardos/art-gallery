<?php
class MGA_Compatibility {
    
    public function __construct() {
        $this->check_for_conflicts();
        $this->add_integrations();
    }
    
    private function check_for_conflicts() {
        // Detectar plugins conflictivos
        add_action('admin_init', array($this, 'detect_conflicting_plugins'));
    }
    
    public function detect_conflicting_plugins() {
        $conflicting_plugins = array(
            'modula-gallery/Modula.php' => 'Modula Gallery'
        );
        
        $active_plugins = get_option('active_plugins');
        $conflicts = array();
        
        foreach ($conflicting_plugins as $plugin => $name) {
            if (in_array($plugin, $active_plugins)) {
                $conflicts[] = $name;
            }
        }
        
        if (!empty($conflicts)) {
            add_action('admin_notices', function() use ($conflicts) {
                echo '<div class="notice notice-warning">';
                echo '<p>' . sprintf(
                    __('Mi Galería de Arte puede tener conflictos con los siguientes plugins: %s. Por favor verifica la compatibilidad.', 'MGA-mi-galeria-arte'),
                    implode(', ', $conflicts)
                ) . '</p>';
                echo '</div>';
            });
        }
    }
    
    private function add_integrations() {
        // Integración con Elementor
        add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widget'));
        
        // Integración con WooCommerce
        if (class_exists('WooCommerce')) {
            add_filter('woocommerce_product_data_tabs', array($this, 'add_woocommerce_tab'));
        }
    }
    
    public function register_elementor_widget() {
        require_once MGA_PLUGIN_DIR . 'includes/widgets/class-MGA_Elementor_Widget.php';
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new MGA_Elementor_Widget());
    }
    
    public function add_woocommerce_tab($tabs) {
        $tabs['MGA_gallery'] = array(
            'label'    => __('Galería de Arte', 'MGA-mi-galeria-arte'),
            'target'   => 'MGA_gallery_data',
            'class'    => array(),
            'priority' => 80,
        );
        return $tabs;
    }
}