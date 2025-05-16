<?php
class PlanoTesteList extends TStandardList
{
    use Adianti\Base\AdiantiStandardListTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setDatabase('sgpt_db');
        $this->setActiveRecord('PlanoTeste');
        $this->setDefaultOrder('id_plano_teste', 'asc');
        $this->addFilterField('nm_titulo', 'like');

        $this->addColumns([
            new TDataGridColumn('id_plano_teste', 'ID', 'center'),
            new TDataGridColumn('nm_titulo', 'Título', 'left'),
            new TDataGridColumn('ds_plano', 'Descrição', 'left'),
            new TDataGridColumn('dt_criacao', 'Criado em', 'center'),
        ]);

        $this->addAction(new TDataGridAction(['PlanoTesteForm', 'onEdit'], ['id_plano_teste' => '{id_plano_teste}']), 'Editar', 'far:edit');
        $this->addDeleteAction('Excluir', 'id_plano_teste', 'far:trash-alt');

        parent::addDatagrid($this->datagrid);
        parent::createSearchForm();
    }
}
