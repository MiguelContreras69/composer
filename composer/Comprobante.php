<?php
namespace composer;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use composer/Emisor;
use composer/Receptor;
use composer/Concepto;
use composer/TrasladoConcepto;
use composer/RetencionConcepto;
use composer/TrasladoGlobal;
use composer/RetencionGlobal;
use composer/TimbreFiscalDigital;
use composer/Addenda;
use composer/Csd;
use composer/DOMValidator;


include_once("Data/Arrays.php");

class Comprobante {

    //normales
    private $Version = '3.3';
    private $Serie;
    private $Folio;
    private $Fecha;
    private $Sello;
    private $NoCertificado;
    private $Certificado;
    private $SubTotal;
    private $Moneda;
    private $Total;
    private $TipoDeComprobante;
    private $FormaPago;
    private $MetodoPago;
    private $CondicionesDePago;
    private $Descuento;
    private $TipoCambio = 1;
    private $Confirmacion;
    private $MotivoDescuento;
    private $LugarExpedicion;
    //objetos
    var $xml_base;
    var $Emisor;
    var $Receptor;
    var $Conceptos = array();
    var $Traslados = null;
    var $Retenciones = null;
    var $Complemento;
    var $cer;
    var $key;
    var $TotalImpuestosTrasladados = 0;
    var $TotalImpuestosRetenidos = 0;
    var $TotalConceptos = 0;
    var $TotalDescuentos = 0;
    var $TimbreFiscalDigital;
    var $PorcentajeVariacion;
    protected $Decimales;

    // recibe las keys como constructor para poder usarlas en la generacion de la cadena original y el sello encriptado
    public function addKeys($cer, $key) {
        $this->cer = $cer;
        $this->key = $key;
    }

    public function Comprobante($NoCertificado, $SubTotal, $Moneda, $Total, $TipoDeComprobante, $FormaDePago, $TipoCambio, $LugarExpedicion, $MetodoPago, $Serie = null, $Folio = null, $Certificado = null, $CondicionesDePago = null, $Descuento = null, $MotivoDescuento = null, $Confirmacion = null) {
        global $arrayMoneda;
        $this->Serie = $Serie;
        $this->Folio = $Folio;
        $this->Fecha = date("Y-m-d\TH:i:s");


        $this->NoCertificado = trim($NoCertificado);
        $this->Certificado = $Certificado;
        $this->SubTotal = trim($SubTotal);
        $this->Moneda = trim($Moneda);
        $this->Total = trim($Total);
        $this->TipoDeComprobante = $TipoDeComprobante;
        $this->FormaPago = $FormaDePago;
        $this->CondicionesDePago = $CondicionesDePago;
        $this->Descuento = trim($Descuento);
        $this->TipoCambio = trim($TipoCambio);
        $this->MotivoDescuento = $MotivoDescuento;
        $this->LugarExpedicion = $LugarExpedicion;
        $this->MetodoPago = $MetodoPago;
        $this->Conceptos = array();
        $this->Decimales = $arrayMoneda[$this->Moneda]['decimales']; // toma la cantidad de decimales desde el array de moneda
        $this->PorcentajeVariacion = $arrayMoneda[$this->Moneda]['porcentaje_variacion']; // porcentaje de variacion de la moneda 
        if ($this->Decimales == null or $this->Decimales == '') {
            error_log(date("Y-m-d H:i:s") . " : Construct comprobante() La moneda declarada " . $this->Moneda . " no se encuentra dentro del catalogo del SAT\n", 3, "debug.log");
            throw new Exception('La moneda declarada ' . $this->Moneda . '  no se encuentra declarada en el catalogo de monedas.');
        }
    }

    //************************ AGREGAR LAS VALIDACIONES DE LOS NODOS FALTANTES ************************+

    function validar() {

        # valida los archivos .key y .pem por medio de algunos metodos que se encuentran declarados en este script
        # si llegan estar fuera del rango de fecha o este mal declarado el certificado te regrese una exepcion
        # como tambien que el formato del archivo sea legible

        $noCertificado = $this->getNoCertificado();
        if ($this->NoCertificado != $noCertificado) {
            error_log(date("Y-m-d H:i:s") . " : Comprobante validar(): Campo no puede estar vacio :" . print_r($field, true) . "\n", 3, "debug.log");
            throw new Exception('El numero de certificado declarado :' . $this->NoCertificado . " no coincide con el numero de certificado del archivo .pem : " . $noCertificado);
        }
        $this->verifyValidityPeriod();
        $this->verifyValidCsd();

        # valida campos requeridos de comprobantes

        if ($this->Moneda != 'MXN' and ( $this->TipoCambio == 1 or $this->TipoCambio == '')) {
            error_log(date("Y-m-d H:i:s") . " : Comprobante validar(): debe colocar un tipo de cambio correspondiente a la moneda extranjera" . $this->TipoCambio . " \n", 3, "debug.log");
            throw new Exception('debe colocar un tipo de cambio correspondiente a la moneda extranjera, tipo de cambio declarado: ' . $this->TipoCambio);
        }

        $required = array(
            'Version',
            'Moneda',
            'MetodoPago',
            'Fecha',
            "FormaPago",
            "NoCertificado",
            "SubTotal",
            "TipoCambio",
            "Total",
            "TipoDeComprobante",
            "LugarExpedicion"
        );
        foreach ($required as $field) {
            if (!isset($this->$field) || $this->$field == '') {
                error_log(date("Y-m-d H:i:s") . " : Comprobante validar(): Campo no puede estar vacio :" . print_r($field, true) . "\n", 3, "debug.log");
                throw new Exception('Comprobante Campo Requerido: ' . $field);
            }
        }

        if ($this->SubTotal <> $this->TotalConceptos) {
            error_log(date("Y-m-d H:i:s") . " : Comprobante validar(): El valor del subtotal :" . $this->SubTotal . "no coincide con la suma de los valores de los conceptos " . $this->TotalConceptos . "  \n", 3, "debug.log");
            throw new Exception("El valor del subtotal declarado :" . $this->SubTotal . " debe ser igual a la suma de los importes de los conceptos " . $this->TotalConceptos);
        }

        // AQUI VALIDA EL TOTAL DEL COMPROBANTE 
        $impuestos = $this->TotalImpuestosTrasladados - $this->TotalImpuestosRetenidos;
        $totalComprobante = ($this->TotalConceptos + $impuestos) - $this->TotalDescuentos;


        if ($totalComprobante <> $this->Total) {
            error_log(date("Y-m-d H:i:s") . " : Comprobante validar(): El valor del total declarado " . $this->Total . " no coincide con el valor del subtotal + impuestos - descuentos " . $totalComprobante . "  \n", 3, "debug.log");
            throw new Exception("El campo Total no corresponde con la suma del subtotal, menos los descuentos aplicables,"
            . " más las contribuciones recibidas (impuestos trasladados - federales o locales, derechos, productos,"
            . " aprovechamientos, aportaciones de seguridad social, contribuciones de mejoras) menos los impuestos retenidos."
            . " , Valor Declarado : " . $this->Total
            . " , Valor Esperado : " . $totalComprobante);
        }

        #valida emisor
        $this->Emisor->validar();
        #valida receptor
        $this->Receptor->validar();
        #valida conceptos
        // valida que al menos exista un concepto
        if ($this->Conceptos == null or empty($this->Conceptos)) {
            error_log(date("Y-m-d H:i:s") . " : Comprobante validar(): no declaro ningun concepto  \n", 3, "debug.log");
            throw new Exception("No se declaro ningun concepto, declare al menos uno para emitir.");
        }


        // foreach para validar los conceptos y dentro de cada concepto se valida sus impuestos de traslado y retencion
        // como tambien los valores maximos y minimos.
        foreach ($this->Conceptos as $concepto) {
            $concepto->validar();
        }


        #valida maximos y minimos de comprobante
        #valida datos de catalogos de comprobante
        #valida cfdi_relacionados
        if (count($this->Conceptos) == 0) {
            error_log(date("Y-m-d H:i:s") . " : Comprobante validar(): No se encontraron conceptos :\n", 3, "debug.log");
            throw new Exception('No se encontraron Conceptos en el Comprobante: ');
        }
        foreach ($this->Conceptos as $key => $concepto) {
            $concepto->validar();
        }

        #valida impuestos
        #valida complemento
        #valida subtotal, total , etc

        error_log(date("Y-m-d H:i:s") . " : Comprobante validar(): Validaciones Correctas \n", 3, "debug.log");
    }

    function toXML() {
        $this->xml_base = new DOMdocument("1.0", "UTF-8");
        $comprobante = $this->xml_base->createElement("cfdi:Comprobante");
        $this->xml_base->appendChild($comprobante);
        $comprobante->setAttribute('xmlns:cfdi', 'http://www.sat.gob.mx/cfd/3');
        $comprobante->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $comprobante->setAttribute('xsi:schemaLocation', 'http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd');
        $comprobante->SetAttribute('Version', $this->Version);
        $comprobante->SetAttribute('Fecha', $this->Fecha);
        $comprobante->SetAttribute('NoCertificado', $this->NoCertificado);
        $comprobante->SetAttribute('SubTotal', $this->SubTotal);
        $comprobante->SetAttribute('Total', $this->Total);
        $comprobante->SetAttribute('FormaPago', $this->FormaPago);
        $comprobante->SetAttribute('TipoCambio', $this->TipoCambio);
        $comprobante->SetAttribute('Moneda', $this->Moneda);
        $comprobante->SetAttribute('TipoDeComprobante', $this->TipoDeComprobante);
        $comprobante->SetAttribute('FormaPago', $this->FormaPago);
        $comprobante->SetAttribute('MetodoPago', $this->MetodoPago);
        $comprobante->SetAttribute('LugarExpedicion', $this->LugarExpedicion);
        if ($this->Descuento)
            $comprobante->SetAttribute('Descuento', $this->Descuento);
        if ($this->MotivoDescuento)
            $comprobante->SetAttribute('MotivoDescuento', $this->MotivoDescuento);
        if ($this->Serie)
            $comprobante->SetAttribute('Serie', $this->Serie);
        if ($this->Folio)
            $comprobante->SetAttribute('Folio', $this->Folio);
        if ($this->NoCertificado)
            $comprobante->SetAttribute('NoCertificado', $this->NoCertificado);
        if ($this->Certificado)
            $comprobante->SetAttribute('Certificado', $this->Certificado);
        if ($this->CondicionesDePago)
            $comprobante->SetAttribute('CondicionesDePago', $this->CondicionesDePago);


        # cfdi_relacionados
        # emisor        
        $emisorData = $this->Emisor->toXML();
        $domEmisor = $this->xml_base->importNode($emisorData, true);
        $comprobante->appendChild($domEmisor);

        # receptor
        $receptorData = $this->Receptor->toXML();
        $domReceptor = $this->xml_base->importNode($receptorData, true);
        $comprobante->appendChild($domReceptor);

        # conceptos
        $conceptos = $this->xml_base->createElement("cfdi:Conceptos");
        $comprobante->appendChild($conceptos);
        foreach ($this->Conceptos as $key => $concepto) {
            $concepto->toXML();
            $concepto_xml = $this->xml_base->importNode($concepto->importXML(), true);
            $conceptos->appendChild($concepto_xml);
        }


        #impuestos globales
        /**
         * 
         */
        $impuestos = $this->xml_base->createElement("cfdi:Impuestos");

        if ($this->TotalImpuestosRetenidos) {
            $impuestos->setAttribute('TotalImpuestosRetenidos', $this->TotalImpuestosRetenidos);
            $retenciones = $this->xml_base->createElement("cfdi:Retenciones");

            foreach ($this->Retenciones as $retencion) {
                $retencionnodo = $this->xml_base->createElement("cfdi:Retencion");
                $retencionnodo->setAttribute("Impuesto", $retencion["Impuesto"]);
                $retencionnodo->setAttribute("Importe", $retencion["Importe"]);
                $retenciones->appendChild($retencionnodo);
            }
            $impuestos->appendChild($retenciones);
        }
        if ($this->TotalImpuestosTrasladados) {
            $impuestos->setAttribute('TotalImpuestosTrasladados', $this->TotalImpuestosTrasladados);
            $traslados = $this->xml_base->createElement("cfdi:Traslados");

            foreach ($this->Traslados as $traslado) {
                $trasladonodo = $this->xml_base->createElement("cfdi:Traslado");
                $trasladonodo->setAttribute("Impuesto", $traslado["Impuesto"]);
                $trasladonodo->setAttribute("TipoFactor", $traslado["TipoFactor"]);
                $trasladonodo->setAttribute("TasaOCuota", $traslado["TasaOCuota"]);
                $trasladonodo->setAttribute("Importe", $traslado["Importe"]);
                $traslados->appendChild($trasladonodo);
            }

            $impuestos->appendChild($traslados);
        }
        $comprobante->appendChild($impuestos);
        $this->setSello();


        // nodos que se añadiran despues de timbrar con exito desde el pac.
        if (isset($this->Complemento->TimbreFiscalDigital)) {
            $complemento = $this->xml_base->createElement('cfdi:Complemento');
            $timbreData = $this->Complemento->TimbreFiscalDigital->toXML();

            $domTimbre = $this->xml_base->importNode($timbreData->documentElement, true);
            $comprobante->appendChild($complemento);
            $complemento->appendChild($domTimbre);
        }

        if (isset($this->Complemento->Addenda)) {

            $xmlAddenda = $this->Complemento->Addenda->toXML();
            $xmlAddenda = $this->xml_base->importNode($xmlAddenda->documentElement, true);
            $complemento->appendChild($xmlAddenda);
        }
    }

    function toStringXML() {

        return $this->xml_base->saveXML();
    }

// aqui faltaria ver si la api me retornaria una excepcion en dado caso que llegase a fallar el timbrado
    // independientemente de las validaciones del pac
    function toSaveXML($path = null) {
        $uuid = $this->Complemento->TimbreFiscalDigital->UUID;

        if ($uuid) {
            $name = $uuid . ".xml";
        } else {
            error_log(date("Y-m-d H:i:s") . " : toSaveXml(): no se encontro el valor del UUID del xml probablemente no se timbro intente nuevamente  \n", 3, "debug.log");
            throw new Exception("Fallo al guardar : No se encontro el valor del UUID del timbre fiscal digital");
        }
        if (!$path)
            $path = getcwd() . '/';

        if ($this->xml_base->save($ruta = $path . $name)) {
            chmod($ruta, 0777);
            return $ruta;
        }

        // return $this->xml_base->save($path);
    }

    /**
     * Funcion que valida el xml vs los xsd del SAT posee 2 parametros para que en dado caso
     * valide un xml en fisico o un xml DOMDocument string.
     * @param string $xmlObject el xml 
     * @param string $path la ruta del xml a validar 
     * @return boleean u array de los errores
     */
    public function validateXSD($xmlObject = null, $path = null) {
        $validator = new DomValidator;
        if ($xmlObject)
            $validated = $validator->validateFeeds($xmlObject);
        if ($path)
            $validated = $validator->validateFeeds($path);

        if ($validated) {
            return true;
            error_log(date("Y-m-d H:i:s") . " : Comprobante validateXSD(): Validaciones vs XSD Correctas \n", 3, "debug.log");
        } else {
            error_log(date("Y-m-d H:i:s") . " : Comprobante validateXSD(): fallo la validacion : \n" . print_r($validator->displayErrors(), true) . " \n", 3, "debug.log");
            throw new Exception(print_r($validator->displayErrors()));

            return false;
        }
    }

    // funciones de add para cada metodo 

    function addReceptor($Rfc, $UsoCFDI, $Nombre = null, $ResidenciaFiscal = null, $NumRegIdTrib = null) {
        $receptor = new Receptor(
                trim($Rfc), trim($UsoCFDI), $Nombre, $ResidenciaFiscal, $NumRegIdTrib
        );
        $receptor->validar();
        $this->Receptor = $receptor;
        return $receptor;
    }

    function addEmisor($Rfc, $Nombre = null, $RegimenFiscal = null) {
        $emisor = new Emisor(
                trim($Rfc), $Nombre, $RegimenFiscal
        );
        $emisor->validar();
        $this->Emisor = $emisor;
        return $emisor;
    }

    function addConcepto($ClaveProdServ, $Descripcion, $Cantidad, $ValorUnitario, $Unidad = null, $ClaveUnidad, $NoIdentificacion = null, $Descuento = null) {
        $concepto = new Concepto(
                trim($ClaveProdServ), $Descripcion, trim($Cantidad), $ValorUnitario, $Unidad, trim($ClaveUnidad), $NoIdentificacion, $Descuento, $this->Decimales
        );

        $concepto->validar();
        $this->Conceptos[] = $concepto;
        // este valor es equivalente al subtotal y se utlilizara para validar
        $this->TotalConceptos += round(($Cantidad * $ValorUnitario) - $Descuento, $this->Decimales);
        return $concepto;
    }

    /**
     * Funcion que agrupa los impuestos retenidos declarados en el comprobante 
     * para que posteriormente se puedan añadir al nodo de impuestos con su respectivo total
     */
    function addRetencionGlobal() {
        foreach ($this->Conceptos as $concepto) {
            foreach ($concepto->Retenciones as $key => $retencion) {
                if ($this->Retenciones == null) {
                    $this->Retenciones[] = array('Impuesto' => $retencion->Impuesto, 'Importe' => $retencion->Importe);
                } else {
                    // print_r($this->Retenciones);
                    $key = array_search($retencion->Impuesto, array_column($this->Retenciones, 'Impuesto'));
                    // var_dump($key);
                    if (!is_int($key)) {
                        $this->Retenciones[] = array('Impuesto' => $retencion->Impuesto, 'Importe' => $retencion->Importe);
                    }
                    if (is_int($key)) {
                        $suma = $this->Retenciones[$key]['Importe'] + $retencion->Importe;
                        //   var_dump($suma);
                        $this->Retenciones[$key]['Importe'] = $suma;
                    }
                }
            }
        }

        foreach ($this->Retenciones as $retenciones) {
            $retencionObj = new RetencionGlobal($retenciones['Impuesto'], $retenciones['Importe']);
            $this->TotalImpuestosRetenidos = $this->TotalImpuestosRetenidos + $retenciones['Importe'];
        }
    }

    /**
     * Funcion que agrupa los impuestos trasladados declarados en el comprobante 
     * para que posteriormente se puedan añadir al nodo de impuestos con su respectivo total
     */
    function addTrasladoGlobal() {
        foreach ($this->Conceptos as $concepto) {
            foreach ($concepto->Traslados as $key => $traslado) {

                if ($this->Traslados == null) {
                    $this->Traslados[] = array('Impuesto' => $traslado->Impuesto,
                        'TipoFactor' => $traslado->TipoFactor,
                        'TasaOCuota' => $traslado->TasaOCuota,
                        'Importe' => $traslado->Importe);
                } else {
                    // print_r($this->Traslados);
                    $impuesto = array_search($traslado->Impuesto, array_column($this->Traslados, 'Impuesto'));
                    $factor = array_search($traslado->TipoFactor, array_column($this->Traslados, 'TipoFactor'));
                    $tasaocuota = array_search($traslado->TasaOCuota, array_column($this->Traslados, 'TasaOCuota'));

                    if (!is_int($impuesto) or ! is_int($factor) or ! is_int($tasaocuota)) {
                        $this->Traslados[] = array('Impuesto' => $traslado->Impuesto,
                            'TipoFactor' => $traslado->TipoFactor,
                            'TasaOCuota' => $traslado->TasaOCuota,
                            'Importe' => $traslado->Importe);
                    }
                    if (is_int($impuesto) and is_int($factor) and is_int($tasaocuota)) {
                        $suma = $this->Traslados[$impuesto]['Importe'] + $traslado->Importe;
                        //   var_dump($suma);
                        $this->Traslados[$impuesto]['Importe'] = $suma;
                    }
                }
            }
        }

        foreach ($this->Traslados as $traslados) {
            $retencionObj = new TrasladoGlobal($traslados['Impuesto'], $traslados['TipoFactor'], $traslados['TasaOCuota'], $traslados['Importe']);
            $this->TotalImpuestosTrasladados = $this->TotalImpuestosTrasladados + $traslados['Importe'];
        }
    }

    /**
     * Este metodo se encarga de crear un objeto del tipo comprobante
     * el string del xml lo carga con un simple sxml para convertirlo a objeto, asi 
     * se extraen sus atributos y puede guardardarse dentro del objeto global de comprobante
     * @param string $xmlString
     */
    function addTimbreFiscalDigital($xmlString) {
        $xml = new DOMDocument();
        libxml_use_internal_errors(true);
        if (!$xml->loadXML($xmlString)) {
            $errors = libxml_get_errors();
            error_log(date("Y-m-d H:i:s") . " : addTimbreFiscalDigital(): fallo al cargar el xml del timbre " . print_r($errors, true) . " \n", 3, "debug.log");
            throw new Exception("Error al importar el nodo del xml del timbre fiscal digital " . print_r($errors, true));
        }

        $timbre = $xml->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital', 'TimbreFiscalDigital')->item(0);

        $attrs = array();
        // este for itera los atributos y los añade a un arreglo para si poder tener acceso a las propiedades del timbre recibido
        for ($i = 0; $i < $timbre->attributes->length; ++$i) {
            $node = $timbre->attributes->item($i);
            $attrs[$node->nodeName] = $node->nodeValue;
        }

        $timbreFiscal = new TimbreFiscalDigital(
                $attrs['Version'], $attrs['UUID'], $attrs['FechaTimbrado'], $attrs['RfcProvCertif'], $attrs['SelloCFD'], $attrs['NoCertificadoSAT'], $attrs['SelloSAT'], $xmlString);

        // creo un objeto en tiempo de ejecucion y me genera un warning
        $this->Complemento->TimbreFiscalDigital = $timbreFiscal;
    }

    /**
     * Recibe un string , luego crea un dom document que que importa al nodo principal del xml
     * este metodo se debe de llamar despues de haber timbrado el XML, no deberia de emitirse una addenda antes de haber timbrado
     * @param string $addendaxml
     * @return object Addenda
     */
    function addAddenda($addendaxml) {

        if ($this->Complemento->TimbreFiscalDigital->UUID == null or $this->Complemento->TimbreFiscalDigital->FechaTimbrado == null) {

            error_log(date("Y-m-d H:i:s") . " : addAddenda():No se encontro el UUID o la fecha de timbrado se trato de agregar la addenda sin haber timbrado, objeto timbre :" . print_r($this->Complemento->TimbreFiscalDigital) . "\n", 3, "debug.log");
            throw new Exception('No se encontraron los datos del timbre fiscal digital, verifique que el comprobante contenga el timbre con los datos necesarios para establecer los parametros en la addenda: ');
        }

        $variables = array('@@FS@@uuid@@FS@@', '@@CDF@@SerieFolio@@CDF@@', '@@CDF@@RazonSocialReceptor@@CDF@@', '@@CDF@@FechaTimbrado@@CDF@@');

        if (!mb_detect_encoding($addendaxml, 'UTF-8')) {
            $addendaxml = utf8_encode($addendaxml);
        }
        $replace = array(
            'UUID' => $this->Complemento->TimbreFiscalDigital->UUID,
            'SerieFolio' => $this->Serie . $this->Folio,
            'Nombre' => $this->Receptor->Nombre,
            'FechaTimbrado' => $this->Complemento->TimbreFiscalDigital->FechaTimbrado,
        );
        $data = str_replace($variables, $replace, $addendaxml);

        $addenda = new Addenda($data);
        $this->Complemento->Addenda = $addenda;
        // print_r($this);
    }

    // calcula el limite superior e inferior del tipo de cambio si la moneda es distinta al peso mexicano
    // si es peso mexicano no es necesario realizar estos pasos ya que se puede omitir el valor del tipo
    // de cambio del comprobante o dejarlo con el valor de 1.

    public function getMax() {
        $maximo = $this->TipoCambio * 1 + $this->PorcentajeVariacion;
        return $maximo;
    }

    public function getMin() {
        $minimo = $this->TipoCambio * 1 - $this->PorcentajeVariacion;
        return $minimo;
    }

    function getCadenaOriginal() {
//        if (!is_null($this->cadena_original)) {
//            return $this->cadena_original;
//        }

        $xsl = new DOMDocument;
        $xsl->load(dirname(__FILE__) . "/xslt/cadenaoriginal_3_3.xslt");
        $procesador = new XSLTProcessor;
        $procesador->importStyleSheet($xsl);
        $paso = new DOMDocument;
        $paso->loadXML($this->toStringXML());
        return $procesador->transformToXML($paso);
        //return $this->cadena_original = $procesador->transformToXML($paso);
    }

    function setSello() {
        $this->validateKeys();
        $pkeyid = openssl_get_privatekey(file_get_contents($this->key));
        openssl_sign($this->getCadenaOriginal(), $crypttext, $pkeyid, OPENSSL_ALGO_SHA256); // convierte la cadena a sha256
        openssl_free_key($pkeyid); //libera la clave asociada con el indetificador de clave

        $comprobante = $this->xml_base->getElementsByTagName("cfdi:Comprobante")->item(0);

        $comprobante->setAttribute("Sello", $this->Sello = base64_encode($crypttext));
        $comprobante->setAttribute("Certificado", $this->getCertificado());
        return base64_encode($crypttext);
        //$this->comprobante->setAttribute("Certificado", $this->getCertificado());
    }

    public function getCertificado() {
        if (!is_null($this->Certificado)) {
            return $this->Certificado;
        }

        return $this->parseCertificado();
    }

    protected function parseCertificado() {
        $datos = file($this->cer);
        for ($i = 0; $i < sizeof($datos); $i++) {
            if (strstr($datos[$i], "END CERTIFICATE") || strstr($datos[$i], "BEGIN CERTIFICATE")) {
                continue;
            }
            $this->Certificado .= trim($datos[$i]);
        }

        return $this->Certificado;
    }

    function validateDecimals() {
        $Total = strlen(substr(strrchr($this->Total, "."), 1));
        $Subtotal = strlen(substr(strrchr($this->SubTotal, "."), 1));


        if (!empty($this->Descuento)) {
            $decimalesDescuento = strlen(substr(strrchr($this->Descuento, "."), 1));
            if ($decimalesDescuento > $this->Decimales) {
                throw new Exception("El descuento de " . $this->Descuento .
                " en el comprobante es mayor que el valor de los decimales especificado por la moneda , valor de decimales: " . $this->Decimales);
            }
        }

        if ($Total > $this->Decimales) {
            throw new Exception("El total de " . $this->Total .
            " no coincide con el valor de los decimales especificado por la moneda " . $this->Moneda . " ,valor de decimales: " . $this->Decimales);
        }
        if ($Subtotal > $this->Decimales) {
            throw new Exception("El subtotal de " . $this->SubTotal .
            " no coincide con el valor de los decimales especificado por la moneda " . $this->Moneda . "  ,valor de decimales: " . $this->Decimales);
        }
    }

    // FUNCIONES PARA VALIDAR LAS KEYS DESDE EL OBJETO DE COMPROBANTE


    public function getNoCertificado() {
        exec("openssl x509 -in {$this->cer} -noout -serial", $out, $errors);

        if ($errors) {
            $msg = openssl_error_string();
            throw new Exception("Ha ocurrido un error al intentar validar el csd " . $msg);
            error_log(date("Y-m-d H:i:s") . " : getNoCertificado(): Ha ocurrido un error al intentar validar el csd :" . $msg . "\n", 3, "debug.log");
        }

        $vars = explode("=", $out[0]);
        $certificado = end($vars);
        $no_certificado = '';

        for ($i = 0; $i < strlen($certificado); $i++) {
            if ($i % 2 != 0) {
                $no_certificado .= substr($certificado, $i, 1);
            }
        }

        return $no_certificado;
    }

    public function verifyValidityPeriod() {

        exec("openssl x509 -noout -in {$this->cer} -dates", $out, $errors);

        if ($errors) {
            $msg = openssl_error_string();
            throw new Exception("Ha ocurrido un error al intentar validar el csd " . $msg);
            error_log(date("Y-m-d H:i:s") . " : verifyValidityPeriod(): Ha ocurrido un error al intentar validar el csd :" . $msg . "\n", 3, "debug.log");
        }
        $fecha_inicial = explode("=", $out[0]);
        $fecha_final = explode("=", $out[1]);

        $now = new DateTime("now", new DateTimeZone('America/Mexico_City'));
        $fecha_inicial = new DateTime(end($fecha_inicial), new DateTimeZone('America/Mexico_City'));
        $fecha_final = new DateTime(end($fecha_final), new DateTimeZone('America/Mexico_City'));

        if ($now < $fecha_inicial || $now > $fecha_final) {
            error_log(date("Y-m-d H:i:s") . " : verifyValidityPeriod() el archivo cer.key las fechas del certificado no son validas para emitir, Fecha inicial: "
                    . $fecha_inicial . ",Fecha final :" . $fecha_final . " Hoy " . $now . "\n", 3, "debug.log");

            throw new Exception("Las fechas del certificado no son validas para emitir, Fecha inicial: "
            . $fecha_inicial . ", Fecha final :" . $fecha_final . " Hoy : " . $now);
        }

        return compact('fecha_inicial', 'fecha_final');
    }

    public function verifyValidCsd() {
        exec("openssl x509 -in {$this->cer} -subject -noout", $out, $errors);
        if ($errors) {
            $msg = openssl_error_string();
            throw new Exception("Ha ocurrido un error al intentar validar el csd " . $msg);
            error_log(date("Y-m-d H:i:s") . " : verifyValidCsd(): Ha ocurrido un error al intentar validar el csd :" . $msg . "\n", 3, "debug.log");
        }
        $vars = preg_split("/\s?\/\s?/", $out[0]);
        $validCsd = end($vars);
        $valid = explode('=', $validCsd);

        if (!empty($vars)) {
            $razonSocial = str_replace('name=', '', $vars[2]);
            $rfc = str_replace('x500UniqueIdentifier=', '', $vars[4]);

            if ($this->Emisor->Rfc != $rfc) {
                error_log(date("Y-m-d H:i:s") . " : verifyValidCsd() El RFC del emisor " . $this->Emisor->Rfc . "no coincide con el RFC del archivo .key " . $rfc . " \n", 3, "debug.log");
                throw new Exception("el RFC del emisor " . $this->Emisor->Rfc . "no coincide con el RFC del archivo .key " . $rfc);
            }
            if (isset($this->Emisor->Nombre)) {
                if ($this->Emisor->Nombre != $razonSocial) {
                    error_log(date("Y-m-d H:i:s") . " : verifyValidCsd() El nombre del emisor " . $this->Emisor->Nombre . "no coincide con el nombre/razon social del archivo .key " . $razonSocial . " \n", 3, "debug.log");
                    throw new Exception("el nombre/razon social del emisor " . $this->Emisor->Nombre . "no coincide con el nombre/razon social del archivo .key " . $razonSocial);
                }
            }
        }
        return $valid[0] === 'OU';
    }

    public function validateKeys() {
        if (!file_exists($this->cer)) {
            error_log(date("Y-m-d H:i:s") . "No se encontro el archivo .cer en la ruta especificada " . $this->cer . " \n", 3, "debug.log");
            throw new Exception("No se encontro el archivo .cer en la ruta especificada " . $this->cer . " verfique la ruta del archivo.");
        }
        if (!file_exists($this->key)) {
            error_log(date("Y-m-d H:i:s") . "No se encontro el archivo .key en la ruta especificada " . $this->key . " \n", 3, "debug.log");
            throw new Exception("No se encontro el archivo .key en la ruta especificada " . $this->key . " verfique la ruta del archivo.");
        }
    }

}

?>