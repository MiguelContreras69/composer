<?php
namespace composer;

class Concepto {

    //normales
    var $ClaveProdServ;
    var $Descripcion;
    var $Cantidad;
    var $ValorUnitario;
    var $Importe;
    var $Unidad;
    var $NoIdentificacion;
    var $ClaveUnidad;
    var $Descuento;
    //objetos
    var $xml_base;
    var $Traslados = array();
    var $Retenciones = array();
    public $Decimales;

    function Concepto($ClaveProdServ, $Descripcion, $Cantidad, $ValorUnitario, $Unidad = null, $ClaveUnidad, $NoIdentificacion = null, $Descuento = null, $Decimales = 2) {
        $this->Decimales = $Decimales;
        $this->ClaveProdServ = $ClaveProdServ;
        $this->Descripcion = $Descripcion;
        $this->Cantidad = $Cantidad;
        $this->ValorUnitario = $ValorUnitario;
        $this->Importe = round(($Cantidad * $ValorUnitario) - $Descuento, $this->Decimales);
        $this->Unidad = $Unidad;
        $this->NoIdentificacion = $NoIdentificacion;
        $this->Descuento = $Descuento;
        $this->ClaveUnidad = $ClaveUnidad;
        $this->Traslados = array();
        $this->Retenciones = array();
    }

    function validar() {
        # valida campos requeridos de concepto
        $required = array(
            'ClaveProdServ',
            'Descripcion',
            'Cantidad',
            'ValorUnitario',
            'ClaveUnidad'
        );
        foreach ($required as $field) {
            if (!isset($this->$field) || $this->$field == '') {
                error_log(date("Y-m-d H:i:s") . " : Concepto validar(): Campo no puede estar vacio :" . print_r($field, true) . "\n", 3, "debug.log");
                throw new Exception('Concepto Campo Requerido: ' . $field);
            }
        }

        // foreach de las validaciones de los traslados y retenciones 
        // automatizar el campo de los decimales
        $this->validateDecimals();
        $limites = $this->validateMaxMin($this->Cantidad, $this->ValorUnitario, 2);

        #valida maximos y minimos de concepto
        if ($this->Importe >= $limites['minimo'] and $this->Importe <= $limites['maximo']) {
            
        } else {
            error_log(date("Y-m-d H:i:s") . " : Concepto validar maximos y minimos(): el importe de " . $this->Importe . "esta fuera del rango permitido minimo :" . $limites["minimo"] . " maximo: " . $limites["maximo"] . "\n", 3, "debug.log");
            throw new Exception('El Concepto con el importe' . $this->Importe . 'esta fuera del limite permitido , minimo :' . $limite["minimo"] . " maximo :" . $limite["maximo"]);
        }
        #valida traslados y retenciones
        foreach ($this->Traslados as $traslado) {
            $traslado->validar();
        }
        foreach ($this->Retenciones as $retencion) {
            $retencion->validar();
        }

        #valida datos de catalogos de concepto
    }

    function toXML() {
        $this->xml_base = new DOMdocument("1.0", "UTF-8");
        $concepto = $this->xml_base->createElement("cfdi:Concepto");
        $this->xml_base->appendChild($concepto);

        # datos de concepto
        $concepto->SetAttribute('ClaveProdServ', $this->ClaveProdServ);
        $concepto->SetAttribute('Descripcion', $this->Descripcion);
        $concepto->SetAttribute('Cantidad', $this->Cantidad);
        $concepto->SetAttribute('ValorUnitario', $this->ValorUnitario);
        if ($this->Unidad)
            $concepto->SetAttribute('Unidad', $this->Unidad);
        $concepto->SetAttribute('ClaveUnidad', $this->ClaveUnidad);
        if ($this->NoIdentificacion)
            $concepto->SetAttribute('NoIdentificacion', $this->NoIdentificacion);

        if ($this->Descuento)
            $concepto->SetAttribute('Descuento', $this->Descuento);
        $concepto->SetAttribute('Importe', $this->Importe);

        # impuestos
        if (!empty($this->Traslados) || !empty($this->Retenciones)) {
            $impuestos = $this->xml_base->createElement("cfdi:Impuestos");
            $concepto->appendChild($impuestos);

            # traslados
            if (!empty($this->Traslados)) {
                $traslados = $this->xml_base->createElement("cfdi:Traslados");
                $impuestos->appendChild($traslados);
                foreach ($this->Traslados as $key => $traslado) {
                    $traslado->toXML();
                    $traslado_xml = $this->xml_base->importNode($traslado->importXML(), true);
                    $traslados->appendChild($traslado_xml);
                }
            }


            if (!empty($this->Retenciones)) {
                $retenciones = $this->xml_base->createElement("cfdi:Retenciones");
                $impuestos->appendChild($retenciones);
                foreach ($this->Retenciones as $key => $retencion) {
                    $retencion->toXML();
                    $retencion_xml = $this->xml_base->importNode($retencion->importXML(), true);
                    $retenciones->appendChild($retencion_xml);
                }
            }
        }
    }

    function toStringXML() {
        return $this->xml_base->saveXML();
    }

    function importXML() {
        $xml = $this->xml_base->getElementsByTagName("cfdi:Concepto")->item(0);
        return $xml;
    }

    function addTraslado($Base, $Impuesto, $TipoFactor, $TasaOCuota, $Importe) {
        $traslado = new TrasladoConcepto(
                trim($Base), $Impuesto, $TipoFactor, trim($TasaOCuota), trim($Importe), $this->Decimales);


        $this->Traslados[] = $traslado;

        return $traslado;
    }

    function addRetencion($Base, $Impuesto, $TipoFactor, $TasaOCuota = null, $Importe = null) {
        $retencion = new RetencionConcepto(
                trim($Base), $Impuesto, trim($TipoFactor), trim($TasaOCuota), $Importe, $this->Decimales
        );

        $this->Retenciones[] = $retencion;

        return $retencion;
    }

    function getMin() {
        $decimalesNum = strlen(substr(strrchr(sprintf('%0.' . $this->Decimales . 'f', $this->ValorUnitario), "."), 1));
        $minimo = $this->truncateFloat(($this->Cantidad - (pow(10, -$decimalesNum)) / 2) * ($this->ValorUnitario - (pow(10, -$decimalesNum)) / 2), $this->Decimales);
        return str_replace(',', '', $minimo);
    }

    function getMax() {
        $decimalesNum = strlen(substr(strrchr(sprintf('%0.' . $this->Decimales . 'f', $this->ValorUnitario), "."), 1));
        $maximo = round(($this->Cantidad + (pow(10, -$decimalesNum)) / 2 - pow(10, -12)) * ($this->ValorUnitario + (pow(10, -$decimalesNum)) / 2 - pow(10, -12)), $this->Decimales);
        return $maximo;
    }

    private function truncateFloat($number, $digitos) {
        $raiz = 10;
        $multiplicador = pow($raiz, $digitos);
        $resultado = ((int) ($number * $multiplicador)) / $multiplicador;
        return number_format($resultado, $digitos);
    }

    private function validateMaxMin() {
        $minimo = $this->getMin();
        $maximo = $this->getMax();
        $array = ['minimo' => $minimo, 'maximo' => $maximo];
        return $array;
    }

    function validateDecimals() {
        $decimalesTotal = strlen(substr(strrchr($this->Importe, "."), 1));
        $decimalesValorUnitario = strlen(substr(strrchr($this->ValorUnitario, "."), 1));

        // solamente si es mayor al numero de decimales de los especificados en la moneda te retorna una excepcion
        if (!empty($this->Descuento)) {
            $decimalesDescuento = strlen(substr(strrchr($this->Descuento, "."), 1));
            if ($decimalesDescuento > $this->Decimales) {
                throw new Exception("El descuento de " . $this->Descuento .
                " en el concepto es mayor que el valor de los decimales especificado por la moneda , valor de decimales: " . $this->Decimales);
            }
        }

        if ($decimalesTotal > $this->Decimales) {
            throw new Exception("El importe de " . $this->Importe .
            " en el concepto es mayor que el valor de los decimales especificado por la moneda , valor de decimales: " . $this->Decimales);
        }
        if ($decimalesValorUnitario > $this->Decimales) {
            throw new Exception("El valor unitario de " . $this->ValorUnitario .
            " en el concepto no coincide con el valor de los decimales especificado por la moneda ,valor de decimales: " . $this->Decimales);
        }
    }

}

?>