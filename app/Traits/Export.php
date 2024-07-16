<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\Export as ExportModel;

trait Export
{
    private function xlsx(View $xml)
    {
        $data = $this->prepareDataFromXml($xml);
        
        if(!empty($data["header"]))
        {
            $filename = bin2hex(openssl_random_pseudo_bytes(16)) . ".xlsx";
            
            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToFile(ExportModel::getFilePath($filename));
            
            $rowFromValues = WriterEntityFactory::createRowFromArray($data["header"]);
            $writer->addRow($rowFromValues);
            
            if(!empty($data["data"]))
            {
                foreach($data["data"] as $row)
                {
                    $rowFromValues = WriterEntityFactory::createRowFromArray($row);
                    $writer->addRow($rowFromValues);
                }
            }
            
            $writer->close();
            
            return ExportModel::getFilePath($filename, false);
        }
    }
    
    public function xlsxOffice(View $xml, string $filename) : string
    {
        $file = $this->xlsx($xml);
        
        $row = new ExportModel;
        $row->file = $file;
        $row->source = ExportModel::SOURCE_OFFICE;
        $row->user_id = Auth::guard("office")->user()->id;
        $row->filename = $filename;
        $row->save();
        
        return $row->id;
    }
    
    private function prepareDataFromXml(View $xml)
    {
        $xml = simplexml_load_string($xml->render());

        $header = [];
        foreach($xml->header as $h)
            $header[] = (string)$h;

        $data = [];
        foreach($xml->data as $rows)
        {
            $line = [];
            foreach($rows as $row)
                $line[] = trim((string)$row);
            $data[] = $line;
        }

        return [
            "header" => $header,
            "data" => $data,
        ];
    }
    
    public static function prepareCaseExportName(CaseRegistry $case, $basename)
    {
        $part = [
            $case->getCustomerName(),
            $case->customer_signature,
            $basename
        ];
        
        return Helper::__no_pl(implode(".", $part));
    }
}