<?php

// Classe base abstrata para todos os campos de formulário.
// Cada subclasse representa um tipo de input HTML diferente e é responsável
// por gerar seu próprio HTML (renderizar) e validar seu próprio valor (validar).
// FormGenerico itera um array de Campo[] sem saber o tipo concreto de cada um —
// esse é o ponto central de polimorfismo do sistema de formulários.
abstract class Campo
{
    protected string $nome;
    protected string $rotulo;
    protected mixed  $valor       = null;
    protected bool   $obrigatorio = false;
    protected array  $regras      = [];
    protected array  $erros       = [];

    public function __construct(
        string $nome,
        string $rotulo,
        bool   $obrigatorio = false,
        array  $regras      = []
    ) {
        $this->nome        = $nome;
        $this->rotulo      = $rotulo;
        $this->obrigatorio = $obrigatorio;
        $this->regras      = $regras;
    }

    // Cada subclasse gera o HTML do seu próprio input (text, select, file, etc.)
    abstract public function renderizar(): string;

    // Validação base: verifica obrigatoriedade. Subclasses chamam parent::validar()
    // e adicionam suas próprias regras (tamanho, formato, intervalo numérico, etc.)
    public function validar(mixed $valor): bool
    {
        $this->erros = [];

        if ($this->obrigatorio && ($valor === null || $valor === '')) {
            $this->erros[] = "{$this->rotulo} é obrigatório.";
            return false;
        }
        return true;
    }

    public function getNome(): string          { return $this->nome; }
    public function getRotulo(): string        { return $this->rotulo; }
    public function getErros(): array          { return $this->erros; }
    public function isObrigatorio(): bool      { return $this->obrigatorio; }
    public function setValor(mixed $valor): void { $this->valor = $valor; }
    public function getValor(): mixed            { return $this->valor; }

    protected function renderizarRotulo(): string
    {
        $req = $this->obrigatorio ? ' <span class="text-danger">*</span>' : '';
        return "<label class=\"form-label\" for=\"{$this->nome}\">{$this->rotulo}{$req}</label>";
    }
}
