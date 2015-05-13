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
		$this->load->model('M_Categoria','',TRUE);

	}

	public function index()
	{
		$clientes = $this->M_Cliente->get_paged_list(100000, 0)->result();

		$data['actionDelForm'] = 'clientes/traerClientes';
		$data['clientes'] = $clientes;
		$out = $this->load->view('view_clientesList.php', $data, TRUE);
		$data['cuerpo'] = $out;

		parent::cargarTemplate($data);
	}

	public function traerClientesJson(){
		$clientes = $this->M_Cliente->get_paged_list(100000, 0)->result();

		$data['actionDelForm'] = 'clientes/traerClientes';
		$data['clientes'] = $clientes;
		$out = $this->load->view('view_clientesList.php', $data, TRUE);
		$data['cuerpo'] = $out;

		$json = json_encode($data['clientes']);
		echo $json;
	}

	public function loadClientes()
	{
        $this->datatables->select('idCliente,categoria.descripcion,nombre, apellido,telefono, email')
        ->from('cliente')
        ->join('categoria', 'categoria.idCategoria = cliente.idCategoria');
    
        $this->datatables->iDisplayLength(100000);
        echo $this->datatables->generate();
	}

	public function nuevo(){
		// $tiposClientes = $this->M_TipoCliente->get_paged_list(30, 0)->result();
		$categorias = $this->M_Categoria->get_paged_list(30, 0)->result();
		$provincias = $this->M_Provincia->get_paged_list(30, 0)->result();

		$data['cliente'] =  NULL;
		// $data['tiposClientes'] =  $tiposClientes;
		$data['categorias'] =  $categorias;
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
		$data['fechaNacimiento'] = 	$this->input->post('txtFechaNacimiento');
		$data['idCategoria'] = 		$this->input->post('selCategoria');

		if ($this->input->post('txtIdCliente') != null){
			$this->M_Cliente->update($this->input->post('txtIdCliente'),$data);	
		}else {
			$this->M_Cliente->insert($data);	
		}

		redirect(base_url(). 'index.php/clientes', 'index');
		
	}

	public function modificar($idCliente=NULL){
		$categorias = $this->M_Categoria->get_paged_list(30, 0)->result();
		$provincias = $this->M_Provincia->get_paged_list(30, 0)->result();

		if ($idCliente == NULL)
			$idCliente  =  $this->input->post('idCliente');

		$cliente = $this->M_Cliente->get_by_id($idCliente)->result();

		$data['cliente'] 	= $cliente[0];
		$data['categorias'] =  $categorias;
		//$data['categoriasIva'] =  $categoriasIva;
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