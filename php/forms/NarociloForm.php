<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Element/InputCheckbox.php';
require_once 'model/NarociloDB.php';

class NarociloForm extends HTML_QuickForm2 {

    public $potrjeno;
    public $preklicano;
    public $stornirano;
    public $zakljuceno;

    public function __construct($id) {
        parent::__construct($id);

        $this->setAttribute('action', $_SERVER["REQUEST_URI"]);

        $this->potrjeno = new HTML_QuickForm2_Element_InputCheckbox('potrjeno');
        $this->potrjeno->setLabel('Potrjeno');
        $this->addElement($this->potrjeno);

        $this->preklicano = new HTML_QuickForm2_Element_InputCheckbox('preklicano');
        $this->preklicano->setLabel('Preklicano');
        $this->addElement($this->preklicano);

        $this->stornirano = new HTML_QuickForm2_Element_InputCheckbox('stornirano');
        $this->stornirano->setLabel('Stornirano');
        $this->addElement($this->stornirano);

        $this->zakljuceno = new HTML_QuickForm2_Element_InputCheckbox('zakljuceno');
        $this->zakljuceno->setLabel('Zaključeno');
        $this->zakljuceno->setAttribute('class', 'checkbox');
        $this->addElement($this->zakljuceno);


        $this->button = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->button->setAttribute('value', 'Posodobi');
        $this->addElement($this->button);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');

        // Bootstrap
        foreach ($this::getElements() as $el) {
            $el->setAttribute('class', 'checkbox');
        }
        $this->button->setAttribute('class', 'btn btn-primary d-block');

    }

}
