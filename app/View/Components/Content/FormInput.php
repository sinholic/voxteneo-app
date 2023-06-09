<?php

namespace App\View\Components\Content;

use Illuminate\View\Component;

class FormInput extends Component
{
    /**
     * The lists content.
     *
     * @var array
     */
    public $contents;

    /**
     * The lists content.
     *
     * @var array
     */
    public $logs;

    /**
     * Is it edit ?.
     *
     * @var boolean
     */
    public $edit;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($contents, $logs = null, $edit = true)
    {
        $this->contents =   $contents;
        $this->logs     =   $logs;
        $this->edit     =   $edit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.content.form-input');
    }
}
