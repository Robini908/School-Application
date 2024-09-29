<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTable extends Component
{
    public $id;
    public $title;
    public $message;
    public $columns;
    public $data;

    public function __construct($id, $title = '', $message = '', $columns = [], $data = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->message = $message;
        $this->columns = $columns;
        $this->data = $data;
    }

    public function render()
    {
        return view('components.data-table');
    }
}
