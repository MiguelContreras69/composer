<?php
namespace composer;
/**
 * Esta clase se encargara de añadir el nodo addenda despues de haber timbrado el documento
 * @params string $xmlString
 * 
 * @return DOMDocument xml
 */
class Addenda {

    var $StringXML;

    function Addenda($StringXML) {
        $this->StringXML = $StringXML;
    }

    function toXML() {
 

        $xml = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        if (!$xml->loadXML($this->StringXML)) {
            $errors = libxml_get_errors();
            error_log(date("Y-m-d H:i:s") . " : addenda(): fallo al cargar el xml de la addenda " . print_r($errors, true) . " \n", 3, "debug.log");
            throw new Exception("addenda(): fallo al cargar el xml de la addenda " . print_r($errors, true));
        }
        $xml->saveXML();


        $xml2 = new DOMDocument('1.0', 'UTF-8');
        $addenda = $xml2->createElement('cfdi:Addenda');
        $addendaData = $xml2->importNode($xml->documentElement, true);
        $addenda->appendChild($addendaData);
        $xml2->appendChild($addenda);
        $addenda->removeAttribute('xmlns:ad');
        return $xml2;
    }

}
