<?php

namespace App\View\Components\Content;

use Illuminate\View\Component;

class FormUpdate extends Component
{
    /**
     * The lists content.
     *
     * @var array
     */
    public $contents;

    /**
     * The lists log.
     *
     * @var array
     */
    public $logs;

    /**
     * The model.
     *
     * @var App\Models
     */
    public $model;

    /**
     * Is it show_only ?.
     *
     * @var boolean
     */
    public $edit;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($contents, $model, $logs = null, $edit = true)
    {
        $this->contents     =   $contents;
        $this->model        =   $model;
        $this->logs         =   $logs;
        $this->edit         =   $edit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.content.form-update');
    }
}
