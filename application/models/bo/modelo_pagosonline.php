<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class modelo_pagosonline extends CI_Model
{
	function val_compropago()
	{
		$compropago=$this->get_datos_compropago();
		if(!$compropago){
			$dato=array(
					"email" =>	"you@domain.com"
			);
			$this->db->insert("compropago",$dato);
			$compropago=$this->get_datos_compropago();
		}
		return $compropago;
	}

	function val_tucompra()
	{
		$tucompra=$this->get_datos_tucompra();
		if(!$tucompra){
			$dato=array(
					"email" =>	"you@domain.com"
			);
			$this->db->insert("tucompra",$dato);
			$tucompra=$this->get_datos_tucompra();
		}
		return $tucompra;
	}
	
	
	function val_payulatam()
	{
		$payulatam=$this->get_datos_payulatam();
		if(!$payulatam){
			$dato=array(
					"apykey" =>	"6u39nqhq8ftd0hlvnjfs66eh8c"
			);
			$this->db->insert("payulatam",$dato);
			$payulatam=$this->get_datos_payulatam();
		}
		return $payulatam;
	}

    function val_blockchain()
    {
        $blockchain=$this->get_datos_blockchain();
        if(!$blockchain)
            $blockchain=$this->initBlockchain();

        $wallets=$this->get_wallet_blockchain();
        if(!$wallets)
            $wallets = $this->initWallet();

        return array($blockchain,$wallets);
    }

    function val_paypal()
	{
		$payulatam=$this->get_datos_paypal();
		if(!$payulatam){
			$dato=array(
					"email" =>	"seonetworksoft-facilitator@gmail.com"
			);
			$this->db->insert("paypal",$dato);
			$payulatam=$this->get_datos_paypal();
		}
		return $payulatam;
	}

	function get_datos_compropago()
	{
		$q=$this->db->query("select * from compropago");
		$payulatam = $q->result();
		return $payulatam;
	}
	
	function get_datos_tucompra()
	{
		$q=$this->db->query("select * from tucompra");
		$payulatam = $q->result();
		return $payulatam;
	}
	
	function get_datos_payulatam()
	{
		$q=$this->db->query("select * from payulatam");
		$payulatam = $q->result();
		return $payulatam;
	}
	function get_datos_blockchain()
	{
		$q=$this->db->query("select * from blockchain");
		$blockchain = $q->result();
		return $blockchain;
	}
    function get_wallet_blockchain($id = 1,$where = "")
    {
        $q=$this->db->query("select * from blockchain_wallet where id_usuario in ($id) $where");
        $wallets = $q->result();
        return $wallets;
    }
	function get_datos_paypal()
	{
		$q=$this->db->query("select * from paypal");
		$paypal = $q->result();
		return $paypal;
	}

	function cliente_compropago(){

		$compropago  = $this->val_compropago(); 
		
		$test = ($compropago[0]->test!=1) ? true : false;

		$k_test = array($compropago[0]->pk_test,$compropago[0]->sk_test,$test);
		$k_live = array($compropago[0]->pk_live,$compropago[0]->sk_live,$test);

		$key = $k_test;

		if($compropago[0]->test!=1)
			$key=$k_live;

		return $key;

	}

	function actualizar_compropago()
	{
		
		$test=0;
		$estado='DES';

		if(isset($_POST['test']))
			$test=1;
		
		if(isset($_POST['estatus']))
			$estado='ACT';
		
		$dato=array(
			
				"email"     => $_POST['email'],
				"pk_test"   		=> $_POST['pk_test'],
				"sk_test"   		=> $_POST['sk_test'],
				"pk_live"   		=> $_POST['pk_live'],
				"sk_live"   		=> $_POST['sk_live'],
				"moneda"   		=> $_POST['moneda'],
				"test"       			=> $test,
				"estatus"       		=> $estado
		);
	
		$this->db->where('email', $_POST['id']);
		$this->db->update('compropago', $dato);
	
		return true;
	}
	
	
	function actualizar_tucompra()
	{
		
		$test=0;
		$estado='DES';

		if(isset($_POST['test']))
			$test=1;
		
		if(isset($_POST['estatus']))
			$estado='ACT';
		
		$dato=array(
				"cuenta"     => $_POST['cuenta'],
				"email"     => $_POST['email'],
				"llave"   		=> $_POST['llave'],
				"moneda"   		=> $_POST['moneda'],
				"test"       			=> $test,
				"estatus"       		=> $estado
		);
	
		$this->db->where('email', $_POST['id']);
		$this->db->update('tucompra', $dato);
	
		return true;
	}
	
	function actualizar_payulatam()
	{
	
		$test=0;
		$estado='DES';
	
		if(isset($_POST['test']))
			$test=1;
	
			if(isset($_POST['estatus']))
				$estado='ACT';
	
				$dato=array(
						"apykey"     => $_POST['apykey'],
						"id_comercio"   		=> $_POST['id_comercio'],
						"id_cuenta"     		=> $_POST['id_cuenta'],
						"moneda"       			=> $_POST['moneda'],
						"test"       			=> $test,
						"estatus"       		=> $estado
				);
	
				$this->db->where('apykey', $_POST['id']);
				$this->db->update('payulatam', $dato);
	
				return true;
	}

    function actualizar_blockchain()
    {
        $test=0;
        $estado='DES';

        if(isset($_POST['test']))
            $test=1;

        if(isset($_POST['estatus']))
            $estado='ACT';

        $dato = array(
            "apikey" => $_POST['key'],
            "currency" => $_POST['moneda'],
            "test" => $test,
            "estatus" => $estado
        );

        $this->db->where('id', $_POST['id']);
        $this->db->update('blockchain', $dato);

        $dato=array(
            "hashkey"     => $_POST['wallet'],
            "porcentaje"       		=> $_POST['wallet_per'],
        );

        $this->db->where('id_usuario', 1);
        $this->db->update('blockchain_wallet', $dato);

        return true;
    }

	function actualizar_paypal()
	{
	
		$test=0;
		$estado='DES';

		if(isset($_POST['test']))
			$test=1;
		
		if(isset($_POST['estatus']))
			$estado='ACT';
	
		$dato=array(
				"email"     => $_POST['email'],
				"moneda"       		=> $_POST['moneda'],
				"test"       		=> $test,
				"estatus"       		=> $estado
		);
	
		$this->db->where('email', $_POST['id']);
		$this->db->update('paypal', $dato);

		return true;
	}

    private function initBlockchain()
    {
        $dato = array(
            "apikey" => "78d9ce16-e1d6-47f7-acf1-f456409715f5"
        );
        $this->db->insert("blockchain", $dato);
        return $this->get_datos_blockchain();
    }

    private function initWallet()
    {
        $dato = array(
            "id_usuario" => 1,
            "hashkey" => "2c303dc6-3817-4759-b0b1-a55369a56028"
        );
        $this->db->insert("blockchain_wallet", $dato);
        return $this->get_wallet_blockchain();
    }
}

?>
