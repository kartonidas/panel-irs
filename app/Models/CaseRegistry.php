<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dictionary;

class CaseRegistry extends Model
{
    public function getStatusName()
    {
        $statuses = Dictionary::getByType("case_status");
        return $statuses[$this->status_id] ?? $this->status_id;
    }
}