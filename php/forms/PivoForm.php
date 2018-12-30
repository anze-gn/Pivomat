<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Element/Select.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';
require_once 'HTML/QuickForm2/Element/Textarea.php';
require_once 'HTML/QuickForm2/Element/InputCheckbox.php';

abstract class PivoAbstractForm extends HTML_QuickForm2 {

    public $aktiviran;
    public $naziv;
    public $znamka;
    public $stil;
    public $kolicina;
    public $alkohol;
    public $cena;
    public $opis;

    public function __construct($id) {
        parent::__construct($id);

        $this->aktiviran = new HTML_QuickForm2_Element_InputCheckbox('aktiviran');
        $this->aktiviran->setLabel('Aktiviran');
        $this->addElement($this->aktiviran);

        $this->naziv = new HTML_QuickForm2_Element_InputText('naziv');
        $this->naziv->setLabel('Naziv');
        $this->setAttribute('size', 100);
        $this->naziv->addRule('required', 'Naziv ne sme biti prazen.');
        $this->naziv->addRule('regex', 'Dovoljene so samo črke in presledki.', '/^[a-zA-ZščćžŠČĆŽ ]+$/');
        $this->naziv->addRule('maxlength', 'Naziv lahko vsebuje največ 45 znakov.', 45);
        $this->addElement($this->naziv);

        $this->znamka = new HTML_QuickForm2_Element_Select('idZnamka');
        $this->znamka->setLabel('Znamka');
        $this->znamka->loadOptions(array('0'=>'Izberite...', '1'=>'znamka1', '2'=>'znamka2') /* TODO array znamk */);
        # TODO naredi required
        $this->addElement($this->znamka);

        $this->stil = new HTML_QuickForm2_Element_Select('idStil');
        $this->stil->setLabel('Stil');
        $this->stil->loadOptions(array('0'=>'Izberite...', '1'=>'stil1', '2'=>'stil2') /* TODO array stilov */);
        # TODO naredi required
        $this->addElement($this->stil);

        $this->kolicina = new HTML_QuickForm2_Element_InputText('kolicina');
        $this->kolicina->setLabel('Količina');
        $this->kolicina->addRule('required', 'Količina ne sme biti prazna.');
        $this->kolicina->addRule('callback', 'Količina ni pravilno zapisana.', array(
            'callback' => 'filter_var','arguments' => [FILTER_VALIDATE_FLOAT]));
        $this->addElement($this->kolicina);

        $this->alkohol = new HTML_QuickForm2_Element_InputText('alkohol');
        $this->alkohol->setLabel('Delež alkohola');
        $this->alkohol->addRule('required', 'Delež alkohola ne sme biti prazen');
        $this->alkohol->addRule('callback', 'Delež alkohola ni pravilno zapisan.', array(
            'callback' => 'filter_var','arguments' => [FILTER_VALIDATE_FLOAT]));
        $this->addElement($this->alkohol);

        $this->cena = new HTML_QuickForm2_Element_InputText('cena');
        $this->cena->setLabel('Cena');
        $this->setAttribute('size', 10);
        $this->cena->addRule('required', 'Cena ne sme biti prazna.');
        $this->cena->addRule('callback', 'Cena ni pravilno zapisana.', array(
            'callback' => 'filter_var','arguments' => [FILTER_VALIDATE_FLOAT]));
        $this->addElement($this->cena);

        $this->opis = new HTML_QuickForm2_Element_Textarea('opis');
        $this->opis->setLabel('Opis');
        $this->opis->addRule('required', 'Opis ne sme biti prazen.');
        $this->opis->setAttribute('rows', 10);
        $this->opis->setAttribute('cols', 70);
        $this->addElement($this->opis);

        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->addElement($this->button);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');
    }

}

class PivoInsertForm extends PivoAbstractForm {

    public function __construct($id) {
        parent::__construct($id);

        $this->button->setAttribute('value', 'Dodaj pivo');
    }

}

class PivoEditForm extends PivoAbstractForm {

    public $id;

    public function __construct($id) {
        parent::__construct($id);

        $this->button->setAttribute('value', 'Shrani spremembe');
        $this->id = new HTML_QuickForm2_Element_InputHidden("id");
        $this->addElement($this->id);
    }

}

class PivoDeleteForm extends HTML_QuickForm2 {

    public $id;

    public function __construct($id) {
        parent::__construct($id, "post", ["action" => BASE_URL . "piva/delete"]);

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
