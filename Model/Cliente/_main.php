<?php 

class Cliente{

	private $ClienteId;
	private $ClienteNome;
	private $ClienteCep;
	private $ClienteCpfCnpj;
	private $ClienteAtivo;
	private $OficinaId;
	private $ClienteDataCadastro;
	private $ClienteNumeroCasa;
	private $ClienteObs;
	private $ClienteEndereco;
	private $ClienteBairro;

	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceCliente{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Cliente $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	public function validarCliente(){
		$quary = "SELECT ClienteId FROM `Cliente` 
			WHERE ClienteCpfCnpj = ? ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteCpfCnpj'));
		$stmt->execute();
		$aux = $stmt->fetchAll(PDO::FETCH_OBJ);			
		return $aux[0]->ClienteId;
	}

	public function excluirCliente(){	
		$quary ="UPDATE `Cliente` SET `ClienteAtivo` = '0' 
		WHERE `ClienteId` = ? ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteId'));			
		$stmt->execute();
	}


	
	public function AtualizarCliente(){	
		$quary ="UPDATE `Cliente` SET 
		`ClienteNome` = ? , 
		`ClienteCep` = ? , 
		`ClienteCpfCnpj` = ? , 
		`ClienteNumeroCasa` =  ? , 
		`ClienteObs` =  ? , 
		`ClienteEndereco` =  ? , 
		`ClienteBairro` =  ? 
		WHERE ClienteId = ? 
		and  OficinaId = ? ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteNome'));			
		$stmt->bindValue(2, $this->tarefa->__get('ClienteCep'));
		$stmt->bindValue(3, $this->tarefa->__get('ClienteCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('ClienteNumeroCasa'));
		$stmt->bindValue(5, $this->tarefa->__get('ClienteObs'));
		$stmt->bindValue(6, $this->tarefa->__get('ClienteEndereco'));
		$stmt->bindValue(7, $this->tarefa->__get('ClienteBairro'));
		$stmt->bindValue(8, $this->tarefa->__get('ClienteId'));
		session_start();
		$stmt->bindValue(9, $_SESSION["OficinaId"]);
		$stmt->execute();	

		$quary ="SELECT * FROM `Cliente` 
		WHERE ClienteNome = ?  
		and ClienteCpfCnpj = ?  
		and ClienteEndereco = ?  
		and ClienteBairro = ?   ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteNome'));			
		$stmt->bindValue(2, $this->tarefa->__get('ClienteCpfCnpj'));
		$stmt->bindValue(3, $this->tarefa->__get('ClienteEndereco'));
		$stmt->bindValue(4, $this->tarefa->__get('ClienteBairro'));
		$stmt->execute();
		$aux =  $stmt->fetchAll(PDO::FETCH_OBJ);
		if (isset($aux[0]->ClienteNome)){
			return true;
		}
		return false;

	}




	// OficinaId tem que mandar
	public function Salvar(){	
		$quary ="INSERT INTO Cliente( 
		ClienteNome, ClienteCep, 
		ClienteCpfCnpj, ClienteAtivo, 
		OficinaId, ClienteDataCadastro, 
		ClienteNumeroCasa, ClienteObs, 
		ClienteEndereco, ClienteBairro) 
		VALUES ( ?  , ? , ? , ? , ? , ? , ? , ? , ? , ? )";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteNome'));			
		$stmt->bindValue(2, $this->tarefa->__get('ClienteCep'));
		$stmt->bindValue(3, $this->tarefa->__get('ClienteCpfCnpj'));
		$stmt->bindValue(4, '1');
		session_start();
		$stmt->bindValue(5, $_SESSION["OficinaId"]);
		$stmt->bindValue(6, date('Y-m-d',time()));
		$stmt->bindValue(7, $this->tarefa->__get('ClienteNumeroCasa'));
		$stmt->bindValue(8, $this->tarefa->__get('ClienteObs'));
		$stmt->bindValue(9, $this->tarefa->__get('ClienteEndereco'));
		$stmt->bindValue(10, $this->tarefa->__get('ClienteBairro'));
		$stmt->execute();
		

		$quary ="SELECT * FROM `Cliente` ORDER BY ClienteId DESC LIMIT 1";
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		$aux = $stmt->fetchAll(PDO::FETCH_OBJ);
		if (isset($aux[0]->ClienteNome) && $aux[0]->ClienteNome == $this->tarefa->__get('ClienteNome')) {
			return true;
		}
		return false;
		


	}


	
	public function info(){
		$quary = "SELECT p.*, pp.*, pa.*, ve.* FROM Pedido as p

		INNER JOIN Peca_Pedido as pp 
		on (pp.ManutencaoId = p.ManutencaoId)
		
		INNER JOIN Peca as pa 
		on (pa.PecaId = pp.PecaId)
		
		
		INNER JOIN Veiculo as ve 
		on (ve.VeiculoId = p.VeiculoId)
		
		INNER JOIN Cliente as cl 
		on (cl.ClienteId = ve.ClienteId)
		
		WHERE (cl.ClienteCpfCnpj LIKE ? )
		or (ve.VeiculoPlaca LIKE ? )
		or (ve.VeiculoRenavam LIKE ? ) ";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteNome'));
		$stmt->bindValue(2, $this->tarefa->__get('ClienteNome'));
		$stmt->bindValue(3, $this->tarefa->__get('ClienteNome'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	
	public function detalheCliente(){
		session_start();
		$aux = $_SESSION['OficinaId'];
		$quary = "SELECT * FROM `Cliente` WHERE OficinaId = $aux and ClienteAtivo = 1 and ClienteId = ?  ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}


	public function listarCliente(){
		session_start();
		$aux = $_SESSION['OficinaId'];
		$quary = "SELECT * FROM `Cliente` WHERE OficinaId = $aux and ClienteAtivo = 1 ORDER BY ClienteId DESC";
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}



	public function allCliente(){
		$quary = " SELECT c.* from Pedido as p 
		
		INNER JOIN Veiculo as v on (v.VeiculoId = p.VeiculoId)
		
		INNER JOIN Cliente as c on (c.ClienteId = v.ClienteId)
		
		WHERE p.ManutencaoId = ? ";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteNome'));

		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function buscarCliente(){
		$quary = "SELECT * FROM `Cliente` 
		WHERE ClienteNome like ?
		or ClienteCpfCnpj like ? 
		and ( OficinaId = ? )";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteNome'));
		$stmt->bindValue(2, $this->tarefa->__get('ClienteNome'));
		
		session_start();
		$stmt->bindValue(3, $_SESSION['OficinaId']);	
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function excluirCliente($ls){

	$cliente = new Cliente();
	$cliente->__set('ClienteId', $ls['obj']);
	$serviceCliente = new ServiceCliente(new Conexao(), $cliente);
	$aux = $serviceCliente->excluirCliente();

}



function AtualizarCliente($ls){
	$cliente = sv("Cliente", $ls);
	$serviceCliente = new ServiceCliente(new Conexao(), $cliente);
	$aux = $serviceCliente->AtualizarCliente();
	if ($aux) {
		echo '1';
	}else{
		echo '0';
	}
}


function salvarCliente($ls){
	$cliente = sv("Cliente", $ls);
	$serviceCliente = new ServiceCliente(new Conexao(), $cliente);
	$aux = $serviceCliente->Salvar();
	if ($aux == false) {
		echo '0';
		return null;
	}
	$clienteId = $serviceCliente->validarCliente();
	if (isset($clienteId) and $clienteId > 0 ) {
		$listaContatosComAtt = ca('Cliente_Contato', $ls, $clienteId);
		foreach ($listaContatosComAtt as $key => $value) {
			$Cliente_Contato = new ServiceCliente_Contato(new Conexao(), $value);
			$Cliente_Contato->Salvar();			
		}
		echo '1';
	}

	
	$Cliente_Contato = new ServiceCliente_Contato(new Conexao(), new Cliente_Contato());
	$Cliente_Contato->limpar();	
	
}

function pesquisarCliente($obj){;
	$Cliente = new Cliente();
	$Cliente->__set('ClienteNome',  str_replace(strtoupper($obj),"\"","%") );
	$service = new ServiceCliente(new Conexao(), $Cliente);
	$resultado = $service->buscarCliente();
	select($resultado, "Dono(a): ", array('ClienteNome', 'ClienteCpfCnpj'), "Selecione um dono(a)", "selectcliente", "btn btn-light", "ClienteId", 'listarVeiculo()');
}



function info($texto){;

	$Cliente = new Cliente();
	$Cliente->__set('ClienteNome',   str_replace(strtoupper($texto),"\"","%") );
	$service = new ServiceCliente(new Conexao(), $Cliente);
	$ls = $service->info();
	return $ls;
	
}


function getidCliente($idpedido){;
	$Cliente = new Cliente();
	$Cliente->__set('ClienteNome',  $idpedido );
	$service = new ServiceCliente(new Conexao(), $Cliente);
	$ls = $service->allCliente();
	return $ls;
	
	//select($resultado, "Dono(a): ", array('ClienteNome', 'ClienteCpfCnpj'), "Selecione um dono(a)", "selectcliente", "btn text-white -primary", "ClienteId", 'listarVeiculo()');
}




function listarCliente(){;
	$service = new ServiceCliente(new Conexao(), new Cliente());
	$ls = $service->listarCliente();
	return $ls;
}



function detalheCliente($id){;
	$cliente = new Cliente();
	$cliente->__set('ClienteId',  $id );
	$service = new ServiceCliente(new Conexao(),$cliente );
	$ls = $service->detalheCliente();
	return $ls;
}




?>