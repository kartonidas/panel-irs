<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class Controller
{
    abstract protected function modelName() : string;
    
    public function sort(Request $request, string|null $class = null) : RedirectResponse
    {
        $class = $class ?? $this->modelName();
        if(!empty($class::$sortable) && !empty($class::$defaultSortable))
        {
            $sortArray = [];
            $sort = explode(",", $request->get("sort"), 2);
            $sort = array_map("strtolower", $sort);

            $sortArray[0] = !empty($sort[0]) && in_array($sort[0], $class::$sortable) ? $sort[0] : $class::$defaultSortable[0];
            $sortArray[1] = !empty($sort[1]) && in_array($sort[1], ["asc", "desc"]) ? $sort[1] : $class::$defaultSortable[1];

            $request->session()->put("Sort:" . $class, serialize($sortArray));
        }
        
        return redirect()->back();
    }
    
    public function filter(Request $request, string|null $class = null) : RedirectResponse
    {
        $class = $class ?? $this->modelName();
        $filterFields = $this->getFilterFields($class);
        
        if($filterFields)
        {
            $filterParams = [];
            foreach($filterFields as $field)
                $filterParams[$field] = $request->input($field, "");
            $request->session()->put("Filter:" . $class, serialize($filterParams));
        }
        
        return redirect()->back();
    }

    public function clearFilter(Request $request, string|null $class = null) : RedirectResponse
    {
        $class = $class ?? $this->modelName();
        $request->session()->forget("Filter:" . $class);
        
        return redirect()->back();
    }

    protected function getFilter(Request $request, $fromSession = true, string|null $class = null)
    {
        $class = $class ?? $this->modelName();
        
        $out = [];
        $filterFields = $this->getFilterFields($class);
        if($filterFields)
        {
            if($fromSession)
            {
                $filter = $request->session()->get("Filter:" . $class, "");
                $filter = $filter ? unserialize($filter) : [];
            }
            else
                $filter = $request->all();

            $defaultValues = $this->getDefaultFilterValues($class);

            foreach($filterFields as $field)
                $out[$field] = $filter[$field] ?? ($defaultValues[$field] ?? "");
        }
        return $out;
    }

    private function getFilterFields(string $class)
    {
        return $class::$filter ?? [];
    }

    private function getDefaultFilterValues(string $class)
    {
        return $class::$defaultFilter ?? [];
    }
    
    protected function getSortableFields(array $sort, string|null $class = null)
    {
        $class = $class ?? $this->modelName();
        
        $out = [];
        if(!empty($class::$sortable))
        {
            foreach($class::$sortable as $field)
            {
                $out[$field] = sprintf("%s,%s", $field, $field == $sort[0] ? ($sort[1] == "asc" ? "desc" : "asc") : "asc");
                $out["direction." . $field] = sprintf("%s", $field == $sort[0] ? ($sort[1] == "asc" ? "asc" : "desc") : "asc");
                $out["class." . $field] = sprintf("%s", $field == $sort[0] ? ($sort[1] == "asc" ? "sort-active sort-asc" : "sort-active  sort-desc") : "sort-asc");
            }
        }
        return $out;
    }
    
    protected function getSortOrder(Request $request, $fromSession = true, string|null $class = null)
    {
        $class = $class ?? $this->modelName();
        
        $out = $class::$defaultSortable;
        $sortFields = $class::$sortable;
        if($sortFields)
        {
            if($fromSession)
            {
                $sort = $request->session()->get("Sort:" . $class, "");
                $sort = $sort ? unserialize($sort) : [];
            }
            else
                $sort = explode(",", $request->get("sort"));

            if(!empty($sort[0]) && in_array($sort[0], $sortFields))
                $out[0] = $sort[0];

            if(!empty($sort[1]) && in_array($sort[1], ["asc", "desc"]))
                $out[1] = $sort[1];
        }
        return $out;
    }
    
    private function getSortFromGet(Request $request)
    {
        $sort = explode(",", $request->get("sort"), 2);
        return array_map("strtolower", $sort);
    }
    
    public function setPageSize(Request $request, $size, string|null $class = null) : RedirectResponse
    {
        $class = $class ?? $this->modelName();
        
        $default = config("site.lists.size");
        $allowed = config("site.lists.sizes");
        if($request->is(env("ADMIN_PANEL_PREFIX") . "*"))
        {
            $default = config("office.lists.size");
            $allowed = config("office.lists.sizes");
        }
        
        if(!in_array($size, $allowed))
            $size = $default;

        $request->session()->put("PageSize:" . $class, $size);
        
        return redirect()->back();
    }

    protected function getPageSize(Request $request, string|null $class = null)
    {
        $class = $class ?? $this->modelName();
        $default = config("site.lists.size");
        if($request->is(env("ADMIN_PANEL_PREFIX") . "*"))
            $default = config("office.lists.size");
            
        return request()->session()->get("PageSize:" . $class, $default);
    }
}
