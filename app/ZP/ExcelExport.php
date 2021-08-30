<?php

namespace App\ZP;
use App\Entities\Sys\SysLogAcao;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExcelExport implements FromArray, WithHeadings,WithEvents,WithTitle{

    private $title;
    private $header;
    private $body;

    function __construct($title="",$header=[],$body=[]){
       $this->title = $title;    
       $this->header = $header;    
       $this->body = $body;    
    }

    public function title(): string
    {
      return $this->title;
    }
    
    public function setTitle($title){
        $this->title = $title;
    }
    public function setHeader($header){
        $this->header = $header;
    }
    public function setBody($body){
        $this->body = $body;
    }

    public function headings(): array{      
        return $this->header;
    }

    public function array(): array{
        $b=[];
        $i=0;
        foreach((array)$this->body as $val){
            $arVal=(array)$val;
            foreach($this->header as $k=>$v){
               $b[$i][]=!empty($arVal[$k])?$arVal[$k]:'';
            }
            $i++;
        }
       return $b;
    }

    // public function sheets(): array
    // {
    //     $sheets = [];

    //     for ($month = 1; $month <= 12; $month++) {
    //         $sheets[] = new InvoicesPerMonthSheet($this->year, $month);
    //     }

    //     return $sheets;
    // }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $countCols=(!empty($this->header))?count($this->header):0;
                $letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                if ($countCols>26){// define colunas compostas ex: AA,AB,AC,...
                 $arAux = [];
                 foreach ($letters as $p){
                  foreach ($letters as $s){
                    $arAux[] = $p.$s;
                  }
                  if(count($arAux)>$countCols){break;}
                 }
                 $letters = array_merge($letters,$arAux);
                }
                // foreach ($letters as $val){
                //   $event->sheet->getDelegate()->getColumnDimension($val)->setAutoSize(true);
                // }
                // All headers - set font size to 14
                $cellRange = "A1:{$letters[$countCols-1]}1"; 
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle("A1:{$letters[$countCols-1]}".(count($this->body)+1))->getAlignment()->setHorizontal('center');
                // $styleArray = [
                //     'font' => [
                //         'bold' => true,
                //     ],
                //     'alignment' => [
                //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                //     ],
                //     'borders' => [
                //         'top' => [
                //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //         ],
                //     ],
                //     'fill' => [
                //         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                //         'rotation' => 90,
                //         'startColor' => [
                //             'argb' => 'FFA0A0A0',
                //         ],
                //         'endColor' => [
                //             'argb' => 'FFFFFFFF',
                //         ],
                //     ],
                // ];
                
                
                // 
                // $event->sheet->getDelegate()->getStyle('B2:D4')->applyFromArray($styleArray);
                // Apply array of styles to B2:G8 cell range
                /*$styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ]
                    ]
                ];*/
                //$event->sheet->getDelegate()->getStyle('B2:G8')->applyFromArray($styleArray);
                // Set first row to height 20
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);
                // Set A1:D4 range to wrap text in cells
                // $event->sheet->getDelegate()->getStyle('A1:I4')->getAlignment()->setWrapText(true);
            },
        ];
    }

}