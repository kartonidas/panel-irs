<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libraries\Helper;
use App\Models\Court;
use App\Models\Dictionary;

class CaseRegisterCourt extends Model
{
    protected $hidden = [
        "created_at", "updated_at"
    ];
    
    public static $sortable = ["date", "signature", "status_id", "mode_id", "court_id"];
    public static $defaultSortable = ["date", "desc"];
    public static $filter = ["signature", "status_id", "mode_id", "date_from", "date_to"];
    
    public function getStatusName()
    {
        $statuses = Dictionary::getByType("case_status");
        return $statuses[$this->status_id] ?? $this->status_id;
    }
    
    public function getModeName()
    {
        $statuses = Dictionary::getByType("case_mode");
        return $statuses[$this->mode_id] ?? $this->mode_id;
    }
    
    private static $courtCache = [];
    public function getCourtName()
    {
        if(empty(static::$courtCache))
        {
            $courts = Court::all();
            foreach($courts as $court)
                static::$courtCache[$court->id] = $court;
        }
        
        return static::$courtCache[$this->court_id]->name ?? $this->court_id;
    }
    public function getCourtAddress() : string
    {
        if(empty(static::$courtCache))
        {
            $courts = Court::all();
            foreach($courts as $court)
                static::$courtCache[$court->id] = $court;
        }
        
        if(!empty(static::$courtCache[$this->court_id]))
            return Helper::getAddress(static::$courtCache[$this->court_id]->street, '', '', static::$courtCache[$this->court_id]->zip, static::$courtCache[$this->court_id]->city);
        
        return "";
    }
}
