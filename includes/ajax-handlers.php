<?php
if (!defined('ABSPATH')) exit;

/**
 * Maneja las peticiones AJAX para buscar imágenes
 */
function mga_ajax_search_images() {
    check_ajax_referer('mga_ajax_nonce', 'nonce');
    
    if (!current_user_can('upload_files')) {
        wp_send_json_error(__('No tienes permisos suficientes', 'mi-galeria-arte'));
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
add_action('wp_ajax_mga_search_images', 'mga_ajax_search_images');

/**
 * Maneja las peticiones AJAX para obtener información de una imagen
 */
function mga_ajax_get_image_info() {
    check_ajax_referer('mga_ajax_nonce', 'nonce');
    
    if (!current_user_can('upload_files')) {
        wp_send_json_error(__('No tienes permisos suficientes', 'mi-galeria-arte'));
    }
    
    $image_id = absint($_POST['image_id'] ?? 0);
    
    if (!$image_id) {
        wp_send_json_error(__('ID de imagen no válido', 'mi-galeria-arte'));
    }
    
    $image = get_post($image_id);
    
    if (!$image || 'attachment' !== $image->post_type) {
        wp_send_json_error(__('Imagen no encontrada', 'mi-galeria-arte'));
    }
    
    $metadata = get_post_meta($image_id, '_mga_artwork_metadata', true);
    
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
add_action('wp_ajax_mga_get_image_info', 'mga_ajax_get_image_info');