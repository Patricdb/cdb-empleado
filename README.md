# CdB Empleado

Plugin de WordPress que añade el tipo de contenido **Empleado** y las herramientas necesarias para describir su disponibilidad y experiencia dentro de la plataforma CdB.

## Características

- Registro del CPT `empleado` con soporte para editor, imagen destacada y API REST.
- Metacampo "Disponible" para indicar si el empleado está activo.
- Metabox **Equipo y Año** que relaciona cada empleado con un CPT `equipo`; las opciones de equipo se filtran dinámicamente según el año seleccionado.
- Rol personalizado `empleado` con capacidades mínimas de lectura.
- Inyección automática en la vista individual de Empleado de una tarjeta con información, una gráfica de puntuación (a través de filtros) y el listado de equipos.
- Shortcode `[equipos_del_empleado empleado_id="123"]` que muestra los equipos y posiciones del empleado a partir de la tabla `cdb_experiencia`.
- Estilos específicos para el perfil del empleado en `assets/css/perfil-empleado.css`.

## Instalación

1. Copia la carpeta del plugin en `wp-content/plugins`.
2. Activa **CdB Empleado** desde el panel de administración de WordPress.

Este plugin asume que existen los CPT `equipo` y `cdb_posiciones`, así como la tabla personalizada `cdb_experiencia` donde se registra la participación del empleado en cada equipo.

## Uso

1. Crea una entrada del tipo **Empleado**.
2. Completa el metacampo **Disponible** y asigna un **Equipo** y **Año** en la metabox lateral.
3. Inserta el shortcode `[equipos_del_empleado]` (o `[equipos_del_empleado empleado_id="ID"]`) en cualquier contenido para mostrar los equipos en los que ha participado.
4. En la plantilla singular de empleado el plugin inyecta automáticamente una tarjeta con la gráfica, un bloque de calificación y el listado de equipos.

## Hooks relevantes

- `cdb_empleado_inyectar_grafica` y `cdb_empleado_inyectar_calificacion` permiten habilitar o deshabilitar los bloques automáticos.
- `cdb_grafica_empleado_html`, `cdb_grafica_empleado_form_html`, `cdb_grafica_empleado_scores_table_html` y `cdb_grafica_empleado_total` se utilizan para personalizar la información mostrada en la tarjeta y la gráfica.

## Changelog

### 1.0.2
- Se corrigió el paso de datos de equipos a JavaScript para que se envíen como un array nativo en lugar de una cadena JSON.

### 1.0.1
- Se añadió verificación de nonce y comprobación de capacidades al guardar la metabox "Equipo y Año" para mejorar la seguridad.

### 1.0.0
- Versión inicial.

