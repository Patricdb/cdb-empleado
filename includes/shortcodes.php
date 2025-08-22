<?php
add_shortcode('cdb_tarjeta_empleado', function($atts){
    $atts = shortcode_atts(['empleado_id'=>0,'debug'=>'0'], $atts);
    $empleado_id = $atts['empleado_id'] ? (int)$atts['empleado_id'] : cdb_empleado_get_post_by_author( get_current_user_id() );
    if (!$empleado_id) return '';

    ob_start();
    include CDB_EMPLEADO_PLUGIN_DIR . 'templates/empleado-card-oct.php';
    return ob_get_clean();
});
