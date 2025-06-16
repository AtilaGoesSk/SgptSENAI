<?php

class ProjetoForm extends TPage
{
    private $form;

    use Adianti\Base\AdiantiStandardFormTrait;

    public function __construct($param = null)
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_ProjetoForm');
        $this->form->setFormTitle('Cadastro de Projeto');

        // Campos
        $id_projeto   = new THidden('id_projeto');
        $nm_projeto   = new TEntry('nm_projeto');
        $ds_projeto   = new TText('ds_projeto');
        $nu_versao    = new TEntry('nu_versao');
        $id_usuario   = new TDBCombo('id_usuario', 'SGPT_DB', 'SystemUser', 'id', 'name');
        $dt_criacao   = new TDateTime('dt_criacao');

        // Configurações de tamanho
        $nm_projeto->setSize('100%');
        $ds_projeto->setSize('100%', 80);
        $nu_versao->setSize('100%');
        $id_usuario->setSize('100%');
        $dt_criacao->setSize('100%');

        $dt_criacao->setMask('dd/mm/yyyy hh:ii');
        $dt_criacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_criacao->setEditable(false);
        $dt_criacao->setValue(date('d/m/Y H:i'));

        // Placeholders
        $nm_projeto->setProperty('placeholder', 'Nome do Projeto');
        $nu_versao->setProperty('placeholder', 'Ex.: 1.0, 2.5');

        // Validações
        $nm_projeto->addValidation('Nome do Projeto', new TRequiredValidator);
        $nu_versao->addValidation('Versão', new TRequiredValidator);

        // Adiciona campos no formulário
        $row1 = $this->form->addFields(
            [new TLabel('Nome do Projeto: (*)', '#FF0000'), $nm_projeto, $id_projeto],
            [new TLabel('Versão: (*)', '#FF0000'), $nu_versao]
        );
        $row1->layout = ['col-sm-8', 'col-sm-4'];

        $row2 = $this->form->addFields(
            [new TLabel('Descrição:', null), $ds_projeto]
        );
        $row2->layout = ['col-sm-12'];

        $row3 = $this->form->addFields(
            [new TLabel('Usuário Responsável:', null), $id_usuario],
            [new TLabel('Criado em:', null), $dt_criacao]
        );
        $row3->layout = ['col-sm-8', 'col-sm-4'];

        $dt_criacao->setValue(date('d/m/Y H:i'));
        $dt_criacao->setEditable(false);

        // Botões
        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'far:save green');
        $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser');
        $this->form->addActionLink('Voltar', new TAction(['ProjetoList', 'onReload']), 'fa:arrow-left blue');

        parent::add($this->form);
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('SGPT_DB');

            $this->form->validate();

            $data = $this->form->getData();

            if (empty($data->id_projeto)) {
                $data->dt_criacao = date('Y-m-d H:i:s');
            }

            $projeto = new ProjetoTeste();
            $projeto->fromArray((array) $data);
            $projeto->store();

            $data->id_projeto = $projeto->id_projeto;
            $this->form->setData($data);

            TTransaction::close();

            new TMessage('info', 'Projeto salvo com sucesso!');
            TApplication::loadPage('ProjetoList', 'onReload', []);
        } catch (Exception $e) {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {
        // Limpa o formulário
        $this->form->clear(true);
        
        // Define o usuário atual como responsável
        $currentUser = TSession::getValue('user');
        if ($currentUser) {
            $this->form->setData(['id_usuario' => $currentUser->id]);
        }
    }

    public function onEdit($param)
    {
        try {
            if (isset($param['id_projeto'])) {
                TTransaction::open('SGPT_DB');

                $projeto = new ProjetoTeste($param['id_projeto']);
                $this->form->setData($projeto);

                TTransaction::close();
            }
        } catch (Exception $e) {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    public function onClear($param = null)
    {
        $this->form->clear(true);
    }
}
