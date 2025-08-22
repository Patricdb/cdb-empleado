<?php
/** Tarjeta empleado: octágono regular + 3×3 (Nombre, Silueta, Puesto) */
if ( ! function_exists('cdb_empleado_get_card_data') ) return;

$empleado_id = $empleado_id ?? get_the_ID();
$data     = cdb_empleado_get_card_data( (int) $empleado_id );
$name     = $data['name'] ?? '';
$rank     = $data['rank_current'] ?? null;
$rank_str = is_numeric($rank) ? str_pad((string)(int)$rank, 2, '0', STR_PAD_LEFT) : 'ND';
$card_id  = 'empcard8-' . (int) $empleado_id;
?>

<div class="cdb-empcard8" role="region" aria-labelledby="<?php echo esc_attr($card_id); ?>">
  <!-- F1 C2 — Nombre -->
  <h3 class="cdb-empcard8__name" id="<?php echo esc_attr($card_id); ?>" title="<?php echo esc_attr($name); ?>">
    <?php echo esc_html($name); ?>
  </h3>

  <!-- F2 C2 — Silueta -->
  <div class="cdb-empcard8__center" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor" focusable="false">
      <circle cx="128" cy="84" r="36"/>
      <rect x="104" y="116" width="48" height="28" rx="10" ry="10"/>
      <path d="M64 224v-16c0-33.1 28.7-60 64-60s64 26.9 64 60v16H64z"/>
    </svg>
  </div>

  <!-- F2 C3 — Puesto -->
  <div class="cdb-empcard8__rank" aria-label="<?php esc_attr_e('Puesto en ranking', 'cdb-empleado'); ?>">
    <span class="cdb-empcard8__rank-label"><?php esc_html_e('Puesto', 'cdb-empleado'); ?></span>
    <span class="cdb-empcard8__rank-value"><?php echo esc_html($rank_str); ?></span>
  </div>
</div>

