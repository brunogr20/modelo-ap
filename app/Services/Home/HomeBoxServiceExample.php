<?php
namespace App\Services\Home;

use App\Entities\ApiNoticia;
use App\Entities\ApiParoquiano;
use App\Entities\ApiParoquia;
use App\Entities\ApiEstabelecimento;

class HomeBoxService {

  private $noticia_repo;
  private $paroquiano_repo;
  private $paroquia_repo;
  private $estabelecimento_repo;

  public function __construct(ApiNoticia $noticia_repo,ApiParoquiano $paroquiano_repo,
                              ApiParoquia $paroquia_repo,ApiEstabelecimento $estabelecimento_repo){
    $this->noticia_repo = $noticia_repo;
    $this->paroquiano_repo= $paroquiano_repo;
    $this->paroquia_repo=$paroquia_repo;
    $this->estabelecimento_repo=$estabelecimento_repo;
  }
  
  public function getHomeBox(){
    $countNoticia = $this->noticia_repo->where('status','SIM')->count();
    $countParoquiano = $this->paroquiano_repo->where('status','SIM')->count();
    $countParoquia = $this->paroquia_repo->where('status','SIM')->count();
    $countEstabelecimento = $this->estabelecimento_repo->where('status','SIM')->count();
    return [
      ['title'=>'Paroquianos','text'=>'Total de paroquianos','count'=>$countParoquiano,'icon'=>'fas fa-mobile-alt'],
      ['title'=>'Paróquias','text'=>'Total de paróquias','count'=> $countParoquia,'icon'=>'fas fa-church'],
      ['title'=>'Estabelecimentos','text'=>'Total de estabelecimentos','count'=>$countEstabelecimento,'icon'=>'fas fa-cash-register'],
      ['title'=>'Notícias','text'=>'Notícias publicadas','count'=>$countNoticia,'icon'=>'far fa-newspaper'],
    ];
  }

}
?>