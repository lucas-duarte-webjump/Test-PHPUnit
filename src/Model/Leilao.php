<?php

namespace Alura\Leilao\Model;

use DomainException;
use Error;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    private bool $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
        if(!empty($this->lances) && $this->ehUltimoUsuario($lance)) {
            throw new DomainException('Usuário não pode propor dois lances ao mesmo tempo');
        }


        $totalLancesUsuario = $this->quantidadeLancesPorUsuario($lance->getUsuario());
        if($totalLancesUsuario >= 5) {
            throw new DomainException('Usuário não pode propor mais de 5 lances por leilão');
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

      
    public function finaliza()
    {
        $this->finalizado = true;
    }

    public function estaFinalizado(): bool
    {
        return $this->finalizado;
    }

    private function ehUltimoUsuario(Lance $lance)
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];

        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    private function quantidadeLancesPorUsuario(Usuario $usuario): int
    {
        $totalLancesUsuario = array_reduce($this->lances, function (int $totalAcumulado, Lance $lanceAtual) use($usuario) {
            if($lanceAtual->getUsuario() == $usuario) {
                return $totalAcumulado + 1;
            }

            return $totalAcumulado;
        }, 0);

        return $totalLancesUsuario;
    }
}
