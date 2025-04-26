<?php
if (!defined('ABSPATH')) exit;

/**
 * Añade meta boxes al CPT de galerías
 */
function mga_add_meta_boxes() {
    add_meta_box(
        'mga-gallery-builder',
        __('Constructor de Galería', 'mi-galeria-arte'),
        'mga_render_gallery_builder',
        'arte_galeria',
        'normal',
        'high'
    );
    
    add_meta_box(
        'mga-gallery-settings',
        __('Configuración de la Galería', 'mi-galeria-arte'),
        'mga_render_gallery_settings',
        'arte_galeria',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'mga_add_meta_boxes');

/**
 * Renderiza el constructor de galerías
 */
function mga_render_gallery_builder($post) {
    wp_nonce_field('mga_save_gallery_data', 'mga_gallery_nonce');
    
    $gallery = mga_get_gallery($post->ID);
    ?>
    <div class="mga-gallery-builder-container">
        <div class="mga-uploader-container">
            <button type="button" class="button button-primary mga-add-images">
                <?php esc_html_e('Añadir Imágenes', 'mi-galeria-arte'); ?>
            </button>
            <p class="description">
                <?php esc_html_e('Selecciona las imágenes para tu galería', 'mi-galeria-arte'); ?>
            </p>
        </div>
        
        <div class="mga-gallery-preview-container">
            <ul class="mga-gallery-preview sortable">
                <?php foreach ($gallery['images'] as $index => $image): ?>
                    <li class="mga-gallery-item" data-id="<?php echo esc_attr($image['id']); ?>">
                        <input type="hidden" name="mga_gallery[images][<?php echo $index; ?>][id]" value="<?php echo esc_attr($image['id']); ?>">
                        
                        <div class="mga-image-preview">
                            <?php echo wp_get_attachment_image($image['id'], 'thumbnail'); ?>
                            <button type="button" class="mga-remove-image dashicons dashicons-trash"></button>
                        </div>
                        
                        <div class="mga-image-details">
                            <div class="mga-form-group">
                                <label><?php esc_html_e('Título', 'mi-galeria-arte'); ?></label>
                                <input type="text" name="mga_gallery[images][<?php echo $index; ?>][title]" 
                                       value="<?php echo esc_attr($image['title']); ?>" 
                                       placeholder="<?php esc_attr_e('Título de la obra', 'mi-galeria-arte'); ?>">
                            </div>
                            
                            <div class="mga-form-group">
                                <label><?php esc_html_e('Artista', 'mi-galeria-arte'); ?></label>
                                <input type="text" name="mga_gallery[images][<?php echo $index; ?>][author]" 
                                       value="<?php echo esc_attr($image['author']); ?>" 
                                       placeholder="<?php esc_attr_e('Nombre del artista', 'mi-galeria-arte'); ?>">
                            </div>
                            
                            <div class="mga-form-group">
                                <label><?php esc_html_e('Año', 'mi-galeria-arte'); ?></label>
                                <input type="text" name="mga_gallery[images][<?php echo $index; ?>][year]" 
                                       value="<?php echo esc_attr($image['year']); ?>" 
                                       placeholder="<?php esc_attr_e('Año de creación', 'mi-galeria-arte'); ?>">
                            </div>
                            
                            <div class="mga-form-group">
                                <label><?php esc_html_e('Técnica', 'mi-galeria-arte'); ?></label>
                                <input type="text" name="mga_gallery[images][<?php echo $index; ?>][technique]" 
                                       value="<?php echo esc_attr($image['technique']); ?>" 
                                       placeholder="<?php esc_attr_e('Técnica utilizada', 'mi-galeria-arte'); ?>">
                            </div>
                            
                            <div class="mga-form-group">
                                <label><?php esc_html_e('Descripción', 'mi-galeria-arte'); ?></label>
                                <textarea name="mga_gallery[images][<?php echo $index; ?>][description]" 
                                          placeholder="<?php esc_attr_e('Descripción de la obra', 'mi-galeria-arte'); ?>"><?php echo esc_textarea($image['description']); ?></textarea>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Renderiza la configuración de la galería
 */
function mga_render_gallery_settings($post) {
    $gallery = mga_get_gallery($post->ID);
    $settings = $gallery['settings'];
    ?>
    <div class="mga-settings-container">
        <div class="mga-form-group">
            <label for="mga-columns"><?php esc_html_e('Columnas', 'mi-galeria-arte'); ?></label>
            <select id="mga-columns" name="mga_gallery[settings][columns]">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php selected($settings['columns'], $i); ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div class="mga-form-group">
            <label for="mga-gutter"><?php esc_html_e('Espaciado (px)', 'mi-galeria-arte'); ?></label>
            <input type="text" id="mga-gutter" name="mga_gallery[settings][gutter]" 
                   value="<?php echo esc_attr($settings['gutter']); ?>">
        </div>
        
        <div class="mga-form-group">
            <label for="mga-lightbox">
                <input type="checkbox" id="mga-lightbox" name="mga_gallery[settings][lightbox]" 
                       value="1" <?php checked($settings['lightbox'], true); ?>>
                <?php esc_html_e('Activar Lightbox', 'mi-galeria-arte'); ?>
            </label>
        </div>
        
        <div class="mga-form-group">
            <label for="mga-lazy-load">
                <input type="checkbox" id="mga-lazy-load" name="mga_gallery[settings][lazy_load]" 
                       value="1" <?php checked($settings['lazy_load'], true); ?>>
                <?php esc_html_e('Carga diferida (Lazy Load)', 'mi-galeria-arte'); ?>
            </label>
        </div>
        
        <div class="mga-form-group">
            <label for="mga-hover-effect"><?php esc_html_e('Efecto Hover', 'mi-galeria-arte'); ?></label>
            <select id="mga-hover-effect" name="mga_gallery[settings][hover_effect]">
                <option value="fade" <?php selected($settings['hover_effect'], 'fade'); ?>>
                    <?php esc_html_e('Fundido', 'mi-galeria-arte'); ?>
                </option>
                <option value="slide-up" <?php selected($settings['hover_effect'], 'slide-up'); ?>>
                    <?php esc_html_e('Deslizar hacia arriba', 'mi-galeria-arte'); ?>
                </option>
                <option value="zoom" <?php selected($settings['hover_effect'], 'zoom'); ?>>
                    <?php esc_html_e('Zoom', 'mi-galeria-arte'); ?>
                </option>
            </select>
        </div>
        
        <h4><?php esc_html_e('Puntos de ruptura responsive', 'mi-galeria-arte'); ?></h4>
        
        <div class="mga-form-group">
            <label for="mga-mobile-breakpoint"><?php esc_html_e('Móvil (px)', 'mi-galeria-arte'); ?></label>
            <input type="number" id="mga-mobile-breakpoint" 
                   name="mga_gallery[settings][responsive_breakpoints][mobile]" 
                   value="<?php echo esc_attr($settings['responsive_breakpoints']['mobile']); ?>">
        </div>
        
        <div class="mga-form-group">
            <label for="mga-tablet-breakpoint"><?php esc_html_e('Tablet (px)', 'mi-galeria-arte'); ?></label>
            <input type="number" id="mga-tablet-breakpoint" 
                   name="mga_gallery[settings][responsive_breakpoints][tablet]" 
                   value="<?php echo esc_attr($settings['responsive_breakpoints']['tablet']); ?>">
        </div>
        
        <div class="mga-form-group">
            <label for="mga-desktop-breakpoint"><?php esc_html_e('Escritorio (px)', 'mi-galeria-arte'); ?></label>
            <input type="number" id="mga-desktop-breakpoint" 
                   name="mga_gallery[settings][responsive_breakpoints][desktop]" 
                   value="<?php echo esc_attr($settings['responsive_breakpoints']['desktop']); ?>">
        </div>
    </div>
    <?php
}

/**
 * Guarda los datos de la galería
 */
function mga_save_gallery_data($post_id) {
    if (!isset($_POST['mga_gallery_nonce']) || 
        !wp_verify_nonce($_POST['mga_gallery_nonce'], 'mga_save_gallery_data')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['mga_gallery'])) {
        $sanitized_data = mga_sanitize_gallery_data($_POST['mga_gallery']);
        update_post_meta($post_id, '_mga_gallery_data', $sanitized_data);
    }
}
add_action('save_post_arte_galeria', 'mga_save_gallery_data');

/**
 * Añade estilos al admin
 */
function mga_admin_styles() {
    global $post_type;
    
    if ('arte_galeria' === $post_type) {
        echo '<style>
            .mga-gallery-builder-container {
                padding: 15px;
            }
            
            .mga-gallery-preview {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                list-style: none;
                padding: 0;
            }
            
            .mga-gallery-item {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 3px;
                padding: 10px;
                width: calc(33.333% - 10px);
                box-sizing: border-box;
            }
            
            .mga-image-preview {
                position: relative;
                margin-bottom: 10px;
            }
            
            .mga-remove-image {
                position: absolute;
                top: 5px;
                right: 5px;
                background: #ff0000;
                color: #fff;
                border: none;
                border-radius: 50%;
                padding: 2px;
                cursor: pointer;
            }
            
            .mga-form-group {
                margin-bottom: 10px;
            }
            
            .mga-form-group label {
                display: block;
                margin-bottom: 3px;
                font-weight: 600;
            }
            
            .mga-form-group input[type="text"],
            .mga-form-group textarea {
                width: 100%;
                padding: 5px;
            }
            
            .mga-settings-container {
                padding: 10px;
            }
        </style>';
    }
}
add_action('admin_head', 'mga_admin_styles');