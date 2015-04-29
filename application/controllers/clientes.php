<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends MY_Controller {

	private $permiso_autorizaPago;
	function __construct()
	{
		parent::__construct();
		$this->load->library('Datatables');
		$this->load->model('M_Cliente','',TRUE);
		$this->load->model('M_Provincia','',TRUE);
		$this->load->model('M_Notificacion','',TRUE);

	}

	public function index()
	{
		$clientes = $this->M_Cliente->get_paged_list(500, 0)->result();

		$data['actionDelForm'] = 'clientes/traerClientes';
		$data['clientes'] = $clientes;
		$out = $this->load->view('view_clientesList.php', $data, TRUE);
		$data['cuerpo'] = $out;

		parent::cargarTemplate($data);
	}

	public function loadClientes()
	{
		$keyword = $this->input->get('sSearch');
		if (strlen($keyword) > 2){
	        $this->datatables->select('idCliente,nombre,telefono')
	        ->from('cliente')
	        ->where("nombre like '%" . $keyword ."%'");
	        
	        $this->datatables->iDisplayLength=5;
	        echo $this->datatables->generate();

		}else{
			echo "{}";
		}

	}

	public function nuevo(){
		// $tiposClientes = $this->M_TipoCliente->get_paged_list(30, 0)->result();
		// $categoriasIva = $this->M_CategoriaIva->get_paged_list(30, 0)->result();
		$provincias = $this->M_Provincia->get_paged_list(30, 0)->result();

		$data['cliente'] =  NULL;
		// $data['tiposClientes'] =  $tiposClientes;
		// $data['categoriasIva'] =  $categoriasIva;
		$data['provincias'] =  $provincias;
		
		
		$out = $this->load->view('view_clientesDetalle.php', $data, TRUE);
		$data['cuerpo'] = $out;
		parent::cargarTemplate($data);
	}

	public function guardar(){

		$data['nombre'] = 			$this->input->post('txtNombre');
		$data['apellido'] = 		$this->input->post('txtApellido');
		$data['calle'] = 			$this->input->post('txtCalle');
		$data['numero'] = 			$this->input->post('txtNumero');
		$data['idProvincia'] = 		$this->input->post('selProvincia');
		$data['localidad'] = 		$this->input->post('txtLocalidad');
		$data['codigoPostal'] = 	$this->input->post('txtCodigoPostal');
		$data['telefono'] = 		$this->input->post('txtTelefono');
		$data['email'] = 			$this->input->post('txtEmail');
		$data['fechaNacimiento'] = 		$this->input->post('txtFechaNacimiento');

		if ($this->input->post('txtIdCliente') != null){
			$this->M_Cliente->update($this->input->post('txtIdCliente'),$data);	
		}else {
			$this->M_Cliente->insert($data);	
		}

		redirect(base_url(). 'index.php/clientes', 'index');
		
	}

	public function modificar($idCliente=NULL){
		$tiposClientes = $this->M_TipoCliente->get_paged_list(30, 0)->result();
		$provincias = $this->M_Provincia->get_paged_list(30, 0)->result();

		if ($idCliente == NULL)
			$idCliente  =  $this->input->post('idCliente');

		$cliente = $this->M_Cliente->get_by_id($idCliente)->result();

		$data['cliente'] 	= $cliente[0];
		$data['tiposClientes'] =  $tiposClientes;
		$data['categoriasIva'] =  $categoriasIva;
		$data['provincias'] =  $provincias;
		
		$out = $this->load->view('view_clientesDetalle.php', $data, TRUE);
		$data['cuerpo'] = $out;
		parent::cargarTemplate($data);
	}

	public function eliminar(){
		
		$idCliente = $this->input->post('idCliente');
		$this->M_Cliente->delete($idCliente);
		
		redirect(base_url(). 'index.php/clientes', 'index');

	}

	public function existeParte(){
		$codigoParte = $this->input->post("codigo");

		$query = $this->M_Cliente->get_by_codigo($codigoParte);

		if($query->num_rows() > 0){
		    echo 1;
		} else {
		    echo 0;
		}
	}


}