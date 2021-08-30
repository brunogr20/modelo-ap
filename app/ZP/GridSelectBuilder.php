<?php
namespace App\ZP;
use Illuminate\Support\Facades\DB;

class GridSelectBuilder{   
    /**
     * @internal Seta um limite na query
     *  */ 
    static function limit($request){
      $limit = '';
      if(isset($request['start'])&&$request['length']!=-1){
        $limit = " LIMIT ".intval($request['start']).", ".intval($request['length']);
      }
      return $limit;
    }
     /**
     * @internal Seta a Ordem na query
     *  */
    static function order($request,$columns){
      $order = "";
      if(isset($request['order'])&&count($request['order'])){
      $arOrder = array();
     // $arFix = array('asc'=>'ASC','desc'=>'DESC');
      foreach($request['order'] as $req_order){
     //  if(!empty($arFix[$req_order['dir']])){
     //   $arOrder[] = $columns[$req_order['column']]." ".$arFix[$req_order['dir']]; 
        if($req_order['dir']){
           $arOrder[] = $columns[$req_order['column']]." ".$req_order['dir']; 
        }      
      }
      $order = (count($arOrder))? " ORDER BY ".join(', ',$arOrder) :'';
      }
      return $order;
    }
     /**
     * @internal Seta os filtros na query
     *  */
    static function filter($request,$searchable){
      $where = "";
      if(isset($request['search'])&&$request['search']['value']!=''){
      $where = "";
      $i = 0;
        foreach($searchable as $column){
          $searchvalue = "'%".$request['search']['value']."%'"; //be sure to use an escape here for $request['search']['value']
          if($column){
          if($i==0){
            $where .= " $column LIKE ".$searchvalue."";
          }else{
            $where .= " OR $column LIKE ".$searchvalue."";
          }
          $i++;
          }
        }
      }
      return ($where)?" AND ($where)":'';
    }
     /**
     * @internal Cria uma Subquery
     *  */
    static function buildSubquery($baseSql){
      return $baseSql;
      // return "SELECT * FROM (".$baseSql.")b";
    }

    /**
     * Adiciona Colunas
     */
    static function addAsColumns($as,$campo,$array){
      foreach($array as &$value){
      if($value==$campo)
        $value = $as.'.'.$campo;
      }
      return $array;
    }

    /**
     * @internal Retorna o array estrutura do dataTable.
     */
    static function buildTable($request,$baseSql,$functions,$query = true){
      //tratar query inicial
     /* $arCampos = array('id');*/
     if(empty($baseSql['FROM_BODY'])){
       return "Falha na montagem do FROM_BODY";
     }

      $sqlFrom = "";
      $select = "";
      $sqlFrom = $baseSql['FROM_BODY'];
      $colsView=[];
      $colsOrder=['id'];
      foreach($request['columns'] as $valCol){// dados via request
        if($valCol['data']){
          $colsView[] = $valCol['data'];
          $colsOrder[]= $valCol['data'];
        }
      }
      $colsToTbAs=[];
      $asToCol=[];
      if(empty($baseSql['SELECT'])||!is_array($baseSql['SELECT'])){
        $select = join(', ',$colsOrder);
      }else{
        $aux=[];
        foreach($baseSql['SELECT'] AS $kTbAs => $cols){
          foreach($cols AS $kCol =>$as){
            if(is_int($kCol)){
              $aux[]=$kTbAs.".{$as}";
              $colsToTbAs[$as]=$kTbAs;
            }else{
              preg_match_all('/SELECT/',ucfirst($kCol), $matSel); 
              if(count($matSel[0])||count(explode('(',$kCol))>1){
                $aux[]="{$kCol} AS {$as}";
              }else{
                $aux[]=$kTbAs.".{$kCol} AS {$as}";
                $colsToTbAs[$as]=$kTbAs;
                $asToCol[$as]=$kCol;
              }
            }
          }
        }
        $select =(count($aux))? ' '.join(', ',$aux).' ':' * ';
      }
    
      $filter='';
      if(!empty($request['search']['value'])){
        $search="'%{$request['search']['value']}%'";
        $sqlSearch=[];
        foreach($colsView as $kCol){
         if(!empty($colsToTbAs[$kCol])&&!empty($asToCol[$kCol])){
           $sqlSearch[] = " {$colsToTbAs[$kCol]}.{$asToCol[$kCol]}  LIKE {$search} ";
         }elseif(!empty($colsToTbAs[$kCol])){
           $sqlSearch[] = " {$colsToTbAs[$kCol]}.{$kCol}  LIKE {$search} ";
         }else{
          $sqlSearch[] = " {$kCol} LIKE {$search} ";
         }
        }
        $filter.=(count($sqlSearch))? ' AND'.join(' OR ',$sqlSearch):'';
       }
      ///
      $bSql = " SELECT $select FROM {$sqlFrom} WHERE {$baseSql['WHERE']} ";
      $sqlCount = " SELECT count(*) AS total FROM {$sqlFrom} WHERE {$baseSql['WHERE']} ";
      //$sql = self::buildSubquery($bSql);
      $sql = $bSql;
      $where = $filter; //self::filter($request,$searchable);
      $order = (!empty($baseSql['ORDER BY']))?' ORDER BY '.$baseSql['ORDER BY']:self::order($request,$colsOrder);
      $limit = self::limit($request);
      if($query){
        $data = DB::select($sql.$where.$order.$limit);
      }else{
        return $sql.$where.$order.$limit;
      }
      //  if(!empty($functions)){
      //   if($data)
      $data = self::runDataFunctions($data,$functions);
      //  }
      //		array_walk_recursive($data, 'self::encode_items'); //if you need to UTF8-encode your data
      $recordsTotal = DB::select($sqlCount);
      $recordsFiltered = DB::select($sqlCount.$where);
      return array(
          "draw"=>intval($request['draw']),
          "recordsTotal"=>intval($recordsTotal[0]->total),
          "recordsFiltered"=>intval($recordsFiltered[0]->total),
          "data"=>$data
      );
    }

    static function runDataFunctions($data,$functions){
      foreach($data as &$row){
        foreach($row as $k=>&$v){ 
          if(!empty($functions[$k])){
            foreach($functions[$k] as $fn){
              if(is_callable($fn)){
                $v=$fn($v);
              }
            }
          }
        }
      }
      return $data;
    }

    static function editRow($row,$function){
      $rowTemp = $function['replace'];
      foreach($function['params'] as $param){
      $rowTemp = preg_replace("~\{".$param."\}~is",$row[$param],$rowTemp);
      }
      $row[$function['target']] = $rowTemp;
      return $row;
    }

    static function date_format($row,$function){
      foreach($function['columns'] as $column){
       $row[$column] = date_create_from_format($function['format_from'],$row[$column]);
       $row[$column] = date_format($row[$column],$function['format_to']);
      }
      return $row;
    }
    /**
     * @internal Formata String para decimal
     */
    private static function number_format_decimal($row,$function){
      foreach($function['columns'] as $column){
      $row[$column] = $function['prefix'].number_format($row[$column],2,'.',',').$function['suffix'];
      }
      return $row;
    }
    /**
     * @internal Formata String para inteiro
     */
    private static function number_format_whole($row,$function){
      foreach($function['columns'] as $column){
      $row[$column] = $function['prefix'].number_format($row[$column]).$function['suffix'];
      }
      return $row;
    }
    /**
     * @internal Faz encode UTF8 em um dado array
     */
    private static function encode_items(&$item,$key){
      $item = utf8_encode($item);
    }

}
