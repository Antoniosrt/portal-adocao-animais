<?php

class AnimalGenericoDAO extends AnimalDAO
{
    public function buscarPorId(int $id): ?Animal
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, ag.especie_descricao
             FROM animais a
             LEFT JOIN animais_genericos ag ON ag.animal_id = a.id
             WHERE a.id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? AnimalGenerico::fromArray($row) : null;
    }

    public function listarTodos(array $filtros = []): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, ag.especie_descricao
             FROM animais a
             LEFT JOIN animais_genericos ag ON ag.animal_id = a.id
             WHERE a.especie = \'Generico\'
             ORDER BY a.data_cadastro DESC'
        );
        $stmt->execute();
        return array_map(fn($row) => AnimalGenerico::fromArray($row), $stmt->fetchAll());
    }

    public function inserir(Animal $animal): int
    {
        /** @var AnimalGenerico $animal */
        $id  = parent::inserir($animal);
        $esp = $animal->getAtributosEspecificos();
        $stmt = $this->pdo->prepare(
            'INSERT INTO animais_genericos (animal_id, especie_descricao) VALUES (?, ?)'
        );
        $stmt->execute([$id, $esp['especie_descricao']]);
        return $id;
    }

    public function atualizar(Animal $animal): bool
    {
        /** @var AnimalGenerico $animal */
        parent::atualizar($animal);
        $esp  = $animal->getAtributosEspecificos();
        $stmt = $this->pdo->prepare(
            'UPDATE animais_genericos SET especie_descricao=? WHERE animal_id=?'
        );
        return $stmt->execute([$esp['especie_descricao'], $animal->getId()]);
    }
}
