<?php

class CampoSelect extends Campo
{
    private array $opcoes;

    public function __construct(
        string $nome,
        string $rotulo,
        array  $opcoes,
        bool   $obrigatorio = false
    ) {
        parent::__construct($nome, $rotulo, $obrigatorio);
        $this->opcoes = $opcoes;
    }

    public function renderizar(): string
    {
        $req  = $this->obrigatorio ? ' required' : '';
        $html = $this->renderizarRotulo()
              . "<select class=\"form-select\" id=\"{$this->nome}\" name=\"{$this->nome}\"{$req}>"
              . '<option value="">— Selecione —</option>';

        foreach ($this->opcoes as $val => $label) {
            $sel   = ((string)$this->valor === (string)$val) ? ' selected' : '';
            $vEsc  = htmlspecialchars((string)$val);
            $lEsc  = htmlspecialchars((string)$label);
            $html .= "<option value=\"{$vEsc}\"{$sel}>{$lEsc}</option>";
        }

        return $html . '</select>';
    }

    public function validar(mixed $valor): bool
    {
        if (!parent::validar($valor)) return false;

        if ($valor !== '' && $valor !== null && !array_key_exists($valor, $this->opcoes)) {
            $this->erros[] = "{$this->rotulo}: opção inválida selecionada.";
            return false;
        }
        return true;
    }
}
