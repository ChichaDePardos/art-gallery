<?php
class MGA_Shortcodes {

    public function __construct() {
        // Registrar shortcodes en el constructor
        add_shortcode('MGA_galeria_arte', array($this, 'gallery_shortcode'));
        add_shortcode('MGA_obra_arte', array($this, 'artwork_shortcode'));
    }

    public function gallery_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'columns' => 0,
            'gutter' => '',
            'lightbox' => null,
            'lazy_load' => null,
            'hover_effect' => ''
        ), $atts, 'MGA_galeria_arte');
        
        $gallery_id = absint($atts['id']);
        
        if (!$gallery_id) {
            return '<p class="MGA-error">' . esc_html__('Debes especificar un ID de galería válido', 'MGA-mi-galeria-arte') . '</p>';
        }
        
        $gallery = MGA_Gallery_Functions::get_gallery($gallery_id);
        
        // Sobreescribir ajustes con atributos del shortcode
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
        
        if (!empty($atts['hover_effect'])) {
            $gallery['settings']['hover_effect'] = sanitize_key($atts['hover_effect']);
        }
        
        ob_start();
        include MGA_PLUGIN_DIR . 'templates/MGA-gallery-template.php';
        return ob_get_clean();
    }

    public function artwork_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'size' => 'medium',
            'show_title' => true,
            'show_author' => true,
            'show_description' => false,
            'show_year' => false,
            'show_technique' => false
        ), $atts, 'MGA_obra_arte');
        
        $attachment_id = absint($atts['id']);
        
        if (!$attachment_id) {
            return '<p class="MGA-error">' . esc_html__('Debes especificar un ID de imagen válido', 'MGA-mi-galeria-arte') . '</p>';
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
        $metadata = get_post_meta($attachment_id, '_MGA_artwork_metadata', true);
        
        if ($metadata) {
            $artwork['author'] = $metadata['author'] ?? '';
            $artwork['year'] = $metadata['year'] ?? '';
            $artwork['technique'] = $metadata['technique'] ?? '';
        }
        
        // Convertir atributos booleanos
        $show_options = array(
            'title' => filter_var($atts['show_title'], FILTER_VALIDATE_BOOLEAN),
            'author' => filter_var($atts['show_author'], FILTER_VALIDATE_BOOLEAN),
            'description' => filter_var($atts['show_description'], FILTER_VALIDATE_BOOLEAN),
            'year' => filter_var($atts['show_year'], FILTER_VALIDATE_BOOLEAN),
            'technique' => filter_var($atts['show_technique'], FILTER_VALIDATE_BOOLEAN)
        );
        
        ob_start();
        include MGA_PLUGIN_DIR . 'templates/MGA-single-artwork.php';
        return ob_get_clean();
    }
}