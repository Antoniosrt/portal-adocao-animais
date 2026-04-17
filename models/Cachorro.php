<?php

class Cachorro extends Animal
{
    private string $raca     = '';
    private string $porte    = 'medio';
    private bool   $vacinado = false;

    public function getEspecie(): string { return 'Cachorro'; }

    public function renderizarBadge(): string
    {
        return '<span class="badge badge-cachorro">Cachorro</span>';
    }

    public function getAtributosEspecificos(): array
    {
        return [
            'raca'     => $this->raca,
            'porte'    => $this->porte,
            'vacinado' => $this->vacinado ? 1 : 0,
        ];
    }

    public function getRaca(): string               { return $this->raca; }
    public function setRaca(string $raca): void     { $this->raca = $raca; }

    public function getPorte(): string              { return $this->porte; }
    public function setPorte(string $porte): void   { $this->porte = $porte; }

    public function isVacinado(): bool              { return $this->vacinado; }
    public function setVacinado(bool $v): void      { $this->vacinado = $v; }

    public static function fromArray(array $row): static
    {
        $obj = new static();
        $obj->preencherBase($row);
        $obj->raca     = $row['raca'] ?? '';
        $obj->porte    = $row['porte'] ?? 'medio';
        $obj->vacinado = (bool)($row['vacinado'] ?? false);
        return $obj;
    }
}
