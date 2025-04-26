<?php
class MGA_Ajax_Handlers {

    public function __construct() {
        add_action('wp_ajax_MGA_search_images', array($this, 'search_images'));
        add_action('wp_ajax_MGA_get_image_info', array($this, 'get_image_info'));
        add_action('wp_ajax_MGA_save_image_metadata', array($this, 'save_image_metadata'));
    }

    public function search_images() {
        $this->verify_nonce();
        
        if (!current_user_can('upload_files')) {
            wp_send_json_error(__('No tienes permisos suficientes', 'MGA-mi-galeria-arte'));
        }
        
        $search = sanitize_text_field($_POST['search'] ?? '');
        $paged = absint($_POST['page'] ?? 1);
        
        $args = array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => 20,
            'paged' => $paged,
            's' => $search,
            'post_mime_type' => 'image'
        );
        
        $query = new WP_Query($args);
        $images = array();
        
        if ($query->have_posts()) {
            foreach ($query->posts as $post) {
                $images[] = array(
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'thumbnail' => wp_get_attachment_image_url($post->ID, 'thumbnail'),
                    'author' => '',
                    'description' => $post->post_excerpt
                );
            }
        }
        
        wp_send_json_success(array(
            'images' => $images,
            'max_pages' => $query->max_num_pages,
            'found_posts' => $query->found_posts
        ));
    }

    public function get_image_info() {
        $this->verify_nonce();
        
        if (!current_user_can('upload_files')) {
            wp_send_json_error(__('No tienes permisos suficientes', 'MGA-mi-galeria-arte'));
        }
        
        $image_id = absint($_POST['image_id'] ?? 0);
        
        if (!$image_id) {
            wp_send_json_error(__('ID de imagen no válido', 'MGA-mi-galeria-arte'));
        }
        
        $image = get_post($image_id);
        
        if (!$image || 'attachment' !== $image->post_type) {
            wp_send_json_error(__('Imagen no encontrada', 'MGA-mi-galeria-arte'));
        }
        
        $metadata = get_post_meta($image_id, '_MGA_artwork_metadata', true);
        
        wp_send_json_success(array(
            'id' => $image_id,
            'title' => $image->post_title,
            'author' => $metadata['author'] ?? '',
            'description' => $image->post_excerpt,
            'year' => $metadata['year'] ?? '',
            'technique' => $metadata['technique'] ?? '',
            'thumbnail' => wp_get_attachment_image_url($image_id, 'thumbnail'),
            'full_url' => wp_get_attachment_image_url($image_id, 'full')
        ));
    }

    public function save_image_metadata() {
        $this->verify_nonce();
        
        if (!current_user_can('upload_files')) {
            wp_send_json_error(__('No tienes permisos suficientes', 'MGA-mi-galeria-arte'));
        }
        
        $image_id = absint($_POST['image_id'] ?? 0);
        
        if (!$image_id) {
            wp_send_json_error(__('ID de imagen no válido', 'MGA-mi-galeria-arte'));
        }
        
        $metadata = array(
            'author' => sanitize_text_field($_POST['author'] ?? ''),
            'year' => sanitize_text_field($_POST['year'] ?? ''),
            'technique' => sanitize_text_field($_POST['technique'] ?? '')
        );
        
        update_post_meta($image_id, '_MGA_artwork_metadata', $metadata);
        
        wp_send_json_success(__('Metadatos guardados correctamente', 'MGA-mi-galeria-arte'));
    }

    private function verify_nonce() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'MGA_ajax_nonce')) {
            wp_send_json_error(__('Nonce de seguridad no válido', 'MGA-mi-galeria-arte'));
        }
    }
}