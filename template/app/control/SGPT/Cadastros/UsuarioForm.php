<?php


class UsuarioForm extends TPage
{
    private $form;

    use Adianti\Base\AdiantiStandardFormTrait;

    public function __construct($param = null)
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_Usuario');
        $this->form->setFormTitle('Cadastro de Usuário');

        // Campos
        $id_usuario   = new THidden('id_usuario');
        $nm_usuario   = new TEntry('nm_usuario');
        $ds_email     = new TEntry('ds_email');
        $ds_senha     = new TPassword('ds_senha');
        $tp_usuario   = new TCombo('tp_usuario');
        $dt_criacao   = new TDateTime('dt_criacao');

        // Configurações e placeholders
        $nm_usuario->setProperty('placeholder', 'Nome do Usuário');
        $ds_email->setProperty('placeholder', 'E-mail');
        $ds_senha->setProperty('placeholder', 'Senha');

        $nm_usuario->setSize('100%');
        $ds_email->setSize('100%');
        $ds_senha->setSize('100%');
        $tp_usuario->setSize('100%');
        $dt_criacao->setSize('100%');

        $id_usuario->setEditable(false);
        $dt_criacao->setEditable(false);

        $dt_criacao->setValue(date('d/m/Y H:i'));
        $dt_criacao->setMask('dd/mm/yyyy hh:ii');
        $dt_criacao->setDatabaseMask('yyyy-mm-dd hh:ii');

        // Tipo usuário - opções
        $tp_usuario->addItems([
            '1' => 'Administrador',
            '2' => 'Analista',
            '3' => 'Testador',
        ]);

        //$row1 = $this->form->addFields([new TLabel("Nome: (*)", '#FF0000', '14px',null), $nm_orcamentista,$id_orcamentista]);

        // Adiciona campos ao formulário
        $row1 = $this->form->addFields([new TLabel('Nome: (*)', '#FF0000'), $nm_usuario, $id_usuario],[new TLabel('Tipo: (*)', '#FF0000'), $tp_usuario]);
        $row1->layout = ['col-sm-8','col-sm-4'];

        $row2 = $this->form->addFields([new TLabel('Email: (*)', '#FF0000'), $ds_email], [new TLabel('Criado em:', '#FF0000'), $dt_criacao]);
        $row2->layout = ['col-sm-8', 'col-sm-4'];

        $row3 = $this->form->addFields([new TLabel('Senha: (*)', '#FF0000'), $ds_senha]);
        $row3->layout = ['col-sm-12'];

        // Validações
        $nm_usuario->addValidation('Nome', new TRequiredValidator);

        $ds_email->addValidation('E-mail', new TRequiredValidator);
        $ds_email->addValidation('E-mail', new TEmailValidator);

        $ds_senha->addValidation('Senha', new TRequiredValidator);

        $tp_usuario->addValidation('Tipo', new TRequiredValidator);

        // Botões
        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'far:save green');
        $this->form->addAction('Limpar Campos', new TAction([$this, 'onClear']), 'fa:eraser');
        $this->form->addActionLink('Voltar', new TAction(['UsuarioList', 'onReload']), 'fa:arrow-left blue');

        parent::add($this->form);
    }



    public function onSave($param)
    {
        try {
            TTransaction::open('SGPT_DB');

            $this->form->validate();

            $data = $this->form->getData();

            // Se for cadastro novo, seta data de criação
            if (empty($data->id_usuario)) {
                $data->dt_criacao = date('Y-m-d H:i:s');
            }

            $usuario = new Usuario();
            $usuario->fromArray((array) $data);

            // Se quiser, aqui pode colocar hash da senha antes de salvar
            $usuario->ds_senha = password_hash($data->ds_senha, PASSWORD_DEFAULT);

            $usuario->store();

            $data->id_usuario = $usuario->id_usuario;
            $this->form->setData($data);

            TTransaction::close();

            new TMessage('info', 'Usuário salvo com sucesso!');
            TApplication::loadPage('UsuarioList', 'onReload', []);
        } catch (Exception $e) {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
    public function onShow($param = null)
    {
        try
        {
            if (isset($param['id_usuario']) && $param['id_usuario'])
            {
                TTransaction::open('SGPT_DB');

                $usuario = new Usuario($param['id_usuario']);

                $usuario->ds_senha = '';

                $this->form->setData($usuario);

                TTransaction::close();
            }
            else
            {
                $this->onClear($param);
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }


    public function onEdit($param)
    {
        try {
            if (isset($param['id_usuario'])) {
                TTransaction::open('SGPT_DB');

                $usuario = new Usuario($param['id_usuario']);

                // Não preencher senha no formulário
                $usuario->ds_senha = '';

                $this->form->setData($usuario);

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
