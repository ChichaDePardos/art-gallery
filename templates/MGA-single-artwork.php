<?php
/**
 * Plantilla para mostrar una obra de arte individual
 * 
 * @param array $artwork Datos de la obra
 * @param array $show_options Qué elementos mostrar
 */

if (!defined('ABSPATH')) exit;

$size = sanitize_key($atts['size'] ?? 'medium');
$show = $show_options;
?>

<div class="MGA-single-artwork">
    <figure class="MGA-artwork-figure">
        <?php echo wp_get_attachment_image($artwork['id'], $size, false, array(
            'class' => 'MGA-artwork-image',
            'loading' => 'lazy',
            'alt' => $artwork['title']
        )); ?>
        
        <figcaption class="MGA-artwork-caption">
            <?php if ($show['title'] && !empty($artwork['title'])): ?>
                <h3 class="MGA-artwork-title"><?php echo esc_html($artwork['title']); ?></h3>
            <?php endif; ?>
            
            <?php if ($show['author'] && !empty($artwork['author'])): ?>
                <p class="MGA-artwork-author">
                    <?php echo esc_html__('Por:', 'MGA-mi-galeria-arte') . ' ' . esc_html($artwork['author']); ?>
                    
                    <?php if ($show['year'] && !empty($artwork['year'])): ?>
                        <span class="MGA-artwork-year">(<?php echo esc_html($artwork['year']); ?>)</span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            
            <?php if ($show['description'] && !empty($artwork['description'])): ?>
                <div class="MGA-artwork-description">
                    <?php echo wpautop(esc_html($artwork['description'])); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($show['technique'] && !empty($artwork['technique'])): ?>
                <p class="MGA-artwork-technique">
                    <strong><?php esc_html_e('Técnica:', 'MGA-mi-galeria-arte'); ?></strong>
                    <?php echo esc_html($artwork['technique']); ?>
                </p>
            <?php endif; ?>
        </figcaption>
    </figure>
</div>