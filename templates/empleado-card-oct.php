<?php
/**
 * Template for the octogonal employee card.
 *
 * @var int $empleado_id ID del empleado.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$data = cdb_empleado_get_card_data( $empleado_id );
?>
<div class="cdb-empleado-card-oct">
    <div class="cdb-empleado-card-oct__name"><?php echo esc_html( $data['name'] ); ?></div>
    <div class="cdb-empleado-card-oct__availability">
        <?php echo '1' === $data['availability'] ? esc_html__( 'Disponible', 'cdb-empleado' ) : esc_html__( 'No disponible', 'cdb-empleado' ); ?>
    </div>
    <div class="cdb-empleado-card-oct__total"><?php echo esc_html( number_format_i18n( $data['total'], 0 ) ); ?></div>
    <?php if ( ! empty( $data['top_groups'] ) ) : ?>
    <ul class="cdb-empleado-card-oct__top-groups">
        <?php foreach ( $data['top_groups'] as $group => $avg ) : ?>
            <li><?php echo esc_html( $group . ': ' . $avg ); ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <?php if ( isset( $data['rank_current'] ) ) : ?>
    <div class="cdb-empleado-card-oct__rank"><?php echo esc_html( $data['rank_current'] ); ?></div>
    <?php endif; ?>
    <?php if ( ! empty( $data['rank_history'] ) ) : ?>
    <ul class="cdb-empleado-card-oct__rank-history">
        <?php foreach ( $data['rank_history'] as $rank ) : ?>
            <li><?php echo is_null( $rank ) ? '-' : esc_html( $rank ); ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <div class="cdb-empleado-card-oct__badges">
        <?php foreach ( $data['badges'] as $badge ) : ?>
            <span class="cdb-empleado-card-oct__badge"><?php echo esc_html( $badge ); ?></span>
        <?php endforeach; ?>
    </div>
</div>
