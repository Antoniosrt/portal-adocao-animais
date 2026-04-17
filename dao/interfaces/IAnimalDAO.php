<?php

interface IAnimalDAO
{
    public function buscarPorId(int $id): ?Animal;
    public function listarTodos(array $filtros = []): array;
    public function listarPorEspecie(string $especie): array;
    public function inserir(Animal $animal): int;
    public function atualizar(Animal $animal): bool;
    public function deletar(int $id): bool;
}
