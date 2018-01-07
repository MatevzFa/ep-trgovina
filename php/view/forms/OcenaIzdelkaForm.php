<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Container/Fieldset.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';
require_once 'HTML/QuickForm2/Element/Select.php';

class OcenaIzdelkaForm extends HTML_QuickForm2 {

    public $ocena;

    public function __construct($id) {
        parent::__construct($id);

        $this->ocena = new HTML_QuickForm2_Element_InputText('ocena');
        $this->ocena->setAttribute('size', 1);
        $this->ocena->setLabel('Ocena');


        $this->gumb = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->gumb->setAttribute('value', 'Oceni');
        
        $this->addElement($this->ocena);
        $this->addElement($this->gumb);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');
    }

}
