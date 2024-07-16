<?php
 
namespace App\Observers;

use App\Models\Export;

class ExportObserver
{
    public function deleted(Export $export)
    {
        $export->deleteFile();
    }
}
