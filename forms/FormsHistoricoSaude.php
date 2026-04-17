<?php

class FormsHistoricoSaude extends FormGenerico
{
    public function definirCampos(): void
    {
        $this->adicionarCampo(new CampoData('data_evento', 'Data do Evento', true));
        $this->adicionarCampo(new CampoSelect('tipo', 'Tipo', [
            'vacina'    => 'Vacina',
            'consulta'  => 'Consulta',
            'cirurgia'  => 'Cirurgia',
            'exame'     => 'Exame',
            'outro'     => 'Outro',
        ], true));
        $this->adicionarCampo(new CampoTextarea('descricao', 'Descrição', true, ['max' => 1000]));
        $this->adicionarCampo(new CampoTexto('veterinario', 'Veterinário', false, ['max' => 80]));
    }
}
