<?php

namespace Alura\Leilao\Testes\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use DomainException;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{

    public function testLeilaoNaoDeveReberLancesRepetidos()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor dois lances ao mesmo tempo');
        $leilao = new Leilao('Variante');

        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 2000));
    }

    public function testLeilaoNaoDeveReeberMaisDe5LancesPorUsuario()
    { 
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');
        $leilao = new Leilao('Brasília Amarela');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 1500));
        $leilao->recebeLance(new Lance($joao, 1700));
        $leilao->recebeLance(new Lance($maria, 2000));
        $leilao->recebeLance(new Lance($joao, 2200));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 4000));
        $leilao->recebeLance(new Lance($joao, 4500));
        $leilao->recebeLance(new Lance($maria, 5000));
        $leilao->recebeLance(new Lance($joao, 6000));
    }

  


    /**
     * @dataProvider geraLances
     *
     * @return void
     */
    public function testLeilaoDeveReceberLances(int $quantidadeLances, Leilao $leilao, array $valores)
    {
   
        self::assertCount($quantidadeLances, $leilao->getLances());

        foreach($valores as $i => $valoresEsperado) {
            self::assertEquals($valoresEsperado, $leilao->getLances()[$i]->getValor());
        }

    }

    public function geraLances()
    {
        $lucas = new Usuario('Lucas');
        $joao = new Usuario('João');

        $leilaoCom2Lances = new Leilao("Fiat 147 0KM");

        $leilaoCom2Lances->recebeLance(new Lance($lucas, 1000));
        $leilaoCom2Lances->recebeLance(new Lance($joao, 2000));

        $leilaoCom1Lance = new Leilao('Fusca 1972 0KM');

        $leilaoCom1Lance->recebeLance(new Lance($lucas, 5000));

        return [
            '2-lances' => [2, $leilaoCom2Lances, [1000, 2000]],
            '1-lance' => [1, $leilaoCom1Lance, [5000]]
        ];
    }
}