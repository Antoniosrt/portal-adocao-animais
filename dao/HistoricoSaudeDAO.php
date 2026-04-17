<?php

class HistoricoSaudeDAO implements IHistoricoDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorAnimal(int $animalId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM historico_saude WHERE animal_id = ? ORDER BY data_evento DESC'
        );
        $stmt->execute([$animalId]);
        return array_map(fn($row) => HistoricoSaude::fromArray($row), $stmt->fetchAll());
    }

    public function inserir(HistoricoSaude $registro): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO historico_saude (animal_id, data_evento, tipo, descricao, veterinario)
             VALUES (?, ?, ?, ?, ?) RETURNING id'
        );
        $stmt->execute([
            $registro->getAnimalId(),
            $registro->getDataEvento(),
            $registro->getTipo(),
            $registro->getDescricao(),
            $registro->getVeterinario(),
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM historico_saude WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
