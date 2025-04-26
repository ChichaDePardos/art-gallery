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
    'lazy_load' => true
);

$args = wp_parse_args($args, $defaults);
$images = $args['images'];
?>

<div class="mga-gallery-container"
     data-columns="<?php echo esc_attr($args['columns']); ?>"
     data-gutter="<?php echo esc_attr($args['gutter']); ?>">
     
    <?php if (empty($images)): ?>
        <p class="mga-no-images"><?php esc_html_e('No hay imágenes para mostrar.', 'mi-galeria-arte'); ?></p>
    <?php else: ?>
        <div class="mga-gallery-sizer"></div>
        
        <?php foreach ($images as $image): 
            $img_data = wp_get_attachment_metadata($image['id']);
            $srcset = wp_get_attachment_image_srcset($image['id'], 'large');
            $sizes = wp_get_attachment_image_sizes($image['id'], 'large');
            $alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
        ?>
            <div class="mga-gallery-item">
                <figure>
                    <?php if ($args['lightbox']): ?>
                        <a href="<?php echo esc_url(wp_get_attachment_image_url($image['id'], 'full')); ?>" 
                           class="mga-lightbox-link"
                           data-lightbox="mga-gallery"
                           data-title="<?php echo esc_attr($image['title'] . ' - ' . $image['author']); ?>"
                           aria-label="<?php echo esc_attr(sprintf(__('Ver %s en tamaño completo', 'mi-galeria-arte'), $image['title'])); ?>">
                    <?php endif; ?>
                    
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($image['id'], 'medium_large')); ?>"
                         <?php if ($args['lazy_load']): ?>loading="lazy"<?php endif; ?>
                         srcset="<?php echo esc_attr($srcset); ?>"
                         sizes="<?php echo esc_attr($sizes); ?>"
                         alt="<?php echo esc_attr($alt ?: $image['title']); ?>"
                         width="<?php echo esc_attr($img_data['width'] ?? ''); ?>"
                         height="<?php echo esc_attr($img_data['height'] ?? ''); ?>">
                    
                    <?php if ($args['lightbox']): ?>
                        </a>
                    <?php endif; ?>
                    
                    <figcaption class="mga-overlay">
                        <div class="mga-overlay-content">
                            <h3 class="mga-title"><?php echo esc_html($image['title']); ?></h3>
                            <p class="mga-author"><?php echo esc_html($image['author']); ?></p>
                        </div>
                    </figcaption>
                </figure>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>