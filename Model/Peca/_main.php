<?php 





class Peca{
	private $PecaId;
	private $PecaNome;
	private $EmpresaId;
	private $PecaEstoque;
	private $PecaValor;
	private $FornecedorId;
	private $PecaObs;


	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServicePeca{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Peca $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarPeca(){
		$quary = "SELECT * FROM `tb_Peca` 
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
		$quary = "INSERT INTO `Peca` 
			(`PecaId`, 
			`PecaNome`, 
			`PecaCep`, 
			`PecaCpfCnpj`, 
			`PecaAtivo`, 
			`OficinaId`, 
			`PecaDataCadastro`, 
			`PecaNumeroCasa`,
			`PecaObs`,
			`PecaEndereco`,
			`PecaBairro`) 
		 VALUES (NULL,
		 	 'PecaNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('PecaNome'));
		$stmt->bindValue(2, $this->tarefa->__get('PecaCep'));
		$stmt->bindValue(3, $this->tarefa->__get('PecaCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('PecaAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('PecaNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarPeca($lista){
	
	$var = json_decode($lista['obj']);
	$Peca = new Peca();
	$Peca->__set('PecaNome',strtoupper($var[0]->value));
	$Peca->__set('PecaCep',strtoupper($var[2]->value));
	$Peca->__set('PecaCpfCnpj',strtoupper($var[1]->value));
	$Peca->__set('PecaAtivo','1');
	$Peca->__set('OficinaId',$_SESSION['OficinaId']);
	//$Peca->__set('PecaDataCadastro',strtoupper($var[0]->value));
	$Peca->__set('PecaNumeroCasa',strtoupper($var[4]->value));
	$Peca->__set('PecaObs',strtoupper($var[5]->value));
	$Peca->__set('PecaEndereco',strtoupper($var[3]->value));
	$Peca->__set('PecaBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$servicePeca = new ServicePeca($conexao, $Peca);
	$servicePeca->Salvar();

}






function buscarPeca($texto){

	$Peca = new Peca();
	$Peca->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServicePeca($conexao, $Peca);
	$resultado = $service->buscarPeca();
	return $resultado;
}


 ?>