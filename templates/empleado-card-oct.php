<?php
/** Plantilla tarjeta octogonal 3×3 */
if ( ! function_exists('cdb_empleado_get_card_data') ) return;

$empleado_id = $empleado_id ?? get_the_ID();
$data        = cdb_empleado_get_card_data( (int)$empleado_id );
$name        = $data['name'] ?? '';
$rank        = $data['rank_current'] ?? null;
$history     = $data['rank_history'] ?? [null,null,null];
$top_groups  = $data['top_groups'] ?? [];
$badges      = array_slice( (array)($data['badges'] ?? []), 0, 3 );
$card_id     = 'empcard8-'.(int)$empleado_id;
?>
<div class="cdb-empcard8" role="group" aria-labelledby="<?php echo esc_attr($card_id); ?>" aria-describedby="<?php echo esc_attr($card_id); ?>-desc">
  <div class="cdb-empcard8__name" id="<?php echo esc_attr($card_id); ?>" title="<?php echo esc_attr($name); ?>">
    <?php echo esc_html( $name ); ?>
  </div>

  <div class="cdb-empcard8__badges-wrap">
    <span class="cdb-empcard8__badges-label"><?php esc_html_e( 'Insignias', 'cdb-empleado' ); ?></span>
    <div class="cdb-empcard8__badges">
      <?php if ($badges): foreach ($badges as $b): ?>
        <span class="cdb-empcard8__badge" title="<?php echo esc_attr($b['label'] ?? ''); ?>"></span>
      <?php endforeach; else: ?>
        <span class="cdb-empcard8__badge is-empty" aria-hidden="true">—</span>
        <span class="cdb-empcard8__badge is-empty" aria-hidden="true">—</span>
        <span class="cdb-empcard8__badge is-empty" aria-hidden="true">—</span>
      <?php endif; ?>
    </div>
  </div>

  <div class="cdb-empcard8__center" aria-hidden="true">
    <img src="<?php echo esc_url( plugins_url( 'assets/img/empleado-silueta.svg', __FILE__ ) ); ?>" alt="" />
  </div>

  <div class="cdb-empcard8__rank">
    <span class="cdb-empcard8__rank-label"><?php esc_html_e('Puesto', 'cdb-empleado'); ?></span>
    <?php
      $title = $rank ? __( 'Puesto en el ranking total de empleados', 'cdb-empleado' )
                     : __( 'Aún sin ranking', 'cdb-empleado' );
    ?>
    <span class="cdb-empcard8__rank-value" title="<?php echo esc_attr( $title ); ?>">
      <?php echo $rank ? sprintf( '%02d', (int) $rank ) : 'ND'; ?>
    </span>
  </div>

  <div class="cdb-empcard8__footer">
    <div class="cdb-empcard8__positions">
      <span class="cdb-empcard8__section-title"><?php esc_html_e('Últimas posiciones', 'cdb-empleado'); ?></span>
      <?php
        $positions = array_slice($history, 0, 3);
        $text = implode(', ', array_map(
          fn($v) => $v ? (int)$v : '—', $positions));
      ?>
      <p class="cdb-empcard8__positions-values"><?php echo esc_html($text); ?></p>
    </div>
    <div class="cdb-empcard8__groups">
      <span class="cdb-empcard8__section-title"><?php esc_html_e('Top grupos', 'cdb-empleado'); ?></span>
      <?php $top_groups = array_slice( (array) $top_groups, 0, 3 ); ?>
      <div class="cdb-empcard8__groups-table">
        <?php foreach ( $top_groups as $g ):
          $k = strtoupper( (string) ( $g['key'] ?? '' ) );
          $v = isset( $g['avg'] ) ? round( (float) $g['avg'], 1 ) : null; ?>
          <div class="cdb-empcard8__grp">
            <span class="cdb-empcard8__grp-code"><?php echo esc_html( $k ?: '—' ); ?></span>
            <span class="cdb-empcard8__grp-val"><?php echo is_null( $v ) ? '—' : esc_html( number_format_i18n( $v, 1 ) ); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <span id="<?php echo esc_attr($card_id); ?>-desc" class="screen-reader-text">
    <?php
      $r = $rank ? sprintf( __( 'Puesto %d.', 'cdb-empleado' ), (int)$rank ) : __( 'Puesto no disponible.', 'cdb-empleado' );
      echo esc_html( $r );
    ?>
  </span>
</div>

