<?php
/**
 * Tarjeta octogonal 3×3 para CPT empleado.
 * Espera: $empleado_id (int)
 */
$empleado_title = strtoupper( get_the_title( $empleado_id ) );
$puesto         = cdb_empleado_get_rank( $empleado_id );
?>
<div class="cdb-card-oct" role="region" aria-label="<?php esc_attr_e( 'Tarjeta de empleado', 'cdb' ); ?>">
  <div class="cdb-card-oct__inner">
    <h3 class="cdb-card-oct__name"><?php echo esc_html( $empleado_title ); ?></h3>
    <div class="cdb-card-oct__avatar" aria-hidden="true">
      <svg viewBox="0 0 64 64" class="cdb-card-oct__svg" focusable="false" aria-hidden="true">
        <circle cx="32" cy="24" r="10"></circle>
        <path d="M12,56 C12,43 52,43 52,56 Z"></path>
      </svg>
    </div>
    <div class="cdb-card-oct__rank" aria-label="<?php esc_attr_e( 'Puesto en ranking', 'cdb' ); ?>">
      <span class="cdb-card-oct__rank-label"><?php esc_html_e( 'PUESTO', 'cdb' ); ?></span>
      <span class="cdb-card-oct__rank-num"><?php echo is_numeric( $puesto ) ? esc_html( str_pad( (string) $puesto, 2, '0', STR_PAD_LEFT ) ) : '--'; ?></span>
    </div>
  </div>
</div>
