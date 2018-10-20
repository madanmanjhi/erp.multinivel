<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class virtual extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->load->model('bo/modelo_dashboard');
        $this->load->model('bo/model_admin');
        $this->load->model('bo/general');
        $this->load->model('model_tipo_red');
        $this->load->model('model_archivo_soporte_tecnico');
        $this->load->model('bo/model_mercancia');
        $this->load->model('model_datos_generales_soporte_tecnico');
        $this->load->model('model_cat_grupo_soporte_tecnico');
        $this->load->model('bo/model_soporte_tecnico');
        $this->load->model('model_emails_departamentos');
        $this->load->model('bo/modelo_pagosonline');

    }

    function index()
    {
        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        $id=$this->tank_auth->get_user_id();
        $usuario=$this->general->get_username($id);

        if($usuario[0]->id_tipo_usuario!=1)
        {
            redirect('/auth/logout');
        }

        $style=$this->modelo_dashboard->get_style($id);

        $this->template->set("style",$style);

        $this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
        $this->template->build('website/bo/configuracion/virtual');
    }

    function blockchain()
    {
        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        $id=$this->tank_auth->get_user_id();
        $usuario=$this->general->get_username($id);

        if($usuario[0]->id_tipo_usuario!=1)
        {
            redirect('/auth/logout');
        }

        $style=$this->modelo_dashboard->get_style($id);

        $this->template->set("style",$style);

        $blockchain  = $this->modelo_pagosonline->val_blockchain();
        $this->template->set("blockchain",$blockchain);

        $this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
        $this->template->build('website/bo/configuracion/pagosOnline/blockchain');
    }

    function payuLatam()
    {
        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        $id=$this->tank_auth->get_user_id();
        $usuario=$this->general->get_username($id);

        if($usuario[0]->id_tipo_usuario!=1)
        {
            redirect('/auth/logout');
        }

        $style=$this->modelo_dashboard->get_style($id);

        $this->template->set("style",$style);

        $payulatam  = $this->modelo_pagosonline->val_payulatam();
        $this->template->set("payulatam",$payulatam);

        $this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
        $this->template->build('website/bo/configuracion/pagosOnline/payulatam');
    }

    function payPal()
    {
        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        $id=$this->tank_auth->get_user_id();
        $usuario=$this->general->get_username($id);

        if($usuario[0]->id_tipo_usuario!=1)
        {
            redirect('/auth/logout');
        }

        $style=$this->modelo_dashboard->get_style($id);

        $this->template->set("style",$style);

        $paypal  = $this->modelo_pagosonline->val_paypal();
        $this->template->set("paypal",$paypal);

        $this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
        $this->template->build('website/bo/configuracion/pagosOnline/paypal');
    }

    function Tucompra()
    {
        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        $id=$this->tank_auth->get_user_id();
        $usuario=$this->general->get_username($id);

        if($usuario[0]->id_tipo_usuario!=1)
        {
            redirect('/auth/logout');
        }

        $style=$this->modelo_dashboard->get_style($id);

        $this->template->set("style",$style);

        $tucompra  = $this->modelo_pagosonline->val_tucompra();
        $this->template->set("tucompra",$tucompra);

        $this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
        $this->template->build('website/bo/configuracion/pagosOnline/Tucompra');
    }

    function compropago()
    {
        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        $id=$this->tank_auth->get_user_id();
        $usuario=$this->general->get_username($id);

        if($usuario[0]->id_tipo_usuario!=1)
        {
            redirect('/auth/logout');
        }

        $style=$this->modelo_dashboard->get_style($id);

        $this->template->set("style",$style);

        $compropago  = $this->modelo_pagosonline->val_compropago();
        $this->template->set("compropago",$compropago);

        $this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
        $this->template->build('website/bo/configuracion/pagosOnline/Compropago');
    }

    function actualizarCompropago()
    {
        $compropago = $this->modelo_pagosonline->actualizar_compropago();
        echo $compropago
            ? "Se ha actualizado los datos de Compropago."
            : "No se ha podido actualizar los datos de Compropago.";
    }

    function actualizarTucompra()
    {
        $tucompra = $this->modelo_pagosonline->actualizar_tucompra();
        echo $tucompra
            ? "Se ha actualizado los datos de Tucompra."
            : "No se ha podido actualizar los datos de Tucompra.";
    }

    function actualizarPayuLatam()
    {
        $payulatam = $this->modelo_pagosonline->actualizar_payulatam();
        echo $payulatam
            ? "Se ha actualizado los datos de payulatam."
            : "No se ha podido actualizar los datos de payulatam.";
    }

    function actualizarPayPal()
    {
        $payulatam = $this->modelo_pagosonline->actualizar_paypal();
        echo $payulatam
            ? "Se ha actualizado los datos de paypal."
            : "No se ha podido actualizar los datos de paypal.";
    }

    function actualizar_blockchain()
    {
        $blockchain = $this->modelo_pagosonline->actualizar_blockchain();
        echo $blockchain
            ? "Se ha actualizado los datos de blockchain."
            : "No se ha podido actualizar los datos de blockchain.";
    }

}