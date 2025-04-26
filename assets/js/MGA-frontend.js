jQuery(document).ready(function($) {
    // Namespace para evitar conflictos
    if (typeof window.MGA === 'undefined') {
        window.MGA = {};
    }
    
    MGA.Gallery = {
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.adjustLayout();
        },
        
        cacheElements: function() {
            this.$galleries = $('.MGA-gallery-container');
            this.$window = $(window);
        },
        
        bindEvents: function() {
            this.$window.on('resize orientationchange', _.debounce(this.adjustLayout.bind(this), 250));
            
            // Efectos hover con soporte para touch
            $('.MGA-gallery-item').each(function() {
                if ('ontouchstart' in window) {
                    $(this).on('touchstart touchend', MGA.Gallery.handleTouch);
                } else {
                    $(this).hover(
                        function() { $(this).addClass('MGA-is-hovered'); },
                        function() { $(this).removeClass('MGA-is-hovered'); }
                    );
                }
            });
        },
        
        adjustLayout: function() {
            this.$galleries.each(function() {
                const $gallery = $(this);
                const windowWidth = $(window).width();
                const breakpoints = MGA_vars.breakpoints || {
                    mobile: 480,
                    tablet: 768,
                    desktop: 1024
                };
                
                // Aplicar clases según el tamaño
                $gallery.removeClass('MGA-mobile MGA-tablet MGA-desktop');
                
                if (windowWidth < breakpoints.mobile) {
                    $gallery.addClass('MGA-mobile');
                } else if (windowWidth < breakpoints.tablet) {
                    $gallery.addClass('MGA-tablet');
                } else {
                    $gallery.addClass('MGA-desktop');
                }
            });
        },
        
        handleTouch: function(e) {
            $(this).toggleClass('MGA-is-touched', e.type === 'touchstart');
            e.preventDefault();
        }
    };
    
    // Inicializar todas las galerías
    $('.MGA-gallery-container').each(function() {
        MGA.Gallery.init();
    });
    
    // Lightbox con detección de conflictos
    if (typeof lightbox === 'undefined') {
        $('[data-MGA-lightbox]').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true
            }
        });
    }
});