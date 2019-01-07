<?php

abstract class DeleteForm extends HTML_QuickForm2 {

    public $id;

    public function __construct($id, $method, $params) {
        parent::__construct($id, $method, $params);

        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);

        $this->confirmation = new HTML_QuickForm2_Element_InputCheckbox("confirmation");
        $this->confirmation->setLabel('Potrditev brisanja');
        $this->confirmation->addRule('required', 'Za brisanje označite to polje.');
        $this->addElement($this->confirmation);

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->button->setAttribute('value', 'Izbriši');
        $this->addElement($this->button);

        // Bootstrap
        foreach ($this::getElements() as $el) {
            $el->setAttribute('class', 'form-control');
        }
        $this->confirmation->setAttribute('class', 'checkbox');
        $this->button->setAttribute('class', 'btn btn-primary d-block mx-auto');
    }

}