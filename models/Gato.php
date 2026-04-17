<?php

class Gato extends Animal
{
    private string $raca     = '';
    private string $pelagem  = 'curto';
    private bool   $castrado = false;

    public function getEspecie(): string { return 'Gato'; }

    public function renderizarBadge(): string
    {
        return '<span class="badge badge-gato">Gato</span>';
    }

    public function getAtributosEspecificos(): array
    {
        return [
            'raca'     => $this->raca,
            'pelagem'  => $this->pelagem,
            'castrado' => $this->castrado ? 1 : 0,
        ];
    }

    public function getRaca(): string               { return $this->raca; }
    public function setRaca(string $raca): void     { $this->raca = $raca; }

    public function getPelagem(): string                { return $this->pelagem; }
    public function setPelagem(string $pelagem): void   { $this->pelagem = $pelagem; }

    public function isCastrado(): bool                  { return $this->castrado; }
    public function setCastrado(bool $c): void          { $this->castrado = $c; }

    public static function fromArray(array $row): static
    {
        $obj = new static();
        $obj->preencherBase($row);
        $obj->raca     = $row['raca'] ?? '';
        $obj->pelagem  = $row['pelagem'] ?? 'curto';
        $obj->castrado = (bool)($row['castrado'] ?? false);
        return $obj;
    }
}
