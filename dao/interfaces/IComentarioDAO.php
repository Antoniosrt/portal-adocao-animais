<?php

interface IComentarioDAO
{
    public function buscarPorAnimal(int $animalId): array;
    public function inserir(Comentario $comentario): int;
    public function deletar(int $id): bool;
}
