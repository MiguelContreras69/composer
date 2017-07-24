<?php
namespace cfdiV33;

class RetencionGlobal{
    
 protected $Impuesto;   
 protected $Importe;   
    
    function RetencionGlobal($Impuesto,$Importe){
        
        $this->Impuesto = $Impuesto;
        $this->Importe = $Importe;
        
    }
      
    function toXML(){
        
    }
}

