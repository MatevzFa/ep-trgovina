<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Container/Fieldset.php';
require_once 'HTML/QuickForm2/Element/InputSubmit.php';
require_once 'HTML/QuickForm2/Element/InputText.php';
require_once 'HTML/QuickForm2/Element/Select.php';
require_once 'HTML/QuickForm2/Element/Textarea.php';

class DodajanjeIzdelkaForm extends HTML_QuickForm2 {
    
    public $ime;
    public $opis;
    public $cena;
    public $gumb;
    public $fs;

    /**
     * DodajanjeIzdelkaForm constructor.
     * @param $id
     * @throws HTML_QuickForm2_InvalidArgumentException
     * @throws HTML_QuickForm2_NotFoundException
     */
    public function __construct($id) {
        parent::__construct($id);

        $this->ime = new HTML_QuickForm2_Element_InputText('ime');
        $this->ime->setAttribute('size', 15);
        $this->ime->setLabel('Ime izdelka:');
        $this->ime->addRule('required', 'Vnesite ime izdelka.');
        $this->ime->addRule('maxlength', 'Ime izdelka naj bo daljse od 200 znakov.', 255);

        $this->opis = new HTML_QuickForm2_Element_Textarea('opis');
        $this->opis->setAttribute('size', 200);
        $this->opis->setLabel('Opis izdelka:');
        $this->opis->addRule('required', 'Vnesite opis za izdelek.');
        $this->opis->addRule('maxlength', 'Opis izdelka naj bo daljsi od 1000 znakov.', 1000);
        
        $this->cena = new HTML_QuickForm2_Element_InputText('cena');
        $this->cena->setAttribute('size', 5);
        $this->cena->setLabel('Cena izdelka:');
        
        $this->cena->addRule('required', 'Vnesite ceno izdelka.');
        $this->cena->addRule('regex', 'Vnesite ceno oblike X.XX', '/^\d{0,8}(\.\d{1,2})?$/');
        $this->cena->addRule('maxlength', 'Cena izdelka naj bo daljsa od 11 znakov.', 11);

        $this->gumb = new HTML_QuickForm2_Element_InputSubmit(null);
        $this->gumb->setAttribute('value', 'Dodaj izdelek');

        $this->fs = new HTML_QuickForm2_Container_Fieldset();
        $this->fs->setLabel('Dodajanje novega izdelka');
        $this->addElement($this->fs);


        $this->addElement($this->ime);
        $this->addElement($this->opis);
        $this->addElement($this->cena);
        
        $this->addElement($this->gumb);

        $this->addRecursiveFilter('trim');
        $this->addRecursiveFilter('htmlspecialchars');
    }

}
