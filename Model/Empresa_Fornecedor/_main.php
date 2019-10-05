<?php 




class Empresa_Fornecedor{

	private $FornecedorId;
	private $EmpresaId;


	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceEmpresa_Fornecedor{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Empresa_Fornecedor $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarEmpresa_Fornecedor(){
		$quary = "SELECT * FROM `tb_Empresa_Fornecedor` 
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
		$quary = "INSERT INTO `Empresa_Fornecedor` 
			(`Empresa_FornecedorId`, 
			`Empresa_FornecedorNome`, 
			`Empresa_FornecedorCep`, 
			`Empresa_FornecedorCpfCnpj`, 
			`Empresa_FornecedorAtivo`, 
			`OficinaId`, 
			`Empresa_FornecedorDataCadastro`, 
			`Empresa_FornecedorNumeroCasa`,
			`Empresa_FornecedorObs`,
			`Empresa_FornecedorEndereco`,
			`Empresa_FornecedorBairro`) 
		 VALUES (NULL,
		 	 'Empresa_FornecedorNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('Empresa_FornecedorNome'));
		$stmt->bindValue(2, $this->tarefa->__get('Empresa_FornecedorCep'));
		$stmt->bindValue(3, $this->tarefa->__get('Empresa_FornecedorCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('Empresa_FornecedorAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('Empresa_FornecedorNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarEmpresa_Fornecedor($lista){
	
	$var = json_decode($lista['obj']);
	$Empresa_Fornecedor = new Empresa_Fornecedor();
	$Empresa_Fornecedor->__set('Empresa_FornecedorNome',strtoupper($var[0]->value));
	$Empresa_Fornecedor->__set('Empresa_FornecedorCep',strtoupper($var[2]->value));
	$Empresa_Fornecedor->__set('Empresa_FornecedorCpfCnpj',strtoupper($var[1]->value));
	$Empresa_Fornecedor->__set('Empresa_FornecedorAtivo','1');
	$Empresa_Fornecedor->__set('OficinaId',$_SESSION['OficinaId']);
	//$Empresa_Fornecedor->__set('Empresa_FornecedorDataCadastro',strtoupper($var[0]->value));
	$Empresa_Fornecedor->__set('Empresa_FornecedorNumeroCasa',strtoupper($var[4]->value));
	$Empresa_Fornecedor->__set('Empresa_FornecedorObs',strtoupper($var[5]->value));
	$Empresa_Fornecedor->__set('Empresa_FornecedorEndereco',strtoupper($var[3]->value));
	$Empresa_Fornecedor->__set('Empresa_FornecedorBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceEmpresa_Fornecedor = new ServiceEmpresa_Fornecedor($conexao, $Empresa_Fornecedor);
	$serviceEmpresa_Fornecedor->Salvar();

}






function buscarEmpresa_Fornecedor($texto){

	$Empresa_Fornecedor = new Empresa_Fornecedor();
	$Empresa_Fornecedor->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceEmpresa_Fornecedor($conexao, $Empresa_Fornecedor);
	$resultado = $service->buscarEmpresa_Fornecedor();
	return $resultado;
}


 ?>