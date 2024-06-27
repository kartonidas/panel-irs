<?php
 
namespace App\Http\Controllers\Office;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

use App\Http\Requests\Office\DictionaryStoreRequest;
use App\Http\Requests\Office\DictionaryUpdateRequest;
use App\Libraries\Helper;
use App\Models\Dictionary;
use App\Models\OfficeUser;
use App\Traits\Form;

class DictionaryController
{
    use Form;
    
    public function list(Request $request)
    {
        OfficeUser::checkAccess("dictionaries:list");
        view()->share("activeMenuItem", "dictionaries");
        
        $filter = Helper::getFilter($request, "office:dictionaries");
        
        $dictionaries = Dictionary::orderBy("type", "ASC")->orderBy("value", "ASC");
        if(!empty($filter["type"]))
            $dictionaries->where("type", $filter["type"]);
        
        $dictionaries = $dictionaries->paginate(config("office.lists.size"));
        
        $vData = [
            "filter" => $filter,
            "dictionaries" => $dictionaries,
            "dictionaryTypes" => config("dictionaries.types"),
        ];
        return view("office.dictionaries.list", $vData);
    }
    
    public function dictionaryCreate(Request $request)
    {
        OfficeUser::checkAccess("dictionaries:create");
        view()->share("activeMenuItem", "dictionaries");
        
        $vData = [
            "form" => $request->old() ? $request->old() : [],
            "dictionaryTypes" => config("dictionaries.types"),
        ];
        return view("office.dictionaries.create", $vData);
    }
    
    public function dictionaryCreatePost(DictionaryStoreRequest $request)
    {
        OfficeUser::checkAccess("dictionaries:create");
        
        $validated = $request->validated();
        
        $dictionary = DB::transaction(function () use($validated) {
            $dictionary = new Dictionary;
            $dictionary->type = $validated["type"];
            $dictionary->value = $validated["value"];
            $dictionary->save();
            
            $dictionary->saveExtraValues($validated["extra"][$dictionary->type] ?? []);
            
            return $dictionary;
        });

        Helper::setMessage("office:dictionaries", __("Wartość została dodana"));
        if($this->isApply())
            return redirect()->route("office.dictionary.update", $dictionary->id);
        else
            return redirect()->route("office.dictionaries");
    }
    
    public function dictionaryUpdate(Request $request, $id)
    {
        OfficeUser::checkAccess("dictionaries:update");
        view()->share("activeMenuItem", "dictionaries");
        
        $dictionary = Dictionary::find($id);
        if(!$dictionary)
            return redirect()->route("office.dictionaries")->withErrors(["msg" => __("Wartość nie istnieje")]);
        
        $dictionaryArray = $dictionary->toArray();
        $dictionaryArray["extra"][$dictionary->type] = $dictionary->getExtraValues();
        $formData = $request->old() ? $request->old() : $dictionaryArray;
        $formData["type"] = $dictionary->type;
        
        $vData = [
            "id" => $dictionary->id,
            "form" => $formData,
            "dictionaryTypes" => config("dictionaries.types"),
        ];
        
        return view("office.dictionaries.update", $vData);
    }
    
    public function dictionaryUpdatePost(DictionaryUpdateRequest $request, $id)
    {
        OfficeUser::checkAccess("dictionaries:update");
        
        $dictionary = Dictionary::find($id);
        if(!$dictionary)
            return redirect()->route("office.dictionaries")->withErrors(["msg" => __("Wartość nie istnieje")]);
        
        $validated = $request->validated();
        
        DB::transaction(function () use($validated, $dictionary) {
            $dictionary->value = $validated["value"];
            $dictionary->save();
            
            $dictionary->saveExtraValues($validated["extra"][$dictionary->type] ?? []);
        });
        
        Helper::setMessage("office:dictionaries", __("Wartość została zaktualizowana"));
        if($this->isApply())
            return redirect()->route("office.dictionary.update", $dictionary->id);
        else
            return redirect()->route("office.dictionaries");
    }
    
    public function dictionaryDelete(Request $request, $id)
    {
        OfficeUser::checkAccess("dictionaries:delete");
        
        $dictionary = Dictionary::find($id);
        if(!$dictionary)
            return redirect()->route("office.dictionaries")->withErrors(["msg" => __("Wartość nie istnieje")]);

        $dictionary->delete();
        
        Helper::setMessage("office:dictionaries", __("Wartość została usunięta"));
        return redirect()->route("office.dictionaries");
    }
}
