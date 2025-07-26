<?php
/**
 * Definición del rol personalizado "empleado".
 */
if (!defined('ABSPATH')) {
    exit;
}

function cdb_empleado_registrar_rol() {
    add_role(
        'empleado',
        'Empleado',
        array(
            'read' => true,
        )
    );
}

function cdb_empleado_eliminar_rol() {
    remove_role('empleado');
}
