<?php

class CachorroDAO extends AnimalDAO
{
    public function buscarPorId(int $id): ?Animal
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, c.raca, c.porte, c.vacinado
             FROM animais a
             LEFT JOIN cachorros c ON c.animal_id = a.id
             WHERE a.id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Cachorro::fromArray($row) : null;
    }

    public function listarTodos(array $filtros = []): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, c.raca, c.porte, c.vacinado
             FROM animais a
             LEFT JOIN cachorros c ON c.animal_id = a.id
             WHERE a.especie = \'Cachorro\'
             ORDER BY a.data_cadastro DESC'
        );
        $stmt->execute();
        return array_map(fn($row) => Cachorro::fromArray($row), $stmt->fetchAll());
    }

    public function inserir(Animal $animal): int
    {
        /** @var Cachorro $animal */
        $id = parent::inserir($animal);

        $esp = $animal->getAtributosEspecificos();
        $stmt = $this->pdo->prepare(
            'INSERT INTO cachorros (animal_id, raca, porte, vacinado) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$id, $esp['raca'], $esp['porte'], $esp['vacinado']]);
        return $id;
    }

    public function atualizar(Animal $animal): bool
    {
        /** @var Cachorro $animal */
        parent::atualizar($animal);

        $esp  = $animal->getAtributosEspecificos();
        $stmt = $this->pdo->prepare(
            'UPDATE cachorros SET raca=?, porte=?, vacinado=? WHERE animal_id=?'
        );
        return $stmt->execute([$esp['raca'], $esp['porte'], $esp['vacinado'], $animal->getId()]);
    }
}
