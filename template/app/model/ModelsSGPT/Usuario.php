<?php
class Usuario extends TRecord
{
    const TABLENAME  = 'usuario';
    const PRIMARYKEY = 'id_usuario';
    const IDPOLICY   = 'serial'; // ou 'identity' se for SQL Server

    // Relacionamentos
    private $equipe;

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nm_usuario');
        parent::addAttribute('ds_email');
        parent::addAttribute('ds_senha');
        parent::addAttribute('tp_usuario');
        parent::addAttribute('dt_criacao');
    }
}
