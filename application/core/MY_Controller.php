<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	private $data;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');

		date_default_timezone_set("America/Argentina/Buenos_Aires");

		if ($this->session->userdata('usuario') == FALSE)
			redirect(base_url(). 'index.php/login', 'refresh');

    }


    public function cargarTemplate($data){
		$usuario = $this->session->userdata('usuario');
		$data['nombreUsuario'] = $usuario[0]->nombre;
		$data['apellidoUsuario']= $usuario[0]->apellido;
		$data['usuario']= $usuario[0]->username;

    	
    	//$data['nombreUsuario'] = $this->data['nombreUsuario'];
    	//$data['apellidoUsuario'] = $this->data['apellidoUsuario'];
		$this->load->view('view_template.php', $data);
    }
    
} 