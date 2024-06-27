<?php
 
namespace App\Observers;

use App\Models\Dictionary;
use App\Models\DictionaryExtra;

class DictionaryObserver
{
    public function deleted(Dictionary $dictionary)
    {
        DictionaryExtra::where("dictionary_id", $dictionary->id)->delete();
    }
}