<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';

abstract class PrijavaAbstractForm extends HTML_QuickForm2 {

    public $email;
    public $geslo;

    public function __construct($id) {
        parent::__construct($id);

        $this->setAttribute('action', $_SERVER["REQUEST_URI"]);
        
        $this->email = new HTML_QuickForm2_Element_InputText('email');
        $this->email->setLabel('E-mail');
        $this->setAttribute('size', 100);
        $this->email->addRule('required', 'E-mail ne sme biti prazen.');
        $this->email->addRule('email', 'Napačen vnos e-maila.');
        $this->addElement($this->email);
        
        $this->geslo = $this->addElement('password', 'geslo')->setLabel('Geslo');
        $this->gesloRule = $this->geslo->addRule('required', 'Geslo ne sme biti prazno.');

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->addElement($this->button);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');
    }

}

class PrijavaInsertForm extends PrijavaAbstractForm {

    public function __construct($id) {
        parent::__construct($id);

        $this->button->setAttribute('value', 'Prijava');
    }

}