<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait AjaxTable
{
    public function getAjaxTableParams(Request $request)
    {
        $params = [
            "topRecords" => $request->input("toprecords", config("office.lists.ajax.size")),
            "page" => $request->input("page", 1),
        ];

        $filter = $extraParams = [];
        if($request->input("params", ""))
            parse_str($request->input("params", ""), $extraParams);
        
        $filterQuery = $request->input("filterquery", "");
        if($filterQuery)
            parse_str($filterQuery, $filter);

        $params["filter"] = array_merge($extraParams, $filter);
        return $params;
    }
}