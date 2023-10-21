<?php
namespace StMartin\PostType;

use Wof\PostTypeCustomMetabox;

class PostTypeTextareaMetabox extends PostTypeCustomMetabox
{

    public function __construct($keyName, $label, $postType, $displaylocation = ['context' => 'normal', 'priority' => 'default'])
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
        <label for="post<?php echo $this->keyName; ?>div"><?php _e( "Edit your text", 'wof' ); ?></label>
        <br />
        <textarea class="widefat" rows="5" name="<?php echo $this->keyName; ?>" id="post<?php echo $this->keyName; ?>div"><?php echo $this->getValue( $post->ID ); ?></textarea>
        </p>
        
    <?php
    }
}
