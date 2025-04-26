<?php
/**
 * Plantilla de galería Masonry
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

<div class="MGA-masonry-gallery <?php echo esc_attr($hover_class); ?>"
     data-columns="<?php echo esc_attr($settings['columns']); ?>"
     data-gutter="<?php echo esc_attr($settings['gutter']); ?>">
     
    <?php if (empty($images)): ?>
        <p class="MGA-no-images"><?php esc_html_e('No hay imágenes para mostrar.', 'MGA-mi-galeria-arte'); ?></p>
    <?php else: ?>
        <div class="MGA-grid-sizer"></div>
        
        <?php foreach ($images as $index => $image): 
            $img_data = wp_get_attachment_metadata($image['id']);
            $srcset = wp_get_attachment_image_srcset($image['id'], 'large');
            $sizes = wp_get_attachment_image_sizes($image['id'], 'large');
            $alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true) ?: $image['title'];
            $lightbox_url = $settings['lightbox'] ? wp_get_attachment_image_url($image['id'], 'full') : '';
            
            // Obtener relación de aspecto para el item
            $aspect_ratio = ($img_data['height'] > 0) ? $img_data['width'] / $img_data['height'] : 1;
            $item_class = 'MGA-masonry-item';
            $item_class .= $aspect_ratio > 1.5 ? ' MGA-wide' : ($aspect_ratio < 0.8 ? ' MGA-tall' : '');
        ?>
            <div class="<?php echo esc_attr($item_class); ?>">
                <div class="MGA-masonry-content">
                    <?php if ($settings['lightbox']): ?>
                        <a href="<?php echo esc_url($lightbox_url); ?>" 
                           class="MGA-lightbox-link"
                           data-MGA-lightbox="masonry-<?php echo esc_attr($gallery_id); ?>"
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
                         class="MGA-masonry-image">
                    
                    <?php if ($settings['lightbox']): ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="MGA-masonry-overlay">
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
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.MGA-masonry-gallery {
    margin: 0 auto;
    max-width: 1400px;
}

.MGA-masonry-gallery:after {
    content: '';
    display: block;
    clear: both;
}

.MGA-grid-sizer,
.MGA-masonry-item {
    width: calc(25% - <?php echo esc_attr($settings['gutter']); ?>);
    margin-bottom: <?php echo esc_attr($settings['gutter']); ?>;
}

.MGA-masonry-item.MGA-wide {
    width: calc(50% - <?php echo esc_attr($settings['gutter']); ?>);
}

.MGA-masonry-item.MGA-tall {
    height: auto;
}

.MGA-masonry-content {
    position: relative;
    overflow: hidden;
    border-radius: 5px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    height: 100%;
}

.MGA-masonry-image {
    display: block;
    width: 100%;
    height: auto;
    transition: transform 0.3s ease;
}

.MGA-masonry-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 15px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.MGA-masonry-item:hover .MGA-masonry-overlay {
    transform: translateY(0);
}

.MGA-masonry-item:hover .MGA-masonry-image {
    transform: scale(1.05);
}

@media (max-width: 1024px) {
    .MGA-grid-sizer,
    .MGA-masonry-item {
        width: calc(33.333% - <?php echo esc_attr($settings['gutter']); ?>);
    }
    
    .MGA-masonry-item.MGA-wide {
        width: calc(66.666% - <?php echo esc_attr($settings['gutter']); ?>);
    }
}

@media (max-width: 768px) {
    .MGA-grid-sizer,
    .MGA-masonry-item {
        width: calc(50% - <?php echo esc_attr($settings['gutter']); ?>);
    }
    
    .MGA-masonry-item.MGA-wide {
        width: calc(100% - <?php echo esc_attr($settings['gutter']); ?>);
    }
}

@media (max-width: 480px) {
    .MGA-grid-sizer,
    .MGA-masonry-item {
        width: calc(100% - <?php echo esc_attr($settings['gutter']); ?>);
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Inicializar Masonry solo si la librería está disponible
    if (typeof Masonry !== 'undefined') {
        var $grid = $('.MGA-masonry-gallery').masonry({
            itemSelector: '.MGA-masonry-item',
            columnWidth: '.MGA-grid-sizer',
            percentPosition: true,
            gutter: '<?php echo esc_js($settings["gutter"]); ?>',
            transitionDuration: '0.4s'
        });
        
        // Hacer que las imágenes carguen antes de redibujar
        $grid.imagesLoaded().progress(function() {
            $grid.masonry('layout');
        });
    }
});
</script>