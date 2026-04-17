<?php

class PapagaioDAO extends AnimalDAO
{
    public function buscarPorId(int $id): ?Animal
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, p.especie_ave, p.fala_humana, p.cor_plumagem
             FROM animais a
             LEFT JOIN papagaios p ON p.animal_id = a.id
             WHERE a.id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Papagaio::fromArray($row) : null;
    }

    public function listarTodos(array $filtros = []): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, p.especie_ave, p.fala_humana, p.cor_plumagem
             FROM animais a
             LEFT JOIN papagaios p ON p.animal_id = a.id
             WHERE a.especie = \'Papagaio\'
             ORDER BY a.data_cadastro DESC'
        );
        $stmt->execute();
        return array_map(fn($row) => Papagaio::fromArray($row), $stmt->fetchAll());
    }

    public function inserir(Animal $animal): int
    {
        /** @var Papagaio $animal */
        $id  = parent::inserir($animal);
        $esp = $animal->getAtributosEspecificos();
        $stmt = $this->pdo->prepare(
            'INSERT INTO papagaios (animal_id, especie_ave, fala_humana, cor_plumagem) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$id, $esp['especie_ave'], $esp['fala_humana'], $esp['cor_plumagem']]);
        return $id;
    }

    public function atualizar(Animal $animal): bool
    {
        /** @var Papagaio $animal */
        parent::atualizar($animal);
        $esp  = $animal->getAtributosEspecificos();
        $stmt = $this->pdo->prepare(
            'UPDATE papagaios SET especie_ave=?, fala_humana=?, cor_plumagem=? WHERE animal_id=?'
        );
        return $stmt->execute([$esp['especie_ave'], $esp['fala_humana'], $esp['cor_plumagem'], $animal->getId()]);
    }
}
