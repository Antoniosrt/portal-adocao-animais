<?php

class CampoTexto extends Campo
{
    public function renderizar(): string
    {
        $val    = htmlspecialchars((string)($this->valor ?? ''));
        $max    = isset($this->regras['max']) ? " maxlength=\"{$this->regras['max']}\"" : '';
        $req    = $this->obrigatorio ? ' required' : '';
        $erroId = $this->nome . '_erro';

        return $this->renderizarRotulo()
            . "<input type=\"text\" class=\"form-control\" id=\"{$this->nome}\""
            . " name=\"{$this->nome}\" value=\"{$val}\"{$max}{$req}>";
    }

    public function validar(mixed $valor): bool
    {
        if (!parent::validar($valor)) return false;

        if ($valor === '' || $valor === null) return true;

        if (isset($this->regras['min']) && mb_strlen($valor) < $this->regras['min']) {
            $this->erros[] = "{$this->rotulo} deve ter ao menos {$this->regras['min']} caracteres.";
            return false;
        }
        if (isset($this->regras['max']) && mb_strlen($valor) > $this->regras['max']) {
            $this->erros[] = "{$this->rotulo} deve ter no máximo {$this->regras['max']} caracteres.";
            return false;
        }
        return true;
    }
}
