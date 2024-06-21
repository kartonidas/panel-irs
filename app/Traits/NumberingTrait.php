<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Invoice;
use App\Models\InvoiceFirmData;
use App\Models\Numbering;
use App\Models\NumberingLock;

trait NumberingTrait
{
    public function setNumber()
    {
        if($this->number)
            return;

        $type = "";
        switch(get_class($this))
        {
            case Invoice::class:
                $type = "invoice";
            break;
        }

        if(!$type)
            throw new Exception(__("Nieprawidłowy typ"));
        
        DB::transaction(function () use($type) {
            $numberingLock = NumberingLock::where("type", $type)->lockForUpdate()->first();
            if(!$numberingLock)
            {
                $numberingLock = new NumberingLock;
                $numberingLock->type = $type;
            }
            $numberingLock->val = ($numberingLock->val ?? 0) + 1;
            $numberingLock->save();
            
            $currentYear = date("Y");
            $currentMonth = date("m");

            $maskConfig = $this->getMaskNumber($this);
            $fullNumber = $maskConfig["mask"];

            $lastNumberQuery = Numbering::where("type", $type);

            switch($maskConfig["continuity"])
            {
                case "month":
                    $lastNumberQuery->where("date", $currentYear . "-" . $currentMonth);
                break;
                case "year":
                    $lastNumberQuery->whereRaw("SUBSTRING(date, 1, 4) = ?", $currentYear);
                break;
            }

            $number = $lastNumberQuery->max("number") + 1;

            preg_match("/@N([1-9]+)?/i", $maskConfig["mask"], $matches);
            if($matches)
                $fullNumber = str_replace($matches[0], !empty($matches[1]) ? str_pad($number, $matches[1], "0", STR_PAD_LEFT) : $number, $fullNumber);

            $fullNumber = str_ireplace("@M", $currentMonth, $fullNumber);
            $fullNumber = str_ireplace("@Y", $currentYear, $fullNumber);

            $this->number = $number;
            $this->month = $currentMonth;
            $this->year = $currentYear;
            $this->full_number = $fullNumber;
            $this->saveQuietly();

            $numb = new Numbering;
            $numb->type = $type;
            $numb->number = $number;
            $numb->full_number = $fullNumber;
            $numb->date = $currentYear . "-" . $currentMonth;
            $numb->object_id = $this->id;
            $numb->save();
        });
    }
    
    private static function getMaskNumber($object)
    {
        switch(get_class($object))
        {
            case Invoice::class:
                $invoiceFirmData = InvoiceFirmData::find($object->invoice_firm_data_id);
                return [
                    "mask" => $invoiceFirmData ? $invoiceFirmData->mask : InvoiceFirmData::DEFAULT_MASK,
                    "continuity" => $invoiceFirmData ? $invoiceFirmData->continuity : InvoiceFirmData::CONTINUATION_YEAR,
                ];
            break;
        }
        
        throw new Exception(__("Nieprawidłowy typ"));
    }
}