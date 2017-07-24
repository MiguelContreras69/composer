<?php
namespace composer;

class Emisor {

    var $Rfc;
    var $Nombre;
    protected $RegimenFiscal;

    public function Emisor($Rfc, $Nombre = null, $RegimenFiscal = null) {
        $this->Rfc = $Rfc;
        $this->Nombre = $Nombre;
        $this->RegimenFiscal = $RegimenFiscal;
    }

    public function validar() {

        // valida el RFC

        $required = array(
            'Rfc'
        );
        foreach ($required as $field) {
            if (!isset($this->$field) || $this->$field == '') {
                error_log(date("Y-m-d H:i:s") . " : Emisor validar(): Campo no puede estar vacio :" . print_r($field, true) . "\n", 3, "debug.log");
                throw new Exception('Emisor Campo Requerido: ' . $field);
            }
        }
    }

    // Crea los atrubutos tomando como base el array de las reglas de validacion, asi evito que agrege campos de mas en el
    public function toXML() {
        $this->xml = new DOMdocument();
        $domemisor = $this->xml->appendChild($this->xml->createElement('cfdi:Emisor'));

        $domemisor->setAttribute('Rfc', $this->Rfc);
        $domemisor->setAttribute('Nombre', $this->Nombre);
        if ($this->RegimenFiscal)
            $domemisor->setAttribute('RegimenFiscal', $this->RegimenFiscal);



        return $domemisor;
    }

}

?>