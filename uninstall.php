<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Eliminar opciones
delete_option('mga_settings');

// Eliminar custom post types y taxonomías
function mga_unregister_post_type() {
    global $wpdb;
    
    // Eliminar todas las galerías
    $galleries = get_posts(array(
        'post_type' => 'arte_galeria',
        'numberposts' => -1,
        'post_status' => 'any'
    ));
    
    foreach ($galleries as $gallery) {
        wp_delete_post($gallery->ID, true);
    }
    
    // Eliminar meta datos
    $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_mga_%'");
    
    // Eliminar taxonomías
    $wpdb->query("DELETE FROM $wpdb->terms WHERE term_id IN (SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = 'artista' OR taxonomy = 'tecnica')");
    $wpdb->query("DELETE FROM $wpdb->term_taxonomy WHERE taxonomy = 'artista' OR taxonomy = 'tecnica'");
    $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM $wpdb->term_taxonomy)");
}

mga_unregister_post_type();