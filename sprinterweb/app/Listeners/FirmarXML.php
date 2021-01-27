<?php

namespace App\Listeners;

use App\Events\FirmarXMLEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DOMDocument;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use Illuminate\Support\Facades\Session;

class FirmarXML
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    /* Transform */
    const ENVELOPED = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    const EXT_NS = 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2';     

    private $ruc;
    private $documento;
    private $xml_nombre;
    private $clave_algoritmo = XMLSecurityKey::RSA_SHA1; // algoritmo clave
    private $canonicalMethod = XMLSecurityDSig::C14N; // Canonical algorithm URI. By default C14N - AdapterInterface::XML_C14N
    private $digestAlgorithm = XMLSecurityDSig::SHA1; // Digest algorithm URI. By default SHA1 - AdapterInterface::SHA1


    public function __construct()
    {
        $this->documento = new DOMDocument();
    }

    /**
     * Handle the event.
     *
     * @param  FirmarXMLEvents  $event
     * @return void
     */
    public function handle(FirmarXMLEvents $event)
    {
        $this->ruc = $event->ruc;
        $this->xml_nombre = $event->xml_nombre;
        
        $this->obtener_documento();

        $this->firmar();

    }


    private function obtener_documento()
    {
        // CUANDO SE GUARDABA EN LOCAL
        //$this->documento->load(public_path() . '/FIRMAXML/' . $this->ruc . '/' . $this->xml_nombre);
        //dd(public_path() . '/FIRMAXML/' . $this->ruc . '/' . $this->xml_nombre);exit;
        // OBTENER XML STORAGE DIGITAL OCEAN
        $xml = obtener_xml_firma2($this->ruc, $this->xml_nombre);
        
        $this->documento->loadXML($xml);

        
    }


    private function firmar(){

        // Crear una nueva clave de seguridad (privada)
        $clave_algoritmo = new XMLSecurityKey($this->clave_algoritmo,['type' => 'private']);

        // Cargamos la clave privada 
        $clave_algoritmo->loadKey(file_get_contents(public_path() . '/certificado/cert.pem'));

        // Crear un nuevo objeto de seguridad
        $objetoSecurityDSig = new XMLSecurityDSig();

        // Utilizar la canonización exclusiva de c14n
        $objetoSecurityDSig->setCanonicalMethod($this->canonicalMethod);
        
        // Firmar con SHA-256
        $objetoSecurityDSig->addReference($this->documento, $this->digestAlgorithm, [self::ENVELOPED], ['force_uri' => true]);

        // Firma el archivo XML        
        $objetoSecurityDSig->sign($clave_algoritmo, $this->getNodeSign($this->documento));

        // Agregar la clave pública asociada a la firma
        $objetoSecurityDSig->add509Cert(file_get_contents(public_path() . '/certificado/cert.pem'));
        
        // CUANDO SE GUARDABA EN LOCAL
        //$this->documento->save(public_path() . '/FIRMAXML/' . $this->ruc . '/' . $this->xml_nombre);

        // STORAGE DIGITAL OCEAN
        $xml_firmado = $this->documento->saveXML();
         
        guardar_xml_firma2($this->ruc, $this->xml_nombre, $xml_firmado);

    }
   

    private function getNodeSign(DOMDocument $data)
    {
        $els = $data->getElementsByTagNameNS(
            self::EXT_NS,
            'ExtensionContent');

        $nodeSign = null;
        foreach ($els as $element) {
            /** @var \DOMElement $element*/
            $val = $element->nodeValue;
            if (strlen(trim($val)) === 0) {
                $nodeSign = $element;
                break;
            }
        }

        if ($nodeSign == null) {
            $nodeSign = $data->documentElement;
        }

        return $nodeSign;
    }

}
