<?php
/**
 * Template tags and helpers for CdB Empleado.
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Datos para la tarjeta octogonal.
 *
 * @param int $empleado_id ID del empleado.
 *
 * @return array
 */
function cdb_empleado_get_card_data( int $empleado_id ): array {
    $name         = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $empleado_id ) );
    $availability = get_post_meta( $empleado_id, 'disponible', true );
    $total        = (float) cdb_grafica_get_empleado_total( $empleado_id );

    $group_avgs = (array) cdb_grafica_get_empleado_group_avgs( $empleado_id );
    $items      = array();
    foreach ( $group_avgs as $group => $avg ) {
        $items[] = array(
            'key'   => $group,
            'value' => (float) $avg,
        );
    }

    usort( $items, function ( $a, $b ) {
        if ( $a['value'] === $b['value'] ) {
            return strcmp( (string) $a['key'], (string) $b['key'] );
        }
        return ( $a['value'] < $b['value'] ) ? 1 : -1;
    } );

    $top_groups = array();
    foreach ( array_slice( $items, 0, 3 ) as $item ) {
        $top_groups[ $item['key'] ] = $item['value'];
    }

    $rank_current = cdb_empleado_get_rank_current( $empleado_id );

    $data = array(
        'name'         => $name,
        'availability' => $availability,
        'total'        => $total,
        'top_groups'   => $top_groups,
        'rank_current' => $rank_current,
        'rank_history' => array( null, null, null ),
        'badges'       => array(),
    );

    return apply_filters( 'cdb_empleado_card_data', $data, $empleado_id );
}

/**
 * Ranking provisional cacheado.
 *
 * @param int $empleado_id ID del empleado.
 *
 * @return int|null Posición actual o null si no aplica.
 */
function cdb_empleado_get_rank_current( int $empleado_id ): ?int {
    $map = get_transient( 'cdb_empleado_rank_map' );

    if ( ! is_array( $map ) || ! isset( $map[ $empleado_id ] ) ) {
        $ids = get_posts( array(
            'post_type'              => 'empleado',
            'post_status'            => 'publish',
            'posts_per_page'         => -1,
            'fields'                 => 'ids',
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'cache_results'          => false,
        ) );

        $totals = array();
        foreach ( $ids as $id ) {
            $totals[ $id ] = (float) cdb_grafica_get_empleado_total( $id );
        }

        arsort( $totals );

        $map      = array();
        $position = 1;
        foreach ( array_keys( $totals ) as $id ) {
            $map[ $id ] = $position++;
        }

        $ttl = (int) apply_filters( 'cdb_empleado_rank_ttl', 600 );
        set_transient( 'cdb_empleado_rank_map', $map, $ttl );
    }

    $rank = isset( $map[ $empleado_id ] ) ? (int) $map[ $empleado_id ] : null;

    return apply_filters( 'cdb_empleado_rank_current', $rank, $empleado_id );
}

/**
 * Renderiza la tarjeta de perfil del empleado.
 *
 * @param int $empleado_id Opcional. ID del empleado. Usa el post actual si es 0.
 */
function cdb_empleado_render_profile_card( $empleado_id = 0 ) {
    $empleado_id = $empleado_id ? (int) $empleado_id : get_the_ID();
    if ( apply_filters( 'cdb_empleado_use_new_card', false ) ) {
        $empleado_id = get_the_ID();
        echo '<section class="cdb-vis-pair" data-cdb-vis="pair">';
          echo '<div class="cdb-vis-pair__card">';
            include CDB_EMPLEADO_PLUGIN_DIR . 'templates/empleado-card-oct.php';
          echo '</div>';
          echo '<div class="cdb-vis-pair__chart">';
            // La gráfica se inyectará desde cdb-grafica en el siguiente prompt
            do_action( 'cdb_grafica/render_radar_empleado', $empleado_id );
          echo '</div>';
        echo '</section>';
        return; // evita render legacy
    }

    $empleado_author = (int) get_post_field( 'post_author', $empleado_id );
    $disponible      = ( '1' === get_post_meta( $empleado_id, 'disponible', true ) );
    $total           = (float) apply_filters( 'cdb_grafica_empleado_total', 0, $empleado_id );

    echo '<div class="cdb-empleado-card">';
    echo '<div class="cdb-empleado-card__avatar">' . get_avatar( $empleado_author, 96 ) . '</div>';
    echo '<div class="cdb-empleado-card__name">' . esc_html( get_the_title( $empleado_id ) ) . '</div>';
    echo '<div class="cdb-pill ' . ( $disponible ? 'ok' : 'off' ) . '">';
    echo $disponible ? __( 'Disponible', 'cdb-empleado' ) : __( 'No disponible', 'cdb-empleado' );
    echo '</div>';
    echo '<div class="cdb-empleado-card__score" aria-label="Puntuación total">' .
         number_format_i18n( $total, 0 ) . '</div>';
    echo '</div>';
}

// Feature flag: nueva tarjeta desactivada por defecto.
add_filter( 'cdb_empleado_use_new_card', '__return_false' );
