<?php
/**
 * Plantilla de galería responsive
 * 
 * @param array $args Argumentos de la galería
 */

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

$defaults = array(
    'images' => array(),
    'columns' => 4,
    'gutter' => '15px',
    'lightbox' => true,
    'lazy_load' => true,
    'hover_effect' => 'fade'
);

$args = wp_parse_args($args, $defaults);
$images = $args['images'];
$settings = $args;
$hover_class = 'MGA-hover-' . sanitize_html_class($settings['hover_effect']);
?>

<div class="MGA-gallery-container <?php echo esc_attr($hover_class); ?>"
     data-columns="<?php echo esc_attr($settings['columns']); ?>"
     data-gutter="<?php echo esc_attr($settings['gutter']); ?>"
     style="--MGA-columns: <?php echo esc_attr($settings['columns']); ?>;
            --MGA-gutter: <?php echo esc_attr($settings['gutter']); ?>">
     
    <?php if (empty($images)): ?>
        <p class="MGA-no-images"><?php esc_html_e('No hay imágenes para mostrar.', 'MGA-mi-galeria-arte'); ?></p>
    <?php else: ?>
        <div class="MGA-gallery-sizer"></div>
        
        <?php foreach ($images as $index => $image): 
            $img_data = wp_get_attachment_metadata($image['id']);
            $srcset = wp_get_attachment_image_srcset($image['id'], 'large');
            $sizes = wp_get_attachment_image_sizes($image['id'], 'large');
            $alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true) ?: $image['title'];
            $lightbox_url = $settings['lightbox'] ? wp_get_attachment_image_url($image['id'], 'full') : '';
        ?>
            <div class="MGA-gallery-item">
                <figure class="MGA-artwork-figure">
                    <?php if ($settings['lightbox']): ?>
                        <a href="<?php echo esc_url($lightbox_url); ?>" 
                           class="MGA-lightbox-link"
                           data-MGA-lightbox="gallery-<?php echo esc_attr($gallery_id); ?>"
                           data-MGA-title="<?php echo esc_attr($image['title']); ?>"
                           data-MGA-author="<?php echo esc_attr($image['author']); ?>"
                           aria-label="<?php echo esc_attr(sprintf(__('Ver %s en tamaño completo', 'MGA-mi-galeria-arte'), $image['title'])); ?>">
                    <?php endif; ?>
                    
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($image['id'], 'medium_large')); ?>"
                         <?php if ($settings['lazy_load']): ?>loading="lazy"<?php endif; ?>
                         srcset="<?php echo esc_attr($srcset); ?>"
                         sizes="<?php echo esc_attr($sizes); ?>"
                         alt="<?php echo esc_attr($alt); ?>"
                         width="<?php echo esc_attr($img_data['width'] ?? ''); ?>"
                         height="<?php echo esc_attr($img_data['height'] ?? ''); ?>"
                         class="MGA-artwork-image">
                    
                    <?php if ($settings['lightbox']): ?>
                        </a>
                    <?php endif; ?>
                    
                    <figcaption class="MGA-overlay">
                        <div class="MGA-overlay-content">
                            <h3 class="MGA-title"><?php echo esc_html($image['title']); ?></h3>
                            <p class="MGA-author"><?php echo esc_html($image['author']); ?></p>
                            
                            <?php if (!empty($image['year']) || !empty($image['technique'])): ?>
                                <div class="MGA-meta">
                                    <?php if (!empty($image['year'])): ?>
                                        <span class="MGA-year"><?php echo esc_html($image['year']); ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($image['technique'])): ?>
                                        <span class="MGA-technique"><?php echo esc_html($image['technique']); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </figcaption>
                </figure>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>