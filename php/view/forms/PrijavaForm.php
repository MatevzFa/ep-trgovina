<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Container/Fieldset.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';
require_once 'HTML/QuickForm2/Element/Select.php';
require_once 'HTML/QuickForm2/Element/InputPassword.php';

class PrijavaForm extends HTML_QuickForm2 {

    public $email;
    public $geslo;
    public $gumb;

    public function __construct($id) {
        parent::__construct($id);

        $this->email = new HTML_QuickForm2_Element_InputText('email');
        $this->email->setAttribute('size', 25);
        $this->email->setLabel('Elektronski naslov:');

        $this->geslo = new HTML_QuickForm2_Element_InputPassword('geslo');
        $this->geslo->setLabel('Geslo:');
        $this->geslo->setAttribute('size', 15);
        $this->geslo->addRule('required', 'Vnesite geslo.');

        $this->gumb = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->gumb->setAttribute('value', 'Prijava');
        
        $this->addElement($this->email);
        $this->addElement($this->geslo);
        $this->addElement($this->gumb);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');
    }

}
