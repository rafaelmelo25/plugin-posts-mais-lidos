<?php
/**
 * Plugin Name: Contador de Visualizações
 * Description: Contabiliza e exibe o número de visualizações dos posts.
 * Version: 1.2
 * Author: Rafael Melo
 * License: GPL2
 */

// ... o restante do código do plugin será adicionado aqui

// CONTADOR DE VIEWS
function cv_registrar_visualizacao() {
    if (is_singular()) {
        global $wpdb;
        $post_id = get_the_ID();
        $table_name = $wpdb->prefix . 'posts_views';
        $views = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT views FROM $table_name WHERE post_id = %d",
                $post_id
            )
        );
        if ($views) {
            $wpdb->update(
                $table_name,
                array(
                    'date_visited' => current_time('mysql'),
                    'views' => $views + 1
                ),
                array('post_id' => $post_id),
                array('%s', '%d'),
                array('%d')
            );
        } else {
            $wpdb->insert(
                $table_name,
                array(
                    'post_id' => $post_id,
                    'date_visited' => current_time('mysql'),
                    'views' => 1
                ),
                array('%d', '%s', '%d')
            );
        }
    }
}
add_action('wp_head', 'cv_registrar_visualizacao');



// SULMARIZADOR COM CRON
// add_action( 'obter_posts_mais_lidos', 'update_wp_mais_lidas' );

// add_action( 'wp_update_mais_lidos', 'agendar_cron_obter_posts_mais_lidos' );
// function agendar_cron_obter_posts_mais_lidos() {
//     if ( ! wp_next_scheduled( 'obter_posts_mais_lidos' ) ) {
//         wp_schedule_event( time(), 'every_30_minutes', 'obter_posts_mais_lidos' );
//     }
// function update_wp_mais_lidas($args) {
//     global $wpdb;
    
//     // Pega o ID do post que foi aberto
//     // $post_id = get_the_ID();
    
//     // Inicia a transação
//     $wpdb->query('START TRANSACTION');
//     error_log("Inniciando cron wp_mais_lidas");
//     // Monta a query
//     $query = "INSERT INTO wp_mais_lidas (post_id, category_id, author_id, channel_id, views_today, views_7, views_30) 
//     SELECT 
//         v.post_id, 
//         t.term_taxonomy_id AS category_id, 
//         auth_meta.post_author AS author_id, 
//         chan_meta.meta_value AS channel_id,
//         SUM(CASE 
//             WHEN v.date_visited >= CURDATE() THEN v.views 
//             ELSE 0 
//         END) AS views_today,
//         SUM(CASE 
//             WHEN v.date_visited >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN v.views 
//             ELSE 0 
//         END) AS views_7,
//         SUM(CASE 
//             WHEN v.date_visited >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN v.views 
//             ELSE 0 
//         END) AS views_30
//     FROM wp_posts_views v 
//     INNER JOIN wp_posts auth_meta ON auth_meta.id=v.post_id
//     INNER JOIN wp_term_taxonomy cat_meta ON cat_meta.taxonomy ='category'
//     INNER JOIN wp_term_relationships t ON t.object_id = v.post_id AND cat_meta.term_taxonomy_id = t.term_taxonomy_id
//     -- LEFT JOIN wp_postmeta chan_meta ON chan_meta.post_id=v.post_id AND chan_meta.meta_key='_canal'
//     LEFT JOIN wp_postmeta chan_meta ON chan_meta.post_id=v.post_id AND chan_meta.meta_key='canal'
//     WHERE v.date_visited >= DATE_SUB(NOW(), INTERVAL 30 DAY)
//     GROUP BY v.post_id, t.term_taxonomy_id, auth_meta.post_author, channel_id
// ";

//     // Executa a query
//     $wpdb->query($query);
    
//     // Finaliza a transação
//     $wpdb->query('COMMIT');
//     wp_reset_query();
//     error_log("Termionando cron wp_mais_lidas");
// }

// }

// add_filter( 'cron_schedules', 'definir_intervalo_cron' );
// function definir_intervalo_cron( $schedules ) {
//     $schedules['every_30_minutes'] = array(
//         'interval' => 1800, // intervalo em segundos
//         'display' => __( 'A cada 30 minutos' ),
//     );
//     return $schedules;
// }
?>
