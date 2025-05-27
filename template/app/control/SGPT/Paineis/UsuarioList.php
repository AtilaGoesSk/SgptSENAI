<?php

use Adianti\Widget\Base\TElement;

class UsuarioList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;

    use Adianti\Base\AdiantiStandardListTrait;

    public function __construct()
    {
        parent::__construct();

        // Configurações principais
        $this->setDatabase('SGPT_DB');                // Conexão
        $this->setActiveRecord('Usuario');            // Active Record
        $this->setDefaultOrder('id_usuario', 'asc');  // Ordenação padrão

        // Criação da datagrid com bootstrap wrapper (mais bonito)
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';

        // Campos para filtros
        $id_usuario   = new TEntry('id_usuario');
        $nm_usuario   = new TEntry('nm_usuario');
        $ds_email     = new TEntry('ds_email');
        $dt_criacao   = new TDate('dt_criacao');

        // Configurações básicas dos filtros
        $nm_usuario->setProperty('placeholder', 'Filtrar por Nome');
        $id_usuario->setProperty('placeholder', 'Filtrar por ID');
        $ds_email->setProperty('placeholder', 'Filtrar por E-mail');
        $dt_criacao->setMask('dd/mm/yyyy');

        // Exitar/enter para disparar o filtro
        $id_usuario->exitOnEnter();
        $nm_usuario->exitOnEnter();
        $ds_email->exitOnEnter();

        $id_usuario->setSize('100%');
        $nm_usuario->setSize('100%');
        $ds_email->setSize('100%');
        $dt_criacao->setSize('100%');
        

        // Setar ação de filtro ao sair
        $id_usuario->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));
        $nm_usuario->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));
        $ds_email->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));
        $dt_criacao->setExitAction(new TAction([$this, 'onSearch'], ['static' => 1]));

        // Colunas da datagrid
        $column_id_usuario = new TDataGridColumn('id_usuario', 'ID', 'center', 50);
        $column_nm_usuario = new TDataGridColumn('nm_usuario', 'Nome', 'left', 200);
        $column_ds_email   = new TDataGridColumn('ds_email', 'E-mail', 'left', 200);
        $column_dt_criacao = new TDataGridColumn('dt_criacao', 'Criado em', 'center', 100);

        // Permitir ordenar colunas
        $column_id_usuario->setAction(new TAction([$this, 'onReload']), ['order' => 'id_usuario']);
        $column_nm_usuario->setAction(new TAction([$this, 'onReload']), ['order' => 'nm_usuario']);
        $column_ds_email->setAction(new TAction([$this, 'onReload']), ['order' => 'ds_email']);
        $column_dt_criacao->setAction(new TAction([$this, 'onReload']), ['order' => 'dt_criacao']);

        // Adiciona as colunas na datagrid
        $this->datagrid->addColumn($column_id_usuario);
        $this->datagrid->addColumn($column_nm_usuario);
        $this->datagrid->addColumn($column_ds_email);
        $this->datagrid->addColumn($column_dt_criacao);

        // Ações da datagrid: Editar e Excluir com confirmação
        $actionEdit = new TDataGridAction(['UsuarioForm', 'onEdit'], ['id_usuario' => '{id_usuario}']);
        $actionDelete = new TDataGridAction([$this, 'onConfirmDelete'], ['id_usuario' => '{id_usuario}']);

        $this->datagrid->addAction($actionEdit, 'Editar', 'far:edit blue');
        $this->datagrid->addAction($actionDelete, 'Excluir', 'far:trash-alt red');

        // Cria modelo da datagrid
        $this->datagrid->createModel();

        // Linha de filtros no cabeçalho da datagrid (após createModel)
        $tr = new TElement('tr');
        $tr->add(TElement::tag('td',''));
        $tr->add(TElement::tag('td',''));
        $tr->add(TElement::tag('td', $id_usuario));
        $tr->add(TElement::tag('td', $nm_usuario));
        $tr->add(TElement::tag('td', $ds_email));
        $tr->add(TElement::tag('td', $dt_criacao));
        $this->datagrid->prependRow($tr);

        // Adiciona filtros para pesquisa
        $this->addFilterField('id_usuario', '=', 'id_usuario');
        $this->addFilterField('nm_usuario', 'ilike', 'nm_usuario');
        $this->addFilterField('ds_email', 'ilike', 'ds_email');
        $this->addFilterField('dt_criacao', '=', 'dt_criacao');

        // Formulário para conter os filtros
        $this->form = new TForm('UsuarioListForm');
        $this->form->add($this->datagrid);
        $this->form->style = 'overflow-x:auto';

        // Registra campos no formulário
        $this->form->addField($id_usuario);
        $this->form->addField($nm_usuario);
        $this->form->addField($ds_email);
        $this->form->addField($dt_criacao);

        // Ajusta tamanho dos filtros
        $id_usuario->setSize('100%');
        $nm_usuario->setSize('100%');
        $ds_email->setSize('100%');
        $dt_criacao->setSize('100%');

        // Recupera filtros da sessão (persistência)
        $this->form->setData(TSession::getValue(__CLASS__.'_filter_data'));

        // Paginação
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->enableCounters();

        // Painel principal
        $panel = new TPanelGroup('Listagem de Usuários');
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        $panel->addHeaderActionLink('Criar', new TAction(['UsuarioForm', 'onShow'], ['register_state' => 'false']), 'fa:plus green');

        // Container com breadcrumb e painel
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);

        parent::add($container);
    }

    /**
     * Confirmação de exclusão
     */
    public static function onConfirmDelete($param)
    {
        $action = new TAction([__CLASS__, 'onDelete']);
        $action->setParameters(['key' => $param['id_usuario']]);

        new TQuestion('Você tem certeza que deseja excluir este usuário?', $action);
    }
    /**
     * Excluir registro
     */
    public static function onDelete($param)
    {
        try
        {
            $key = $param['key'];
            TTransaction::open('SGPT_DB');

            $usuario = new Usuario($key);
            $usuario->delete();

            TTransaction::close();

            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', 'Registro excluído com sucesso', $pos_action);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * Método onReload para carregar os dados com filtros, paginação e ordenação
     */
   public function onReload($param = null)
{
    try
    {
        TTransaction::open('SGPT_DB');
        
        $criteria = new TCriteria;
        $criteria->setProperty('order', 'id_usuario');
        $criteria->setProperty('direction', 'asc');
        $criteria->setProperty('limit', 20);

        $repository = new TRepository('Usuario');
        $objects = $repository->load($criteria, false);

        $this->datagrid->clear();
        if ($objects)
        {
            foreach ($objects as $object)
            {
                $this->datagrid->addItem($object);
            }
        }

        // Clona criteria para count e remove order/limit
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
    }
    catch (Exception $e)
    {
        new TMessage('error', $e->getMessage());
        TTransaction::rollback();
    }
}


}
