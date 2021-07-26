<?php
namespace Alura\Leilao\Testes\Services;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Services\Avaliador;
use DomainException;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase

{
  
  private Avaliador $leiloeiro;
  
  protected function setUp(): void
  {
    $this->leiloeiro = new Avaliador();
  }
  
  public function testLeilaoVazioNaoPodeSerSalvo() 
  {
    $this->expectException(DomainException::class);
    $this->expectExceptionMessage('Não é possível avaliar um leilão vazio');
    $leilao = new Leilao('Fusca Azul');

    $this->leiloeiro->avalia($leilao);
  }
  /**
   * @dataProvider leilaoEmOrdemCrescente
   * @dataProvider leilaoEmOrdemDecrescente
   * @dataProvider leilaoEmOrdemAleatoria
   *
   */
  public function testAvaliadorDeveEncontraMaiorValor(Leilao $leilao)
  {

    $this->leiloeiro->avalia($leilao);

    $maiorValor = $this->leiloeiro->getMaiorValor();

    self::assertEquals(2500, $maiorValor);
  }
 
  /**
   * @dataProvider leilaoEmOrdemCrescente
   * @dataProvider leilaoEmOrdemDecrescente
   * @dataProvider leilaoEmOrdemAleatoria
   *
   */
  public function testAvaliadorDeveEncontraMenorValor(Leilao $leilao)
  {
    $this->leiloeiro->avalia($leilao);

    $menorValor = $this->leiloeiro->getMenorValor();

    self::assertEquals(1700, $menorValor);
  }
   /**
   * @dataProvider leilaoEmOrdemCrescente
   * @dataProvider leilaoEmOrdemDecrescente
   * @dataProvider leilaoEmOrdemAleatoria
   *
   */

  public function testAvaliadorDeveBuscar3MaioresValores(Leilao $leilao)
  {
   
    $this->leiloeiro->avalia($leilao);

    $maiores = $this->leiloeiro->getMaioresLances();

    self::assertCount(3, $maiores);
    self::assertEquals(2500, $maiores[0]->getValor());
    self::assertEquals(2000, $maiores[1]->getValor());
    self::assertEquals(1700, $maiores[2]->getValor());

  }

  public function testLeilaoFinalizadoNaoPodeSerFinalizado()
  {
    $this->expectException(DomainException::class);
    $this->expectExceptionMessage("Leilão já Finalizado"); 

    $leilao = new Leilao('Fiat 147 0KM');

    $maria = new Usuario('Maria');

    $leilao->recebeLance(new Lance($maria, 2000));

    $leilao->finaliza();

    $this->leiloeiro->avalia($leilao); 

  }

  public function leilaoEmOrdemCrescente()
  {
    $leilao = new Leilao('Fiat 147 0KM');


    $maria = new Usuario('Maria');

    $joao = new Usuario('Joao');
    $ana = new Usuario('Ana');


    $leilao->recebeLance(new Lance($ana, 1700));
    $leilao->recebeLance(new Lance($joao, 2000));
    $leilao->recebeLance(new Lance($maria, 2500));

    return [
      'Ordem-crescente' => [$leilao]
    ];
  }

  public function leilaoEmOrdemDecrescente()
  {
    $leilao = new Leilao('Fiat 147 0KM');


    $maria = new Usuario('Maria');

    $joao = new Usuario('Joao');
    $ana = new Usuario('Ana');


    $leilao->recebeLance(new Lance($maria, 2500));
    $leilao->recebeLance(new Lance($joao, 2000));
    $leilao->recebeLance(new Lance($ana, 1700));

    return [
      'Ordem-decrescente' => [$leilao]
    ]; 
  }
  public function leilaoEmOrdemAleatoria()
  {
    $leilao = new Leilao('Fiat 147 0KM');


    $maria = new Usuario('Maria');

    $joao = new Usuario('Joao');
    $ana = new Usuario('Ana');


    $leilao->recebeLance(new Lance($joao, 2000));
    $leilao->recebeLance(new Lance($maria, 2500));
    $leilao->recebeLance(new Lance($ana, 1700));

    return [
      'Ordem-aleatoria' => [$leilao]
    ];
  }
}
