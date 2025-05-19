<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Reviews extends Component
{
    public $reviews;

    public function __construct()
    {
        
        $this->reviews = [
            [
                'avatar' => 'https://i.pravatar.cc/50?img=1',
                'name' => 'Viola Lucas',
                'date' => 'August 13, 2025',
                'stars' => '★★★★★',
                'text' => 'It’s really easy to use and it is exactly what I am looking for. A lot of good looking templates & it\'s highly customizable. Live support is helpful, solved my issue in no time.',
                'images' => [
                    'https://themesflat.co/html/proty/images/blog/comment-1.jpg',
                    'https://themesflat.co/html/proty/images/blog/comment-2.jpg',
                    'https://themesflat.co/html/proty/images/blog/comment-3.jpg',
                ],
            ],
            [
                'avatar' => 'https://i.pravatar.cc/50?img=2',
                'name' => 'Viola Lucas',
                'date' => 'August 13, 2025',
                'stars' => '★★★★★',
                'text' => 'It’s really easy to use and it is exactly what I am looking for. A lot of good looking templates & it\'s highly customizable. Live support is helpful, solved my issue in no time.',
                'images' => [],
            ],
            [
                'avatar' => 'https://i.pravatar.cc/50?img=3',
                'name' => 'Viola Lucas',
                'date' => 'August 13, 2025',
                'stars' => '★★★★★',
                'text' => 'It’s really easy to use and it is exactly what I am looking for. A lot of good looking templates & it\'s highly customizable. Live support is helpful, solved my issue in no time.',
                'images' => [],
            ],
        ];
    }

    public function render()
    {
        return view('components.reviews');
    }
}
