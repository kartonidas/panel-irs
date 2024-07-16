<?php
 
namespace App\Http\Controllers\Office;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CourtStoreRequest;
use App\Http\Requests\Office\CourtUpdateRequest;
use App\Libraries\Helper;
use App\Models\Court;
use App\Models\OfficeUser;
use App\Traits\Form;

class CourtController extends Controller
{
    use Form;
    
    protected function modelName() : string
    {
        return Court::class;
    }
    
    public function list(Request $request)
    {
        OfficeUser::checkAccess("courts:list");
        view()->share("activeMenuItem", "courts");
        
        $filter = $this->getFilter($request);
        $sort = $this->getSortOrder($request);
        
        $courts = Court::orderBy($sort[0], $sort[1]);
        if(!empty($filter["name"]))
            $courts->where("name", "LIKE", "%" . $filter["name"] . "%");
        if(!empty($filter["city"]))
            $courts->where("city", "LIKE", "%" . $filter["city"] . "%");

        $courts = $courts->paginate(config("office.lists.size"));
        
        $vData = [
            "filter" => $filter,
            "courts" => $courts,
            "sort" => $sort,
            "sortColumns" => $this->getSortableFields($sort),
        ];
        return view("office.courts.list", $vData);
    }
    
    public function courtCreate(Request $request)
    {
        OfficeUser::checkAccess("courts:create");
        view()->share("activeMenuItem", "courts");
        
        $formData = [];

        $vData = [
            "form" => $request->old() ? $request->old() : $formData,
        ];
        return view("office.courts.create", $vData);
    }
    
    public function courtCreatePost(CourtStoreRequest $request)
    {
        OfficeUser::checkAccess("courts:create");
        
        $validated = $request->validated();
        
        $court = DB::transaction(function () use($validated) {
            $court = new Court;
            $court->name = $validated["name"];
            $court->street = $validated["street"];
            $court->city = $validated["city"];
            $court->zip = $validated["zip"];
            $court->phone = $validated["phone"];
            $court->fax = $validated["fax"];
            $court->email = $validated["email"];
            $court->save();
            
            return $court;
        });

        Helper::setMessage("office:courts", __("Sąd został dodany"));
        if($this->isApply())
            return redirect()->route("office.court.update", $court->id);
        else
            return redirect()->route("office.courts");
    }
    
    public function courtUpdate(Request $request, $id)
    {
        OfficeUser::checkAccess("courts:update");
        view()->share("activeMenuItem", "courts");
        
        $court = Court::find($id);
        if(!$court)
            return redirect()->route("ofice.courts")->withErrors(["msg" => __("Sąd nie istnieje")]);
        
        $formData = $court->toArray();
        
        $vData = [
            "id" => $court->id,
            "form" => $request->old() ? $request->old() : $formData,
            "court" => $court,
        ];
        
        return view("office.courts.update", $vData);
    }
    
    public function courtUpdatePost(CourtUpdateRequest $request, $id)
    {
        OfficeUser::checkAccess("courts:update");
        
        $court = Court::find($id);
        if(!$court)
            return redirect()->route("ofice.courts")->withErrors(["msg" => __("Sąd nie istnieje")]);
        
        $validated = $request->validated();
        
        DB::transaction(function () use($court, $validated) {
            $court->name = $validated["name"];
            $court->street = $validated["street"];
            $court->city = $validated["city"];
            $court->zip = $validated["zip"];
            $court->phone = $validated["phone"];
            $court->fax = $validated["fax"];
            $court->email = $validated["email"];
            $court->save();
        });

        Helper::setMessage("office:courts", __("Sąd został zaktualizowany"));
        if($this->isApply())
            return redirect()->route("office.court.update", $court->id);
        else
            return redirect()->route("office.court.show", $court->id);
    }
    
    public function courtShow(Request $request, $id)
    {
        OfficeUser::checkAccess("courts:list");
        view()->share("activeMenuItem", "courts");
        
        $court = Court::find($id);
        if(!$court)
            return redirect()->route("ofice.courts")->withErrors(["msg" => __("Sąd nie istnieje")]);
        
        $vData = [
            "court" => $court,
        ];
        
        return view("office.courts.show", $vData);
    }
    
    public function courtDelete(Request $request, $id)
    {
        OfficeUser::checkAccess("courts:delete");

        $court = Court::find($id);
        if(!$court)
            return redirect()->route("ofice.courts")->withErrors(["msg" => __("Sąd nie istnieje")]);

        $court->delete();
        
        Helper::setMessage("office:courts", __("Sąd został usunięty"));
        return redirect()->route("office.courts");
    }
    
    public function courtImport(Request $request)
    {
        return view("office.courts.import");
    }
    
    public function courtImportPost(Request $request)
    {
        Validator::validate($request->all(), ["xls" => ["required", File::types(["xlsx"])]]);
        
        $courts = [];
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($request->file("xls")->path());
        foreach ($reader->getSheetIterator() as $sheet)
        {
            foreach ($sheet->getRowIterator() as $i => $row)
            {
                $row = $row->toArray();
                $courts[] = [
                    str($row[0])->squish(),
                    str($row[1])->squish(),
                    str($row[2])->squish(),
                    str($row[3])->squish(),
                    str($row[4])->squish(),
                    str($row[5])->squish(),
                    str($row[6])->squish(),
                ];
            }
        }
        $reader->close();
        
        DB::transaction(function() use($courts) {
            foreach($courts as $court)
            {
                $courtRow = new Court;
                $courtRow->name = $court[0];
                $courtRow->street = $court[1];
                $courtRow->zip = $court[2];
                $courtRow->city = $court[3];
                $courtRow->phone = $court[4];
                $courtRow->fax = $court[5];
                $courtRow->email = $court[6];
                $courtRow->save();
            }
        });
        
        Helper::setMessage("office:courts", __("Import został wykonany"));
        return redirect()->route("office.courts.import");
    }
}