<?php
class MGA_Admin_Settings {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_MGA_arte_galeria', array($this, 'save_gallery_data'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function add_meta_boxes() {
        add_meta_box(
            'MGA-gallery-builder',
            __('Constructor de Galería', 'MGA-mi-galeria-arte'),
            array($this, 'render_gallery_builder'),
            'MGA_arte_galeria',
            'normal',
            'high'
        );
        
        add_meta_box(
            'MGA-gallery-settings',
            __('Configuración de la Galería', 'MGA-mi-galeria-arte'),
            array($this, 'render_gallery_settings'),
            'MGA_arte_galeria',
            'side',
            'default'
        );
    }

    public function render_gallery_builder($post) {
        wp_nonce_field('MGA_save_gallery_data', 'MGA_gallery_nonce');
        
        $gallery = MGA_Gallery_Functions::get_gallery($post->ID);
        ?>
        <div class="MGA-gallery-builder-container">
            <div class="MGA-uploader-container">
                <button type="button" class="button button-primary MGA-add-images">
                    <?php esc_html_e('Añadir Imágenes', 'MGA-mi-galeria-arte'); ?>
                </button>
                <p class="description">
                    <?php esc_html_e('Selecciona las imágenes para tu galería', 'MGA-mi-galeria-arte'); ?>
                </p>
            </div>
            
            <div class="MGA-gallery-preview-container">
                <ul class="MGA-gallery-preview MGA-sortable">
                    <?php foreach ($gallery['images'] as $index => $image): ?>
                        <li class="MGA-gallery-item" data-id="<?php echo esc_attr($image['id']); ?>">
                            <input type="hidden" name="MGA_gallery[images][<?php echo $index; ?>][id]" value="<?php echo esc_attr($image['id']); ?>">
                            
                            <div class="MGA-image-preview">
                                <?php echo wp_get_attachment_image($image['id'], 'thumbnail'); ?>
                                <button type="button" class="MGA-remove-image dashicons dashicons-trash"></button>
                            </div>
                            
                            <div class="MGA-image-details">
                                <div class="MGA-form-group">
                                    <label><?php esc_html_e('Título', 'MGA-mi-galeria-arte'); ?></label>
                                    <input type="text" name="MGA_gallery[images][<?php echo $index; ?>][title]" 
                                           value="<?php echo esc_attr($image['title']); ?>" 
                                           placeholder="<?php esc_attr_e('Título de la obra', 'MGA-mi-galeria-arte'); ?>">
                                </div>
                                
                                <div class="MGA-form-group">
                                    <label><?php esc_html_e('Artista', 'MGA-mi-galeria-arte'); ?></label>
                                    <input type="text" name="MGA_gallery[images][<?php echo $index; ?>][author]" 
                                           value="<?php echo esc_attr($image['author']); ?>" 
                                           placeholder="<?php esc_attr_e('Nombre del artista', 'MGA-mi-galeria-arte'); ?>">
                                </div>
                                
                                <div class="MGA-form-group">
                                    <label><?php esc_html_e('Año', 'MGA-mi-galeria-arte'); ?></label>
                                    <input type="text" name="MGA_gallery[images][<?php echo $index; ?>][year]" 
                                           value="<?php echo esc_attr($image['year']); ?>" 
                                           placeholder="<?php esc_attr_e('Año de creación', 'MGA-mi-galeria-arte'); ?>">
                                </div>
                                
                                <div class="MGA-form-group">
                                    <label><?php esc_html_e('Técnica', 'MGA-mi-galeria-arte'); ?></label>
                                    <input type="text" name="MGA_gallery[images][<?php echo $index; ?>][technique]" 
                                           value="<?php echo esc_attr($image['technique']); ?>" 
                                           placeholder="<?php esc_attr_e('Técnica utilizada', 'MGA-mi-galeria-arte'); ?>">
                                </div>
                                
                                <div class="MGA-form-group">
                                    <label><?php esc_html_e('Descripción', 'MGA-mi-galeria-arte'); ?></label>
                                    <textarea name="MGA_gallery[images][<?php echo $index; ?>][description]" 
                                              placeholder="<?php esc_attr_e('Descripción de la obra', 'MGA-mi-galeria-arte'); ?>"><?php echo esc_textarea($image['description']); ?></textarea>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    public function render_gallery_settings($post) {
        $gallery = MGA_Gallery_Functions::get_gallery($post->ID);
        $settings = $gallery['settings'];
        ?>
        <div class="MGA-settings-container">
            <div class="MGA-form-group">
                <label for="MGA-columns"><?php esc_html_e('Columnas', 'MGA-mi-galeria-arte'); ?></label>
                <select id="MGA-columns" name="MGA_gallery[settings][columns]">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php selected($settings['columns'], $i); ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div class="MGA-form-group">
                <label for="MGA-gutter"><?php esc_html_e('Espaciado (px)', 'MGA-mi-galeria-arte'); ?></label>
                <input type="text" id="MGA-gutter" name="MGA_gallery[settings][gutter]" 
                       value="<?php echo esc_attr($settings['gutter']); ?>">
            </div>
            
            <div class="MGA-form-group">
                <label for="MGA-lightbox">
                    <input type="checkbox" id="MGA-lightbox" name="MGA_gallery[settings][lightbox]" 
                           value="1" <?php checked($settings['lightbox'], true); ?>>
                    <?php esc_html_e('Activar Lightbox', 'MGA-mi-galeria-arte'); ?>
                </label>
            </div>
            
            <div class="MGA-form-group">
                <label for="MGA-lazy-load">
                    <input type="checkbox" id="MGA-lazy-load" name="MGA_gallery[settings][lazy_load]" 
                           value="1" <?php checked($settings['lazy_load'], true); ?>>
                    <?php esc_html_e('Carga diferida (Lazy Load)', 'MGA-mi-galeria-arte'); ?>
                </label>
            </div>
            
            <div class="MGA-form-group">
                <label for="MGA-hover-effect"><?php esc_html_e('Efecto Hover', 'MGA-mi-galeria-arte'); ?></label>
                <select id="MGA-hover-effect" name="MGA_gallery[settings][hover_effect]">
                    <option value="fade" <?php selected($settings['hover_effect'], 'fade'); ?>>
                        <?php esc_html_e('Fundido', 'MGA-mi-galeria-arte'); ?>
                    </option>
                    <option value="slide-up" <?php selected($settings['hover_effect'], 'slide-up'); ?>>
                        <?php esc_html_e('Deslizar hacia arriba', 'MGA-mi-galeria-arte'); ?>
                    </option>
                    <option value="zoom" <?php selected($settings['hover_effect'], 'zoom'); ?>>
                        <?php esc_html_e('Zoom', 'MGA-mi-galeria-arte'); ?>
                    </option>
                </select>
            </div>
            
            <h4><?php esc_html_e('Puntos de ruptura responsive', 'MGA-mi-galeria-arte'); ?></h4>
            
            <div class="MGA-form-group">
                <label for="MGA-mobile-breakpoint"><?php esc_html_e('Móvil (px)', 'MGA-mi-galeria-arte'); ?></label>
                <input type="number" id="MGA-mobile-breakpoint" 
                       name="MGA_gallery[settings][responsive_breakpoints][mobile]" 
                       value="<?php echo esc_attr($settings['responsive_breakpoints']['mobile']); ?>">
            </div>
            
            <div class="MGA-form-group">
                <label for="MGA-tablet-breakpoint"><?php esc_html_e('Tablet (px)', 'MGA-mi-galeria-arte'); ?></label>
                <input type="number" id="MGA-tablet-breakpoint" 
                       name="MGA_gallery[settings][responsive_breakpoints][tablet]" 
                       value="<?php echo esc_attr($settings['responsive_breakpoints']['tablet']); ?>">
            </div>
            
            <div class="MGA-form-group">
                <label for="MGA-desktop-breakpoint"><?php esc_html_e('Escritorio (px)', 'MGA-mi-galeria-arte'); ?></label>
                <input type="number" id="MGA-desktop-breakpoint" 
                       name="MGA_gallery[settings][responsive_breakpoints][desktop]" 
                       value="<?php echo esc_attr($settings['responsive_breakpoints']['desktop']); ?>">
            </div>
        </div>
        <?php
    }

    public function save_gallery_data($post_id) {
        if (!isset($_POST['MGA_gallery_nonce']) || 
            !wp_verify_nonce($_POST['MGA_gallery_nonce'], 'MGA_save_gallery_data')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        if (isset($_POST['MGA_gallery'])) {
            $sanitized_data = $this->sanitize_gallery_data($_POST['MGA_gallery']);
            update_post_meta($post_id, '_MGA_gallery_data', $sanitized_data);
        }
    }

    private function sanitize_gallery_data($data) {
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

    public function enqueue_styles() {
        wp_enqueue_style(
            'MGA-admin-css',
            MGA_PLUGIN_URL . 'assets/css/MGA-admin.css',
            array(),
            MGA_VERSION,
            'all'
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            'MGA-admin-js',
            MGA_PLUGIN_URL . 'assets/js/MGA-admin.js',
            array('jquery', 'jquery-ui-sortable', 'wp-util'),
            MGA_VERSION,
            true
        );
        
        wp_localize_script('MGA-admin-js', 'MGA_admin_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('MGA_ajax_nonce'),
            'media_title' => __('Seleccionar imágenes para la galería', 'MGA-mi-galeria-arte'),
            'media_button' => __('Añadir a la galería', 'MGA-mi-galeria-arte')
        ));
    }
}