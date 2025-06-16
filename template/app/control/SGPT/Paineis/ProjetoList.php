<?php

use Adianti\Widget\Base\TElement;

class ProjetoList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;

    use Adianti\Base\AdiantiStandardListTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setDatabase('SGPT_DB');                // Banco de dados
        $this->setActiveRecord('ProjetoTeste');            // Active Record
        $this->setDefaultOrder('id_projeto', 'asc');  // Ordenação padrão

        // Criação da datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';

        // Campos de filtro
        $nm_projeto = new TEntry('nm_projeto');
        $nu_versao  = new TEntry('nu_versao');
        $dt_criacao = new TDate('dt_criacao');

        $nm_projeto->setProperty('placeholder', 'Filtrar por Nome');
        $nu_versao->setProperty('placeholder', 'Filtrar por Versão');
        $dt_criacao->setMask('dd/mm/yyyy');

        $nm_projeto->exitOnEnter();
        $nu_versao->exitOnEnter();
        $dt_criacao->exitOnEnter();

        $nm_projeto->setSize('100%');
        $nu_versao->setSize('100%');
        $dt_criacao->setSize('100%');

        $nm_projeto->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));
        $nu_versao->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));
        $dt_criacao->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));

        // Colunas da datagrid
        $column_nm_projeto = new TDataGridColumn('nm_projeto', 'Nome', 'left');
        $column_nu_versao  = new TDataGridColumn('nu_versao', 'Versão', 'center');
        $column_dt_criacao = new TDataGridColumn('dt_criacao', 'Criado em', 'center');

        $column_dt_criacao->setTransformer(function($value) {
            return FormatarCampos::formatarData($value);
        });

        // Ordenação nas colunas
        $column_nm_projeto->setAction(new TAction([$this, 'onReload']), ['order' => 'nm_projeto']);
        $column_nu_versao->setAction(new TAction([$this, 'onReload']), ['order' => 'nu_versao']);
        $column_dt_criacao->setAction(new TAction([$this, 'onReload']), ['order' => 'dt_criacao']);

        // Adiciona colunas
        $this->datagrid->addColumn($column_nm_projeto);
        $this->datagrid->addColumn($column_nu_versao);
        $this->datagrid->addColumn($column_dt_criacao);

        // Ações da datagrid
        $actionEdit = new TDataGridAction(['ProjetoForm', 'onEdit'], ['id_projeto' => '{id_projeto}']);
        $actionDelete = new TDataGridAction([$this, 'onConfirmDelete'], ['id_projeto' => '{id_projeto}']);

        $this->datagrid->addAction($actionEdit, 'Editar', 'far:edit blue');
        $this->datagrid->addAction($actionDelete, 'Excluir', 'far:trash-alt red');

        // Cria o modelo da datagrid
        $this->datagrid->createModel();

        // Linha de filtros no cabeçalho
        $tr = new TElement('tr');
        $tr->add(TElement::tag('td', ''));
        $tr->add(TElement::tag('td', ''));
        $tr->add(TElement::tag('td', $nm_projeto));
        $tr->add(TElement::tag('td', $nu_versao));
        $tr->add(TElement::tag('td', $dt_criacao));
        $this->datagrid->prependRow($tr);

        // Filtros
        $this->addFilterField('nm_projeto', 'ilike', 'nm_projeto');
        $this->addFilterField('nu_versao', 'ilike', 'nu_versao');
        $this->addFilterField('dt_criacao', '=', 'dt_criacao');

        $column_dt_criacao->setTransformer(function($value) {
        return date('d/m/Y H:i', strtotime($value));
        });


        // Formulário
        $this->form = new TForm('ProjetoListForm');
        $this->form->add($this->datagrid);
        $this->form->style = 'overflow-x:auto';
        $this->form->addField($nm_projeto);
        $this->form->addField($nu_versao);
        $this->form->addField($dt_criacao);

        $this->form->setData(TSession::getValue(__CLASS__ . '_filter_data'));

        // Paginação
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->enableCounters();

        // Painel
        $panel = new TPanelGroup('Listagem de Projetos');
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        $panel->addHeaderActionLink('Novo Projeto', new TAction(['ProjetoForm', 'onShow'], ['register_state' => 'false']), 'fa:plus green');

        // Container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);

        parent::add($container);
    }

    public static function onConfirmDelete($param)
    {
        $action = new TAction([__CLASS__, 'onDelete']);
        $action->setParameters(['key' => $param['id_projeto']]);

        new TQuestion('Deseja realmente excluir este projeto?', $action);
    }

    public static function onDelete($param)
    {
        try {
            $key = $param['key'];
            TTransaction::open('SGPT_DB');

            $projeto = new Projeto($key);
            $projeto->delete();

            TTransaction::close();

            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', 'Projeto excluído com sucesso', $pos_action);
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onReload($param = null)
    {
        try {
            TTransaction::open('SGPT_DB');

            $criteria = new TCriteria;
            $criteria->setProperties($param);
            $criteria->setProperty('order', 'id_projeto');
            $criteria->setProperty('direction', 'asc');
            $criteria->setProperty('limit', 20);

            $repository = new TRepository('ProjetoTeste');
            $objects = $repository->load($criteria, false);

            $this->datagrid->clear();
            if ($objects) {
                foreach ($objects as $object) {
                    $this->datagrid->addItem($object);
                }
            }

            $criteria_count = clone $criteria;
            $criteria_count->setProperty('order', null);
            $criteria_count->setProperty('direction', null);
            $criteria_count->setProperty('limit', null);

            $total = $repository->count($criteria_count);

            $this->pageNavigation->setCount($total);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($criteria->getProperty('limit'));

            TTransaction::close();
            $this->loaded = true;
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}

