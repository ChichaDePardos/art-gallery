# Mi Galería de Arte - Plugin WordPress

![Plugin Version](https://img.shields.io/badge/version-1.0-blue) 
![WordPress Version](https://img.shields.io/badge/WordPress-5.0%2B-brightgreen) 
![License](https://img.shields.io/badge/license-Privada-red)

Un plugin WordPress para crear hermosas galerías de arte con diseño responsive y efectos hover que muestran información de las obras.

![Captura de pantalla de la galería](assets/images/screenshot.png)

## Características principales

✅ **Diseño responsive** – Se adapta perfectamente a móviles, tablets y desktop  
✅ **Efectos hover elegantes** – Muestra título, autor y detalles al pasar el cursor  
✅ **Múltiples layouts** – Diseños en mosaico, grid y masonry  
✅ **Lightbox integrado** – Visualización a pantalla completa de las obras  
✅ **Gestión sencilla** – Interfaz intuitiva en el panel de administración  
✅ **Shortcodes** – Fácil inserción en cualquier página/post  
✅ **Optimizado para SEO** – Metadatos y marcado semántico para mejor indexación  

## Requisitos técnicos

- WordPress 5.0 o superior
- PHP 7.4 o superior
- MySQL 5.6 o superior

## Instalación

1. Descarga el archivo ZIP del plugin
2. Ve a tu panel de WordPress: `Plugins > Añadir nuevo > Subir plugin`
3. Selecciona el archivo ZIP y haz clic en "Instalar ahora"
4. Activa el plugin a través del menú 'Plugins' en WordPress

## Uso básico

1. Ve a `Galerías de Arte > Añadir nueva` en tu panel de WordPress
2. Añade imágenes y completa la información de cada obra (título, autor, etc.)
3. Configura las opciones de visualización según tus preferencias
4. Inserta la galería en cualquier página/post usando el shortcode:

   ```html
   [mi_galeria_arte id="X"]
   ```
   (Reemplaza "X" con el ID de tu galería)

## Personalización avanzada

### Atributos del shortcode

| Atributo       | Descripción                              | Valores aceptados        | Por defecto |
|----------------|------------------------------------------|---------------------------|-------------|
| `id`           | ID de la galería                         | Número entero              | Requerido   |
| `columns`      | Número de columnas                       | 1-6                        | 4           |
| `gutter`       | Espacio entre imágenes                   | Valor CSS (ej. "15px")     | "15px"      |
| `lightbox`     | Activar lightbox                         | true / false               | true        |
| `lazy_load`    | Carga diferida de imágenes               | true / false               | true        |
| `hover_effect` | Efecto al pasar el cursor                | fade / slide-up / zoom     | fade        |

### Ejemplos de shortcode

```html
[mi_galeria_arte id="5" columns="3" gutter="10px" hover_effect="zoom"]

[mi_galeria_arte id="8" columns="2" lightbox="false"]
```

## Hooks y filtros

El plugin incluye varios hooks para desarrolladores:

```php
// Modificar los argumentos de la galería antes de renderizar
add_filter('mga_gallery_args', function($args) {
    $args['columns'] = 3;
    return $args;
});

// Añadir contenido adicional después de la galería
add_action('mga_after_gallery', function($gallery_id) {
    echo '<div class="mga-credits">Galería creada con Mi Galería de Arte</div>';
});
```

## Soporte técnico

Si encuentras algún problema o tienes preguntas:

1. Revisa la [sección de issues](https://github.com/tuusuario/mi-galeria-arte/issues) en GitHub
2. Abre un nuevo issue si no encuentras solución
3. Para soporte prioritario, contacta a soporte@tudominio.com

## Contribuciones

¡Las contribuciones son bienvenidas! Por favor:

1. Haz fork del repositorio
2. Crea una rama para tu feature (`git checkout -b feature/awesome-feature`)
3. Haz commit de tus cambios (`git commit -am 'Add awesome feature'`)
4. Haz push a la rama (`git push origin feature/awesome-feature`)
5. Abre un Pull Request

## Licencia

Este plugin es de **licencia privada**.  
Queda **prohibida** su copia, redistribución o modificación sin autorización expresa del autor.

## Changelog

### 1.0 – 2023-11-15
- Versión inicial del plugin
- Funcionalidad básica de galerías
- Efectos hover y lightbox
- Soporte responsive

## Créditos

Desarrollado con ❤️ por ChichadePardos

---
```
