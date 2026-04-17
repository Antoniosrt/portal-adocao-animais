<?php

class FormsCadastroGato extends FormGenerico
{
    public function definirCampos(): void
    {
        $this->adicionarCampo(new CampoTexto('nome', 'Nome', true, ['min' => 2, 'max' => 80]));
        $this->adicionarCampo(new CampoNumero('idade_meses', 'Idade (meses)', true, ['min' => 0, 'max' => 300]));
        $this->adicionarCampo(new CampoTexto('raca', 'Raça', false, ['max' => 60]));
        $this->adicionarCampo(new CampoSelect('pelagem', 'Pelagem', [
            'curto'    => 'Curto',
            'longo'    => 'Longo',
            'sem_pelo' => 'Sem pelo',
        ], true));
        $this->adicionarCampo(new CampoCheckbox('castrado', 'Castrado?'));
        $this->adicionarCampo(new CampoTextarea('descricao', 'Descrição / Cuidados', false, ['max' => 2000]));
        $this->adicionarCampo(new CampoArquivo('foto', 'Foto'));
        $this->adicionarCampo(new CampoSelect('status', 'Status', [
            'disponivel' => 'Disponível',
            'adotado'    => 'Adotado',
        ], true));
    }
}
