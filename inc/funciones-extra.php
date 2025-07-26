<?php
/**
 * Shortcode: [equipos_del_empleado empleado_id="123"]
 * Muestra los equipos a los que pertenece un Empleado, con enlace a cada Posición.
 */
function cdb_equipos_del_empleado_shortcode($atts) {
    // Atributos por defecto
    $atts = shortcode_atts(array(
        'empleado_id' => 0
    ), $atts, 'equipos_del_empleado');

    $empleado_id = (int) $atts['empleado_id'];

    // Si no se especifica empleado_id, usamos el ID del post actual
    if (!$empleado_id) {
        $empleado_id = get_the_ID();
    }

    // Verificamos que sea realmente un CPT "empleado"
    if (get_post_type($empleado_id) !== 'empleado') {
        return '<p>No se ha especificado un empleado válido para mostrar sus equipos.</p>';
    }

    // Obtenemos el nombre del Empleado
    $empleado_nombre = get_the_title($empleado_id);

    global $wpdb;
    $tabla_exp = $wpdb->prefix . 'cdb_experiencia';

    // 1. Obtener IDs de equipos donde participa este empleado
    $equipos_ids = $wpdb->get_col(
        $wpdb->prepare("
            SELECT DISTINCT equipo_id
            FROM $tabla_exp
            WHERE empleado_id = %d
        ", $empleado_id)
    );

    // Si no hay equipos, mensaje
    if (empty($equipos_ids)) {
        return '<p>' . esc_html($empleado_nombre) . ' no forma parte de ningún equipo.</p>';
    }

    // 2. Traer los posts del CPT "equipo"
    $equipos = get_posts(array(
        'post_type'      => 'equipo',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'post__in'       => $equipos_ids,
        'orderby'        => 'meta_value_num',
        'meta_key'       => '_cdb_equipo_year',
        'order'          => 'DESC'
    ));

    if (empty($equipos)) {
        return '<p>' . esc_html($empleado_nombre) . ' no forma parte de ningún equipo publicado.</p>';
    }

    // 3. Renderizamos
    ob_start();
    ?>
    <div class="equipos-del-empleado">
        <h3>Equipos de <?php echo esc_html($empleado_nombre); ?></h3>

        <?php foreach ($equipos as $equipo_post) : ?>
            <div class="equipo-item">
                <h4>
                    <a href="<?php echo esc_url(get_permalink($equipo_post->ID)); ?>">
                        <?php echo esc_html($equipo_post->post_title); ?>
                    </a>
                </h4>
                <?php
                // Obtener las posiciones específicas de este empleado en cada equipo
                $filas = $wpdb->get_results($wpdb->prepare("
                    SELECT posicion_id
                    FROM $tabla_exp
                    WHERE empleado_id = %d
                      AND equipo_id   = %d
                ", $empleado_id, $equipo_post->ID));

                if ($filas) {
                    // Ordenar por 'pos_score' si es necesario
                    foreach ($filas as $fila) {
                        $fila->pos_score = (int) get_post_meta($fila->posicion_id, '_cdb_posiciones_score', true);
                    }
                    usort($filas, function($a, $b) {
                        return $b->pos_score <=> $a->pos_score;
                    });

                    echo '<ul>';
                    foreach ($filas as $fila) {
                        $pos_id   = (int) $fila->posicion_id;
                        $pos_post = get_post($pos_id);

                        if ($pos_post && $pos_post->post_type === 'cdb_posiciones') {
                            $pos_nombre = get_the_title($pos_post);
                            $pos_link   = get_permalink($pos_post);
                        } else {
                            $pos_nombre = 'Posición desconocida';
                            $pos_link   = '#';
                        }

                        echo '<li>';
                            // Enlazamos la posición si tenemos un título
                            if ($pos_nombre && $pos_nombre !== 'Posición desconocida') {
                                echo '<a href="' . esc_url($pos_link) . '">';
                                echo esc_html($pos_nombre);
                                echo '</a>';
                            } else {
                                echo esc_html($pos_nombre);
                            }
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No se registraron posiciones específicas para este equipo.</p>';
                }
                ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

// Registrar el shortcode
function cdb_equipos_del_empleado_registrar_shortcode() {
    add_shortcode('equipos_del_empleado', 'cdb_equipos_del_empleado_shortcode');
}
add_action('init', 'cdb_equipos_del_empleado_registrar_shortcode');

// Inyectar el shortcode automáticamente en single-empleado
function cdb_inyectar_equipos_del_empleado_en_contenido($content) {
    if (is_singular('empleado') && in_the_loop() && is_main_query()) {
        $shortcode_output = do_shortcode('[equipos_del_empleado]');
        $content .= $shortcode_output;
    }
    return $content;
}
add_filter('the_content', 'cdb_inyectar_equipos_del_empleado_en_contenido');
