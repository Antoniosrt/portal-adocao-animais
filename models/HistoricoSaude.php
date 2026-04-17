<?php

class HistoricoSaude
{
    private int    $id          = 0;
    private int    $animalId    = 0;
    private string $dataEvento  = '';
    private string $tipo        = 'outro';
    private string $descricao   = '';
    private string $veterinario = '';
    private string $criadoEm   = '';

    public function getId(): int                      { return $this->id; }
    public function setId(int $id): void              { $this->id = $id; }

    public function getAnimalId(): int                { return $this->animalId; }
    public function setAnimalId(int $id): void        { $this->animalId = $id; }

    public function getDataEvento(): string               { return $this->dataEvento; }
    public function setDataEvento(string $d): void        { $this->dataEvento = $d; }

    public function getTipo(): string                 { return $this->tipo; }
    public function setTipo(string $t): void          { $this->tipo = $t; }

    public function getDescricao(): string            { return $this->descricao; }
    public function setDescricao(string $d): void     { $this->descricao = $d; }

    public function getVeterinario(): string              { return $this->veterinario; }
    public function setVeterinario(string $v): void       { $this->veterinario = $v; }

    public function getCriadoEm(): string             { return $this->criadoEm; }
    public function setCriadoEm(string $dt): void     { $this->criadoEm = $dt; }

    public static function fromArray(array $row): static
    {
        $obj = new static();
        $obj->id          = (int)($row['id'] ?? 0);
        $obj->animalId    = (int)($row['animal_id'] ?? 0);
        $obj->dataEvento  = $row['data_evento'] ?? '';
        $obj->tipo        = $row['tipo'] ?? 'outro';
        $obj->descricao   = $row['descricao'] ?? '';
        $obj->veterinario = $row['veterinario'] ?? '';
        $obj->criadoEm    = $row['criado_em'] ?? '';
        return $obj;
    }
}
