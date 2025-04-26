jQuery(document).ready(function($) {
    // Solo ejecutar en el post type de galerías
    if ($('body').hasClass('post-type-arte_galeria')) {
        initGalleryBuilder();
    }
    
    function initGalleryBuilder() {
        const $galleryContainer = $('.mga-gallery-preview');
        const $addImagesBtn = $('.mga-add-images');
        let mediaFrame;
        
        // Abrir el modal de medios al hacer clic en el botón
        $addImagesBtn.on('click', function(e) {
            e.preventDefault();
            
            if (mediaFrame) {
                mediaFrame.open();
                return;
            }
            
            // Configurar el modal de medios
            mediaFrame = wp.media({
                title: 'Seleccionar imágenes para la galería',
                button: {
                    text: 'Añadir a la galería'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });
            
            // Manejar la selección de imágenes
            mediaFrame.on('select', function() {
                const attachments = mediaFrame.state().get('selection').toJSON();
                const currentItems = $galleryContainer.children().length;
                
                attachments.forEach(function(attachment, index) {
                    const itemIndex = currentItems + index;
                    
                    // Obtener más información via AJAX si es necesario
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'mga_get_image_info',
                            image_id: attachment.id,
                            nonce: mga_vars.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                const image = response.data;
                                addGalleryItem(image, itemIndex);
                            }
                        }
                    });
                });
            });
            
            mediaFrame.open();
        });
        
        // Añadir un ítem a la galería
        function addGalleryItem(image, index) {
            const $item = $(
                `<li class="mga-gallery-item" data-id="${image.id}">
                    <input type="hidden" name="mga_gallery[images][${index}][id]" value="${image.id}">
                    
                    <div class="mga-image-preview">
                        <img src="${image.thumbnail}" alt="${image.title}">
                        <button type="button" class="mga-remove-image dashicons dashicons-trash"></button>
                    </div>
                    
                    <div class="mga-image-details">
                        <div class="mga-form-group">
                            <label>Título</label>
                            <input type="text" name="mga_gallery[images][${index}][title]" 
                                   value="${image.title}" placeholder="Título de la obra">
                        </div>
                        
                        <div class="mga-form-group">
                            <label>Artista</label>
                            <input type="text" name="mga_gallery[images][${index}][author]" 
                                   value="${image.author}" placeholder="Nombre del artista">
                        </div>
                        
                        <div class="mga-form-group">
                            <label>Año</label>
                            <input type="text" name="mga_gallery[images][${index}][year]" 
                                   value="${image.year}" placeholder="Año de creación">
                        </div>
                        
                        <div class="mga-form-group">
                            <label>Técnica</label>
                            <input type="text" name="mga_gallery[images][${index}][technique]" 
                                   value="${image.technique}" placeholder="Técnica utilizada">
                        </div>
                        
                        <div class="mga-form-group">
                            <label>Descripción</label>
                            <textarea name="mga_gallery[images][${index}][description]" 
                                      placeholder="Descripción de la obra">${image.description}</textarea>
                        </div>
                    </div>
                </li>`
            );
            
            $galleryContainer.append($item);
            initSortable();
            bindRemoveButtons();
        }
        
        // Hacer la galería ordenable
        function initSortable() {
            $galleryContainer.sortable({
                items: '> li',
                cursor: 'move',
                opacity: 0.7,
                placeholder: 'mga-sortable-placeholder',
                stop: function() {
                    // Reindexar los ítems después de ordenar
                    $galleryContainer.find('li').each(function(index) {
                        $(this).find('input, textarea').each(function() {
                            const name = $(this).attr('name')
                                .replace(/\[\d+\]/, '[' + index + ']');
                            $(this).attr('name', name);
                        });
                    });
                }
            });
        }
        
        // Manejar la eliminación de imágenes
        function bindRemoveButtons() {
            $('.mga-remove-image').off('click').on('click', function() {
                $(this).closest('.mga-gallery-item').fadeOut(300, function() {
                    $(this).remove();
                    // Reindexar los ítems restantes
                    $galleryContainer.find('li').each(function(index) {
                        $(this).find('input, textarea').each(function() {
                            const name = $(this).attr('name')
                                .replace(/\[\d+\]/, '[' + index + ']');
                            $(this).attr('name', name);
                        });
                    });
                });
            });
        }
        
        // Inicializar
        initSortable();
        bindRemoveButtons();
        
        // Buscar imágenes
        $('.mga-search-images').on('click', function() {
            const searchTerm = $('.mga-search-input').val();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'mga_search_images',
                    search: searchTerm,
                    page: 1,
                    nonce: mga_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Mostrar resultados
                    }
                }
            });
        });
    }
});