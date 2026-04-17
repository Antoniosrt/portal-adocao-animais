<?php

class CampoNumero extends Campo
{
    public function renderizar(): string
    {
        $val = htmlspecialchars((string)($this->valor ?? ''));
        $min = isset($this->regras['min']) ? " min=\"{$this->regras['min']}\"" : '';
        $max = isset($this->regras['max']) ? " max=\"{$this->regras['max']}\"" : '';
        $req = $this->obrigatorio ? ' required' : '';

        return $this->renderizarRotulo()
            . "<input type=\"number\" class=\"form-control\" id=\"{$this->nome}\""
            . " name=\"{$this->nome}\" value=\"{$val}\"{$min}{$max}{$req}>";
    }

    public function validar(mixed $valor): bool
    {
        if (!parent::validar($valor)) return false;

        if ($valor === '' || $valor === null) return true;

        if (!is_numeric($valor)) {
            $this->erros[] = "{$this->rotulo} deve ser um número.";
            return false;
        }
        $num = (float)$valor;
        if (isset($this->regras['min']) && $num < $this->regras['min']) {
            $this->erros[] = "{$this->rotulo} deve ser no mínimo {$this->regras['min']}.";
            return false;
        }
        if (isset($this->regras['max']) && $num > $this->regras['max']) {
            $this->erros[] = "{$this->rotulo} deve ser no máximo {$this->regras['max']}.";
            return false;
        }
        return true;
    }
}
