<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTable extends Component
{
    public $id;
    public $title;
    public $message;
    public $header; // Header property

    /**
     * Create a new component instance.
     *
     * @param string $id
     * @param string $title
     * @param string $message
     * @param array $header
     */
    public function __construct($id, $title, $message, $header = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->message = $message;
        $this->header = $header; // Assign header
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.data-table');
    }
}
