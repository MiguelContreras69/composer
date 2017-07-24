<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use cfdi/Comprobante;
include_once("Data/Arrays.php");


try {
    //se crea objeto comprobante
    // agregarle los paths de las keys
//    $cer = getcwd() . "/keys/2.cer";
//    $key = getcwd() . "/keys/1.key";
//    $rfc = "PAU1207301E5";
//    $pass = "caracoles44";
//
//    $keys = new Csd($cer, $key, $rfc, $pass, $path = getcwd() . "/keys/");
//
//    try {
//        $keys->convertCerToPem(); 
//        $keys->convertKeyToPem();
//        echo 'se convirtieron a pem';
//    } catch (Exception $e) {
//        var_dump($e->getMessage());
//        return;
//    }

    $key = getcwd() . "/keys/PAU1207301E5.key.pem";
    $cer = getcwd() . "/keys/PAU1207301E5.cer.pem";
    $cfdi = new Comprobante('00001000000401148681', '3000', 'MXN', '3000.1', 'I', '01', '1.0', '35045', 'PUE', '12');




    // añadir este metodo en la creacion de los xml , se encarga de guardar en el objeto las rutas de los archivos .pem
    // necesarios para la creacion de la cadena original y el sello encriptado en sha1
    $cfdi->addKeys($cer, $key);




    $cfdi->addEmisor('PAU1207301E5', 'PILOTO AUTOMATICO SA DE CV', '601');
    $cfdi->addReceptor('RPE841207E99', 'G01', 'RESTAURANTE PERISUR SA DE CV');

    //se agrega concepto al comprobante
    $concepto = $cfdi->addConcepto('01010101', 'descripcion de prueba', '1', '1500', 'TONELADA', 'F52', '0001');

    $concepto->addTraslado('10', '002', 'Tasa', '0.160000', '1.6');
    $concepto->addRetencion('10', '002', 'Tasa', '0.150000', '1.5');
    $concepto = $cfdi->addConcepto('01010101', 'descripcion de prueba', '1', '1500', 'TONELADA', 'F52', '0001');

//declarar estos 2 metodos para que agrege los impuestos 
    $cfdi->addRetencionGlobal();
    $cfdi->addTrasladoGlobal();

    //$cfdi->validar();
    //print_r($cfdi);
    error_log(date("Y-m-d H:i:s") . " : Cfdi: " . print_r($cfdi, true) . "\n", 3, "debug.log");

    // $cfdi->toSaveXML();


    $cfdi->toXML();


    $xml = $cfdi->toStringXML();
    $cfdi->validateXSD($xml);
    //print_r($xml);
    error_log(date("Y-m-d H:i:s") . " : XML: " . print_r($xml, true) . "\n", 3, "debug.log");



    // PRUEBA DE TIMBRADO DESDE LA API
    // $stringxml = file_get_contents($xml);
// curl que se encarga de enviar el xml a la API

    $data = array("Xml" => $xml, 'Authorization' => 'PAU1207301E5');

    $ch = curl_init("http://localhost:3000/V33/timbrar");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);
    if (curl_error($ch)) {
        echo 'error : ' . curl_error($ch);
        return false;
    } else {
        var_dump($response);
        // $response;
    }


    if ($response == null or $response == '') {
        error_log(date("Y-m-d H:i:s") . "No se recibio respuesta del la api", 3, "debug.log");
        throw new Exception("No se recibio respuesta de la api");
    }

    if (substr($response, 0, 5) == "<?xml") {
        $cfdi->addTimbreFiscalDigital($response);
    } else {
        error_log(date("Y-m-d H:i:s") . "Fallo al timbrar el xml " . $response . "\n", 3, "debug.log");
        throw new Exception("Fallo al timbrar el xml " . $response);
    }




    // este metodo realiza la verficacion de que se haya timbrado correectamente



    $cfdi->addAddenda('<ad:MiAddenda xmlns:ad="http://www.addenda.com">
                           <ad:Cabecera UUID="@@FS@@uuid@@FS@@" SerieFolio="@@CDF@@SerieFolio@@CDF@@">
                              <ad:Conceptos>
                                 <ad:Concepto RazonSocialReceptor="@@CDF@@RazonSocialReceptor@@CDF@@" FechaTimbrado="@@CDF@@FechaTimbrado@@CDF@@" />
                              </ad:Conceptos>
                           </ad:Cabecera>
                        </ad:MiAddenda>');
    $cfdi->validar();
    $xml = $cfdi->toStringXML();
    $cfdi->validateXSD($xml);
    $cfdi->toXML();
    $ruta = $cfdi->toSaveXML();

    //print_r($cfdi);

    echo 'Se genero el xml con el timbre fiscal en ' . $ruta;
} catch (Exception $e) {
    echo 'Error al generar el CFDI: ', $e->getMessage(), "\n";
    error_log(date("Y-m-d H:i:s") . " : Error al generar el CFDI: " . print_r($e->getMessage(), true) . "\n", 3, "debug.log");
}
?>

curl -v --request POST \
 --url http://localhost:3000/V33/timbrar \
 --header 'cache-control: no-cache' \
 --header 'content-type: multipart/form-data' \
 --form Authorization=PAU1207301E5\
 --form Xml=prueba