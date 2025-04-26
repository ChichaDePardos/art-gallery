<?php
/**
 * Plantilla para mostrar una obra de arte individual
 * 
 * @param array $artwork Datos de la obra
 * @param array $atts Atributos del shortcode
 */

if (!defined('ABSPATH')) exit;

$size = sanitize_key($atts['size'] ?? 'medium');
$show_title = (bool)($atts['show_title'] ?? true);
$show_author = (bool)($atts['show_author'] ?? true);
$show_description = (bool)($atts['show_description'] ?? false);
?>

<div class="mga-single-artwork">
    <figure class="mga-artwork-figure">
        <?php echo wp_get_attachment_image($artwork['id'], $size, false, array(
            'class' => 'mga-artwork-image',
            'loading' => 'lazy'
        )); ?>
        
        <figcaption class="mga-artwork-caption">
            <?php if ($show_title && !empty($artwork['title'])): ?>
                <h3 class="mga-artwork-title"><?php echo esc_html($artwork['title']); ?></h3>
            <?php endif; ?>
            
            <?php if ($show_author && !empty($artwork['author'])): ?>
                <p class="mga-artwork-author">
                    <?php echo esc_html__('Por:', 'mi-galeria-arte') . ' ' . esc_html($artwork['author']); ?>
                    
                    <?php if (!empty($artwork['year'])): ?>
                        <span class="mga-artwork-year">(<?php echo esc_html($artwork['year']); ?>)</span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            
            <?php if ($show_description && !empty($artwork['description'])): ?>
                <div class="mga-artwork-description">
                    <?php echo wpautop(esc_html($artwork['description'])); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($artwork['technique'])): ?>
                <p class="mga-artwork-technique">
                    <strong><?php esc_html_e('Técnica:', 'mi-galeria-arte'); ?></strong>
                    <?php echo esc_html($artwork['technique']); ?>
                </p>
            <?php endif; ?>
        </figcaption>
    </figure>
</div>