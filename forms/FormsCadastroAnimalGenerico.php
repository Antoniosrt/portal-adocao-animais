<?php

class FormsCadastroAnimalGenerico extends FormGenerico
{
    public function definirCampos(): void
    {
        $this->adicionarCampo(new CampoTexto('nome', 'Nome', true, ['min' => 2, 'max' => 80]));
        $this->adicionarCampo(new CampoTexto('especie_descricao', 'Espécie', true, ['min' => 2, 'max' => 100]));
        $this->adicionarCampo(new CampoNumero('idade_meses', 'Idade (meses)', true, ['min' => 0, 'max' => 600]));
        $this->adicionarCampo(new CampoTextarea('descricao', 'Descrição / Cuidados', false, ['max' => 2000]));
        $this->adicionarCampo(new CampoArquivo('foto', 'Foto'));
        $this->adicionarCampo(new CampoSelect('status', 'Status', [
            'disponivel' => 'Disponível',
            'adotado'    => 'Adotado',
        ], true));
    }
}
