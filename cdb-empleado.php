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
 * Cargar el dominio de traducción del plugin.
 */
function cdb_empleado_load_textdomain() {
    load_plugin_textdomain( 'cdb-empleado', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'cdb_empleado_load_textdomain' );

/**
 * Registrar el Custom Post Type "Empleado".
 */
function cdb_registrar_cpt_empleado() {
    $labels = array(
        'name'               => _x( 'Empleados', 'Post Type General Name', 'cdb-empleado' ),
        'singular_name'      => _x( 'Empleado', 'Post Type Singular Name', 'cdb-empleado' ),
        'menu_name'          => __( 'Empleados', 'cdb-empleado' ),
        'name_admin_bar'     => __( 'Empleado', 'cdb-empleado' ),
        'add_new'            => __( 'Añadir Nuevo', 'cdb-empleado' ),
        'add_new_item'       => __( 'Añadir Nuevo Empleado', 'cdb-empleado' ),
        'new_item'           => __( 'Nuevo Empleado', 'cdb-empleado' ),
        'edit_item'          => __( 'Editar Empleado', 'cdb-empleado' ),
        'view_item'          => __( 'Ver Empleado', 'cdb-empleado' ),
        'all_items'          => __( 'Todos los Empleados', 'cdb-empleado' ),
        'search_items'       => __( 'Buscar Empleados', 'cdb-empleado' ),
        'not_found'          => __( 'No se encontraron empleados', 'cdb-empleado' ),
        'not_found_in_trash' => __( 'No se encontraron empleados en la papelera', 'cdb-empleado' )
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
    cdb_empleado_registrar_rol();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cdb_empleado_activar' );

/**
 * Desactivación del plugin - Limpiar reglas de reescritura.
 */
function cdb_empleado_desactivar() {
    cdb_empleado_eliminar_rol();
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cdb_empleado_desactivar' );

// Incluir metacampos básicos del empleado
require_once plugin_dir_path(__FILE__) . 'inc/metacampos-empleado.php';
// Gestión de roles y capacidades específicos
require_once plugin_dir_path(__FILE__) . 'inc/roles-capacidades.php';

/**
 * Agregar la metabox de Equipo y Año en el CPT Empleado.
 */
function cdb_empleado_registrar_meta_box() {
    add_meta_box(
        'cdb_empleado_equipo_year',
        __('Equipo y Año', 'cdb-empleado'),
        'cdb_empleado_meta_box_callback',
        'empleado',
        'side'
    );
}
add_action('add_meta_boxes', 'cdb_empleado_registrar_meta_box');

/**
 * Obtener todos los equipos con título y año.
 *
 * @return array
 */
function cdb_empleado_get_equipos() {
    $ids = get_posts(array(
        'post_type'               => 'equipo',
        'posts_per_page'          => -1,
        'post_status'             => 'publish',
        'meta_key'                => '_cdb_equipo_year',
        'orderby'                 => 'meta_value_num',
        'order'                   => 'DESC',
        'cache_results'           => false,
        'update_post_meta_cache'  => false,
        'update_post_term_cache'  => false,
        'no_found_rows'           => true,
        'fields'                  => 'ids',
    ));

    if (empty($ids)) {
        return array();
    }

    $posts  = array_map('get_post', $ids);
    $titles = wp_list_pluck($posts, 'post_title', 'ID');

    $years_data = array_map(function($id) {
        return array(
            'ID'   => $id,
            'year' => get_post_meta($id, '_cdb_equipo_year', true),
        );
    }, $ids);
    $years = wp_list_pluck($years_data, 'year', 'ID');

    $equipos = array();
    foreach ($ids as $id) {
        $equipos[] = array(
            'ID'         => $id,
            'post_title' => isset($titles[$id]) ? $titles[$id] : '',
            'meta'       => array(
                '_cdb_equipo_year' => isset($years[$id]) ? $years[$id] : '',
            ),
        );
    }

    return $equipos;
}

/**
 * Encolar el script de la metabox y pasar los datos de equipos.
 */
function cdb_empleado_admin_assets($hook) {
    global $post_type;
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }
    if ('empleado' !== $post_type) {
        return;
    }

    $equipos = cdb_empleado_get_equipos();

    wp_enqueue_script(
        'cdb-empleado-metabox',
        plugin_dir_url(__FILE__) . 'assets/js/equipo-year.js',
        array(),
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/equipo-year.js' ),
        true
    );

    wp_localize_script(
        'cdb-empleado-metabox',
        'cdbEmpleadoEquiposData',
        $equipos
    );

    wp_localize_script(
        'cdb-empleado-metabox',
        'cdbEmpleadoTexts',
        array(
            'select_team' => __('Seleccionar un Equipo', 'cdb-empleado')
        )
    );
}
add_action('admin_enqueue_scripts', 'cdb_empleado_admin_assets');

/**
 * Encolar estilos del perfil del empleado en el frontal.
 */
add_action('wp_enqueue_scripts', 'cdb_empleado_front_assets');
function cdb_empleado_front_assets() {
    if (!is_admin() && is_singular('empleado')) {
        wp_enqueue_style(
            'cdb-perfil-empleado',
            plugins_url('assets/css/perfil-empleado.css', __FILE__),
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/perfil-empleado.css' )
        );
    }
}

/**
 * Desactivar cache en la página singular de Empleado.
 */
function cdb_empleado_nocache() {
    if (is_singular('empleado')) {
        nocache_headers();
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }
}
add_action('template_redirect', 'cdb_empleado_nocache');

/**
 * Render de la Metabox para asignar Equipo y Año.
 */
function cdb_empleado_meta_box_callback($post) {
    $selected_equipo = get_post_meta($post->ID, '_cdb_empleado_equipo', true);
    $selected_year   = get_post_meta($post->ID, '_cdb_empleado_year', true);

    // Nonce para verificar la intención al guardar
    wp_nonce_field('cdb_empleado_equipo_nonce_action', 'cdb_empleado_equipo_nonce');

    // Obtener todos los equipos disponibles
    $equipos = cdb_empleado_get_equipos();

    echo '<label for="cdb_empleado_year">' . __('Seleccionar Año:', 'cdb-empleado') . '</label>';
    echo '<select name="cdb_empleado_year" id="cdb_empleado_year">';
    echo '<option value="">' . __('Seleccionar un Año', 'cdb-empleado') . '</option>';

    // Obtener años únicos de los equipos
    $years = [];
    foreach ($equipos as $equipo) {
        $year = $equipo['meta']['_cdb_equipo_year'];
        if ($year && !in_array($year, $years)) {
            $years[] = $year;
            echo '<option value="' . esc_attr($year) . '" ' . selected($selected_year, $year, false) . '>' . esc_html($year) . '</option>';
        }
    }
    echo '</select><br><br>';

    echo '<label for="cdb_empleado_equipo">' . __('Seleccionar un Equipo:', 'cdb-empleado') . '</label>';
    echo '<select name="cdb_empleado_equipo" id="cdb_empleado_equipo">';
    echo '<option value="">' . __('Seleccionar un Equipo', 'cdb-empleado') . '</option>';

    // Mostrar equipos solo del año seleccionado
    foreach ($equipos as $equipo) {
        $year = $equipo['meta']['_cdb_equipo_year'];
        if ($selected_year == $year) {
            echo '<option value="' . esc_attr($equipo['ID']) . '" ' . selected($selected_equipo, $equipo['ID'], false) . '>' . esc_html($equipo['post_title']) . '</option>';
        }
    }
    echo '</select>';
}

/**
 * Guardar los valores de Equipo y Año del empleado.
 */
function cdb_empleado_guardar_equipo_year($post_id) {
    if (!isset($_POST['cdb_empleado_equipo_nonce']) ||
        !wp_verify_nonce($_POST['cdb_empleado_equipo_nonce'], 'cdb_empleado_equipo_nonce_action')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['cdb_empleado_year'])) {
        update_post_meta($post_id, '_cdb_empleado_year', sanitize_text_field($_POST['cdb_empleado_year']));
    }

    if (isset($_POST['cdb_empleado_equipo'])) {
        update_post_meta($post_id, '_cdb_empleado_equipo', intval($_POST['cdb_empleado_equipo']));
    }
}
add_action('save_post_empleado', 'cdb_empleado_guardar_equipo_year');

// Mantén cualquier lógica adicional sobre permisos en archivo separado.
require_once plugin_dir_path(__FILE__) . 'inc/permisos.php';

// Se requiere el archivo con el shortcode y lógica extra
require_once plugin_dir_path(__FILE__) . 'inc/funciones-extra.php';

