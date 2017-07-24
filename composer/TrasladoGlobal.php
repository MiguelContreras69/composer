<?php
namespace cfdiV33;

class TrasladoGlobal{
    var $Base;
    var $Impuesto;
    var $TipoFactor;
    var $TasaOCuota;
    var $Importe;
    
    function TrasladoGlobal($Impuesto,$TipoFactor,$TasaOCuota,$Importe){   
        $this->Impuesto = $Impuesto;
        $this->TipoFactor = $TipoFactor;
        $this->TasaOCuota = $TasaOCuota;
        $this->Importe = $Importe;
        
    }
    // pendiente para ver como va estar el rollo
    function toXML(){
        
    }
    
}

