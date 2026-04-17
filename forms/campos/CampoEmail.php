<?php

class CampoEmail extends CampoTexto
{
    public function renderizar(): string
    {
        $val = htmlspecialchars((string)($this->valor ?? ''));
        $req = $this->obrigatorio ? ' required' : '';

        return $this->renderizarRotulo()
            . "<input type=\"email\" class=\"form-control\" id=\"{$this->nome}\""
            . " name=\"{$this->nome}\" value=\"{$val}\"{$req}>";
    }

    public function validar(mixed $valor): bool
    {
        if (!parent::validar($valor)) return false;

        if ($valor !== '' && $valor !== null && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $this->erros[] = "{$this->rotulo} deve ser um e-mail válido.";
            return false;
        }
        return true;
    }
}
