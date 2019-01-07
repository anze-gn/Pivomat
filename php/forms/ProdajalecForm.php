<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';
require_once 'HTML/QuickForm2/Element/InputCheckbox.php';
require_once 'forms/DeleteForm.php';

abstract class ProdajalecAbstractForm extends HTML_QuickForm2 {

    public $aktiviran;
    public $ime;
    public $priimek;
    public $email;
    public $geslo;

    public function __construct($id) {
        parent::__construct($id);

        $this->setAttribute('action', $_SERVER["REQUEST_URI"]);

        $this->aktiviran = new HTML_QuickForm2_Element_InputCheckbox('aktiviran');
        $this->aktiviran->setLabel('Aktiviran');
        $this->addElement($this->aktiviran);

        $this->ime = new HTML_QuickForm2_Element_InputText('ime');
        $this->ime->setLabel('Ime');
        $this->setAttribute('size', 100);
        $this->ime->addRule('required', 'Ime ne sme biti prazno.');
        $this->ime->addRule('regex', 'Dovoljene so samo črke in presledki.', '/^[a-zA-ZščćžŠČĆŽ ]+$/');
        $this->ime->addRule('maxlength', 'Ime lahko vsebuje največ 45 znakov.', 45);
        $this->addElement($this->ime);

        $this->priimek = new HTML_QuickForm2_Element_InputText('priimek');
        $this->priimek->setLabel('Priimek');
        $this->setAttribute('size', 100);
        $this->priimek->addRule('required', 'Priimek ne sme biti prazen.');
        $this->priimek->addRule('regex', 'Dovoljene so samo črke in presledki.', '/^[a-zA-ZščćžŠČĆŽ ]+$/');
        $this->priimek->addRule('maxlength', 'Priimek lahko vsebuje največ 45 znakov.', 45);
        $this->addElement($this->priimek);
        
        $this->email = new HTML_QuickForm2_Element_InputText('email');
        $this->email->setLabel('E-mail');
        $this->setAttribute('size', 100);
        $this->email->addRule('required', 'E-mail ne sme biti prazen.');
        $this->email->addRule('email', 'Napačen vnos e-maila.');
        $this->email->addRule('maxlength', 'E-mail lahko vsebuje največ 45 znakov.', 45);
        $this->addElement($this->email);
        
        $this->geslo = $this->addElement('password', 'geslo')->setLabel('Geslo');
        $this->gesloRule = $this->geslo->addRule('required', 'Geslo ne sme biti prazno');
        $this->geslo->addRule('minlength', 'Geslo mora vsebovati najmanj 5 znakov.', 5);
        $this->geslo->addRule('maxlength', 'Geslo lahko vsebuje največ 45 znakov.', 45);

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->addElement($this->button);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');

        // Bootstrap
        foreach ($this::getElements() as $el) {
            $el->setAttribute('class', 'form-control');
        }
        $this->aktiviran->setAttribute('class', 'checkbox');
        $this->button->setAttribute('class', 'btn btn-primary d-block mx-auto');
    }

}

class ProdajalecInsertForm extends ProdajalecAbstractForm {

    public function __construct($id) {
        parent::__construct($id);

        $this->button->setAttribute('value', 'Dodaj prodajalca');
    }

}

class ProdajalecEditForm extends ProdajalecAbstractForm {

    public $id;

    public function __construct($id) {
        parent::__construct($id);

        $this->geslo->setLabel('Geslo (pustite prazno za ohranitev starega gesla)');
        $this->geslo->removeRule($this->gesloRule);
        $this->button->setAttribute('value', 'Shrani spremembe');
        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);
    }

}

class ProdajalecDeleteForm extends DeleteForm {

    public $id;

    public function __construct($id) {
        parent::__construct($id, "post", ["action" => BASE_URL . "prodajalci/delete"]);
    }

}