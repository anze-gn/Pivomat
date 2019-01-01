<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Element/Select.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';
require_once 'HTML/QuickForm2/Element/InputCheckbox.php';
require_once 'model/KrajDB.php';

abstract class StrankaAbstractForm extends HTML_QuickForm2 {

    public $aktiviran;
    public $ime;
    public $priimek;
    public $email;
    public $ulica;
    public $hisnaSt;
    public $postnaSt;
    public $telefon;
    public $geslo;

    public function __construct($id) {
        parent::__construct($id);

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
        $this->email->addRule('regex', 'Dovoljen vnos e-maila.', '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/');
        $this->email->addRule('maxlength', 'E-mail lahko vsebuje največ 45 znakov.', 45);
        $this->addElement($this->email);
        
        $this->ulica = new HTML_QuickForm2_Element_InputText('ulica');
        $this->ulica->setLabel('Ulica');
        $this->setAttribute('size', 100);
        $this->ulica->addRule('required', 'Ulica ne sme biti prazna.');
        $this->ulica->addRule('regex', 'Dovoljene so samo črke in presledki.', '/^[a-zA-ZščćžŠČĆŽ ]+$/');
        $this->ulica->addRule('maxlength', 'Ime lahko vsebuje največ 45 znakov.', 45);
        $this->addElement($this->ulica);
        
        $this->hisnaSt = new HTML_QuickForm2_Element_InputText('hisnaSt');
        $this->hisnaSt->setLabel('Hišna številka');
        $this->hisnaSt->addRule('required', 'Hišna številka ne sme biti prazna');
        $this->hisnaSt->addRule('callback', 'Hišna številka ni pravilno zapisana.', array(
            'callback' => 'filter_var','arguments' => [FILTER_VALIDATE_INT]));
        $this->addElement($this->hisnaSt);
        
        $this->postnaSt = new HTML_QuickForm2_Element_Select('postnaSt');
        $this->postnaSt->setLabel('Kraj - izberi');
        $kraji = KrajDB::getAll();
        foreach ($kraji as $kraj):
            $this->postnaSt->addOption($kraj["ime"], $kraj["postnaSt"]);
        endforeach;
        $this->addElement($this->postnaSt);
        
        $this->telefon = new HTML_QuickForm2_Element_InputText('telefon');
        $this->telefon->setLabel('Telefonska številka');
        $this->setAttribute('size', 100);
        $this->telefon->addRule('required', 'Telefonska številka ne sme biti prazna.');
        $this->telefon->addRule('regex', 'Napačen zapis telefonske številke.', '/^[0-9]{9}$/');
        $this->addElement($this->telefon);
        
        $this->geslo = $this->addElement('password', 'geslo')->setLabel('Geslo');
        $this->geslo->addRule('required', 'Geslo ne sme biti prazno');
        $this->geslo->addRule('minlength', 'Geslo mora vsebovati najmanj 5 znakov.', 5);
        $this->geslo->addRule('maxlength', 'Geslo lahko vsebuje največ 45 znakov.', 45);

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->addElement($this->button);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');
    }

}

class StrankaInsertForm extends StrankaAbstractForm {

    public function __construct($id) {
        parent::__construct($id);

        $this->button->setAttribute('value', 'Dodaj stranko');
    }

}

class StrankaEditForm extends StrankaAbstractForm {

    public $id;

    public function __construct($id) {
        parent::__construct($id);

        $this->button->setAttribute('value', 'Shrani spremembe');
        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);
    }

}

class StrankaDeleteForm extends HTML_QuickForm2 {

    public $id;

    public function __construct($id) {
        parent::__construct($id, "post", ["action" => BASE_URL . "stranke/delete"]);

        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);

        $this->confirmation = new HTML_QuickForm2_Element_InputCheckbox("confirmation");
        $this->confirmation->setLabel('Potrditev brisanja');
        $this->confirmation->addRule('required', 'Za brisanje označite to polje.');
        $this->addElement($this->confirmation);

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->button->setAttribute('value', 'Izbriši');
        $this->addElement($this->button);
    }

}