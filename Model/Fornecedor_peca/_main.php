<?php 




class Fornecedor_peca{

	private $FornecedorId;
	private $EmpresaId;


	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceFornecedor_peca{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Fornecedor_peca $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarFornecedor_peca(){
		$quary = "SELECT * FROM `tb_Fornecedor_peca` 
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
		$quary = "INSERT INTO `Fornecedor_peca` 
			(`Fornecedor_pecaId`, 
			`Fornecedor_pecaNome`, 
			`Fornecedor_pecaCep`, 
			`Fornecedor_pecaCpfCnpj`, 
			`Fornecedor_pecaAtivo`, 
			`OficinaId`, 
			`Fornecedor_pecaDataCadastro`, 
			`Fornecedor_pecaNumeroCasa`,
			`Fornecedor_pecaObs`,
			`Fornecedor_pecaEndereco`,
			`Fornecedor_pecaBairro`) 
		 VALUES (NULL,
		 	 'Fornecedor_pecaNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('Fornecedor_pecaNome'));
		$stmt->bindValue(2, $this->tarefa->__get('Fornecedor_pecaCep'));
		$stmt->bindValue(3, $this->tarefa->__get('Fornecedor_pecaCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('Fornecedor_pecaAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('Fornecedor_pecaNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarFornecedor_peca($lista){
	
	$var = json_decode($lista['obj']);
	$Fornecedor_peca = new Fornecedor_peca();
	$Fornecedor_peca->__set('Fornecedor_pecaNome',strtoupper($var[0]->value));
	$Fornecedor_peca->__set('Fornecedor_pecaCep',strtoupper($var[2]->value));
	$Fornecedor_peca->__set('Fornecedor_pecaCpfCnpj',strtoupper($var[1]->value));
	$Fornecedor_peca->__set('Fornecedor_pecaAtivo','1');
	$Fornecedor_peca->__set('OficinaId',$_SESSION['OficinaId']);
	//$Fornecedor_peca->__set('Fornecedor_pecaDataCadastro',strtoupper($var[0]->value));
	$Fornecedor_peca->__set('Fornecedor_pecaNumeroCasa',strtoupper($var[4]->value));
	$Fornecedor_peca->__set('Fornecedor_pecaObs',strtoupper($var[5]->value));
	$Fornecedor_peca->__set('Fornecedor_pecaEndereco',strtoupper($var[3]->value));
	$Fornecedor_peca->__set('Fornecedor_pecaBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceFornecedor_peca = new ServiceFornecedor_peca($conexao, $Fornecedor_peca);
	$serviceFornecedor_peca->Salvar();

}






function buscarFornecedor_peca($texto){

	$Fornecedor_peca = new Fornecedor_peca();
	$Fornecedor_peca->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceFornecedor_peca($conexao, $Fornecedor_peca);
	$resultado = $service->buscarFornecedor_peca();
	return $resultado;
}


 ?>