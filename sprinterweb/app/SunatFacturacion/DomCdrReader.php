<?php

namespace App\SunatFacturacion;

use App\SunatFacturacion\CdrResponse;

/**
 * Class DomCdrReader.
 */
class DomCdrReader
{
    /**
     * @var XmlReader
     */
    private $reader;

    /**
     * DomCdrReader constructor.
     * @param XmlReader $reader
     */
    public function __construct(XmlReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Get Cdr using DomDocument.
     *
     * @param string $xml
     *
     * @return CdrResponse
     */
    public function getCdrResponse($xml)
    {
        $this->reader->loadXpath($xml);

        $cdr = $this->createCdr();

        return $cdr;
    }

    /**
     * @return CdrResponse
     */
    private function createCdr()
    {
        $nodePrefix = 'cac:DocumentResponse/cac:Response/';

        $cdr = new CdrResponse();
        $cdr->setId($this->reader->getValue($nodePrefix.'cbc:ReferenceID'))
            ->setCode($this->reader->getValue($nodePrefix.'cbc:ResponseCode'))
            ->setDescription($this->reader->getValue($nodePrefix.'cbc:Description'))
            ->setNotes($this->getNotes());

        return $cdr;
    }

    /**
     * Get Notes if exist.
     *
     * @return string[]
     */
    private function getNotes()
    {
        $xpath = $this->reader->getXpath();

        $nodes = $xpath->query($this->reader->getRoot().'/cbc:Note');
        $notes = [];
        if ($nodes->length === 0) {
            return $notes;
        }

        /** @var \DOMElement $node */
        foreach ($nodes as $node) {
            $notes[] = $node->nodeValue;
        }

        return $notes;
    }
}
