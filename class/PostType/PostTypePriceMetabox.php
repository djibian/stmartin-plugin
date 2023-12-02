<?php
namespace StMartin\PostType;

use Wof\PostTypeCustomMetabox;

class PostTypePriceMetabox extends PostTypeCustomMetabox
{

    public function __construct($keyName, $label, $postType, $displaylocation = ['context' => 'side', 'priority' => 'high'])
    {
        parent::__construct($keyName, $label, $postType, $displaylocation);
    }

    /* Display the post meta box. */
    public function displayPostTypeMetabox( $post )
    {
    ?>
        <!-- creating nonce (number used once) : technique pour sécuriser l'utilisation des données du formulaire */-->
        <?php wp_nonce_field( $this->keyName . '_wof', $this->keyName . '_nonce' ); ?>

        <p>
        <label for="post<?php echo $this->keyName; ?>div"><?php _e( "Edit price per kg or L", 'stmartin-plugin' ); ?></label>
        <br />
        <input id="post<?php echo $this->keyName; ?>div" class="widefat" type="text" placeholder="12,50€/kg" pattern="^\d{1,5},\d{2}€/(kg|L)$" name="<?php echo $this->keyName; ?>" value="<?php echo esc_attr( $this->getValue( $post->ID ) ); ?>" size="30" />
        </p>
        
    <?php
    }
}
