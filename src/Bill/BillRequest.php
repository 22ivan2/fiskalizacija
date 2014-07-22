<?php namespace Nticaric\Fiskalizacija\Bill;

use Nticaric\Fiskalizacija\Request;
use XMLWriter;

class BillRequest extends Request
{

    public $bill;
    
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    public function toXML()
    {
        $ns = 'tns';

        $writer = new XMLWriter();
        $writer->openMemory();
 
        $writer->setIndent(true);
        $writer->setIndentString("    ");
        $writer->startElementNs($ns, 'RacunZahtjev', 'http://www.apis-it.hr/fin/2012/types/f73');
        $writer->writeAttribute('Id', uniqid());
        $writer->startElementNs($ns, 'Zaglavlje', null);
        $writer->writeElementNs($ns, 'IdPoruke', null, $this->generateUUID());
        $writer->writeElementNs($ns, 'DatumVrijeme', null, \Carbon\Carbon::now()->format('d.m.Y\Th:i:s'));
        $writer->endElement();

        //PoslovniProstor
        $writer->writeRaw($this->bill->toXML());
        $writer->endElement();
        
        return $writer->outputMemory();
    }
}