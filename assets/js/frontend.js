jQuery(document).ready(function($) {
    // Detectar cambios de tamaño y ajustar la galería
    function adjustGalleryLayout() {
        const $gallery = $('.mga-gallery-container');
        const windowWidth = $(window).width();
        
        // Ajustar según breakpoints
        if (windowWidth < 480) {
            $gallery.addClass('mobile-layout').removeClass('tablet-layout desktop-layout');
        } else if (windowWidth < 768) {
            $gallery.addClass('tablet-layout').removeClass('mobile-layout desktop-layout');
        } else if (windowWidth < 1024) {
            $gallery.addClass('small-desktop-layout').removeClass('mobile-layout tablet-layout');
        } else {
            $gallery.addClass('desktop-layout').removeClass('mobile-layout tablet-layout small-desktop-layout');
        }
        
        // Forzar redibujado para evitar problemas de renderizado
        $gallery.hide().show(0);
    }
    
    // Ejecutar al cargar y al redimensionar
    adjustGalleryLayout();
    $(window).on('resize orientationchange', _.debounce(adjustGalleryLayout, 250));
    
    // Usar imagesloaded para asegurar que las imágenes estén cargadas
    $('.mga-gallery-container').imagesLoaded(function() {
        // Inicializar masonry o grid según tamaño
        if ($(window).width() >= 768) {
            initMasonry();
        }
        
        // Efectos hover con touch support
        setupHoverEffects();
    });
    
    function initMasonry() {
        $('.mga-gallery-container').masonry({
            itemSelector: '.mga-gallery-item',
            columnWidth: '.mga-gallery-sizer',
            percentPosition: true,
            transitionDuration: '0.4s',
            resize: true
        });
    }
    
    function setupHoverEffects() {
        // Soporte para touch devices
        if ('ontouchstart' in window) {
            $('.mga-gallery-item').on('touchstart touchend', function(e) {
                $(this).toggleClass('is-touched', e.type === 'touchstart');
                e.preventDefault();
            });
        } else {
            $('.mga-gallery-item').hover(
                function() { $(this).addClass('is-hovered'); },
                function() { $(this).removeClass('is-hovered'); }
            );
        }
    }
});