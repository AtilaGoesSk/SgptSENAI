<?php

class EquipeList extends TStandardList
{
    use Adianti\Base\AdiantiStandardListTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setDatabase('sgpt_db');
        $this->setActiveRecord('Equipe');
        $this->setDefaultOrder('id_equipe', 'asc');
        $this->addFilterField('nm_equipe', 'like');

        $this->addColumns([
            new TDataGridColumn('id_equipe', 'ID', 'center'),
            new TDataGridColumn('nm_equipe', 'Nome da Equipe', 'left'),
            new TDataGridColumn('dt_criacao', 'Criado em', 'center'),
        ]);

        $this->addAction(new TDataGridAction(['EquipeForm', 'onEdit'], ['id_equipe' => '{id_equipe}']), 'Editar', 'far:edit');
        $this->addDeleteAction('Excluir', 'id_equipe', 'far:trash-alt');

        parent::addDatagrid($this->datagrid);
        parent::createSearchForm();
    }
}
