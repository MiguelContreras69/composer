<?php
namespace composer;

class Receptor {

    protected $Rfc;
    public $Nombre;
    protected $ResidenciaFiscal;
    protected $NumRegIdTrib;
    protected $UsoCFDI;

    public function Receptor($Rfc, $UsoCFDI, $Nombre = null, $ResidenciaFiscal = null, $NumRegIdTrib = null) {
        $this->Rfc = $Rfc;
        $this->Nombre = $Nombre;
        $this->ResidenciaFiscal = $ResidenciaFiscal;
        $this->NumRegIdTrib = $NumRegIdTrib;
        $this->UsoCFDI = $UsoCFDI;
    }

    public function validar() {
        $required = array(
            'Rfc',
            'UsoCFDI'
        );
        foreach ($required as $field) {
            if (!isset($this->$field) || $this->$field == '') {
                error_log(date("Y-m-d H:i:s") . " : Receptor validar(): Campo no puede estar vacio :" . print_r($field, true) . "\n", 3, "debug.log");
                throw new Exception('Receptor Campo Requerido: ' . $field);
            }
        }
    }

    public function toXML() {
        $this->xml = new DOMdocument();
        $domreceptor = $this->xml->appendChild($this->xml->createElement('cfdi:Receptor'));

        $domreceptor->setAttribute('Rfc', $this->Rfc);
        if ($this->Nombre)
            $domreceptor->setAttribute('Nombre', $this->Nombre);
        if ($this->ResidenciaFiscal)
            $domreceptor->setAttribute('ResidenciaFiscal', $this->ResidenciaFiscal);
        if ($this->NumRegIdTrib)
            $domreceptor->setAttribute('NumRegIdTrib', $this->NumRegIdTrib);
        $domreceptor->setAttribute('UsoCFDI', $this->UsoCFDI);


        return $domreceptor;
    }

}
