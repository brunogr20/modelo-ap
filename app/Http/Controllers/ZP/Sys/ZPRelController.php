<?php
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysCoreController;
use App\Services\Sys\SysCoreService;
use App\ZP\Message;

class ZPRelController extends SysCoreController {  
    private $report;
    function __construct(SysCoreService $core_service,$report){
        parent::__construct($core_service);
        $this->report = $report;
    }
  
    public function filter(Request $request){
        $data=$request->all();
        
        $r = $this->report->filter($data);
        if($data['rel_type']=='VIEW'){
        return $r;
        }else if($data['rel_type']=='EXCEL'){
        if($r['status']){
        return Message::get("SUCCESS","FILE_GENERATE",['nameFile'=>$r['nameFile']]);
        }else{
        Message::get("ERROR","FILE_GENERATE");
        }
        }
    }

    public function getDownloadReport(Request $request,$file){  
        return $this->report->getDownloadReport($file);
    }

}