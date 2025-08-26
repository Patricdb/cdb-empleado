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

    register_setting( 'cdb_empleado_roles', 'role_autores', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_role_autores',
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
        'role_autores',
        __( 'Roles permitidos en selector', 'cdb-empleado' ),
        'cdb_empleado_campo_role_autores',
        'cdb-empleado-roles',
        'cdb_empleado_roles_section'
    );
}
add_action( 'admin_init', 'cdb_empleado_registrar_ajustes_roles' );

/**
 * Registrar ajustes de metacampos.
 */
function cdb_empleado_registrar_ajustes_metacampos() {
    register_setting( 'cdb_empleado_metacampos', 'label_disponible', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Disponible',
    ) );
    register_setting( 'cdb_empleado_metacampos', 'default_disponible', array(
        'sanitize_callback' => 'cdb_empleado_sanitizar_checkbox',
        'default'           => 1,
    ) );

    add_settings_section(
        'cdb_empleado_metacampos_section',
        __( 'Metacampo Disponible', 'cdb-empleado' ),
        '__return_false',
        'cdb-empleado-metacampos'
    );

    add_settings_field(
        'label_disponible',
        __( 'Etiqueta personalizada', 'cdb-empleado' ),
        'cdb_empleado_campo_label_disponible',
        'cdb-empleado-metacampos',
        'cdb_empleado_metacampos_section'
    );

    add_settings_field(
        'default_disponible',
        __( 'Valor por defecto', 'cdb-empleado' ),
        'cdb_empleado_campo_default_disponible',
        'cdb-empleado-metacampos',
        'cdb_empleado_metacampos_section'
    );
}
add_action( 'admin_init', 'cdb_empleado_registrar_ajustes_metacampos' );

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
function cdb_empleado_sanitizar_role_autores( $valor ) {
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
function cdb_empleado_campo_role_autores() {
    $valor = (array) get_option( 'role_autores', array( 'administrator', 'editor', 'author', 'empleado' ) );
    global $wp_roles;

    echo '<select name="role_autores[]" multiple="multiple" size="5">';
    foreach ( $wp_roles->roles as $role_key => $data ) {
        echo '<option value="' . esc_attr( $role_key ) . '" ' . selected( in_array( $role_key, $valor, true ), true, false ) . '>' . esc_html( $data['name'] ) . '</option>';
    }
    echo '</select>';
}

/**
 * Campo de texto para la etiqueta del metacampo disponible.
 */
function cdb_empleado_campo_label_disponible() {
    $valor = get_option( 'label_disponible', 'Disponible' );
    echo '<input type="text" name="label_disponible" value="' . esc_attr( $valor ) . '" />';
}

/**
 * Campo select para el valor por defecto del metacampo disponible.
 */
function cdb_empleado_campo_default_disponible() {
    $valor = get_option( 'default_disponible', 1 );
    echo '<select name="default_disponible">';
    echo '<option value="1" ' . selected( $valor, 1, false ) . '>' . esc_html__( 'Sí', 'cdb-empleado' ) . '</option>';
    echo '<option value="0" ' . selected( $valor, 0, false ) . '>' . esc_html__( 'No', 'cdb-empleado' ) . '</option>';
    echo '</select>';
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
 * Render de la página de ajustes de metacampos.
 */
function cdb_empleado_pagina_metacampos() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Metacampos', 'cdb-empleado' ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'cdb_empleado_metacampos' );
            do_settings_sections( 'cdb-empleado-metacampos' );
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
 * Render de la página de integraciones.
 */
function cdb_empleado_pagina_integraciones() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Integraciones', 'cdb-empleado' ); ?></h1>

        <h2><?php esc_html_e( 'Shortcodes', 'cdb-empleado' ); ?></h2>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Shortcode', 'cdb-empleado' ); ?></th>
                    <th><?php esc_html_e( 'Atributos', 'cdb-empleado' ); ?></th>
                    <th><?php esc_html_e( 'Descripción', 'cdb-empleado' ); ?></th>
                    <th><?php esc_html_e( 'Ejemplo', 'cdb-empleado' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>equipos_del_empleado</code></td>
                    <td><code>empleado_id</code> (int, <?php esc_html_e( 'opcional; por defecto el post ID', 'cdb-empleado' ); ?>)</td>
                    <td><?php esc_html_e( 'Lista equipos y posiciones del empleado consultando ${prefix}cdb_experiencia.', 'cdb-empleado' ); ?></td>
                    <td><code>[equipos_del_empleado empleado_id="15"]</code></td>
                </tr>
            </tbody>
        </table>

        <h2><?php esc_html_e( 'Hooks y filtros', 'cdb-empleado' ); ?></h2>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Hook/filtro', 'cdb-empleado' ); ?></th>
                    <th><?php esc_html_e( 'Propósito', 'cdb-empleado' ); ?></th>
                    <th><?php esc_html_e( 'Parámetros', 'cdb-empleado' ); ?></th>
                    <th><?php esc_html_e( 'Retorno', 'cdb-empleado' ); ?></th>
                    <th><?php esc_html_e( 'Ejemplo', 'cdb-empleado' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>cdb_empleado_inyectar_grafica</code></td>
                    <td><?php esc_html_e( 'Habilita/inhibe la gráfica en single de empleado.', 'cdb-empleado' ); ?></td>
                    <td><code>bool $enabled, int $empleado_id</code></td>
                    <td><code>bool</code></td>
                    <td><code>add_filter('cdb_empleado_inyectar_grafica','__return_false');</code></td>
                </tr>
                <tr>
                    <td><code>cdb_empleado_inyectar_calificacion</code></td>
                    <td><?php esc_html_e( 'Controla el bloque de calificación.', 'cdb-empleado' ); ?></td>
                    <td><code>bool $enabled, int $empleado_id</code></td>
                    <td><code>bool</code></td>
                    <td><code>add_filter('cdb_empleado_inyectar_calificacion','__return_false');</code></td>
                </tr>
                <tr>
                    <td><code>cdb_grafica_empleado_html</code></td>
                    <td><?php esc_html_e( 'Sustituye el HTML de la gráfica.', 'cdb-empleado' ); ?></td>
                    <td><code>string $html, int $empleado_id, array $args</code></td>
                    <td><code>string</code></td>
                    <td><code>add_filter('cdb_grafica_empleado_html','mi_callback',10,3);</code></td>
                </tr>
                <tr>
                    <td><code>cdb_grafica_empleado_form_html</code></td>
                    <td><?php esc_html_e( 'Personaliza el formulario de calificación.', 'cdb-empleado' ); ?></td>
                    <td><code>string $html, int $empleado_id, array $args</code></td>
                    <td><code>string</code></td>
                    <td><code>add_filter('cdb_grafica_empleado_form_html','mi_form',10,3);</code></td>
                </tr>
                <tr>
                    <td><code>cdb_grafica_empleado_scores_table_html</code></td>
                    <td><?php esc_html_e( 'Ajusta la tabla de puntuaciones.', 'cdb-empleado' ); ?></td>
                    <td><code>string $html, int $empleado_id, array $args</code></td>
                    <td><code>string</code></td>
                    <td><code>add_filter('cdb_grafica_empleado_scores_table_html','mi_tabla',10,3);</code></td>
                </tr>
                <tr>
                    <td><code>cdb_grafica_empleado_total</code></td>
                    <td><?php esc_html_e( 'Modifica el puntaje total mostrado.', 'cdb-empleado' ); ?></td>
                    <td><code>float $total, int $empleado_id</code></td>
                    <td><code>float</code></td>
                    <td><code>add_filter('cdb_grafica_empleado_total','mi_total',10,2);</code></td>
                </tr>
                <tr>
                    <td><code>cdb_empleado_use_new_card</code></td>
                    <td><?php esc_html_e( 'Decide uso de tarjeta alternativa.', 'cdb-empleado' ); ?></td>
                    <td><code>bool $use_new, int $empleado_id</code></td>
                    <td><code>bool</code></td>
                    <td><code>add_filter('cdb_empleado_use_new_card','__return_true');</code></td>
                </tr>
                <tr>
                    <td><code>cdb_grafica_empleado_notice</code></td>
                    <td><?php esc_html_e( 'Personaliza aviso en la sección de calificación.', 'cdb-empleado' ); ?></td>
                    <td><code>string $msg, int $empleado_id</code></td>
                    <td><code>string</code></td>
                    <td><code>add_filter('cdb_grafica_empleado_notice','mi_aviso',10,2);</code></td>
                </tr>
                <tr>
                    <td><code>cdb_empleado_card_data</code></td>
                    <td><?php esc_html_e( 'Filtra datos mostrados en la tarjeta.', 'cdb-empleado' ); ?></td>
                    <td><code>array $data, int $empleado_id</code></td>
                    <td><code>array</code></td>
                    <td><code>add_filter('cdb_empleado_card_data','mi_data',10,2);</code></td>
                </tr>
                <tr>
                    <td><code>cdb_empleado_rank_ttl</code></td>
                    <td><?php esc_html_e( 'Ajusta duración del caché de rankings.', 'cdb-empleado' ); ?></td>
                    <td><code>int $seconds</code></td>
                    <td><code>int</code></td>
                    <td><code>add_filter('cdb_empleado_rank_ttl', fn()=>300);</code></td>
                </tr>
                <tr>
                    <td><code>cdb_empleado_rank_current</code></td>
                    <td><?php esc_html_e( 'Modifica ranking calculado para un empleado.', 'cdb-empleado' ); ?></td>
                    <td><code>mixed $rank, int $empleado_id</code></td>
                    <td><code>mixed</code></td>
                    <td><code>add_filter('cdb_empleado_rank_current','mi_rank',10,2);</code></td>
                </tr>
            </tbody>
        </table>

        <p>
            <?php esc_html_e( 'Más información en:', 'cdb-empleado' ); ?>
            <a href="https://github.com/proyectocdb/cdb-empleado#shortcodes" target="_blank"><?php esc_html_e( 'README – Shortcodes', 'cdb-empleado' ); ?></a>,
            <a href="https://github.com/proyectocdb/cdb-empleado#hooks-y-filtros" target="_blank"><?php esc_html_e( 'README – Hooks y filtros', 'cdb-empleado' ); ?></a>,
            <a href="https://developer.wordpress.org/plugins/shortcodes/" target="_blank"><?php esc_html_e( 'Shortcodes en WordPress', 'cdb-empleado' ); ?></a>,
            <a href="https://developer.wordpress.org/plugins/hooks/" target="_blank"><?php esc_html_e( 'Hooks en WordPress', 'cdb-empleado' ); ?></a>.
        </p>
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
        __( 'Metacampos', 'cdb-empleado' ),
        __( 'Metacampos', 'cdb-empleado' ),
        'manage_options',
        'cdb-empleado-metacampos',
        'cdb_empleado_pagina_metacampos'
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
        __( 'Integraciones', 'cdb-empleado' ),
        __( 'Integraciones', 'cdb-empleado' ),
        'manage_options',
        'cdb-empleado-integraciones',
        'cdb_empleado_pagina_integraciones'
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
