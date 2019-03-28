<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'models/modelos.php';
class general extends mGeneral
{
    function get_groups()
    {
        $q=$this->db->query('select * from cat_grupo');
        return $q->result();
    }
    function get_tipo_archivo($ext)
    {
        $q=$this->$this->db->query('select id from cat_tipo_archivo where descripcion= '.$ext);
        return $q->result();
    }
    function get_video()
    {
        $q=$this->db->query('select * from archivo where id_tipo=2');
        return $q->result();
    }


    function totalAfiliados()
    {
        $q=$this->db->query('SELECT count(*)as total FROM users u , user_profiles up
								where u.id=up.user_id 
								and up.id_tipo_usuario=2
								and u.id!=2;');
        return $q->result();
    }
}