<?php

class ComentarioDAO implements IComentarioDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorAnimal(int $animalId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM comentarios WHERE animal_id = ? ORDER BY criado_em DESC'
        );
        $stmt->execute([$animalId]);
        return array_map(fn($row) => Comentario::fromArray($row), $stmt->fetchAll());
    }

    public function inserir(Comentario $comentario): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO comentarios (animal_id, autor, texto) VALUES (?, ?, ?) RETURNING id'
        );
        $stmt->execute([
            $comentario->getAnimalId(),
            $comentario->getAutor(),
            $comentario->getTexto(),
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM comentarios WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
