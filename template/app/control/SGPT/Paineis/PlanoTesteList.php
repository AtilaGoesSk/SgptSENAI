<?php

use Adianti\Widget\Base\TElement;

class PlanoTesteList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;

    use Adianti\Base\AdiantiStandardListTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setDatabase('SGPT_DB');                // Banco de dados
        $this->setActiveRecord('PlanoTeste');            // Active Record
        $this->setDefaultOrder('id_plano_teste', 'asc');  // Ordenação padrão

        // Criação da datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';

        // Campos de filtro
        $nm_plano = new TEntry('nm_plano');
        $dt_criacao = new TDate('dt_criacao');

        $nm_plano->setProperty('placeholder', 'Filtrar por Nome');
        $dt_criacao->setMask('dd/mm/yyyy');

        $nm_plano->exitOnEnter();
        $dt_criacao->exitOnEnter();

        $nm_plano->setSize('100%');
        $dt_criacao->setSize('100%');

        $nm_plano->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));
        $dt_criacao->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));

        // Colunas da datagrid
        $column_nm_projeto = new TDataGridColumn('nm_plano', 'Nome do plano', 'left');
        $column_dt_criacao = new TDataGridColumn('dt_criacao', 'Criado em', 'center');

        $column_dt_criacao->setTransformer(function($value) {
            return FormatarCampos::formatarData($value);
        });

        // Ordenação nas colunas
        $column_nm_projeto->setAction(new TAction([$this, 'onReload']), ['order' => 'nm_plano']);
        $column_dt_criacao->setAction(new TAction([$this, 'onReload']), ['order' => 'dt_criacao']);

        // Adiciona colunas
        $this->datagrid->addColumn($column_nm_projeto);
        $this->datagrid->addColumn($column_dt_criacao);

        // Ações da datagrid
        $actionEdit = new TDataGridAction(['PlanoTesteForm', 'onEdit'], ['id_plano_teste' => '{id_plano_teste}']);
        $actionDelete = new TDataGridAction([$this, 'onConfirmDelete'], ['id_plano_teste' => '{id_plano_teste}']);

        $this->datagrid->addAction($actionEdit, 'Editar', 'far:edit blue');
        $this->datagrid->addAction($actionDelete, 'Excluir', 'far:trash-alt red');

        // Cria o modelo da datagrid
        $this->datagrid->createModel();

        // Linha de filtros no cabeçalho
        $tr = new TElement('tr');
        $tr->add(TElement::tag('td', ''));
        $tr->add(TElement::tag('td', ''));
        $tr->add(TElement::tag('td', $nm_plano));
        $tr->add(TElement::tag('td', $dt_criacao));
        $this->datagrid->prependRow($tr);

        // Filtros
        $this->addFilterField('nm_plano', 'ilike', 'nm_plano');
        $this->addFilterField('dt_criacao', '=', 'dt_criacao');

        $column_dt_criacao->setTransformer(function($value) {
        return date('d/m/Y', strtotime($value));
        });

        // Formulário
        $this->form = new TForm('PlanoTesteListForm');
        $this->form->add($this->datagrid);
        $this->form->style = 'overflow-x:auto';
        $this->form->addField($nm_plano);
        $this->form->addField($dt_criacao);

        $this->form->setData(TSession::getValue(__CLASS__ . '_filter_data'));

        // Paginação
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->enableCounters();

        // Painel
        $panel = new TPanelGroup('Listagem de Planos de Teste');
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        $panel->addHeaderActionLink('Novo Plano', new TAction(['PlanoTesteForm', 'onShow'], ['register_state' => 'false']), 'fa:plus green');

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
        $action->setParameters(['key' => $param['id_plano_teste']]);

        new TQuestion('Deseja realmente excluir este plano de teste?', $action);
    }

    public static function onDelete($param)
    {
        try {
            $key = $param['key'];
            TTransaction::open('SGPT_DB');

            $plano = new PlanoTeste($key);
            $plano->delete();

            TTransaction::close();

            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', 'Plano de teste excluído com sucesso', $pos_action);
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
            $criteria->setProperty('order', 'id_plano_teste');
            $criteria->setProperty('direction', 'asc');
            $criteria->setProperty('limit', 20);

            $repository = new TRepository('PlanoTeste');
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

