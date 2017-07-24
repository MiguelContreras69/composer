<?php
namespace cfdiV33;

class TimbreFiscalDigital {

    public $SchemaLocation = "http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd";
    public $Tfd = "http://www.sat.gob.mx/TimbreFiscalDigital";
    public $Xsi = "http://www.w3.org/2001/XMLSchema-instance";
    public $Version;
    public $UUID;
    public $FechaTimbrado;
    public $RfcProvCertif;
    public $SelloCFD;
    public $NoCertificadoSAT;
    public $SelloSAT;
    public $StringXML;

    /**
     * Clase que se encarga de recibir el string del XML para posteriormente añadirlo al xml base del CFDI
     * recibe el string del xml del la respuesta del pac, lo instancia en un objeto dom document, que posteriormente
     * enviara al toXML del comprobante para añadirlo una vez timbrado el XML
     * @params string $timbreXML
     * 
     * @return DOMdocument object
     */
    function TimbreFiscalDigital($Version, $UUID, $FechaTimbrado, $RfcProvCertif, $SelloCFD, $NoCertificadoSAT, $SelloSAT) {

        $this->Version = $Version;
        $this->UUID = $UUID;
        $this->FechaTimbrado = $FechaTimbrado;
        $this->RfcProvCertif = $RfcProvCertif;
        $this->SelloCFD = $SelloCFD;
        $this->NoCertificadoSAT = $NoCertificadoSAT;
        $this->SelloSAT = $SelloSAT;

        // print_r($this);
    }

//Aqui crea 2 elementos uno que va a ser el base y el otro simplemente parsea el string a un DOMDocument para luego asi importar el nodo
    function toXML() {


        $xml = new DOMDocument();
        $timbre = $xml->createElement('tfd:TimbreFiscalDigital');
        $xml->appendChild($timbre);
        $timbre->setAttribute('xmlns:tfd', $this->Tfd);
        $timbre->setAttribute('xmlns:xsi', $this->Xsi);
        $timbre->setAttribute('xsi:schemaLocation', $this->SchemaLocation);
        $timbre->setAttribute('Version', $this->Version);
        $timbre->setAttribute('UUID', $this->UUID);
        $timbre->setAttribute('FechaTimbrado', $this->FechaTimbrado);
        $timbre->setAttribute('RfcProvCertif', $this->RfcProvCertif);
        $timbre->setAttribute('SelloCFD', $this->SelloCFD);
        $timbre->setAttribute('NoCertificadoSAT', $this->NoCertificadoSAT);
        $timbre->setAttribute('SelloSAT', $this->SelloSAT);

        // $timbre = $xml->loadXML($this->StringXML);
        // $timbre = $xml->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital', 'TimbreFiscalDigital')->item(0);

        return $xml;
    }

}
