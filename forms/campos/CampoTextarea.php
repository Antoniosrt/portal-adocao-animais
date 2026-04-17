<?php

class CampoTextarea extends Campo
{
    private int $linhas;

    public function __construct(
        string $nome,
        string $rotulo,
        bool   $obrigatorio = false,
        array  $regras      = [],
        int    $linhas      = 4
    ) {
        parent::__construct($nome, $rotulo, $obrigatorio, $regras);
        $this->linhas = $linhas;
    }

    public function renderizar(): string
    {
        $val = htmlspecialchars((string)($this->valor ?? ''));
        $req = $this->obrigatorio ? ' required' : '';

        return $this->renderizarRotulo()
            . "<textarea class=\"form-control\" id=\"{$this->nome}\""
            . " name=\"{$this->nome}\" rows=\"{$this->linhas}\"{$req}>{$val}</textarea>";
    }

    public function validar(mixed $valor): bool
    {
        if (!parent::validar($valor)) return false;

        if (isset($this->regras['max']) && mb_strlen((string)$valor) > $this->regras['max']) {
            $this->erros[] = "{$this->rotulo} deve ter no máximo {$this->regras['max']} caracteres.";
            return false;
        }
        return true;
    }
}
