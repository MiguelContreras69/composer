<?php
namespace cfdiV33;

class RetencionConcepto {

    //normales
    var $Base;
    var $Impuesto;
    var $TipoFactor;
    var $TasaOCuota;
    var $Importe;
    var $xml_base;
    var $Decimales;

    function RetencionConcepto($Base, $Impuesto, $TipoFactor, $TasaOCuota = null, $Importe = null, $Decimales = 2) {

        $this->Base = $Base;
        $this->Impuesto = $Impuesto;
        $this->TipoFactor = $TipoFactor;
        $this->TasaOCuota = $TasaOCuota;
        $this->Importe = $Importe;
        $this->Decimales = $Decimales;
    }

    function validar() {
        $required = array(
            'Base',
            'Impuesto',
            'TipoFactor',
            'TasaOCuota',
            "Importe"
        );
        foreach ($required as $field) {
            if (!isset($this->$field) || $this->$field == '') {
                error_log(date("Y-m-d H:i:s") . " : Retencion validar(): Campo no puede estar vacio :" . print_r($field, true) . "\n", 3, "debug.log");
                throw new Exception('Retencion Campo Requerido: ' . $field);
            }
        }
        $this->validateDecimals();
        //$this->validateTax();
        $limite = $this->validateMaxMin();

        if ($this->Importe >= $limite['minimo'] and $this->Importe <= $limite['maximo']) {
            
        } else {
            error_log(date("Y-m-d H:i:s") . " : Retencion validar maximos y minimos(): el importe de " . $this->Importe . "esta fuera del rango permitido minimo :" . $limite["minimo"] . " maximo: " . $limite["maximo"] . "\n", 3, "debug.log");
            throw new Exception('La retencion con el importe ' . $this->Importe . ' esta fuera del limite permitido , minimo : ' . $limite["minimo"] . " maximo :" . $limite["maximo"]);
        }
    }

    function toXML() {
        $this->xml_base = new DOMdocument("1.0", "UTF-8");
        $traslado = $this->xml_base->createElement("cfdi:Retencion");
        $this->xml_base->appendChild($traslado);

        # datos de tralado
        $traslado->SetAttribute('Base', $this->Base);
        $traslado->SetAttribute('Impuesto', $this->Impuesto);
        $traslado->SetAttribute('TipoFactor', $this->TipoFactor);
        if ($this->TasaOCuota)
            $traslado->SetAttribute('TasaOCuota', $this->TasaOCuota);
        if ($this->Importe)
            $traslado->SetAttribute('Importe', $this->Importe);
    }

    function toStringXML() {
        return $this->xml_base->saveXML();
    }

    function importXML() {
        $xml = $this->xml_base->getElementsByTagName("cfdi:Retencion")->item(0);
        return $xml;
    }

//    function roundTax($base = null, $tasaocuota = null, $decimales = null) {
//        $decimalesNum = strlen(substr(strrchr($base, "."), 1));
//        $minimo = $this->truncateFloat(($base - (pow(10, -$decimalesNum)) / 2) * ($tasaocuota), $decimales);
//        $maximo = round(($base + (pow(10, -$decimalesNum)) / 2 - pow(10, -12)) * ($tasaocuota), $decimales);
//        $array = ['minimo' => str_replace(',', '', $minimo), 'maximo' => $maximo];
//        return $array;
//    }


    function getMin() {
        $decimalesNum = strlen(substr(strrchr($this->Base, "."), 1));
        $minimo = $this->truncateFloat(($this->Base - (pow(10, -$decimalesNum)) / 2) * ($this->TasaOCuota), $this->Decimales);
        return str_replace(',', '', $minimo);
    }

    function getMax() {
        $decimalesNum = strlen(substr(strrchr($this->Base, "."), 1));
        $maximo = round(($this->Base + (pow(10, -$decimalesNum)) / 2 - pow(10, -12)) * ($this->TasaOCuota), $this->Decimales);
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

    function validateTax() {
        $valorTasa = null;
        require 'Data/Arrays.php';
        $valorTasa = array_search((float) $this->TasaOCuota, $arrayTasa[$this->Impuesto][$this->TipoFactor]);

        if (!is_int($valorTasa)) {
            throw new Exception('El valor del campo TasaOCuota : ' . $this->TasaOCuota . ' del traslado no contiene un valor del catalogo de c_TasaOCuota especificado por el SAT.<br>'
            . 'Impuestos 001,002,003 valor introducido :' . $this->Impuesto . '<br>'
            . 'Factores Tasa,Cuota,Exento valor introducido :' . $this->TipoFactor . '<br>');
        }
        if ($this->Impuesto == '002' and $this->TipoFactor == "Taza") {
            if ((float) $this->TasaOCuota > 0.0000 and (float) $this->TasaOCuota <= 0.160000) {
                
            } else {
                throw new Exception('El valor de' . $this->TipoFactor . ': ' . $this->TasaOCuota . ' en la retencion No esta dentro del rango permitido 0.000000 a 0.160000 verfique sus datos');
            }
        }

        if ($this->TipoFactor == "Cuota" and $this->Impuesto == "003") {
            if ((float) $this->TasaOCuota > 0.0000 and (float) $this->TasaOCuota <= 43.770000) {
                
            } else {
                throw new Exception('El valor de la ' . $this->TipoFactor . ': ' . $this->TasaOCuota . ' en la retencion No esta dentro del rango permitido 0.000000 a 43.770000 verfique sus datos');
            }
        }

        // checar aqui a ver que ondazz
    }

    function validateDecimals() {
        $decimalesTotal = strlen(substr(strrchr($this->Importe, "."), 1));
        if ($decimalesTotal > $this->Decimales) {
            throw new Exception("El importe de " . $this->Importe .
            " en la retencion no coincide con el valor de los decimales especificado por la moneda ,valor de decimales: " . $this->Decimales);
        }
    }

}
?>


