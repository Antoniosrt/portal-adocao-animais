<?php

class Papagaio extends Animal
{
    private string $especieAve  = '';
    private bool   $falaHumana  = false;
    private string $corPlumagem = '';

    public function getEspecie(): string { return 'Papagaio'; }

    public function renderizarBadge(): string
    {
        return '<span class="badge badge-papagaio">Papagaio</span>';
    }

    public function getAtributosEspecificos(): array
    {
        return [
            'especie_ave'  => $this->especieAve,
            'fala_humana'  => $this->falaHumana ? 1 : 0,
            'cor_plumagem' => $this->corPlumagem,
        ];
    }

    public function getEspecieAve(): string                   { return $this->especieAve; }
    public function setEspecieAve(string $e): void            { $this->especieAve = $e; }

    public function isFalaHumana(): bool                      { return $this->falaHumana; }
    public function setFalaHumana(bool $f): void              { $this->falaHumana = $f; }

    public function getCorPlumagem(): string                  { return $this->corPlumagem; }
    public function setCorPlumagem(string $cor): void         { $this->corPlumagem = $cor; }

    public static function fromArray(array $row): static
    {
        $obj = new static();
        $obj->preencherBase($row);
        $obj->especieAve  = $row['especie_ave'] ?? '';
        $obj->falaHumana  = (bool)($row['fala_humana'] ?? false);
        $obj->corPlumagem = $row['cor_plumagem'] ?? '';
        return $obj;
    }
}
