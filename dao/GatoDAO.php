<?php

class GatoDAO extends AnimalDAO
{
    public function buscarPorId(int $id): ?Animal
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, g.raca, g.pelagem, g.castrado
             FROM animais a
             LEFT JOIN gatos g ON g.animal_id = a.id
             WHERE a.id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Gato::fromArray($row) : null;
    }

    public function listarTodos(array $filtros = []): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, g.raca, g.pelagem, g.castrado
             FROM animais a
             LEFT JOIN gatos g ON g.animal_id = a.id
             WHERE a.especie = \'Gato\'
             ORDER BY a.data_cadastro DESC'
        );
        $stmt->execute();
        return array_map(fn($row) => Gato::fromArray($row), $stmt->fetchAll());
    }

    public function inserir(Animal $animal): int
    {
        /** @var Gato $animal */
        $id  = parent::inserir($animal);
        $esp = $animal->getAtributosEspecificos();
        $stmt = $this->pdo->prepare(
            'INSERT INTO gatos (animal_id, raca, pelagem, castrado) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$id, $esp['raca'], $esp['pelagem'], $esp['castrado']]);
        return $id;
    }

    public function atualizar(Animal $animal): bool
    {
        /** @var Gato $animal */
        parent::atualizar($animal);
        $esp  = $animal->getAtributosEspecificos();
        $stmt = $this->pdo->prepare(
            'UPDATE gatos SET raca=?, pelagem=?, castrado=? WHERE animal_id=?'
        );
        return $stmt->execute([$esp['raca'], $esp['pelagem'], $esp['castrado'], $animal->getId()]);
    }
}
