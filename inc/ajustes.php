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
 * Registrar ajustes de estilos de la tarjeta.
 */
function cdb_empleado_registrar_ajustes_estilos() {
    register_setting( 'cdb_empleado_estilos', 'usar_tarjeta_oct', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_checkbox',
        'default'           => 0,
    ) );
    register_setting( 'cdb_empleado_estilos', 'tarjeta_oct_ink', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'default'           => '#66604e',
    ) );
    register_setting( 'cdb_empleado_estilos', 'tarjeta_oct_bg_start', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'default'           => '#f5e8c8',
    ) );
    register_setting( 'cdb_empleado_estilos', 'tarjeta_oct_bg_end', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'default'           => '#efe1b4',
    ) );
    register_setting( 'cdb_empleado_estilos', 'tarjeta_oct_font_body', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_fuente',
        'default'           => 'sans',
    ) );
    register_setting( 'cdb_empleado_estilos', 'tarjeta_oct_font_heading', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_fuente',
        'default'           => 'sans',
    ) );

    add_settings_section(
        'cdb_empleado_estilos_section',
        __( 'Tarjeta', 'cdb-empleado' ),
        '__return_false',
        'cdb-empleado-estilos'
    );

    add_settings_field(
        'usar_tarjeta_oct',
        __( 'Usar tarjeta octogonal', 'cdb-empleado' ),
        'cdb_empleado_campo_usar_tarjeta_oct',
        'cdb-empleado-estilos',
        'cdb_empleado_estilos_section'
    );

    add_settings_field(
        'tarjeta_oct_ink',
        __( 'Color de tinta', 'cdb-empleado' ),
        'cdb_empleado_campo_tarjeta_oct_ink',
        'cdb-empleado-estilos',
        'cdb_empleado_estilos_section'
    );

    add_settings_field(
        'tarjeta_oct_bg',
        __( 'Gradiente de fondo', 'cdb-empleado' ),
        'cdb_empleado_campo_tarjeta_oct_bg',
        'cdb-empleado-estilos',
        'cdb_empleado_estilos_section'
    );

    add_settings_field(
        'tarjeta_oct_font_body',
        __( 'Fuente principal', 'cdb-empleado' ),
        'cdb_empleado_campo_tarjeta_oct_font_body',
        'cdb-empleado-estilos',
        'cdb_empleado_estilos_section'
    );

    add_settings_field(
        'tarjeta_oct_font_heading',
        __( 'Fuente de encabezados', 'cdb-empleado' ),
        'cdb_empleado_campo_tarjeta_oct_font_heading',
        'cdb-empleado-estilos',
        'cdb_empleado_estilos_section'
    );
}
add_action( 'admin_init', 'cdb_empleado_registrar_ajustes_estilos' );

/**
 * Registrar ajustes de rendimiento.
 */
function cdb_empleado_registrar_ajustes_rendimiento() {
    register_setting( 'cdb_empleado_rendimiento', 'rank_ttl', array(
        'sanitize_callback' => 'absint',
        'default'           => 600,
    ) );

    add_settings_section(
        'cdb_empleado_rendimiento_section',
        __( 'Opciones de rendimiento', 'cdb-empleado' ),
        '__return_false',
        'cdb-empleado-rendimiento'
    );

    add_settings_field(
        'rank_ttl',
        __( 'TTL de ranking (segundos)', 'cdb-empleado' ),
        'cdb_empleado_campo_rank_ttl',
        'cdb-empleado-rendimiento',
        'cdb_empleado_rendimiento_section'
    );
}
add_action( 'admin_init', 'cdb_empleado_registrar_ajustes_rendimiento' );

/**
 * Registrar ajustes de roles y capacidades.
 */
function cdb_empleado_registrar_ajustes_roles() {
    register_setting( 'cdb_empleado_roles', 'cdb_empleado_extra_caps', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_caps',
        'default'           => array(),
    ) );

    register_setting( 'cdb_empleado_roles', 'cdb_empleado_selector_roles', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_roles_selector',
        'default'           => array( 'administrator', 'editor', 'author', 'empleado' ),
    ) );

    add_settings_section(
        'cdb_empleado_roles_section',
        __( 'Roles y capacidades', 'cdb-empleado' ),
        '__return_false',
        'cdb-empleado-roles'
    );

    add_settings_field(
        'cdb_empleado_extra_caps',
        __( 'Capacidades extra', 'cdb-empleado' ),
        'cdb_empleado_campo_extra_caps',
        'cdb-empleado-roles',
        'cdb_empleado_roles_section'
    );

    add_settings_field(
        'cdb_empleado_selector_roles',
        __( 'Roles permitidos en selector', 'cdb-empleado' ),
        'cdb_empleado_campo_selector_roles',
        'cdb-empleado-roles',
        'cdb_empleado_roles_section'
    );
}
add_action( 'admin_init', 'cdb_empleado_registrar_ajustes_roles' );

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
 * Sanitizar selección de fuente.
 *
 * @param string $valor Valor enviado.
 * @return string Clave de fuente permitida.
 */
function cdb_empleado_sanitizar_fuente( $valor ) {
    $permitidas = array( 'sans', 'serif', 'mono' );
    return in_array( $valor, $permitidas, true ) ? $valor : 'sans';
}

/**
 * Sanitizar capacidades extra del rol empleado.
 *
 * @param array $valor Capacidades enviadas.
 * @return array Capacidades permitidas.
 */
function cdb_empleado_sanitizar_caps( $valor ) {
    $permitidas = array( 'edit_posts', 'upload_files' );
    $valor      = is_array( $valor ) ? $valor : array();
    return array_values( array_intersect( $valor, $permitidas ) );
}

/**
 * Sanitizar roles permitidos en el selector de autores.
 *
 * @param array $valor Roles enviados.
 * @return array Roles válidos.
 */
function cdb_empleado_sanitizar_roles_selector( $valor ) {
    global $wp_roles;
    $todos = array_keys( $wp_roles->roles );
    $valor = is_array( $valor ) ? $valor : array();
    return array_values( array_intersect( $valor, $todos ) );
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
 * Campo checkbox para el ajuste usar_tarjeta_oct.
 */
function cdb_empleado_campo_usar_tarjeta_oct() {
    $valor = get_option( 'usar_tarjeta_oct', 0 );
    echo '<input type="checkbox" name="usar_tarjeta_oct" value="1" ' . checked( 1, $valor, false ) . ' />';
}

/**
 * Campo color para la variable --ink.
 */
function cdb_empleado_campo_tarjeta_oct_ink() {
    $valor = get_option( 'tarjeta_oct_ink', '#66604e' );
    echo '<input type="color" name="tarjeta_oct_ink" value="' . esc_attr( $valor ) . '" />';
}

/**
 * Campo para gradiente de fondo.
 */
function cdb_empleado_campo_tarjeta_oct_bg() {
    $ini = get_option( 'tarjeta_oct_bg_start', '#f5e8c8' );
    $fin = get_option( 'tarjeta_oct_bg_end', '#efe1b4' );
    echo '<input type="color" name="tarjeta_oct_bg_start" value="' . esc_attr( $ini ) . '" /> ';
    echo '<input type="color" name="tarjeta_oct_bg_end" value="' . esc_attr( $fin ) . '" />';
}

/**
 * Campo numérico para el ajuste rank_ttl.
 */
function cdb_empleado_campo_rank_ttl() {
    $valor = get_option( 'rank_ttl', 600 );
    echo '<input type="number" name="rank_ttl" value="' . esc_attr( $valor ) . '" min="0" step="1" />';
}

/**
 * Campo para seleccionar capacidades extra del rol empleado.
 */
function cdb_empleado_campo_extra_caps() {
    $valor = (array) get_option( 'cdb_empleado_extra_caps', array() );
    $caps  = array(
        'edit_posts'   => __( 'Editar entradas', 'cdb-empleado' ),
        'upload_files' => __( 'Subir archivos', 'cdb-empleado' ),
    );

    foreach ( $caps as $cap => $label ) {
        echo '<label><input type="checkbox" name="cdb_empleado_extra_caps[]" value="' . esc_attr( $cap ) . '" ' . checked( in_array( $cap, $valor, true ), true, false ) . ' /> ' . esc_html( $label ) . '</label><br />';
    }
}

/**
 * Campo para seleccionar roles permitidos en el selector de autores.
 */
function cdb_empleado_campo_selector_roles() {
    $valor = (array) get_option( 'cdb_empleado_selector_roles', array( 'administrator', 'editor', 'author', 'empleado' ) );
    global $wp_roles;

    foreach ( $wp_roles->roles as $role_key => $data ) {
        echo '<label><input type="checkbox" name="cdb_empleado_selector_roles[]" value="' . esc_attr( $role_key ) . '" ' . checked( in_array( $role_key, $valor, true ), true, false ) . ' /> ' . esc_html( $data['name'] ) . '</label><br />';
    }
}

/**
 * Opciones de fuentes disponibles.
 */
function cdb_empleado_fuentes_disponibles() {
    return array(
        'sans' => array(
            'label' => __( 'Sans serif', 'cdb-empleado' ),
            'stack' => 'ui-sans-serif,system-ui,-apple-system,"Helvetica Neue",Arial,sans-serif',
        ),
        'serif' => array(
            'label' => __( 'Serif', 'cdb-empleado' ),
            'stack' => 'ui-serif,Georgia,Cambria,"Times New Roman",Times,serif',
        ),
        'mono' => array(
            'label' => __( 'Monospace', 'cdb-empleado' ),
            'stack' => 'ui-monospace,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace',
        ),
    );
}

/**
 * Campo select para fuente principal.
 */
function cdb_empleado_campo_tarjeta_oct_font_body() {
    $valor   = get_option( 'tarjeta_oct_font_body', 'sans' );
    $fuentes = cdb_empleado_fuentes_disponibles();
    echo '<select name="tarjeta_oct_font_body">';
    foreach ( $fuentes as $key => $data ) {
        echo '<option value="' . esc_attr( $key ) . '" ' . selected( $valor, $key, false ) . '>' . esc_html( $data['label'] ) . '</option>';
    }
    echo '</select>';
}

/**
 * Campo select para fuente de encabezados.
 */
function cdb_empleado_campo_tarjeta_oct_font_heading() {
    $valor   = get_option( 'tarjeta_oct_font_heading', 'sans' );
    $fuentes = cdb_empleado_fuentes_disponibles();
    echo '<select name="tarjeta_oct_font_heading">';
    foreach ( $fuentes as $key => $data ) {
        echo '<option value="' . esc_attr( $key ) . '" ' . selected( $valor, $key, false ) . '>' . esc_html( $data['label'] ) . '</option>';
    }
    echo '</select>';
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
 * Render de la página de ajustes de estilos.
 */
function cdb_empleado_pagina_estilos() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Estilos de tarjeta', 'cdb-empleado' ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'cdb_empleado_estilos' );
            do_settings_sections( 'cdb-empleado-estilos' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Render de la página de ajustes de rendimiento.
 */
function cdb_empleado_pagina_rendimiento() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Ajustes de rendimiento', 'cdb-empleado' ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'cdb_empleado_rendimiento' );
            do_settings_sections( 'cdb-empleado-rendimiento' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Render de la página de ajustes de roles.
 */
function cdb_empleado_pagina_roles() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Roles', 'cdb-empleado' ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'cdb_empleado_roles' );
            do_settings_sections( 'cdb-empleado-roles' );
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

    add_submenu_page(
        'cdb-empleado',
        __( 'Estilos', 'cdb-empleado' ),
        __( 'Estilos', 'cdb-empleado' ),
        'manage_options',
        'cdb-empleado-estilos',
        'cdb_empleado_pagina_estilos'
    );

    add_submenu_page(
        'cdb-empleado',
        __( 'Roles', 'cdb-empleado' ),
        __( 'Roles', 'cdb-empleado' ),
        'manage_options',
        'cdb-empleado-roles',
        'cdb_empleado_pagina_roles'
    );

    add_submenu_page(
        'cdb-empleado',
        __( 'Rendimiento', 'cdb-empleado' ),
        __( 'Rendimiento', 'cdb-empleado' ),
        'manage_options',
        'cdb-empleado-rendimiento',
        'cdb_empleado_pagina_rendimiento'
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

/**
 * Filtro para usar tarjeta octogonal según ajuste.
 */
function cdb_empleado_opcion_usar_tarjeta_oct() {
    return (bool) get_option( 'usar_tarjeta_oct', 0 );
}
add_filter( 'cdb_empleado_use_new_card', 'cdb_empleado_opcion_usar_tarjeta_oct', 20 );

/**
 * Filtro para TTL del ranking según ajuste.
 */
function cdb_empleado_opcion_rank_ttl() {
    return (int) get_option( 'rank_ttl', 600 );
}
add_filter( 'cdb_empleado_rank_ttl', 'cdb_empleado_opcion_rank_ttl' );
