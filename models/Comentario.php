<?php

class Comentario
{
    private int    $id        = 0;
    private int    $animalId  = 0;
    private string $autor     = '';
    private string $texto     = '';
    private string $criadoEm  = '';

    public function getId(): int                    { return $this->id; }
    public function setId(int $id): void            { $this->id = $id; }

    public function getAnimalId(): int              { return $this->animalId; }
    public function setAnimalId(int $id): void      { $this->animalId = $id; }

    public function getAutor(): string              { return $this->autor; }
    public function setAutor(string $a): void       { $this->autor = $a; }

    public function getTexto(): string              { return $this->texto; }
    public function setTexto(string $t): void       { $this->texto = $t; }

    public function getCriadoEm(): string           { return $this->criadoEm; }
    public function setCriadoEm(string $dt): void   { $this->criadoEm = $dt; }

    public static function fromArray(array $row): static
    {
        $obj = new static();
        $obj->id       = (int)($row['id'] ?? 0);
        $obj->animalId = (int)($row['animal_id'] ?? 0);
        $obj->autor    = $row['autor'] ?? '';
        $obj->texto    = $row['texto'] ?? '';
        $obj->criadoEm = $row['criado_em'] ?? '';
        return $obj;
    }
}
