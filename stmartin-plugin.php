<?php
/**
 * Plugin Name: stmartin-plugin
 * Description: Woocommerce plugin to help students of saint martin high school practicing e-commerce.
 * Author: Emmanuel BLANCHARD
 * Text Domain: stmartin-plugin
 * Domain Path: /languages
 * 
 * Ce fichier porte le nom du plugin (du répertoire du plugin)
 * => C'est le point d'entrée du plugin
 */
use StMartin\Plugin;

// Attention il faudra ajouter cette ligne au début du fichier wp/wp-includes/capabilities.php
// include_once(ABSPATH . 'wp-includes/pluggable.php');

// WARNING penser à lancer composer install à la racine du plugin
// puis renommer vendor en static-vendor afin qu'il ne soit pas gitignoré
require __DIR__ . '/static-vendor/autoload.php';

// WARNING ne pas oublier cet include pour utiliser le Wof !!! Bien sûr le plugin Wof doit également se trouver dans le dossier plugin
require __DIR__ .'/../stmartin-wof/autoload.php';

// Chargement du fichier de langue lors du chargement du plugin
function stmartin_plugin_init() {
    if (!is_plugin_active('stmartin-wof/stmartin-wof.php')) {
        return; // Ne rien faire si le plugin dépendant n'est pas actif
    }
    $plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages'; /* Relative to WP_PLUGIN_DIR */
    load_plugin_textdomain( 'stmartin-plugin', false, $plugin_rel_path );
}
add_action('init', 'stmartin_plugin_init', 1); /* on doit le charger les langues sur init depuis wordpress 6.7.0 mais aussi avant les __() */

$plugin = new Plugin();

// WARNING attention il faut mettre  __FILE__ le fichier qui gère le plugin
register_activation_hook(__FILE__, [$plugin, 'activate']);
register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);
register_uninstall_hook(__FILE__, [Plugin::class, 'uninstall']);

//$plugin->register();