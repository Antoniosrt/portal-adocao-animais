<?php

class CampoData extends Campo
{
    public function renderizar(): string
    {
        $val = htmlspecialchars((string)($this->valor ?? ''));
        $req = $this->obrigatorio ? ' required' : '';

        return $this->renderizarRotulo()
            . "<input type=\"date\" class=\"form-control\" id=\"{$this->nome}\""
            . " name=\"{$this->nome}\" value=\"{$val}\"{$req}>";
    }

    public function validar(mixed $valor): bool
    {
        if (!parent::validar($valor)) return false;

        if ($valor !== '' && $valor !== null) {
            $dt = \DateTime::createFromFormat('Y-m-d', (string)$valor);
            if (!$dt || $dt->format('Y-m-d') !== $valor) {
                $this->erros[] = "{$this->rotulo} deve ser uma data válida (AAAA-MM-DD).";
                return false;
            }
        }
        return true;
    }
}
