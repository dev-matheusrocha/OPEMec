<?php 





class Fornecedor{
	private $FornecedorId;
	private $FornecedorNome;
	private $FornecedorCnpj;
	private $FornecedorCadastro;
	private $FornecedorObs;
	private $FornecedorCep;
	private $FornecedorEndereco;
	private $FornecedorNumero;

	


	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceFornecedor{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Fornecedor $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	
	public function listarfornecedores(){
		$quary = "SELECT * FROM `Fornecedor` ORDER BY FornecedorId DESC";
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function salvarFornecedor(){
	

		$quary = " INSERT INTO `Fornecedor` (
		 
		`FornecedorNome`, 
		`FornecedorCnpj`, 
		`FornecedorCadastro`, 
		`FornecedorObs`, 
		`FornecedorCep`, 
		`FornecedorEndereco`, 
		`FornecedorNumero`) 
		VALUES (
		? , 
		? , 
		CURRENT_TIMESTAMP, 
		? , 
		? , 
		? , 
		? ) ;";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('FornecedorNome'));
		$stmt->bindValue(2, $this->tarefa->__get('FornecedorCnpj'));
		$stmt->bindValue(3, $this->tarefa->__get('FornecedorObs'));
		$stmt->bindValue(4, $this->tarefa->__get('FornecedorCep'));
		$stmt->bindValue(5, $this->tarefa->__get('FornecedorEndereco'));
		$stmt->bindValue(6, $this->tarefa->__get('FornecedorNumero'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);

		
	}
	
	public function buscarFornecedor(){
		$quary = "SELECT * FROM `tb_Fornecedor` 
		WHERE nome like ? 
		or celular like ? 
		or telefone LIKE ? ";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('nome'));
		$stmt->bindValue(2, $this->tarefa->__get('nome'));
		$stmt->bindValue(3, $this->tarefa->__get('nome'));
		
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function Salvar(){
		$quary = "INSERT INTO `Fornecedor` 
			(`FornecedorId`, 
			`FornecedorNome`, 
			`FornecedorCep`, 
			`FornecedorCpfCnpj`, 
			`FornecedorAtivo`, 
			`OficinaId`, 
			`FornecedorDataCadastro`, 
			`FornecedorNumeroCasa`,
			`FornecedorObs`,
			`FornecedorEndereco`,
			`FornecedorBairro`) 
		 VALUES (NULL,
		 	 'FornecedorNome',
		 	 '1111',
		 	 '1111',
		 	 '1',
		 	 '1',
		 	 CURRENT_TIMESTAMP,
		 	 '1',
		 	 '1',
		 	 '1',
			  '1')";
			  
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('FornecedorNome'));
		$stmt->bindValue(2, $this->tarefa->__get('FornecedorCep'));
		$stmt->bindValue(3, $this->tarefa->__get('FornecedorCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('FornecedorAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('FornecedorNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

/*

function salvarFornecedor($lista){
	
	$var = json_decode($lista['obj']);
	$Fornecedor = new Fornecedor();
	$Fornecedor->__set('FornecedorNome',strtoupper($var[0]->value));
	$Fornecedor->__set('FornecedorCep',strtoupper($var[2]->value));
	$Fornecedor->__set('FornecedorCpfCnpj',strtoupper($var[1]->value));
	$Fornecedor->__set('FornecedorAtivo','1');
	$Fornecedor->__set('OficinaId',$_SESSION['OficinaId']);
	//$Fornecedor->__set('FornecedorDataCadastro',strtoupper($var[0]->value));
	$Fornecedor->__set('FornecedorNumeroCasa',strtoupper($var[4]->value));
	$Fornecedor->__set('FornecedorObs',strtoupper($var[5]->value));
	$Fornecedor->__set('FornecedorEndereco',strtoupper($var[3]->value));
	$Fornecedor->__set('FornecedorBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceFornecedor = new ServiceFornecedor($conexao, $Fornecedor);
	$serviceFornecedor->Salvar();

}
*/





function buscarFornecedor($texto){

	$Fornecedor = new Fornecedor();
	$Fornecedor->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceFornecedor($conexao, $Fornecedor);
	$resultado = $service->buscarFornecedor();
	return $resultado;
}





function salvarFornecedor($fornecedor){

	$conexao = new Conexao();
	$service = new ServiceFornecedor($conexao, $fornecedor);
	$resultado = $service->salvarFornecedor();
	

}

function listarfornecedores(){
	$service = new ServiceFornecedor(new Conexao(), new Fornecedor());
	return $service->listarfornecedores();
}

 ?>