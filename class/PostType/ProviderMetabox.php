<?php
namespace StMartin\PostType;

use StMartinWof\WPModels\Post;
use StMartinWof\PostTypeCustomMetabox;

class ProviderMetabox extends PostTypeCustomMetabox
{
    /* Display the post meta box. */
    public function displayPostTypeMetabox( $post )
    {
    ?>
        <!--creating nonce (number used once) : technique pour sécuriser l'utilisation des données du formulaire-->
        <?php wp_nonce_field( $this->keyName . '_wof', $this->keyName . '_nonce' ); ?>
    
        <p>
        <label for="post<?php echo $this->keyName; ?>div"><?php _e( "local provider", 'stmartin-plugin' ); ?></label>
        <br />
        <select id="post<?php echo $this->keyName; ?>div" name="<?php echo $this->keyName; ?>" class="component-select-control">
        <option value="" <?php if ($this->getValue( $post->ID ) == '') {echo 'selected';}?>>(non renseigné)</option>
        <?php
        // The Query
        $queryFilters = [
            'post_type' => 'producer',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1
        ];
        $query = new \WP_Query( $queryFilters );
 
        // The Loop (ne pas utiliser $query->the_post ni the_title, get_the_title, etc.)
        if ( $query->have_posts() ) {
            foreach ( $query->get_posts() as $producer) {
                echo '<option value="' . $producer->ID . '" ';
                if ($this->getValue( $post->ID ) == $producer->ID) {echo 'selected';}
                echo '>' . $producer->post_title . '</option>';
            }
        } else {
            // no posts found
        }

        ?>
        </select>
        </p>
    <?php
    }

}
