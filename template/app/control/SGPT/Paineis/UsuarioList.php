<?php

class UsuarioList extends TStandardList
{
    use Adianti\Base\AdiantiStandardListTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setDatabase('sgpt_db');
        $this->setActiveRecord('Usuario');
        $this->setDefaultOrder('id_usuario', 'asc');
        $this->addFilterField('nm_usuario', 'like');
        $this->addFilterField('ds_email', 'like');

        $this->setLimit(10);

        $this->addColumns([
            new TDataGridColumn('id_usuario', 'ID', 'center'),
            new TDataGridColumn('nm_usuario', 'Nome', 'left'),
            new TDataGridColumn('ds_email', 'Email', 'left'),
            new TDataGridColumn('tp_usuario', 'Tipo', 'left'),
            new TDataGridColumn('dt_criacao', 'Criado em', 'center'),
        ]);

        $this->addAction(new TDataGridAction(['UsuarioForm', 'onEdit'], ['id_usuario' => '{id_usuario}']), 'Editar', 'far:edit');
        $this->addDeleteAction('Excluir', 'id_usuario', 'far:trash-alt');

        parent::addDatagrid($this->datagrid);
        parent::addFilterField('nm_usuario', 'like');
        parent::createSearchForm();
    }
}
