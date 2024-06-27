<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

use App\Models\DictionaryExtra;
use App\Observers\DictionaryObserver;

#[ObservedBy([DictionaryObserver::class])]
class Dictionary extends Model
{
    public function canDelete()
    {
        return true;
    }
    
    public function getTypeLabel()
    {
        $dictionaryTypes = config("dictionaries.types");
        return $dictionaryTypes[$this->type]["name"] ?? $his->type;
    }
    
    private static $cachedByType = [];
    public static function getByType(string $type)
    {
        if(!isset(self::$cachedByType[$type]))
        {
            $rows = self::where("type", $type)->get();
            foreach($rows as $row)
                self::$cachedByType[$type][$row->id] = $row->value;
        }
        return self::$cachedByType[$type];
    }
    
    public function saveExtraValues($extras)
    {
        $usedFields = [];
        foreach($extras as $field => $value)
        {
            $extra = DictionaryExtra::where("dictionary_id", $this->id)->where("field", $field)->first();
            if(!$extra)
            {
                $extra = new DictionaryExtra;
                $extra->dictionary_id = $this->id;
                $extra->field = $field;
            }
            
            $extra->value = $value;
            $extra->save();
            
            $usedFields[] = $field;
        }
        
        DictionaryExtra::where("dictionary_id", $this->id)->whereNotIn("field", $usedFields)->delete();
    }
    
    public function getExtraValues()
    {
        $extras = [];
        $extraRows = DictionaryExtra::where("dictionary_id", $this->id)->get();
        foreach($extraRows as $extraRow)
            $extras[$extraRow->field] = $extraRow->value;
        return $extras;
    }
}
