<?php
 
namespace App\Http\Controllers\Office;

use Exception;
use Illuminate\Http\Request;

use App\Http\Requests\Office\CustomerSftpRequest;
use App\Libraries\Ftp;
use App\Models\CaseRegistry;

class AjaxController
{
    public function run(Request $request, $method)
    {
        if(method_exists($this, $method))
        {
            return json_encode($this->$method($request));
        }
    }
    
    public function sftpTestConfiguration(Request $request)
    {
        $out = ["status" => false];
        
        $customerSftpRequest = app(CustomerSftpRequest::class);
        $data = $customerSftpRequest->validated();
        
        if(!empty($data))
        {
            $out["status"] = Ftp::testConfiguration($data);
            if(empty($out["status"]))
                $out["error"] = Ftp::$lastError;
        }
        return $out;
    }
}