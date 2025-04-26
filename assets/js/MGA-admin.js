jQuery(document).ready(function($) {
    // Namespace para el admin
    if (typeof MGA_Admin === 'undefined') {
        MGA_Admin = {};
    }

    MGA_Admin.GalleryBuilder = {
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.initSortable();
        },

        cacheElements: function() {
            this.$container = $('.MGA-gallery-builder-container');
            this.$galleryPreview = $('.MGA-gallery-preview');
            this.$addImagesBtn = $('.MGA-add-images');
            this.mediaFrame = null;
        },

        bindEvents: function() {
            this.$addImagesBtn.on('click', this.openMediaModal.bind(this));
            $(document).on('click', '.MGA-remove-image', this.removeImage.bind(this));
        },

        openMediaModal: function(e) {
            e.preventDefault();

            if (this.mediaFrame) {
                this.mediaFrame.open();
                return;
            }

            this.mediaFrame = wp.media({
                title: MGA_admin_vars.media_title,
                button: {
                    text: MGA_admin_vars.media_button
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });

            this.mediaFrame.on('select', this.handleMediaSelection.bind(this));
            this.mediaFrame.open();
        },

        handleMediaSelection: function() {
            const attachments = this.mediaFrame.state().get('selection').toJSON();
            const currentItems = this.$galleryPreview.children().length;

            attachments.forEach((attachment, index) => {
                const itemIndex = currentItems + index;
                this.addGalleryItem(attachment, itemIndex);
            });
        },

        addGalleryItem: function(attachment, index) {
            const template = wp.template('MGA-gallery-item');
            const data = $.extend({}, attachment, {
                index: index,
                author: '',
                year: '',
                technique: '',
                description: attachment.caption || ''
            });

            this.$galleryPreview.append(template(data));
            this.initSortable();
        },

        removeImage: function(e) {
            e.preventDefault();
            const $item = $(e.target).closest('.MGA-gallery-item');
            
            $item.fadeOut(300, () => {
                $item.remove();
                this.reindexItems();
            });
        },

        initSortable: function() {
            this.$galleryPreview.sortable({
                items: '> li',
                cursor: 'move',
                opacity: 0.7,
                placeholder: 'MGA-sortable-placeholder',
                stop: this.reindexItems.bind(this)
            });
        },

        reindexItems: function() {
            this.$galleryPreview.find('li').each(function(index) {
                $(this).find('input, textarea, select').each(function() {
                    const $el = $(this);
                    const name = $el.attr('name').replace(/\[\d+\]/, '[' + index + ']');
                    $el.attr('name', name);
                });
            });
        }
    };

    // Template para los ítems de la galería
    wp.template('MGA-gallery-item', `
        <li class="MGA-gallery-item" data-id="{{ data.id }}">
            <input type="hidden" name="MGA_gallery[images][{{ data.index }}][id]" value="{{ data.id }}">
            
            <div class="MGA-image-preview">
                <img src="{{ data.thumbnail }}" alt="{{ data.title }}">
                <button type="button" class="MGA-remove-image dashicons dashicons-trash"></button>
            </div>
            
            <div class="MGA-image-details">
                <div class="MGA-form-group">
                    <label><?php _e('Título', 'MGA-mi-galeria-arte'); ?></label>
                    <input type="text" name="MGA_gallery[images][{{ data.index }}][title]" 
                           value="{{ data.title }}" placeholder="<?php _e('Título de la obra', 'MGA-mi-galeria-arte'); ?>">
                </div>
                
                <div class="MGA-form-group">
                    <label><?php _e('Artista', 'MGA-mi-galeria-arte'); ?></label>
                    <input type="text" name="MGA_gallery[images][{{ data.index }}][author]" 
                           value="{{ data.author }}" placeholder="<?php _e('Nombre del artista', 'MGA-mi-galeria-arte'); ?>">
                </div>
                
                <div class="MGA-form-group">
                    <label><?php _e('Año', 'MGA-mi-galeria-arte'); ?></label>
                    <input type="text" name="MGA_gallery[images][{{ data.index }}][year]" 
                           value="{{ data.year }}" placeholder="<?php _e('Año de creación', 'MGA-mi-galeria-arte'); ?>">
                </div>
                
                <div class="MGA-form-group">
                    <label><?php _e('Técnica', 'MGA-mi-galeria-arte'); ?></label>
                    <input type="text" name="MGA_gallery[images][{{ data.index }}][technique]" 
                           value="{{ data.technique }}" placeholder="<?php _e('Técnica utilizada', 'MGA-mi-galeria-arte'); ?>">
                </div>
                
                <div class="MGA-form-group">
                    <label><?php _e('Descripción', 'MGA-mi-galeria-arte'); ?></label>
                    <textarea name="MGA_gallery[images][{{ data.index }}][description]" 
                              placeholder="<?php _e('Descripción de la obra', 'MGA-mi-galeria-arte'); ?>">{{ data.description }}</textarea>
                </div>
            </div>
        </li>
    `);

    // Inicializar el constructor de galerías
    MGA_Admin.GalleryBuilder.init();
});