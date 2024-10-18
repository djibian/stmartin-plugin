<?php
namespace StMartin\Tool;

class Image
{
   public static function createPlaceholderImage($placeholderImageUrl, $keyName) {
      $placeholder_image = get_option( 'stmartin-' . $keyName . '-placeholder-image', 0 );

      // Validate current setting if set. If set, return.
      if ( ! empty( $placeholder_image ) ) {
          if ( ! is_numeric( $placeholder_image ) ) {
              return;
          } elseif ( $placeholder_image && wp_attachment_is_image( $placeholder_image ) ) {
              return;
          }
      }

      $upload_dir = wp_upload_dir();
      $source     = plugins_url() . $placeholderImageUrl;
      $filename   = $upload_dir['basedir'] . '/stmartin-' . $keyName . '-placeholder.png';

      if ( ! file_exists( $filename ) ) {
          copy( $source, $filename ); // @codingStandardsIgnoreLine.
      }

      if ( ! file_exists( $filename ) ) {
          update_option( 'stmartin-' . $keyName . '-placeholder-image', 0 );
          return;
      }
      // Attention il faudra ajouter cette ligne au début du fichier wp/wp-includes/capabilities.php pour que wp_check_filetype() fonctionne
      // include_once(ABSPATH . 'wp-includes/pluggable.php');

      $filetype   = wp_check_filetype( basename( $filename ), null );
      $attachment = array(
          'guid'           => $upload_dir['url'] . '/' . basename( $filename ),
          'post_mime_type' => $filetype['type'],
          'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
          'post_content'   => '',
          'post_status'    => 'inherit',
      );
      $attach_id  = wp_insert_attachment( $attachment, $filename );

      update_option( 'stmartin-' . $keyName . '-placeholder-image', $attach_id );

      // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
      require_once ABSPATH . 'wp-admin/includes/image.php';

      // Generate the metadata for the attachment, and update the database record.
      $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
      wp_update_attachment_metadata( $attach_id, $attach_data );
    }


    /**
     * Get the placeholder image URL either from media, or use the fallback image.
     *
     * @param string $size Thumbnail size to use.
     * @return string
     */
    public static function stmartin_placeholder_img_src() {
        $src               = plugins_url() . '/stmartin-plugin/assets/images/placeholder.png';
        $placeholder_image = get_option( 'stmartin_placeholder_image', 0 );

        if ( ! empty( $placeholder_image ) ) {
            if ( is_numeric( $placeholder_image ) ) {
                $image = wp_get_attachment_image_src( $placeholder_image );

                if ( ! empty( $image[0] ) ) {
                    $src = $image[0];
                }
            } else {
                $src = $placeholder_image;
            }
        }

        return $src;
    }
}