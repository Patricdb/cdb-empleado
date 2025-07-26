<?php
/**
 * Plugin Name: CdB_Empleado
 * Plugin URI:  https://proyectocdb.es
 * Description: Plugin para gestionar el tipo de contenido "Empleado" en la plataforma CdB.
 * Version:     1.0.0
 * Author:      CdB_
 * Author URI:  https://proyectocdb.es
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cdb-empleado
 */

// Bloqueo de acceso directo al archivo
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Evita el acceso directo al archivo
}

/**
 * Registrar el Custom Post Type "Empleado".
 */
function cdb_registrar_cpt_empleado() {
    $labels = array(
        'name'               => 'Empleados',
        'singular_name'      => 'Empleado',
        'menu_name'          => 'Empleados',
        'name_admin_bar'     => 'Empleado',
        'add_new'            => 'Añadir Nuevo',
        'add_new_item'       => 'Añadir Nuevo Empleado',
        'new_item'           => 'Nuevo Empleado',
        'edit_item'          => 'Editar Empleado',
        'view_item'          => 'Ver Empleado',
        'all_items'          => 'Todos los Empleados',
        'search_items'       => 'Buscar Empleados',
        'not_found'          => 'No se encontraron empleados',
        'not_found_in_trash' => 'No se encontraron empleados en la papelera'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'empleado' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-businessperson', // Icono en el menú de administración
        'supports'           => array( 'title', 'editor', 'thumbnail', 'author', 'custom-fields' ),
        'show_in_rest'       => true, // Compatibilidad con Gutenberg y la API REST
    );

    register_post_type( 'empleado', $args );
}
add_action( 'init', 'cdb_registrar_cpt_empleado' );

/**
 * Activación del plugin - Refrescar los enlaces permanentes.
 */
function cdb_empleado_activar() {
    cdb_registrar_cpt_empleado();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cdb_empleado_activar' );

/**
 * Desactivación del plugin - Limpiar reglas de reescritura.
 */
function cdb_empleado_desactivar() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cdb_empleado_desactivar' );

// Se requiere el archivo con los metacampos para “Empleado”
require_once plugin_dir_path(__FILE__) . 'inc/metacampos-empleado.php';

/**
 * Render de la Metabox para asignar Equipo y Año.
 */
function cdb_empleado_meta_box_callback($post) {
    $selected_equipo = get_post_meta($post->ID, '_cdb_empleado_equipo', true);
    $selected_year   = get_post_meta($post->ID, '_cdb_empleado_year', true);

    // Obtener todos los equipos disponibles
    $equipos = get_posts(array(
        'post_type'      => 'equipo',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_key'       => '_cdb_equipo_year', // Ordenar por año
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC'
    ));

    echo '<label for="cdb_empleado_year">' . __('Seleccionar Año:', 'cdb-bar') . '</label>';
    echo '<select name="cdb_empleado_year" id="cdb_empleado_year">';
    echo '<option value="">' . __('Seleccionar un Año', 'cdb-bar') . '</option>';

    // Obtener años únicos de los equipos
    $years = [];
    foreach ($equipos as $equipo) {
        $year = get_post_meta($equipo->ID, '_cdb_equipo_year', true);
        if ($year && !in_array($year, $years)) {
            $years[] = $year;
            echo '<option value="' . esc_attr($year) . '" ' . selected($selected_year, $year, false) . '>' . esc_html($year) . '</option>';
        }
    }
    echo '</select><br><br>';

    echo '<label for="cdb_empleado_equipo">' . __('Seleccionar un Equipo:', 'cdb-bar') . '</label>';
    echo '<select name="cdb_empleado_equipo" id="cdb_empleado_equipo">';
    echo '<option value="">' . __('Seleccionar un Equipo', 'cdb-bar') . '</option>';

    // Mostrar equipos solo del año seleccionado
    foreach ($equipos as $equipo) {
        $year = get_post_meta($equipo->ID, '_cdb_equipo_year', true);
        if ($selected_year == $year) {
            echo '<option value="' . esc_attr($equipo->ID) . '" ' . selected($selected_equipo, $equipo->ID, false) . '>' . esc_html($equipo->post_title) . '</option>';
        }
    }
    echo '</select>';

    // JavaScript para actualizar equipos según el año seleccionado
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var yearSelect = document.getElementById("cdb_empleado_year");
            var equipoSelect = document.getElementById("cdb_empleado_equipo");
            var equiposData = ' . json_encode($equipos) . ';

            function updateEquipos() {
                var selectedYear = yearSelect.value;
                equipoSelect.innerHTML = "<option value=\'\'>Seleccionar un Equipo</option>";

                for (var i = 0; i < equiposData.length; i++) {
                    var equipo = equiposData[i];
                    if (equipo.meta["_cdb_equipo_year"] == selectedYear) {
                        var option = document.createElement("option");
                        option.value = equipo.ID;
                        option.textContent = equipo.post_title;
                        equipoSelect.appendChild(option);
                    }
                }
            }

            yearSelect.addEventListener("change", updateEquipos);
            updateEquipos(); // Inicializa la lista al cargar
        });
    </script>';
}

// Mantén cualquier lógica adicional sobre permisos en archivo separado.
require_once plugin_dir_path(__FILE__) . 'inc/permisos.php';

// Se requiere el archivo con el shortcode y lógica extra
require_once plugin_dir_path(__FILE__) . 'inc/funciones-extra.php';

