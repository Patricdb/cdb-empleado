<?php
/** Tarjeta empleado basada en SVG con contorno y textos */
if ( ! function_exists('cdb_empleado_get_card_data') ) return;

$empleado_id = $empleado_id ?? get_the_ID();
$data     = cdb_empleado_get_card_data( (int) $empleado_id );
$name     = $data['name'] ?? '';
$rank     = $data['rank_current'] ?? null;
$rank_str = is_numeric($rank) ? str_pad((string) (int) $rank, 2, '0', STR_PAD_LEFT) : 'ND';
$card_id  = 'empcard8-' . (int) $empleado_id;

$parts = preg_split('/\s+/', trim($name));
$line1 = mb_strtoupper( $parts[0] ?? '', 'UTF-8' );
$line2 = isset($parts[1]) ? mb_strtoupper( implode(' ', array_slice($parts,1)), 'UTF-8' ) : '';
?>

<div class="cdb-empcard8" role="region" aria-labelledby="<?php echo esc_attr($card_id); ?>">
  <svg viewBox="0 0 888 874" aria-label="<?php esc_attr_e('Tarjeta empleado', 'cdb-empleado'); ?>">
    <?php
    $bg_svg = get_option( 'tarjeta_oct_bg_svg', '' );
    if ( '' === $bg_svg ) {
        $bg_svg = file_get_contents( plugin_dir_path( __FILE__ ) . '../assets/svg/tarjeta-oct-bg.svg' );
    }
    echo $bg_svg;
    ?>
    <g class="t" text-anchor="middle">
      <text class="t name" x="50%" y="155" font-size="90" id="<?php echo esc_attr($card_id); ?>">
        <tspan x="50%" dy="0"><?php echo esc_html($line1); ?></tspan>
        <?php if ( $line2 ) : ?>
        <tspan x="50%" dy="88"><?php echo esc_html($line2); ?></tspan>
        <?php endif; ?>
      </text>
    </g>
    <g class="t t--small" text-anchor="start">
      <text class="puesto" x="620" y="320" font-size="40"><?php esc_html_e('Puesto', 'cdb-empleado'); ?></text>
      <text class="num" x="620" y="540" font-size="180" font-weight="900"><?php echo esc_html($rank_str); ?></text>
    </g>
  </svg>
</div>

