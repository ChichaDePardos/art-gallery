<?php
/**
 * Plugin Name: Mi Galería de Arte
 * Description: Galería de arte responsive con mosaicos y efectos hover
 * Version: 1.0
 * Author: Tu Nombre
 * Text Domain: mi-galeria-arte
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

// Definir constantes del plugin
define('MGA_VERSION', '1.0');
define('MGA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MGA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MGA_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Cargar archivos principales
require_once MGA_PLUGIN_DIR . 'includes/class-galeria-arte.php';

// Iniciar el plugin
function run_mi_galeria_arte() {
    $plugin = new Galeria_Arte();
    $plugin->run();
}
run_mi_galeria_arte();