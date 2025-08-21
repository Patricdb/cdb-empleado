<?php
/** Tarjeta octogonal 3×3 conforme a la referencia */
if ( ! function_exists('cdb_empleado_get_card_data') ) return;

$empleado_id = $empleado_id ?? get_the_ID();
$data        = cdb_empleado_get_card_data( (int)$empleado_id );
$name        = $data['name'] ?? '';
$rank        = $data['rank_current'] ?? null;
$history     = (array)($data['rank_history'] ?? [null,null,null]);
$top_groups  = array_slice( (array)($data['top_groups'] ?? []), 0, 3 );
$badges      = array_slice( (array)($data['badges'] ?? []), 0, 3 );

$positions_text = implode(', ', array_map(
  static function($v){ return $v ? (string)(int)$v : '—'; },
  array_slice($history, 0, 3)
));

$card_id = 'empcard8-'.(int)$empleado_id;
?>
<div class="cdb-empcard8" role="group" aria-labelledby="<?php echo esc_attr($card_id); ?>" aria-describedby="<?php echo esc_attr($card_id); ?>-desc">
  <!-- NAME -->
  <div class="cdb-empcard8__name" id="<?php echo esc_attr($card_id); ?>" title="<?php echo esc_attr($name); ?>">
    <?php echo esc_html( mb_strtoupper($name) ); ?>
  </div>

  <!-- BADGES -->
  <div class="cdb-empcard8__badges">
    <div class="cdb-empcard8__badges-title"><?php esc_html_e('Insignias', 'cdb-empleado'); ?></div>
    <div class="cdb-empcard8__badges-list" aria-label="<?php esc_attr_e('Insignias', 'cdb-empleado'); ?>">
      <?php if ($badges): foreach ($badges as $b): ?>
        <span class="cdb-empcard8__badge" title="<?php echo esc_attr($b['label'] ?? ''); ?>"></span>
      <?php endforeach; else: ?>
        <span class="cdb-empcard8__badge is-empty">—</span>
        <span class="cdb-empcard8__badge is-empty">—</span>
        <span class="cdb-empcard8__badge is-empty">—</span>
      <?php endif; ?>
    </div>
  </div>

  <!-- SILUETA -->
  <div class="cdb-empcard8__center" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor" focusable="false">
      <circle cx="128" cy="84" r="36"/><rect x="104" y="116" width="48" height="28" rx="10" ry="10"/>
      <path d="M64 224v-16c0-33.1 28.7-60 64-60s64 26.9 64 60v16H64z"/>
    </svg>
  </div>

  <!-- RANK -->
  <div class="cdb-empcard8__rank">
    <span class="cdb-empcard8__rank-label"><?php esc_html_e('Puesto', 'cdb-empleado'); ?></span>
    <span class="cdb-empcard8__rank-value" title="<?php esc_attr_e('Puesto en el ranking total de empleados', 'cdb-empleado'); ?>">
      <?php echo $rank ? esc_html( number_format_i18n( (int)$rank ) ) : 'ND'; ?>
    </span>
  </div>

  <!-- FOOTER -->
  <div class="cdb-empcard8__footer">
    <div>
      <span class="cdb-empcard8__section-title"><?php esc_html_e('Últimas posiciones', 'cdb-empleado'); ?></span>
      <div class="cdb-empcard8__positions-line"><?php echo esc_html( $positions_text ); ?></div>
    </div>

    <div>
      <span class="cdb-empcard8__section-title"><?php esc_html_e('Top grupos', 'cdb-empleado'); ?></span>
      <ul class="cdb-empcard8__groups-list" aria-label="<?php esc_attr_e('Tres grupos con mayor promedio', 'cdb-empleado'); ?>">
        <?php if ($top_groups): foreach ($top_groups as $g):
          $k = strtoupper( (string)($g['key'] ?? '') );
          $v = isset($g['avg']) ? round((float)$g['avg'], 1) : null; ?>
          <li class="cdb-empcard8__grp">
            <span class="cdb-empcard8__grp-code"><?php echo esc_html($k ?: '—'); ?></span>
            <span class="cdb-empcard8__grp-val"><?php echo is_null($v) ? '—' : esc_html( number_format_i18n($v,1) ); ?></span>
          </li>
        <?php endforeach; else: ?>
          <li class="cdb-empcard8__grp">—</li>
          <li class="cdb-empcard8__grp">—</li>
          <li class="cdb-empcard8__grp">—</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <span id="<?php echo esc_attr($card_id); ?>-desc" class="screen-reader-text">
    <?php
      $r = $rank ? sprintf( __( 'Puesto %d.', 'cdb-empleado' ), (int)$rank ) : __( 'Puesto no disponible.', 'cdb-empleado' );
      echo esc_html( $r . ' ' . __( 'Top grupos mostrados.', 'cdb-empleado' ) );
    ?>
  </span>
</div>
