# Mi Galería de Arte - Plugin WordPress

![Plugin Version](https://img.shields.io/badge/version-1.0-blue) 
![WordPress Version](https://img.shields.io/badge/WordPress-5.0%2B-brightgreen) 
![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-purple)
![License](https://img.shields.io/badge/license-GPLv3-blue)

Un plugin WordPress profesional para crear galerías de arte con diseño responsive, efectos hover y máxima compatibilidad.

![Captura de pantalla de la galería](assets/images/screenshot.png)

## Características principales

✅ **Diseño completamente responsive** - Adaptación perfecta a todos los dispositivos  
✅ **Efectos hover personalizables** - Muestra título, autor y detalles con animaciones CSS3  
✅ **Múltiples layouts** - Grid, Masonry y Mosaico con CSS Grid  
✅ **Lightbox integrado** - Compatible con las principales librerías  
✅ **Prefijo MGA_** - Total compatibilidad con otros plugins  
✅ **Shortcodes avanzados** - Más de 10 parámetros configurables  
✅ **Optimización SEO** - Schema markup para obras de arte  
✅ **WP-CLI support** - Comandos para gestión masiva  

## Requisitos técnicos

- WordPress 5.6+
- PHP 7.4+ (recomendado 8.0+)
- MySQL 5.7+ o MariaDB 10.3+
- Soporte para JavaScript moderno (ES6+)

## Instalación

### Método 1: Desde WordPress
1. Descarga el [paquete ZIP](https://tudominio.com/mi-galeria-arte.zip)
2. Ve a `Plugins > Añadir nuevo > Subir plugin`
3. Sube el archivo ZIP y activa el plugin

### Método 2: Vía CLI
```bash
wp plugin install https://tudominio.com/mi-galeria-arte.zip --activate
```

## Uso básico

1. Crea una nueva galería en `Galerías de Arte > Añadir nueva`
2. Configura los ajustes responsive y efectos hover
3. Inserta con shortcode:  
   ```html
   [MGA_galeria_arte id="123" layout="masonry" columns="4"]
   ```

## Shortcodes avanzados

### Parámetros principales:

| Atributo        | Valores                    | Default | Descripción |
|-----------------|----------------------------|---------|-------------|
| `id`            | ID de galería              | -       | Requerido   |
| `layout`        | grid, masonry, mosaic      | grid    | Tipo de layout |
| `columns`       | 1-6                        | 4       | Columnas en desktop |
| `hover_effect`  | fade, slide, zoom, flip    | fade    | Efecto hover |

### Ejemplos:

```html
[MGA_galeria_arte id="5" layout="masonry" hover_effect="zoom"]

[MGA_obra_arte id="42" size="large" show_author="false"]
```

## API para desarrolladores

### Hooks disponibles:

```php
// Filtro para modificar las imágenes
add_filter('MGA_gallery_images', function($images, $gallery_id) {
    return $images;
}, 10, 2);

// Acción después del render
add_action('MGA_after_render', function($gallery_html, $args) {
}, 10, 2);
```

### WP-CLI Commands:
```bash
wp mga import-csv /path/to/artworks.csv
wp mga regenerate-thumbnails --gallery-id=123
```

## Soporte técnico

- 📌 [Reportar issues](https://github.com/ChichaDePardos/art-gallery/issues)

## Roadmap 2024

- 🚀 Integración con WooCommerce (Q1)
- 📱 App móvil para gestión (Q2)
- 🖼️ Soporte para realidad aumentada (Q3)
- 🌐 Traducciones multidioma (Q4)

## Contribuciones

Seguimos las mejores prácticas de WordPress:
1. Fork del repositorio
2. Crea una feature branch (`git checkout -b feature/nueva-funcionalidad`)
3. Commit con estándares WP (`git commit -m "Nueva funcionalidad: Breve descripción"`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request detallado

## Licencia

Licenciado bajo GPLv3. Libre uso, modificación y distribución siguiendo los términos de la licencia.

## Changelog

### 1.0.1 - 2023-12-01
- 🐛 Corregido conflicto con plugins de caché
- ✨ Añadido soporte para WebP
- 🌍 Mejorada internacionalización

### 1.0.0 - 2023-11-15
- Versión inicial estable
- Todos los features básicos implementados
- Documentación completa

## Equipo

Desarrollado por [ChichaDePardos](https://github.com/ChichaDePardos)

---