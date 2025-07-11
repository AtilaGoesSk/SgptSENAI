<?php
/**
 * SystemUserForm
 *
 * @version    8.1
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemUserForm extends TPage
{
    protected $form; // form
    protected $program_list;
    protected $unit_list;
    protected $group_list;
    protected $role_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_System_user');
        $this->form->setFormTitle( _t('User') );
        $this->form->enableClientValidation();
        
        // create the form fields
        $id            = new TEntry('id');
        $name          = new TEntry('name');
        $login         = new TEntry('login');
        $password      = new TPassword('password');
        $repassword    = new TPassword('repassword');
        $email         = new TEntry('email');
        $unit_id       = new TDBCombo('system_unit_id','permission','SystemUnit','id','name');
        $frontpage_id  = new TDBUniqueSearch('frontpage_id', 'permission', 'SystemProgram', 'id', 'name', 'name');
        $phone         = new TEntry('phone');
        $address       = new TEntry('address');
        $function_name = new TEntry('function_name');
        $about         = new TEntry('about');
        $custom_code   = new TEntry('custom_code');
        $role          = new TCombo('tp_cargo');
        
        $password->disableAutoComplete();
        $repassword->disableAutoComplete();
        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        //$this->form->addActionLink( _t('Back'), new TAction(array('SystemUserList','onReload')), 'far:arrow-alt-circle-left blue');
        $role->addItems(Enum::TP_CARGO);
        
        // define the sizes
        $id->setSize('50%');
        $name->setSize('100%');
        $login->setSize('100%');
        $password->setSize('100%');
        $repassword->setSize('100%');
        $email->setSize('100%');
        $unit_id->setSize('100%');
        $frontpage_id->setSize('100%');
        $frontpage_id->setMinLength(1);
        $role->setSize('100%');

        
        // outros
        $id->setEditable(false);
        
        // validations
        $name->addValidation(_t('Name'), new TRequiredValidator);
        $login->addValidation('Login', new TRequiredValidator);
        $email->addValidation('Email', new TEmailValidator);
        
        $this->form->addFields( [new TLabel('ID')], [$id],  [new TLabel(_t('Name'))], [$name] );
        $this->form->addFields( [new TLabel(_t('Login'))], [$login],  [new TLabel(_t('Email'))], [$email] );
        $this->form->addFields( [new TLabel(_t('Address'))], [$address],  [new TLabel(_t('Phone'))], [$phone] );
        $this->form->addFields( [new TLabel(_t('Function'))], [$function_name],  [new TLabel(_t('About'))], [$about] );
        $this->form->addFields( [new TLabel(_t('Main unit'))], [$unit_id],  [new TLabel(_t('Front page'))], [$frontpage_id] );
        $this->form->addFields( [new TLabel(_t('Password'))], [$password],  [new TLabel(_t('Password confirmation'))], [$repassword] );
        $this->form->addFields( [new TLabel(_t('Custom code'))], [$custom_code] );
        $this->form->addFields( [new TLabel(_t('Role'))], [$role] );
        
        $subform = new BootstrapFormBuilder;
        $subform->setFieldSizes('100%');
        $subform->setProperty('style', 'border:none');
        
        $subform->appendPage( _t('Groups') );
        $this->group_list = new TDBCheckList('group_list', 'permission', 'SystemGroup', 'id', 'name');
        $this->group_list->makeScrollable();
        $this->group_list->setHeight(210);
        $subform->addFields( [$this->group_list] );
        
        $subform->appendPage( _t('Units') );
        $this->unit_list = new TDBCheckList('unit_list', 'permission', 'SystemUnit', 'id', 'name');
        $this->unit_list->makeScrollable();
        $this->unit_list->setHeight(210);
        
        $subform->addFields( [$this->unit_list] );
        
        $subform->appendPage( _t('Roles') );
        $this->role_list = new TDBCheckList('role_list', 'permission', 'SystemRole', 'id', 'name');
        $this->role_list->makeScrollable();
        $this->role_list->setHeight(210);
        
        $subform->addFields( [$this->role_list] );
        
        $subform->appendPage( _t('Programs') );
        $this->program_list = new TCheckList('program_list');
        $this->program_list->setIdColumn('id');
        $this->program_list->addColumn('id',    'ID',    'center',  '10%');
        $col_name    = $this->program_list->addColumn('name', _t('Name'),    'left',   '50%');
        $col_program = $this->program_list->addColumn('controller', _t('Menu path'),    'left',   '40%');
        $col_program->enableAutoHide(500);
        $this->program_list->setHeight(150);
        $this->program_list->makeScrollable();
        
        $subform->addFields( [$this->program_list] );
        
        $this->form->addContent([$subform]);
        
        $col_name->enableSearch();
        $search_name = $col_name->getInputSearch();
        $search_name->placeholder = _t('Search');
        $search_name->style = 'width:50%;margin-left: 4px; display:inline';
        
        
        $col_program->setTransformer( function($value, $object, $row) {
            $menuparser = new TMenuParser('menu.xml');
            $paths = $menuparser->getPath($value);
            
            if ($paths)
            {
                return implode(' &raquo; ', $paths);
            }
        });
        
        TTransaction::open('permission');
        $this->program_list->addItems( SystemProgram::get() );
        TTransaction::close();
        
        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', 'SystemUserList'));
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
    }

    /**
     * Save user data
     */
    public function onSave($param)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        try
        {
            // open a transaction with database 'permission'
            TTransaction::open('permission');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $object = new SystemUser;
            $object->fromArray( (array) $data );
            
            unset($object->accepted_term_policy);

            $senha = $object->password;
            
            if( empty($object->login) )
            {
                throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Login')));
            }
            
            if( empty($object->id) )
            {
                if (SystemUser::newFromLogin($object->login) instanceof SystemUser)
                {
                    throw new Exception(_t('An user with this login is already registered'));
                }
                
                if (SystemUser::newFromEmail($object->email) instanceof SystemUser)
                {
                    throw new Exception(_t('An user with this e-mail is already registered'));
                }
                
                if ( empty($object->password) )
                {
                    throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Password')));
                }
                
                $object->active = 'Y';
            }
            else
            {
                if (SystemUser::where('login', '=', $object->login)->where('id', '<>', $object->id)->first() instanceof SystemUser)
                {
                    throw new Exception(_t('An user with this login is already registered'));
                }
            }
            
            if ( $object->password )
            {
                if (isset($ini['general']['validate_strong_pass']) && $ini['general']['validate_strong_pass'] == '1')
                {
                    (new TStrongPasswordValidator)->validate(_t('Password'), $object->password);
                }
                
                if( $object->password !== $param['repassword'] )
                {
                    throw new Exception(_t('The passwords do not match'));
                }
                
                $object->password = SystemUser::passwordHash($object->password);

                if ($object->id)
                {
                    SystemUserOldPassword::validate($object->id, $object->password);
                }
            }
            else
            {
                unset($object->password);
            }
            
            $object->store();

            if ($object->password)
            {
                SystemUserOldPassword::register($object->id, $object->password);
            }
            $object->clearParts();
            
            if( !empty($data->group_list) )
            {
                foreach( $data->group_list as $group_id )
                {
                    $object->addSystemUserGroup( new SystemGroup($group_id) );
                }
            }
            
            if( !empty($data->unit_list) )
            {
                foreach( $data->unit_list as $unit_id )
                {
                    $object->addSystemUserUnit( new SystemUnit($unit_id) );
                }
            }
            
            if (!empty($data->program_list))
            {
                foreach ($data->program_list as $program_id)
                {
                    $object->addSystemUserProgram( new SystemProgram( $program_id ) );
                }
            }
            
            if( !empty($data->role_list) )
            {
                foreach( $data->role_list as $role_id )
                {
                    $object->addSystemUserRole( new SystemRole($role_id) );
                }
            }
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_System_user', $data);
            
            // close the transaction
            TTransaction::close();
            
            $pos_action = new TAction(['SystemUserList', 'onReload']);
            
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'), $pos_action);
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    public function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'permission'
                TTransaction::open('permission');
                
                // instantiates object System_user
                $object = new SystemUser($key);
                
                unset($object->password);
                
                $groups = array();
                $units  = array();
                
                if( $groups_db = $object->getSystemUserGroups() )
                {
                    foreach( $groups_db as $group )
                    {
                        $groups[] = $group->id;
                    }
                }
                
                if( $units_db = $object->getSystemUserUnits() )
                {
                    foreach( $units_db as $unit )
                    {
                        $units[] = $unit->id;
                    }
                }
                
                $program_ids = array();
                foreach ($object->getSystemUserPrograms() as $program)
                {
                    $program_ids[] = $program->id;
                }
                
                $role_ids = array();
                foreach ($object->getSystemUserRoles() as $role)
                {
                    $role_ids[] = $role->id;
                }
                
                $object->program_list = $program_ids;
                $object->group_list   = $groups;
                $object->unit_list    = $units;
                $object->role_list    = $role_ids;
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
