<?php
 
namespace App\Observers;

use App\Models\CaseRegisterDocument;

class CaseRegisterDocumentObserver
{
    public function deleted(CaseRegisterDocument $document)
    {
        $document->deleteFile();
    }
}