<?php 





class Cliente_Contato{
	
	private $ClienteId;
	private $ClienteNumeroContato;
	private $ClienteEmail;
	private $Cliente_ContatoId;
	private $ClienteApelido;
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceCliente_Contato{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Cliente_Contato $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	public function excluirContato(){
		$quary = 'DELETE FROM `Cliente_Contato` WHERE Cliente_ContatoId = ? ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('Cliente_ContatoId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function contato(){
		$quary = 'SELECT * FROM `Cliente_Contato` WHERE ClienteId = ? ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);

	}

	public function limpar(){
		$quary = 'DELETE FROM `Cliente_Contato` WHERE ClienteEmail = "" and ClienteApelido = "" and ClienteNumeroContato = "" ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
	}

	public function Salvar(){
	
		$quary = "INSERT INTO `Cliente_Contato` (
			`ClienteId`, 
			`ClienteNumeroContato`, 
			`ClienteEmail`, 
			`ClienteApelido`)
			VALUES (
				? , 
				? , 
				? , 
				? );";
				
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteId'));
		$stmt->bindValue(2, $this->tarefa->__get('ClienteNumeroContato'));
		$stmt->bindValue(3, $this->tarefa->__get('ClienteEmail'));
		$stmt->bindValue(4, $this->tarefa->__get('ClienteApelido'));
		$stmt->execute();
		$quary = "SELECT * FROM `Cliente_Contato` ORDER BY Cliente_ContatoId DESC LIMIT 1";
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);

	}
}

function salvarCliente_Contato($lista){
	
	$var = json_decode($lista['obj']);
	$Cliente_Contato = new Cliente_Contato();
	$Cliente_Contato->__set('Cliente_ContatoNome',strtoupper($var[0]->value));
	$Cliente_Contato->__set('Cliente_ContatoCep',strtoupper($var[2]->value));
	$Cliente_Contato->__set('Cliente_ContatoCpfCnpj',strtoupper($var[1]->value));
	$Cliente_Contato->__set('Cliente_ContatoAtivo','1');
	$Cliente_Contato->__set('OficinaId',$_SESSION['OficinaId']);
	//$Cliente_Contato->__set('Cliente_ContatoDataCadastro',strtoupper($var[0]->value));
	$Cliente_Contato->__set('Cliente_ContatoNumeroCasa',strtoupper($var[4]->value));
	$Cliente_Contato->__set('Cliente_ContatoObs',strtoupper($var[5]->value));
	$Cliente_Contato->__set('Cliente_ContatoEndereco',strtoupper($var[3]->value));
	$Cliente_Contato->__set('Cliente_ContatoBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceCliente_Contato = new ServiceCliente_Contato($conexao, $Cliente_Contato);
	$serviceCliente_Contato->Salvar();

}






function buscarCliente_Contato($texto){

	$Cliente_Contato = new Cliente_Contato();
	$Cliente_Contato->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceCliente_Contato($conexao, $Cliente_Contato);
	$resultado = $service->buscarCliente_Contato();
	return $resultado;
}




function contato($id){;
	$cliente = new Cliente_Contato();
	$cliente->__set('ClienteId',  $id );
	$service = new ServiceCliente_Contato(new Conexao(),$cliente );
	$ls = $service->contato();
	return $ls;
}


function excluirContato($id){;
	$cliente = new Cliente_Contato();
	$cliente->__set('Cliente_ContatoId',  $id );
	$service = new ServiceCliente_Contato(new Conexao(),$cliente );
	$ls = $service->excluirContato();
	return $ls;
}


function salvarNovoContato($ls){
	$Cliente_Contato = sv("Cliente_Contato", $ls);
	$serviceCliente = new ServiceCliente_Contato(new Conexao(), $Cliente_Contato);
	$aux = $serviceCliente->Salvar();
	if ( isset($aux[0]->ClienteNumeroContato) && $aux[0]->ClienteNumeroContato == $Cliente_Contato->ClienteNumeroContato ) {
		echo '1';
	}else{
		echo '0';
	}
	$serviceCliente->limpar();
}




 ?>