<?php
namespace StMartin\PostType;

use StMartinWof\PostTypeCustomMetabox;

class PostTypeImageMetabox extends PostTypeCustomMetabox
{
	//une image par défaut à personnalisée en surchargeant cet attribut
	protected $placeholderImageFileName = 'placeholder-attachment.png';
	// URL complete vers le fichier de l'image par défaut
	private $placeholderImageUrl;

	public function __construct($keyName, $label, $postType, $displaylocation = ['context' => 'side', 'priority' => 'default'])
    {
		parent::__construct($keyName, $label, $postType, $displaylocation);
		$this->placeholderImageUrl = plugins_url() . '/stmartin-plugin/assets/images/' . $this->placeholderImageFileName;
    }

	public function displayPostTypeMetabox ( $post )
	{
		$image_id = $this->getValue( $post->ID );

		if ( $image_id && get_post( $image_id ) )
		{
			$imageSrc = wp_get_attachment_image_url( $image_id, 'full' );
		}
		else
		{
			$imageSrc = $this->placeholderImageUrl;
		}
		?>
		<span style="cursor: pointer"><img src="<?php echo esc_url($imageSrc); ?>" alt="" style="min-width:254px;max-width:100%;"/></span>
		<p><?php echo esc_html__( 'Click the image to edit or update', 'stmartin-plugin' ); ?></a></p>
		<input type="hidden" name="<?php echo esc_attr($this->keyName) ?>_input_name" class="input_image" value="<?php if ( $image_id ) {echo esc_attr( $image_id );} ?>" />
		<a href="javascript:;" class="remove_image_button"><?php echo esc_html__( 'Remove image', 'stmartin-plugin' ) ?></a>
		
		<script type="text/javascript">
			// Cacher le bouton "Retirer l'image" si il n'y a pas d'image
			if ( ! jQuery( '#wof_post<?php echo $this->keyName; ?>div .input_image' ).val() )
			{
				jQuery( '#wof_post<?php echo $this->keyName; ?>div .remove_image_button' ).hide();
			}

			// lancer l'upload de l'image lors du clique sur l'image
			jQuery('#wof_post<?php echo $this->keyName; ?>div').on('click', 'img', {keyname: "<?php echo $this->keyName; ?>", frametitle: "<?php esc_html_e( 'Image', 'stmartin-plugin' ); ?>", buttontext: "<?php esc_html_e( 'Set image', 'stmartin-plugin' ); ?>"}, function (event)
			{
				event.preventDefault();
				jQuery.fn.uploadImage(event);
			});

			// Supprimer l'image lors du clique sur le lien "Retirer l'image"
			jQuery( '#wof_post<?php echo $this->keyName; ?>div' ).on( 'click', '.remove_image_button', {keyname: "<?php echo $this->keyName; ?>"}, function( event )
			{
				jQuery( event.delegateTarget ).find( '.input_image' ).val( '' );
				jQuery( event.delegateTarget ).find( 'img' ).attr( 'src', '<?php echo esc_js( $this->placeholderImageUrl ); ?>' );
				jQuery( event.delegateTarget ).find( '.remove_image_button' ).hide();
				return false;
			});

		</script>
		<?php
	}

	public function saveMetadata ( $postId )
	{
		$newValue = filter_input(INPUT_POST, $this->keyName . '_input_name');
		if( isset( $newValue ) )
		{
			$imageId = (int) $newValue;
			$this->setValue($postId, $imageId);
		}
	}
}
