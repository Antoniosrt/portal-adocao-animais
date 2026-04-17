<?php

class AnimalGenerico extends Animal
{
    private string $especieDescricao = '';

    public function getEspecie(): string { return 'Generico'; }

    public function renderizarBadge(): string
    {
        $label = htmlspecialchars($this->especieDescricao ?: 'Animal');
        return "<span class=\"badge badge-generico\">{$label}</span>";
    }

    public function getAtributosEspecificos(): array
    {
        return ['especie_descricao' => $this->especieDescricao];
    }

    public function getEspecieDescricao(): string                   { return $this->especieDescricao; }
    public function setEspecieDescricao(string $desc): void        { $this->especieDescricao = $desc; }

    public static function fromArray(array $row): static
    {
        $obj = new static();
        $obj->preencherBase($row);
        $obj->especieDescricao = $row['especie_descricao'] ?? '';
        return $obj;
    }
}
