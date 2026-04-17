<?php

interface IHistoricoDAO
{
    public function buscarPorAnimal(int $animalId): array;
    public function inserir(HistoricoSaude $registro): int;
    public function deletar(int $id): bool;
}
