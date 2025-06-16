<?php

class PlanoTesteForm extends TPage
{
    protected $form; // form
    
    function __construct()
    {
        parent::__construct();
        $this->setTargetContainer('adianti_right_panel');
  
        // creates the form
        $this->form = new BootstrapFormBuilder('form_PlanoTesteForm');
        $this->form->setFormTitle('Cadastro de Plano de Teste');
        $this->form->setProperty('style', 'margin:0;border:0');
        
        // mestre fields
        $id_plano_teste = new THidden('id_plano_teste');
        $nm_plano    = new TEntry('nm_plano');
        $dt_criacao  = new TDate('dt_criacao');
        $dt_final    = new TDate('dt_final');
        $dt_inicio   = new TDate('dt_inicio');
        $nm_projeto  = new TDBUniqueSearch('id_projeto', 'SGPT_DB', 'ProjetoTeste', 'id_projeto', 'nm_projeto');
        $ds_plano    = new TText('ds_plano');
        
        // detalhe fields
        $caso_teste_uniqid      = new THidden('caso_teste_uniqid');
        $id_caso_teste_detalhe  = new THidden('id_caso_teste');
        $nm_caso_teste          = new TEntry('nm_caso_teste');
        $ds_caso_teste          = new TText('ds_caso_teste');
        $tp_categoria           = new TCombo('tp_categoria');
        $tp_status              = new TCombo('tp_status', );
        $ds_resultado_esperado  = new TText('ds_resultado_esperado');
        $dt_criacao_detalhe     = new TDate('dt_criacao_detalhe');

        $tp_categoria->addItems(Enum::TP_CATEGORIA);
        $tp_status->addItems(Enum::TP_STATUS);

        $id_caso_teste_detalhe->setSize('100%');
        $caso_teste_uniqid->setSize('100%');
        $tp_status->setValue(1); // set default value
        $tp_status->setDefaultOption(false);
        $tp_categoria->setProperty('placeholder', 'Selecione a Categoria');
        $tp_status->setProperty('placeholder', 'Selecione o Status');
        $nm_projeto->setSize('100%');
        $nm_projeto->setMinLength(1);
        $dt_criacao->setSize('100%');
        $dt_criacao->setMask('dd/mm/yyyy');
        $dt_criacao->setDatabaseMask('yyyy-mm-dd');
        $dt_criacao->setEditable(false);
        $dt_criacao->setValue(date('d/m/Y'));
        $dt_criacao_detalhe->setSize('100%');
        $dt_criacao_detalhe->setMask('dd/mm/yyyy');
        $dt_criacao_detalhe->setDatabaseMask('yyyy-mm-dd');
        $dt_criacao_detalhe->setEditable(false);
        $dt_criacao_detalhe->setValue(date('d/m/Y'));
        $dt_final->setSize('100%');
        $dt_final->setMask('dd/mm/yyyy');
        $dt_final->setDatabaseMask('yyyy-mm-dd');
        $dt_inicio->setSize('100%');
        $dt_inicio->setMask('dd/mm/yyyy');
        $dt_inicio->setDatabaseMask('yyyy-mm-dd');
        $nm_plano->setSize('100%');
        $nm_plano->setProperty('placeholder', 'Ex.: Plano de Teste 1.0');
        $ds_plano->setProperty('placeholder', 'Descrição do Plano de Teste');
        $ds_plano->setSize('100%', 80);
        $nm_caso_teste->setSize('100%');
        $ds_caso_teste->setSize('100%');
        $ds_caso_teste->setProperty('placeholder', 'Descreva o caso de teste');
        $ds_caso_teste->maxLength = 500;
        $ds_caso_teste->setSize('100%', 80);
        $tp_categoria->setSize('100%');
        $ds_resultado_esperado->setSize('100%', 80);
        $ds_resultado_esperado->setProperty('placeholder', 'Descreva o resultado esperado do caso de teste');
        $ds_resultado_esperado->setProperty('placeholder', 'Descreva o resultado esperado do caso de teste');
        $nm_caso_teste->setProperty('placeholder', 'Ex.: Caso de Teste 1.0');
        $nm_caso_teste->maxLength = 100;
        $tp_status->setSize('100%');
        
        // add validations
        $nm_plano->addValidation('Nome do plano', new TRequiredValidator);
        $dt_criacao->addValidation('Data de criação', new TRequiredValidator);
        $dt_final->addValidation('Data final', new TRequiredValidator);
        $dt_inicio->addValidation('Data de inicio', new TRequiredValidator);
        $nm_projeto->addValidation('Nome do projeto', new TRequiredValidator);
        $ds_plano->addValidation('Descrição do plano', new TRequiredValidator);
        
        // add master form fields
        $row1 = $this->form->addFields([new TLabel('Nome do plano: (*)', '#FF0000', '14px', null, '100%'), $id_plano_teste, $nm_plano],
                                       [new TLabel('Nome do projeto: (*)', '#FF0000', '14px', null, '100%'), $nm_projeto]);
        $row1->layout = [' col-sm-6', ' col-sm-6'];

        $row2 = $this->form->addFields( [new TLabel('Data de criação: (*)', '#FF0000', '14px', null, '100%'), $dt_criacao],  
                                        [new TLabel('Data de inicio: (*)', '#FF0000', '14px', null, '100%'), $dt_inicio],
                                        [new TLabel('Data final: (*)', '#FF0000', '14px', null, '100%'), $dt_final]);
        $row2->layout = [' col-sm-4', ' col-sm-4', ' col-sm-4'];

        $row3 = $this->form->addFields( [new TLabel('Descrição do plano: (*)', '#FF0000', '14px', null, '100%'), $ds_plano]);
        $row3->layout = [' col-sm-12'];
        
        // add detail form fields
        $this->form->addContent( ['<h4>Casos De Teste</h4><hr>'] );
        $this->form->addFields(  [ $caso_teste_uniqid, $id_caso_teste_detalhe] );

        $row4 = $this->form->addFields( [ new TLabel('Nome do caso: (*)', '#FF0000', '14px', null, '100%'), $nm_caso_teste]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addFields( [ new TLabel('Categoria: (*)', '#FF0000', '14px', null, '100%'), $tp_categoria],
                                        [ new TLabel('Status: (*)', '#FF0000', '14px', null, '100%'), $tp_status],
                                        [ new TLabel('Data de criação: (*)', '#FF0000', '14px', null, '100%'), $dt_criacao_detalhe]);
        $row5->layout = [' col-sm-4', ' col-sm-4', ' col-sm-4'];

        $row6 = $this->form->addFields( [ new TLabel('Descrição: (*)', '#FF0000', '14px', null, '100%'), $ds_caso_teste],
                                        [ new TLabel('Resultado esperado: (*)', '#FF0000', '14px', null, '100%'), $ds_resultado_esperado]);
        $row6->layout = [' col-sm-6', ' col-sm-6'];

        $add_casos = TButton::create('add_casos', [$this, 'onAddCasos'], 'Adicionar', 'fa:plus-circle green');
        $add_casos->getAction()->setParameter('static','1');
        $this->form->addFields([$add_casos]);
        
        $this->caso_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->caso_list->setHeight(150);
        $this->caso_list->setId('casos_list');
        $this->caso_list->generateHiddenFields();
        $this->caso_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";
        
        $col_uniq                  = new TDataGridColumn( 'caso_teste_uniqid', '', 'center');
        $col_id_caso_teste         = new TDataGridColumn( 'id_caso_teste', '', 'center');
        $col_nm_caso_teste         = new TDataGridColumn( 'nm_caso_teste', 'Nome do caso', 'center');
        $col_ds_caso_teste         = new TDataGridColumn( 'ds_caso_teste', 'Descrição do caso', 'left');
        $col_ds_resultado_esperado = new TDataGridColumn( 'ds_resultado_esperado', 'Resultado esperado', 'left');
        $col_tp_categoria          = new TDataGridColumn( 'tp_categoria', 'Categoria', 'center');
        $col_tp_status             = new TDataGridColumn( 'tp_status', 'Status', 'center');
        $col_dt_criacao_detalhe    = new TDataGridColumn( 'dt_criacao_detalhe', 'Data de criação', 'center');
        
        $this->caso_list->addColumn( $col_uniq );
        $this->caso_list->addColumn( $col_id_caso_teste );
        $this->caso_list->addColumn( $col_nm_caso_teste );
        $this->caso_list->addColumn( $col_ds_caso_teste );
        $this->caso_list->addColumn( $col_ds_resultado_esperado );
        $this->caso_list->addColumn( $col_tp_categoria );
        $this->caso_list->addColumn( $col_tp_status );
        $this->caso_list->addColumn( $col_dt_criacao_detalhe );
        
        $col_uniq->setVisibility(false);
        $col_id_caso_teste->setVisibility(false);
        
        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onEditCasos'] );
        $action1->setFields( ['uniqid', '*'] );
        
        $action2 = new TDataGridAction([$this, 'onDeleteCasos']);
        $action2->setField('uniqid');
        
        // add the actions to the datagrid
        $this->caso_list->addAction($action1, _t('Edit'), 'far:edit blue');
        $this->caso_list->addAction($action2, _t('Delete'), 'far:trash-alt red');
        
        $this->caso_list->createModel();

        $col_tp_categoria->setTransformer(function($value) {
            return Enum::TP_CATEGORIA[$value];
        });

        $col_tp_status->setTransformer(function($value) {
            return Enum::TP_STATUS[$value];
        });

        $col_dt_criacao_detalhe->setTransformer(function($value) {
            if(!empty($value)) {
                return date('d/m/Y', strtotime($value));
            }
        });
        
        $panel = new TPanelGroup;
        $panel->add($this->caso_list);
        $panel->getBody()->style = 'overflow-x:auto';
        $this->form->addContent( [$panel] );
        
        $this->form->addHeaderActionLink( _t('Close'),  new TAction([__CLASS__, 'onClose'], ['static'=>'1']), 'fa:times red');
        $this->form->addAction( _t('Save'),  new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save green');
        $this->form->addAction( _t('Clear'), new TAction([$this, 'onClear']), 'fa:eraser red');
        
        // create the page container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        parent::add($container);
    }
    
    function onClear($param)
    {
        $this->form->clear();
    }
    
    public function onAddCasos( $param )
    {
        try
        {
            $data = $this->form->getData();
            
            if( (! $data->nm_caso_teste) || (! $data->ds_caso_teste) || (! $data->tp_categoria)  
                || (! $data->tp_status) || (! $data->dt_criacao_detalhe) || (! $data->ds_resultado_esperado))
            {
                throw new Exception('Preencha todos os campos obrigatórios do caso de teste.');
            }
            
            $uniqid = !empty($data->caso_teste_uniqid) ? $data->caso_teste_uniqid : uniqid();
            
            $grid_data = ['uniqid'                => $uniqid,
                          'id_caso_teste'         => $data->id_caso_teste,
                          'nm_caso_teste'         => $data->nm_caso_teste,
                          'ds_caso_teste'         => $data->ds_caso_teste,
                          'tp_categoria'          => $data->tp_categoria,
                          'tp_status'             => $data->tp_status,
                          'ds_resultado_esperado' => $data->ds_resultado_esperado,
                          'dt_criacao_detalhe'    => $data->dt_criacao_detalhe];
            
            // insert row dynamically
            $row = $this->caso_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('casos_list', $uniqid, $row);
            
            // clear product form fields after add
            $data->caso_teste_uniqid     = '';
            $data->id_caso_teste         = '';
            $data->nm_caso_teste         = '';
            $data->ds_caso_teste         = '';
            $data->tp_categoria          = '';
            $data->tp_status             = '1';
            $data->ds_resultado_esperado = '';
            $data->dt_criacao_detalhe    = date('d/m/Y');

            TCombo::disableField('form_PlanoTesteForm', 'tp_status');
            
            // send data, do not fire change/exit events
            TForm::sendData( 'form_PlanoTesteForm', $data, false, false );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }
    
    public static function onEditCasos( $param )
    {
        $dt_criacao_detalhe = $param['dt_criacao_detalhe'] != null ? date('d/m/Y', strtotime($dt_criacao_detalhe)) : date('d/m/Y');

        $data = new stdClass;
        $data->caso_teste_uniqid     = $param['uniqid'];
        $data->id_caso_teste         = $param['id_caso_teste'];
        $data->nm_caso_teste         = $param['nm_caso_teste'] ?? null;
        $data->ds_caso_teste         = $param['ds_caso_teste'] ?? null;
        $data->tp_categoria          = $param['tp_categoria'] ?? null;
        $data->tp_status             = $param['tp_status'] ?? null;
        $data->ds_resultado_esperado = $param['ds_resultado_esperado'] ?? null;
        $data->dt_criacao_detalhe    = $dt_criacao_detalhe;

        TCombo::enableField('form_PlanoTesteForm', 'tp_status');
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_PlanoTesteForm', $data, false, false );
    }
    
    public static function onDeleteCasos( $param )
    {
        $data = new stdClass;
        $data->caso_teste_uniqid     = '';
        $data->id_caso_teste         = '';
        $data->nm_caso_teste         = '';
        $data->ds_caso_teste         = '';
        $data->tp_categoria          = '';
        $data->tp_status             = '1';
        $data->ds_resultado_esperado = '';
        $data->dt_criacao_detalhe    = date('d/m/Y');
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_PlanoTesteForm', $data, false, false );
        
        // remove row
        TDataGrid::removeRowById('casos_list', $param['uniqid']);

        TCombo::disableField('form_PlanoTesteForm', 'tp_status');
    }
    
    public function onEdit($param)
    {
        try
        {
            TTransaction::open('SGPT_DB');
            
            if (isset($param['key']))
            {
                $key = $param['key'];
                
                $object = new PlanoTeste($key);
                $casoTeste = CasoTeste::where('id_plano_teste', '=', $object->id_plano_teste)->load();
                
                foreach( $casoTeste as $item )
                {
                    $item->uniqid = uniqid();
                    $row = $this->caso_list->addItem( $item );
                    $row->id = $item->uniqid;
                }

                $this->form->setData($object);

                TTransaction::close();

                TCombo::disableField('form_PlanoTesteForm', 'tp_status');
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function onSave($param)
    {
        try
        {
            TTransaction::open('SGPT_DB');
            
            $data = $this->form->getData();
            $this->form->validate();
            
            $object = new PlanoTeste;
            $object->fromArray((array) $data);

            $object->store();
            
            CasoTeste::where('id_plano_teste', '=', $object->id_plano_teste)->delete();

            if( !empty($param['casos_list_nm_caso_teste'] ))
            {
                $item = new CasoTeste;

                foreach( $param['casos_list_id_caso_teste'] as $key => $item_id )
                {
                    $item->id_caso_teste         = $item_id ?? uniqid();
                    $item->nm_caso_teste         = $param['casos_list_nm_caso_teste'][$key];
                    $item->tp_categoria          = $param['casos_list_tp_categoria'][$key];
                    $item->tp_status             = $param['casos_list_tp_status'][$key];
                    $item->dt_criacao            = $param['casos_list_dt_criacao_detalhe'][$key];
                    $item->ds_caso_teste         = $param['casos_list_ds_caso_teste'][$key];
                    $item->ds_resultado_esperado = $param['casos_list_ds_resultado_esperado'][$key];
                    
                    $item->id_plano_teste = $object->id_plano_teste;
                    $item->store();
                }
            }
            
            $object->store(); // stores the object
            
            TForm::sendData('form_PlanoTesteForm', (object) ['id_plano_teste' => $object->id_plano_teste]);
            
            TTransaction::close(); // close the transaction
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));

            TApplication::loadPage('PlanoTesteList', 'onReload', ['key' => $object->id_plano_teste]);
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }
    
    public static function onClose()
    {
        TScript::create("Template.closeRightPanel()");
    }

    public function onShow($param = null)
    {
        TCombo::disableField('form_PlanoTesteForm', 'tp_status');
    }
}