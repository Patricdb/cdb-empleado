<?php
/**
 * Template for estilos settings page.
 *
 * @package cdb-empleado
 */
?>
<div class="wrap">
    <h1><?php esc_html_e( 'Estilos de tarjeta', 'cdb-empleado' ); ?></h1>
    <?php cdb_empleado_admin_tabs( 'cdb-empleado-estilos' ); ?>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'cdb_empleado_estilos' );
        do_settings_sections( 'cdb-empleado-estilos' );
        submit_button();
        ?>
    </form>
    <div id="cdb-empleado-preview" class="cdb-preview">
        <h3 class="cdb-preview-title"><?php esc_html_e( 'Vista previa', 'cdb-empleado' ); ?></h3>
        <p><?php esc_html_e( 'Texto de ejemplo', 'cdb-empleado' ); ?></p>
    </div>
</div>
