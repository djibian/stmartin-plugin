<?php
namespace StMartin\PostType;

use StMartin\PostType\PostTypeImageMetabox;

class IdentityPhotoMetabox extends PostTypeImageMetabox
{
    // Changement de l'image par défaut
	protected $placeholderImageFileName = 'placeholderimage-photoprovider.png';
}