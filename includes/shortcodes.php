<?php
if (!defined('ABSPATH')) exit;

/**
 * Shortcode principal para mostrar la galería
 */
function mga_gallery_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
        'columns' => 0,
        'gutter' => '',
        'lightbox' => null,
        'lazy_load' => null
    ), $atts, 'mi_galeria_arte');
    
    $gallery_id = absint($atts['id']);
    
    if (!$gallery_id) {
        return '<p class="mga-error">' . esc_html__('Debes especificar un ID de galería válido', 'mi-galeria-arte') . '</p>';
    }
    
    $gallery = mga_get_gallery($gallery_id);
    
    // Override settings with shortcode attributes
    if ($atts['columns'] > 0) {
        $gallery['settings']['columns'] = min(absint($atts['columns']), 6);
    }
    
    if (!empty($atts['gutter'])) {
        $gallery['settings']['gutter'] = sanitize_text_field($atts['gutter']);
    }
    
    if ($atts['lightbox'] !== null) {
        $gallery['settings']['lightbox'] = (bool)$atts['lightbox'];
    }
    
    if ($atts['lazy_load'] !== null) {
        $gallery['settings']['lazy_load'] = (bool)$atts['lazy_load'];
    }
    
    ob_start();
    include MGA_PLUGIN_DIR . 'templates/gallery-template.php';
    return ob_get_clean();
}
add_shortcode('mi_galeria_arte', 'mga_gallery_shortcode');

/**
 * Shortcode para mostrar una obra individual
 */
function mga_artwork_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
        'size' => 'medium',
        'show_title' => true,
        'show_author' => true,
        'show_description' => false
    ), $atts, 'mga_obra_arte');
    
    $attachment_id = absint($atts['id']);
    
    if (!$attachment_id) {
        return '<p class="mga-error">' . esc_html__('Debes especificar un ID de imagen válido', 'mi-galeria-arte') . '</p>';
    }
    
    $artwork = array(
        'id' => $attachment_id,
        'title' => get_the_title($attachment_id),
        'author' => '',
        'description' => wp_get_attachment_caption($attachment_id),
        'year' => '',
        'technique' => ''
    );
    
    // Obtener metadatos adicionales si están guardados
    $metadata = get_post_meta($attachment_id, '_mga_artwork_metadata', true);
    
    if ($metadata) {
        $artwork['author'] = $metadata['author'] ?? '';
        $artwork['year'] = $metadata['year'] ?? '';
        $artwork['technique'] = $metadata['technique'] ?? '';
    }
    
    ob_start();
    include MGA_PLUGIN_DIR . 'templates/single-artwork.php';
    return ob_get_clean();
}
add_shortcode('mga_obra_arte', 'mga_artwork_shortcode');