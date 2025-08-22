<?php
// Obtener ID del CPT 'empleado' de un autor (utilidad general)
function cdb_empleado_get_post_by_author( $user_id ){
    $posts = get_posts([
        'post_type'      => 'empleado',
        'post_status'    => ['publish','private'],
        'posts_per_page' => 1,
        'author'         => (int) $user_id,
        'fields'         => 'ids',
    ]);
    return $posts ? (int)$posts[0] : 0;
}

// Puntuación total interoperable con cdb-grafica (filtro) + fallback por meta
function cdb_empleado_get_total_score( $empleado_id ){
    $from_filter = apply_filters('cdb_grafica/get_total_empleado', null, (int)$empleado_id);
    if (is_numeric($from_filter)) return (float)$from_filter;

    $from_meta = get_post_meta($empleado_id, '_cdb_total_grafica_empleado', true);
    return is_numeric($from_meta) ? (float)$from_meta : null;
}

// Puesto por ranking descendente de total
function cdb_empleado_get_rank( $empleado_id ){
    $my_total = cdb_empleado_get_total_score($empleado_id);
    if (!is_numeric($my_total)) return null;

    $ids = get_posts([
        'post_type'      => 'empleado',
        'post_status'    => ['publish','private'],
        'fields'         => 'ids',
        'numberposts'    => -1,
        'orderby'        => 'ID',
        'order'          => 'ASC',
    ]);

    $totales = [];
    foreach ($ids as $id){
        $t = cdb_empleado_get_total_score($id);
        if (is_numeric($t)) $totales[(int)$id] = (float)$t;
    }
    if (!$totales) return null;

    arsort($totales, SORT_NUMERIC);
    $rank = array_search((int)$empleado_id, array_keys($totales), true);
    return is_int($rank) ? ($rank + 1) : null;
}
