<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Eliminar opciones
delete_option('MGA_settings');

// Eliminar custom post types y taxonomías
function MGA_unregister_post_type() {
    global $wpdb;
    
    // Eliminar todas las galerías
    $galleries = get_posts(array(
        'post_type' => 'MGA_arte_galeria',
        'numberposts' => -1,
        'post_status' => 'any'
    ));
    
    foreach ($galleries as $gallery) {
        wp_delete_post($gallery->ID, true);
    }
    
    // Eliminar meta datos
    $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_MGA_%'");
    
    // Eliminar taxonomías
    $wpdb->query("DELETE FROM $wpdb->terms WHERE term_id IN (SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = 'MGA_artista' OR taxonomy = 'MGA_tecnica')");
    $wpdb->query("DELETE FROM $wpdb->term_taxonomy WHERE taxonomy = 'MGA_artista' OR taxonomy = 'MGA_tecnica'");
    $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM $wpdb->term_taxonomy)");
    
    // Eliminar tablas personalizadas
    $table_name = $wpdb->prefix . 'MGA_galleries';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

MGA_unregister_post_type();

// Limpiar caché
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}