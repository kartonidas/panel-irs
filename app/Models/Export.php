<?php

namespace App\Models;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

use App\Observers\ExportObserver;

#[ObservedBy([ExportObserver::class])]
class Export extends Model
{
    use HasUuids;
    
    const UPDATED_AT = null;
    
    const DELETE_AFTER_MIN = 5;
    public const TEMP_DIR = "temp/";
    public const SOURCE_OFFICE = "office";
    public const SOURCE_SITE = "site";
    
    public $table = "export";
    
    public static function getFilePath($filename, $absolute = true)
    {
        return $absolute ? storage_path(self::TEMP_DIR) . $filename : self::TEMP_DIR . $filename;
    }
    
    public function deleteFile()
    {
        $file = storage_path($this->file);
        if(file_exists($file))
            unlink($file);
    }
    
    public static function clear()
    {
        $date = (new DateTime())->sub(new DateInterval("PT" . self::DELETE_AFTER_MIN . "M"));
        
        $rowsToDelete = self::where("created_at", "<=", $date->format("Y-m-d H:i:s"))->get();
        foreach($rowsToDelete as $row)
            $row->delete();
    }
}