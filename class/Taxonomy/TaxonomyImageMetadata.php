<?php
namespace StMartin\Taxonomy;

use StMartinWof\TaxonomyCustomMetadata;

class TaxonomyImageMetadata extends TaxonomyCustomMetadata
{
	//une image par défaut à personnalisée en surchargeant cet attribut
	protected $placeholderImageFileName = 'placeholder-attachment.png';
	// URL complete vers le fichier de l'image par défaut
	private $placeholderImageUrl;

	public function __construct($keyName, $label, $taxonomy )
    {
		parent::__construct($keyName, $label, $taxonomy);
		$this->placeholderImageUrl = plugins_url() . '/stmartin-plugin/assets/images/' . $this->placeholderImageFileName;
    }
	
	/**
	 * Surcharge pour définir notre composant permettant d'uploader une image lors de l'ajout d'un élément à la taxonomie
	 *
	 *
	 */
    public function displayAddForm( $taxonomy ) {
        
		?>
		<div class="form-field term-thumbnail-<?php echo $this->keyName; ?>-wrap">
			<label><?php esc_html_e( 'Thumbnail', 'stmartin-plugin' ); ?></label>
			<div class="stmartin-plugin_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $this->placeholderImageUrl ); ?>" width="60px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" class="input_image" name="<?php echo $this->keyName; ?>" />
				<button type="button" class="upload_image_button button"><?php esc_html_e( 'Set image', 'stmartin-plugin' ); ?></button>
				<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'stmartin-plugin' ); ?></button>
			</div>
			<script type="text/javascript">
				// Cacher le bouton "Retirer l'image" si il n'y a pas d'image
				if ( ! jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap .stmartin-plugin_thumbnail_id' ).val() )
				{
					jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap .remove_image_button' ).hide();
				}
				// lancer l'upload de l'image lors du clique sur l'image
				jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap' ).on( 'click', '.upload_image_button', {keyname: "<?php echo $this->keyName; ?>", frametitle: "<?php esc_html_e( 'Image', 'stmartin-plugin' ); ?>", buttontext: "<?php esc_html_e( 'Set image', 'stmartin-plugin' ); ?>"}, function( event )
				{
					event.preventDefault();
					jQuery.fn.uploadImage(event);
				});
				// Supprimer l'image lors du clique sur le lien "Retirer l'image"
				jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap' ).on( 'click', '.remove_image_button', {placeholderImageUrl: "<?php echo esc_js( $this->placeholderImageUrl ); ?>"}, function( event )
				{
					event.preventDefault();
					jQuery.fn.removeImage(event);
				});
				// Supprimer les images lorsque l'élément de la taxonomie a été ajouté
				jQuery( document ).ajaxComplete( function( event, request, options ) {
					if ( request && 4 === request.readyState && 200 === request.status
						&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

						var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
						if ( ! res || res.errors ) {
							return;
						}
						// Clear Thumbnail fields on submit
						jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap' ).find( '.input_image' ).val( '' );
						jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap' ).find( 'img' ).attr( 'src', '<?php echo esc_js( $this->placeholderImageUrl ); ?>' );
						jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap' ).find( '.remove_image_button' ).hide();
						return;
					}
				} );
			</script>
		</div>
		<?php
    }

	/**
	 * Surcharge pour définir notre composant permettant d'uploader une image lors de l'édition d'un élément de la taxonomie
	 *
	 *
	 */
    public function displayEditForm($taxonomy)
    {
		$thumbnail_id = absint( get_term_meta( $taxonomy->term_id, $this->keyName, true ) );
		
		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image = $this->placeholderImageUrl;
		}
		?>
		<tr class="form-field term-thumbnail-<?php echo $this->keyName; ?>-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'stmartin-plugin' ); ?></label></th>
			<td>
				<div id="stmartin-plugin_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" class="input_image" name="<?php echo $this->keyName; ?>" value="<?php if ( $thumbnail_id ) { echo esc_attr( $thumbnail_id );} ?>" />
					<button type="button" class="upload_image_button button"><?php esc_html_e( 'Set image', 'stmartin-plugin' ); ?></button>
					<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'stmartin-plugin' ); ?></button>
				</div>
				<script type="text/javascript">
					// Cacher le bouton "Retirer l'image" si il n'y a pas d'image
					if ( ! jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap .input_image' ).val() ) {
						jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap .remove_image_button' ).hide();
					}
					// lancer l'upload de l'image lors du clique sur l'image
					jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap' ).on( 'click', '.upload_image_button', {keyname: "<?php echo $this->keyName; ?>", frametitle: "<?php esc_html_e( 'Image', 'stmartin-plugin' ); ?>", buttontext: "<?php esc_html_e( 'Set image', 'stmartin-plugin' ); ?>"}, function( event )
					{
						event.preventDefault();
						jQuery.fn.uploadImage(event);
					});
					// Supprimer l'image lors du clique sur le lien "Retirer l'image"
					jQuery( '.term-thumbnail-<?php echo $this->keyName; ?>-wrap' ).on( 'click', '.remove_image_button', {placeholderImageUrl: "<?php echo esc_js( $this->placeholderImageUrl ); ?>"}, function( event )
					{
						event.preventDefault();
						jQuery.fn.removeImage(event);
					});
				</script>
			</td>
		</tr>
		<?php
    }

	/**
	 *  Surcharge pour mettre la colonne au début
	 *  Ajout d'une colonne dans la liste des éléments de la taxonomie sur laquelle porte la metadata
	 *
	 * @param mixed $columns Columns array.
	 * @return array
	 */
	public function addMetadataColumn( $columns ) {
		$new_columns = array();
		// On déplace la première colonne vers notre tableau de nouvelles colonnes
		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
			unset( $columns['cb'] );
		}
		// On ajoute à notre tableau de colonnes la nouvelle colonne juste après celle des checkbox et on fusionne
		$new_columns[$this->keyName] = $this->label;
		$columns           = array_merge( $new_columns, $columns );
		//$columns['handle'] = ''; //un truc utilisé par woocommerce...je ne sais pas pourquoi faire !!!
		return $columns;
	}

	/**
	 * Surcharge pour ajouter les images correspondant aux identifiants
	 * Ajout des valeurs de la colonne de notre metadata dans la liste des éléments de la taxonomie sur laquelle porte la metadata
	 *
	 * @param string $columns Column HTML output.
	 * @param string $column Column name.
	 * @param int    $id Product ID.
	 *
	 * @return string
	 */
	public function addMetadataValuesInColumn( $columns, $column, $id ) {
		if ( $this->keyName === $column ) {

			$thumbnail_id = get_term_meta( $id, $this->keyName, true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = $this->placeholderImageUrl;
			}

			// Prevent esc_url from breaking spaces in urls for image embeds. Ref: https://core.trac.wordpress.org/ticket/23605 .
			$image    = str_replace( ' ', '%20', $image );
			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $this->keyName ) . '" class="wp-post-image" height="48" width="48" />';
		}
		 //un truc utilisé par woocommerce...je ne sais pas pourquoi faire !!!
		//if ( 'handle' === $column ) {
		//	$columns .= '<input type="hidden" name="term_id" value="' . esc_attr( $id ) . '" />';
		//}
		return $columns;
	}

	
}
