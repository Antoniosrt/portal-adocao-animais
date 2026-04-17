<?php

class CampoCheckbox extends Campo
{
    public function renderizar(): string
    {
        $checked = $this->valor ? ' checked' : '';
        return '<div class="form-check">'
            . "<input type=\"checkbox\" class=\"form-check-input\" id=\"{$this->nome}\""
            . " name=\"{$this->nome}\" value=\"1\"{$checked}>"
            . "<label class=\"form-check-label\" for=\"{$this->nome}\">{$this->rotulo}</label>"
            . '</div>';
    }

    public function validar(mixed $valor): bool
    {
        // Checkbox não tem validação obrigatória — ausência no POST significa false
        $this->erros = [];
        return true;
    }
}
