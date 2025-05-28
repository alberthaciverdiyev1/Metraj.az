<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Title extends Component
{
    public string $title;
    public string $subtitle;

    public function __construct(string $title, string $subtitle)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
    }

    public function render()
    {
        return view('components.title');
    }
}
