<?php
/**
 * Plugin Name: Mi Galería de Arte
 * Description: Galería de arte responsive con mosaicos y efectos hover
 * Version: 1.0
 * Author: Tu Nombre
 * Text Domain: MGA-mi-galeria-arte
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

// Definir constantes
define('MGA_VERSION', '1.0');
define('MGA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MGA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MGA_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Cargar archivos principales
require_once MGA_PLUGIN_DIR . 'includes/class-MGA_Galeria_Arte.php';
require_once MGA_PLUGIN_DIR . 'includes/class-MGA_Compatibility.php';

// Iniciar el plugin
function MGA_run_plugin() {
    $plugin = new MGA_Galeria_Arte();
    $plugin->run();
    
    // Iniciar compatibilidad
    new MGA_Compatibility();
}
add_action('plugins_loaded', 'MGA_run_plugin');

// Hook de activación
register_activation_hook(__FILE__, array('MGA_Galeria_Arte', 'activate'));

// Hook de desactivación
register_deactivation_hook(__FILE__, array('MGA_Galeria_Arte', 'deactivate'));