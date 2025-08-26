<?php
// Asegurar que el archivo no se acceda directamente.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registrar ajustes y campos para CdB Empleado.
 */
function cdb_empleado_registrar_ajustes() {
    register_setting( 'cdb_empleado_general', 'inyectar_grafica', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_checkbox',
        'default'           => 1,
    ) );
    register_setting( 'cdb_empleado_general', 'inyectar_calificacion', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_checkbox',
        'default'           => 1,
    ) );

    add_settings_section(
        'cdb_empleado_general_section',
        __( 'Opciones generales', 'cdb-empleado' ),
        '__return_false',
        'cdb-empleado'
    );

    add_settings_field(
        'inyectar_grafica',
        __( 'Inyectar gráfica', 'cdb-empleado' ),
        'cdb_empleado_campo_inyectar_grafica',
        'cdb-empleado',
        'cdb_empleado_general_section'
    );

    add_settings_field(
        'inyectar_calificacion',
        __( 'Inyectar calificación', 'cdb-empleado' ),
        'cdb_empleado_campo_inyectar_calificacion',
        'cdb-empleado',
        'cdb_empleado_general_section'
    );
}
add_action( 'admin_init', 'cdb_empleado_registrar_ajustes' );

/**
 * Sanitizar valores de checkbox.
 *
 * @param mixed $valor Valor enviado desde el formulario.
 * @return int 1 si está marcado, 0 en caso contrario.
 */
function cdb_empleado_sanitizar_checkbox( $valor ) {
    return ! empty( $valor ) ? 1 : 0;
}

/**
 * Campo checkbox para el ajuste inyectar_grafica.
 */
function cdb_empleado_campo_inyectar_grafica() {
    $valor = get_option( 'inyectar_grafica', 1 );
    echo '<input type="checkbox" name="inyectar_grafica" value="1" ' . checked( 1, $valor, false ) . ' />';
}

/**
 * Campo checkbox para el ajuste inyectar_calificacion.
 */
function cdb_empleado_campo_inyectar_calificacion() {
    $valor = get_option( 'inyectar_calificacion', 1 );
    echo '<input type="checkbox" name="inyectar_calificacion" value="1" ' . checked( 1, $valor, false ) . ' />';
}

/**
 * Render de la página de ajustes generales.
 */
function cdb_empleado_pagina_ajustes() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'CdB Empleado', 'cdb-empleado' ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'cdb_empleado_general' );
            do_settings_sections( 'cdb-empleado' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Registrar el menú y submenú de ajustes.
 */
function cdb_empleado_registrar_menu() {
    add_menu_page(
        __( 'CdB Empleado', 'cdb-empleado' ),
        __( 'CdB Empleado', 'cdb-empleado' ),
        'manage_options',
        'cdb-empleado',
        'cdb_empleado_pagina_ajustes'
    );

    add_submenu_page(
        'cdb-empleado',
        __( 'General', 'cdb-empleado' ),
        __( 'General', 'cdb-empleado' ),
        'manage_options',
        'cdb-empleado',
        'cdb_empleado_pagina_ajustes'
    );
}
add_action( 'admin_menu', 'cdb_empleado_registrar_menu' );

/**
 * Filtros para inyectar gráfica y calificación según los ajustes.
 */
function cdb_empleado_opcion_inyectar_grafica() {
    return (bool) get_option( 'inyectar_grafica', 1 );
}
add_filter( 'cdb_empleado_inyectar_grafica', 'cdb_empleado_opcion_inyectar_grafica' );

function cdb_empleado_opcion_inyectar_calificacion() {
    return (bool) get_option( 'inyectar_calificacion', 1 );
}
add_filter( 'cdb_empleado_inyectar_calificacion', 'cdb_empleado_opcion_inyectar_calificacion' );
