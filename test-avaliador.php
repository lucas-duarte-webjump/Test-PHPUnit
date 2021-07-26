<?php

use Alura\Leilao\Model\{Lance, Leilao, Usuario};
use Alura\Leilao\Services\Avaliador;

require_once "vendor/autoload.php";

$leilao = new Leilao('Fiat 147 0KM');


$maria = new Usuario('Maria');

$joao = new Usuario('Joao');

$leilao->recebeLance(new Lance($joao, 2000));

$leilao->recebeLance(new Lance($maria, 2500));

$leiloeiro = new Avaliador();

$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();
