<?php
namespace StMartin\PostType;

use StMartinWof\CustomPostType;

class Producer extends CustomPostType
{
    protected $options = [
        'menu_icon' => 'dashicons-id-alt',
        'supports' => [
            'title',
            'editor',
            'author',
            'excerpt'
        ],
        'has_archive' => false,
    ];
}
