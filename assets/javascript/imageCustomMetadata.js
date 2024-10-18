jQuery(document).ready(function ($) {
    // Création d'un tableau de frame car chacune va être liée à la mise à jour d'une image différente selon son keyname
    var file_frame = new Array(); 

    // fonction pour uploader une image avec l'outil media de wordpress
    jQuery.fn.uploadImage = function (event) {
        // If the media frame already exists, reopen it.
        if (file_frame[event.data.keyname]) 
        {
            file_frame[event.data.keyname].open();
            return;
        }

        // Création de la media frame
        file_frame[event.data.keyname] = wp.media({
            title: event.data.frametitle,
            button:{
                text: event.data.buttontext
            },
            multiple: false
        });

        // When an image is selected, run a callback
        file_frame[event.data.keyname].on('select', function () {
            var attachment = file_frame[event.data.keyname].state().get('selection').first().toJSON();
            jQuery( event.delegateTarget ).find( '.input_image' ).val(attachment.id);
            jQuery( event.delegateTarget ).find( 'img' ).attr('src', attachment.url);
            jQuery( event.delegateTarget ).find( '.remove_image_button' ).show();
        });

        // Finally, open the modal
        file_frame[event.data.keyname].open();
    };

    // fonction pour supprimer une image
    jQuery.fn.removeImage = function (event) {
        jQuery( event.delegateTarget ).find( '.input_image' ).val( '' );
        jQuery( event.delegateTarget ).find( 'img' ).attr( 'src', event.data.placeholderImageUrl );
        jQuery( event.delegateTarget ).find( '.remove_image_button' ).hide();
    };
});