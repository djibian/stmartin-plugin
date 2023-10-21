<?php
// cette classe va nous permettre de gérer notre plugin

namespace StMartin;

use StMartin\PostType\PostTypeTextareaMetabox;
use StMartin\PostType\PostTypePriceMetabox;

class Plugin extends \Wof\Plugin
{
    public function __construct()
    {
        parent::__construct();
        add_filter('init', [$this, 'flushRoutes'], 20);
    }

    public function registerAllPostTypeCustomMetaboxes()
    {
        $this->registerPostTypeCustomMetabox('listofingredients', 'List of ingredients', 'product', PostTypeTextareaMetabox::class);
        $this->registerPostTypeCustomMetabox('priceperunitofmeasure', 'Prix au kg ou L', 'product', PostTypePriceMetabox::class);
    }

     /**
     * Called during wordpress initialisation (init hook) after custom entities registration
     * @return void
     */
    public function flushRoutes()
    {
        if( !get_option('stmartin-route-flushed')) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
            update_option('stmartin-route-flushed', 1);
        }
    }

    /**
     * Called during plugin deactivation
     * @return void
     */
    public function deactivate()
    {
        update_option('stmartin-route-flushed', 0);
    }
}
