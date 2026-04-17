<?php

// Classe base abstrata para todos os animais do sistema.
// Define a interface comum (getEspecie, getAtributosEspecificos, renderizarBadge)
// que deve ser implementada por cada espécie concreta.
// O polimorfismo acontece quando o código cliente trabalha com Animal[] sem
// conhecer o tipo concreto — ex: listar.php chama $animal->renderizarBadge()
// em um array misto de Cachorro, Gato e Papagaio.
abstract class Animal
{
    private int    $id       = 0;
    private string $nome     = '';
    private int    $idadeMeses = 0;
    private string $descricao = '';
    private string $fotoPath  = '';
    private string $status    = 'disponivel';
    private string $dataCadastro = '';

    // Retorna o nome da espécie para identificação no banco e nas views
    abstract public function getEspecie(): string;

    // Retorna os atributos exclusivos da espécie para que o DAO os persista
    // na tabela de extensão correspondente (cachorros, gatos, papagaios, etc.)
    abstract public function getAtributosEspecificos(): array;

    // Gera o badge HTML colorido específico da espécie para exibição na listagem
    abstract public function renderizarBadge(): string;

    public function getId(): int               { return $this->id; }
    public function setId(int $id): void       { $this->id = $id; }

    public function getNome(): string               { return $this->nome; }
    public function setNome(string $nome): void     { $this->nome = $nome; }

    public function getIdadeMeses(): int                   { return $this->idadeMeses; }
    public function setIdadeMeses(int $meses): void        { $this->idadeMeses = $meses; }

    public function getDescricao(): string                   { return $this->descricao; }
    public function setDescricao(string $desc): void         { $this->descricao = $desc; }

    public function getFotoPath(): string                    { return $this->fotoPath; }
    public function setFotoPath(string $path): void          { $this->fotoPath = $path; }

    public function getStatus(): string                      { return $this->status; }
    public function setStatus(string $status): void          { $this->status = $status; }

    public function getDataCadastro(): string                { return $this->dataCadastro; }
    public function setDataCadastro(string $dt): void        { $this->dataCadastro = $dt; }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'especie'      => $this->getEspecie(),
            'nome'         => $this->nome,
            'idade_meses'  => $this->idadeMeses,
            'descricao'    => $this->descricao,
            'foto_path'    => $this->fotoPath,
            'status'       => $this->status,
            'data_cadastro'=> $this->dataCadastro,
        ];
    }

    protected function preencherBase(array $row): void
    {
        $this->id           = (int)($row['id'] ?? 0);
        $this->nome         = $row['nome'] ?? '';
        $this->idadeMeses   = (int)($row['idade_meses'] ?? 0);
        $this->descricao    = $row['descricao'] ?? '';
        $this->fotoPath     = $row['foto_path'] ?? '';
        $this->status       = $row['status'] ?? 'disponivel';
        $this->dataCadastro = $row['data_cadastro'] ?? '';
    }
}
