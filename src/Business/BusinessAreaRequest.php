<?php namespace Nticaric\Fiskalizacija\Business;

use XMLWriter;

class BusinessAreaRequest
{

	public $businessArea;
	
	public function __construct(BusinessArea $businessArea){
		$this->businessArea = $businessArea;
	}

	public function toXML()
	{
		$ns = 'tns';

		$writer = new XMLWriter();
    	$writer->openMemory();
 
    	$writer->setIndent(true);
    	$writer->setIndentString("    ");
		$writer->startElementNs($ns, 'PoslovniProstorZahtjev', 'http://www.apis-it.hr/fin/2012/types/f73');
    	$writer->writeAttribute('Id', uniqid());
		$writer->startElementNs($ns, 'Zaglavlje', null);
		$writer->writeElementNs($ns, 'IdPoruke', null, $this->generateUUID());
		$writer->writeElementNs($ns, 'DatumVrijeme', null, \Carbon\Carbon::now()->format('d.m.Y\Th:i:s'));
		$writer->endElement();

    	//PoslovniProstor
		$writer->writeRaw($this->businessArea->toXML());
		$writer->endElement();
		
		return $writer->outputMemory();
	}

	public function generateUUID() {
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0x0fff) | 0x4000,
			mt_rand(0, 0x3fff) | 0x8000,
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}
}