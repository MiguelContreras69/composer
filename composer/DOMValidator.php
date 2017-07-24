<?php
namespace composer;

class DOMValidator {

    /**
     * @var string
     */
    protected $feedSchema = __DIR__ . '/xsd/cfdv33.xsd';

    /**
     * @var int
     */
    public $feedErrors = 0;

    /**
     * Formatted libxml Error details
     *
     * @var array
     */
    public $errorDetails;

    /**
     * Validation Class constructor Instantiating DOMDocument
     *
     * @param \DOMDocument $handler [description]
     */
    public function __construct() {
        $this->handler = new \DOMDocument('1.0', 'utf-8');
    }

    /**
     * @param \libXMLError object $error
     *
     * @return stringf
     */
    private function libxmlDisplayError($error) {
        $errorString = "Error $error->code in $error->file (Line:{$error->line}):";
        $errorString .= trim($error->message);
        return $errorString;
    }

    /**
     * @return array
     */
    private function libxmlDisplayErrors() {
        $errors = libxml_get_errors();
        $result = [];
        foreach ($errors as $error) {
            $result[] = $this->libxmlDisplayError($error);
        }
        libxml_clear_errors();
        return $result;
    }

    /**
     * Valida el contenido del xml vs lso XSD el SAT
     *
     * @param string $path ruta del archivo 
     * @param string $xmlObject string del xml en DomDocument
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function validateFeeds($xmlObject = null,$path = null) {
        if (!class_exists('DOMDocument')) {
            throw new \DOMException("validateXSD:'DOMDocument' clase no encontrada!");
            return false;
        }
        if (!file_exists($this->feedSchema)) {
            throw new \Exception('validateXSD:No se encontro el archivo xsd, asegurese que la ruta especificada sea correcta');
            return false;
        }
        libxml_use_internal_errors(true);
        if ($path != null) {
            if (!($fp = fopen($path, "r"))) {
                die("validateXSD: No se logro abrir el documento XML");
            }

            $contents = fread($fp, filesize($path));
            fclose($fp);

            $this->handler->loadXML($contents, LIBXML_NOBLANKS);
        }
        if ($xmlObject != null) {
            $this->handler->loadXML($xmlObject, LIBXML_NOBLANKS);
        } 
        if($xmlObject == null and $path == null)
        throw new Exception('validateXSD: Debe declarar al menos un parametro para realizar la validacion.');

        // para que pase por alto la validacion del namespace la addenda se 
        // elimina del objeto DOM Document ya que las addendas no tienen un formato
        // predefinido, no hay problema alguno en torno a su estructura pero causa confilcto por que no tienen una definicion en el xsd
        // del comprobante fiscal.

        $addenda = $this->handler->getElementsByTagName('Addenda');

        if (!empty($addenda['length'])) {
            foreach ($addenda as $nodo) {
                $nodo->parentNode->removeChild($nodo);
            }
        }


        if (!$this->handler->schemaValidate($this->feedSchema)) {
            $this->errorDetails = $this->libxmlDisplayErrors();
            $this->feedErrors = 1;
        } else {
            //The file is valid
            return true;
        }
    }

    /**
     * Display Error if Resource is not validated
     *
     * @return array
     */
    public function displayErrors() {
        return $this->errorDetails;
    }

}
