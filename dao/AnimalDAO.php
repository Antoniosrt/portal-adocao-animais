<?php

class AnimalDAO implements IAnimalDAO
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorId(int $id): ?Animal
    {
        $stmt = $this->pdo->prepare('SELECT * FROM animais WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->hidratarAnimal($row) : null;
    }

    public function listarTodos(array $filtros = []): array
    {
        $sql    = 'SELECT * FROM animais';
        $params = [];

        if (!empty($filtros['especie'])) {
            $sql     .= ' WHERE especie = ?';
            $params[] = $filtros['especie'];
        }
        $sql .= ' ORDER BY data_cadastro DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return array_map([$this, 'hidratarAnimal'], $stmt->fetchAll());
    }

    public function listarPorEspecie(string $especie): array
    {
        return $this->listarTodos(['especie' => $especie]);
    }

    public function inserir(Animal $animal): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO animais (especie, nome, idade_meses, descricao, foto_path, status)
             VALUES (:especie, :nome, :idade_meses, :descricao, :foto_path, :status)
             RETURNING id'
        );
        $dados = $animal->toArray();
        $stmt->execute([
            ':especie'      => $dados['especie'],
            ':nome'         => $dados['nome'],
            ':idade_meses'  => $dados['idade_meses'],
            ':descricao'    => $dados['descricao'],
            ':foto_path'    => $dados['foto_path'],
            ':status'       => $dados['status'],
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function atualizar(Animal $animal): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE animais SET nome=:nome, idade_meses=:idade_meses, descricao=:descricao,
             foto_path=:foto_path, status=:status WHERE id=:id'
        );
        $dados = $animal->toArray();
        return $stmt->execute([
            ':nome'        => $dados['nome'],
            ':idade_meses' => $dados['idade_meses'],
            ':descricao'   => $dados['descricao'],
            ':foto_path'   => $dados['foto_path'],
            ':status'      => $dados['status'],
            ':id'          => $dados['id'],
        ]);
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM animais WHERE id = ?');
        return $stmt->execute([$id]);
    }

    // Instancia a subclasse correta de Animal com base no campo 'especie' da linha do banco.
    // Centraliza aqui a decisão de qual classe concreta criar, evitando que o código
    // cliente precise conhecer todas as subclasses existentes.
    protected function hidratarAnimal(array $row): Animal
    {
        return match($row['especie']) {
            'Cachorro' => Cachorro::fromArray($row),
            'Gato'     => Gato::fromArray($row),
            'Papagaio' => Papagaio::fromArray($row),
            default    => AnimalGenerico::fromArray($row),
        };
    }
}
