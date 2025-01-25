<?php
// cette classe va nous permettre de gérer notre plugin

namespace StMartin;

use StMartin\PostType\Producer;
use StMartin\PostType\IdentityPhotoMetabox;
use StMartin\PostType\UrlMetabox;
use StMartin\PostType\ProviderMetabox;
use StMartin\PostType\PostTypeTextareaMetabox;
use StMartin\PostType\PostTypePriceMetabox;

class Plugin extends \StMartinWof\Plugin
{
    public function __construct()
    {
        // ajouter des fichiers javascript pour l'interface d'administration
        add_action( 'admin_enqueue_scripts', [$this, 'selectively_enqueue_admin_script'] );
        parent::__construct();
        add_filter('init', [$this, 'flushRoutes'], 20); /** déjà fait dans le constructeur parent : bizzare */
    }

    public function selectively_enqueue_admin_script( $hook ) {
        // le hook post.php correspond au chargement de la page d'un post type
        // le hook term.php correpsond au chargement de la page d'une taxonomie
        if ( $hook != ('post.php' || 'term.php') ) {
            return;
        }
        // pour utiliser l'objet wp.media
        wp_enqueue_media();
        wp_enqueue_script( 'imageCustomMetadata', plugins_url() . '/stmartin-plugin/assets/javascript/imageCustomMetadata.js', array(), '1.0' );

    }

    public function registerAllCustomPostTypes()
    {
        $this->registerCustomPostType('producer', 'Fournisseur', Producer::class);
    }

    public function registerAllCustomTaxonomies()
    {
        /*$this->registerCustomTaxonomy('origine', 'Origine', ['product']);*/
        $this->registerCustomTaxonomy('job', 'Métier', ['producer']);
    }

    public function registerAllCustomRoles()
    {
        
        //===========================================================
        // Il faudra configurer le rôle de gestionnaire de boutique
        // afin de leur donner des droits sur les producers
        //===========================================================
        
        // on donnne les droits (capabilities) au rôle gestionnaire de boutique (shop_manager)
        $role = get_role('shop_manager');

        // ajout des autorisations au rôle
        $role->add_cap('edit_producer');
        $role->add_cap('edit_producers');
        $role->add_cap('read_producers');
        $role->add_cap('delete_producers');
        $role->add_cap('delete_published_producers');
        $role->add_cap('delete_others_producers');
        $role->add_cap('edit_others_producers');
        $role->add_cap('publish_producers');
        $role->add_cap('read_private_producers');

        // supression de certaines autorisations...à voir si nécessaire
        /*$role->remove_cap('delete_others_posts');
        $role->remove_cap('delete_posts');
        $role->remove_cap('delete_private_posts');
        $role->remove_cap('delete_published_posts');
        $role->remove_cap('edit_others_posts');
        $role->remove_cap('edit_posts');
        $role->remove_cap('edit_private_posts');
        $role->remove_cap('edit_published_posts');
        $role->remove_cap('manage_categories');
        $role->remove_cap('moderate_comments');
        $role->remove_cap('publish_posts');
        $role->remove_cap('read_private_posts');*/
        
        //===========================================================
        //===========================================================

    }


    public function registerAllPostTypeCustomMetaboxes()
    {
        $this->registerPostTypeCustomMetabox('listofingredients', __( 'List of ingredients' , 'stmartin-plugin' ), 'product', PostTypeTextareaMetabox::class);
        $this->registerPostTypeCustomMetabox('priceperunitofmeasure', __( 'Price per kg or L' , 'stmartin-plugin' ), 'product', PostTypePriceMetabox::class);

        $this->registerPostTypeCustomMetabox('provider', __( 'Provider', 'stmartin-plugin' ), 'product', ProviderMetabox::class);
        $this->registerPostTypeCustomMetabox('photo', __( 'Photo', 'stmartin-plugin' ), 'producer', IdentityPhotoMetabox::class);
        $this->registerPostTypeCustomMetabox('websiteurl', __( 'Website URL', 'stmartin-plugin' ), 'producer', UrlMetabox::class);
        $this->registerPostTypeCustomMetabox('city', __( 'City', 'stmartin-plugin' ), 'producer');
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
     * Called during plugin activation
     * @return void
     */
    public function activate()
    {
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
