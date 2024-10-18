<?php
namespace StMartin\PostType;

use StMartinWof\PostTypeCustomMetabox;

class UrlMetabox extends PostTypeCustomMetabox
{
    /* Display the post meta box. */
    public function displayPostTypeMetabox( $post )
    {
    ?>
        <!--creating nonce (number used once) : technique pour sécuriser l'utilisation des données du formulaire-->
        <?php wp_nonce_field( $this->keyName . '_wof', $this->keyName . '_nonce' ); ?>
    
        <p>
        <label for="post<?php echo $this->keyName; ?>div"><?php _e( "Link to the provider's website.", 'stmartin-plugin' ); ?></label>
        <br />
        <input id="post<?php echo $this->keyName; ?>div" class="widefat" type="url" placeholder="https://example.com" pattern="https?://.*" name="<?php echo $this->keyName; ?>" value="<?php echo esc_attr( $this->getValue( $post->ID ) ); ?>" size="30" />
        </p>
        
    <?php
    }

}
