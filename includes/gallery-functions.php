<?php
if (!defined('ABSPATH')) exit;

/**
 * Obtiene las imágenes de una galería específica
 */
function mga_get_gallery($gallery_id) {
    $gallery = get_post_meta($gallery_id, '_mga_gallery_data', true);
    
    if (!$gallery) {
        return array(
            'images' => array(),
            'settings' => mga_get_default_settings()
        );
    }
    
    return wp_parse_args($gallery, array(
        'images' => array(),
        'settings' => mga_get_default_settings()
    ));
}

/**
 * Configuración por defecto de la galería
 */
function mga_get_default_settings() {
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

/**
 * Sanitiza los datos de la galería
 */
function mga_sanitize_gallery_data($data) {
    $sanitized = array(
        'images' => array(),
        'settings' => array()
    );
    
    if (!empty($data['images']) && is_array($data['images'])) {
        foreach ($data['images'] as $image) {
            $sanitized['images'][] = array(
                'id' => absint($image['id']),
                'title' => sanitize_text_field($image['title'] ?? ''),
                'author' => sanitize_text_field($image['author'] ?? ''),
                'description' => wp_kses_post($image['description'] ?? ''),
                'year' => sanitize_text_field($image['year'] ?? ''),
                'technique' => sanitize_text_field($image['technique'] ?? '')
            );
        }
    }
    
    if (!empty($data['settings']) && is_array($data['settings'])) {
        $sanitized['settings'] = array(
            'columns' => min(max(absint($data['settings']['columns'] ?? 4), 12),
            'gutter' => sanitize_text_field($data['settings']['gutter'] ?? '15px'),
            'lightbox' => !empty($data['settings']['lightbox']),
            'lazy_load' => !empty($data['settings']['lazy_load']),
            'hover_effect' => sanitize_key($data['settings']['hover_effect'] ?? 'fade'),
            'responsive_breakpoints' => array(
                'mobile' => absint($data['settings']['responsive_breakpoints']['mobile'] ?? 480),
                'tablet' => absint($data['settings']['responsive_breakpoints']['tablet'] ?? 768),
                'desktop' => absint($data['settings']['responsive_breakpoints']['desktop'] ?? 1024)
            )
        );
    }
    
    return $sanitized;
}

/**
 * Registra el custom post type para las galerías
 */
function mga_register_post_type() {
    $labels = array(
        'name' => __('Galerías de Arte', 'mi-galeria-arte'),
        'singular_name' => __('Galería de Arte', 'mi-galeria-arte'),
        'menu_name' => __('Galerías de Arte', 'mi-galeria-arte'),
        'name_admin_bar' => __('Galería de Arte', 'mi-galeria-arte'),
        'add_new' => __('Añadir Nueva', 'mi-galeria-arte'),
        'add_new_item' => __('Añadir Nueva Galería', 'mi-galeria-arte'),
        'new_item' => __('Nueva Galería', 'mi-galeria-arte'),
        'edit_item' => __('Editar Galería', 'mi-galeria-arte'),
        'view_item' => __('Ver Galería', 'mi-galeria-arte'),
        'all_items' => __('Todas las Galerías', 'mi-galeria-arte'),
        'search_items' => __('Buscar Galerías', 'mi-galeria-arte'),
        'not_found' => __('No se encontraron galerías', 'mi-galeria-arte'),
        'not_found_in_trash' => __('No hay galerías en la papelera', 'mi-galeria-arte')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'arte-galeria'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => array('title', 'thumbnail'),
        'show_in_rest' => true
    );

    register_post_type('arte_galeria', $args);
}
add_action('init', 'mga_register_post_type');

/**
 * Registra taxonomías para las galerías
 */
function mga_register_taxonomies() {
    // Taxonomía para artistas
    register_taxonomy(
        'artista',
        'arte_galeria',
        array(
            'label' => __('Artistas', 'mi-galeria-arte'),
            'rewrite' => array('slug' => 'artista'),
            'hierarchical' => false,
            'show_admin_column' => true
        )
    );
    
    // Taxonomía para técnicas
    register_taxonomy(
        'tecnica',
        'arte_galeria',
        array(
            'label' => __('Técnicas', 'mi-galeria-arte'),
            'rewrite' => array('slug' => 'tecnica'),
            'hierarchical' => true,
            'show_admin_column' => true
        )
    );
}
add_action('init', 'mga_register_taxonomies');