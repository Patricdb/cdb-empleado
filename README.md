# CdB Empleado

Plugin de WordPress para gestionar un Custom Post Type de empleados y mostrar su experiencia en distintos equipos mediante un shortcode.

## Instalación

1. Copia la carpeta del plugin en `wp-content/plugins`.
2. Activa el plugin desde el panel de administración de WordPress.

## Uso

- Crea entradas del tipo **Empleado** y completa los metacampos disponibles.
- Utiliza el shortcode `[equipos_del_empleado]` para mostrar los equipos asociados a cada empleado.

## Desarrollo

El plugin registra el rol personalizado `empleado` y varias funciones auxiliares. Consulta el código fuente para más detalles.

## Changelog

### 1.0.1

- Se añadió verificación de nonce y comprobación de capacidades al guardar la metabox "Equipo y Año" para mejorar la seguridad.
