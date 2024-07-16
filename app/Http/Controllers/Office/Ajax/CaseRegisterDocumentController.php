<?php
 
namespace App\Http\Controllers\Office\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Http\Requests\Office\CaseDocumentRequest;
use App\Libraries\Ftp;
use App\Libraries\Helper;
use App\Models\CaseRegistry;
use App\Models\CaseRegisterDocument;
use App\Traits\AjaxTable;

class CaseRegisterDocumentController extends Controller
{
    use AjaxTable;
    
    protected function modelName() : string
    {
        return CaseRegisterDocument::class;
    }
    
    public function list(Request $request, $id)
    {
        $params = $this->getAjaxTableParams($request);
        $sort = $this->getSortOrder($request, false);
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $documents = $case->documents();
        
        if(!empty($params["filter"]["name"]))
            $documents->where("name", "LIKE", "%" . $params["filter"]["name"] . "%");
        if(!empty($params["filter"]["date_from"]))
            $documents->where("date", ">=", $params["filter"]["date_from"]);
        if(!empty($params["filter"]["date_to"]))
            $documents->where("date", "<=", $params["filter"]["date_to"]);
        
        $documents->orderBy($sort[0], $sort[1]);
        $maxRows = $documents->count();

        $documents = $documents->paginate($params["topRecords"]);

        $vData = [
            "case" => $case,
            "documents" => $documents,
            "hasSftp" => $case->hasCustomerSftpConfigured(),
        ];

        $view = view("office.case-register.table.document-table", $vData);
        
        $out = [
            "table" => $view->render(),
            "maxrows" => $maxRows,
            "paginator" => $documents->render("office.partials.pagination")->toHtml()
        ];
        
		return $out;
    }
        
    public function getDocument(Request $request, $id, $did)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $document = CaseRegisterDocument::find($did);
        if(!$document)
            throw new Exception(__("Dokument nie istnieje"));
        
        return [
            "data" => $document
        ];
    }
    
    public function documentPost(CaseDocumentRequest $request, $id)
    {
        $validated = $request->validated();
        
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $customer = $case->getCustomer();
        if(!$customer)
            throw new Exception(__("Klient nie istnieje"));
        
        if(!$customer->hasCustomerSftpConfigured())
            throw new Exception(__("Brak skonfigurowanego połączenia SFTP"));
        
        $ftp = FTP::getFTPDriver($customer);
        
        if(!empty($validated["id"]))
        {
            $document = CaseRegisterDocument::find($validated["id"]);
            if(!$document)
                throw new Exception(__("Dokument nie istnieje"));
            
            if(!empty($validated["replace_file"]))
            {
                $document->deleteFile();
                
                $file = $validated["document"];
                $sftpFileName = Str::uuid() . "." . $file->getClientOriginalExtension();
                $ftp->put($sftpFileName, file_get_contents($file->getPathName()));
                
                $document->file = $sftpFileName;
                $document->origfile = $file->getClientOriginalName();
            }
            
            $document->name = $validated["name"];
            $document->save();
        }
        else
        {
            $file = $validated["document"];
            
            $sftpFileName = Str::uuid() . "." . $file->getClientOriginalExtension();
            $ftp->put($sftpFileName, file_get_contents($file->getPathName()));
            
            $document = new CaseRegisterDocument;
            $document->case_registry_id = $case->id;
            $document->name = $validated["name"];
            $document->file = $sftpFileName;
            $document->origfile = $file->getClientOriginalName();
            $document->date = date("Y-m-d");
            $document->save();
        }
        
        return ["success" => true];
    }
    
    public function deleteDocument(Request $request, $id, $did)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $document = CaseRegisterDocument::find($did);
        if(!$document)
            throw new Exception(__("Postępowanie nie istnieje"));
        
        $document->delete();
        
        return ["success" => true];
    }
    
    public function download(Request $request, $id, $did)
    {
        $case = CaseRegistry::find($id);
        if(!$case)
            throw new Exception(__("Sprawa nie istnieje"));
        
        $document = CaseRegisterDocument::find($did);
        if(!$document)
            throw new Exception(__("Postępowanie nie istnieje"));
        
        $customer = $case->getCustomer();
        if(!$customer)
            throw new Exception(__("Klient nie istnieje"));
        
        if(!$customer->hasCustomerSftpConfigured())
            throw new Exception(__("Brak skonfigurowanego połączenia SFTP"));
        
        $ftp = FTP::getFTPDriver($customer);
        $content = $ftp->get($document->file);
        
        $mime = $ftp->mimeType($document->file, $document->origfile);

        $response = Response::make($content, 200);
        $response->header("Content-Type", $mime);
        $response->header("Content-Disposition", "attachment; filename=\"" . $document->origfile . "\"");
        return $response;
    }
}