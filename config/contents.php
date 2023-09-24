<?php
return [
    'feature' => [
        'field_name' => [
            'title' => 'text',
            'information' => 'text',
            'image' => 'file',
        ],
        'validation' => [
            'title.*' => 'required|max:1000',
            'information.*' => 'required|max:10000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '50x50'
        ]
    ],
    'why-chose-us' => [
        'field_name' => [
            'title' => 'text',
            'information' => 'textarea',
            'image' => 'file',
        ],
        'validation' => [
            'title.*' => 'required|max:1000',
            'information.*' => 'required|max:3000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '50x50'
        ]
    ],
    'how-it-work' => [
        'field_name' => [
            'title' => 'text',
            'short_description' => 'textarea',
            'image' => 'file'
        ],
        'validation' => [
            'title.*' => 'required|max:1000',
            'short_description.*' => 'required|max:20000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '46x48'
        ]
    ],
    'how-we-work' => [
        'field_name' => [
            'title' => 'text',
            'image' => 'file'
        ],
        'validation' => [
            'title.*' => 'required|max:1000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '55x55'
        ]
    ],
    'know-more-us' => [
        'field_name' => [
            'title' => 'text',
            'number' => 'text',
            'image' => 'file'
        ],
        'validation' => [
            'title.*' => 'required|max:1000',
            'number.*' => 'required|integer',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '55x55'
        ]
    ],
    'testimonial' => [
        'field_name' => [
            'name' => 'text',
            'designation' => 'text',
            'description' => 'textarea',
            'image' => 'file'
        ],
        'validation' => [
            'name.*' => 'required|max:1000',
            'designation.*' => 'required|max:2000',
            'description.*' => 'required|max:20000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '88x88'
        ]
    ],
    'blog' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea',
            'image' => 'file'
        ],
        'validation' => [
            'title.*' => 'required|max:1000',
            'description.*' => 'required|max:200000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '670x424',
            'thumb' => '290x236'
        ]
    ],
    'faq' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea'
        ],
        'validation' => [
            'title.*' => 'required|max:1900',
            'description.*' => 'required|max:30000'
        ]
    ],

    'social' => [
        'field_name' => [
            'name' => 'text',
            'icon' => 'icon',
            'link' => 'url',
        ],
        'validation' => [
            'name.*' => 'required|max:100',
            'icon.*' => 'required|max:100',
            'link.*' => 'required|max:1000'
        ],
    ],
    'support' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea'
        ],
        'validation' => [
            'title.*' => 'required|max:1000',
            'description.*' => 'required|max:30000'
        ]
    ],


    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
        'integer' => 'This field must be an integer value',
    ],

    'content_media' => [
        'image' => 'file',
        'thumbnail' => 'file',
        'youtube_link' => 'url',
        'button_link' => 'url',
        'link' => 'url',
        'icon' => 'icon'
    ]
];
