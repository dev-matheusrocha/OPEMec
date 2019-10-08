<?php 





class ManutencaoValorAdd{

	private $ManutencaoId;
	private $ServicoId;	


	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceManutencaoValorAdd{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,ManutencaoValorAdd $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarManutencaoValorAdd(){
		$quary = "SELECT * FROM `tb_ManutencaoValorAdd` 
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
		$quary = "INSERT INTO `ManutencaoValorAdd` 
			(`ManutencaoValorAddId`, 
			`ManutencaoValorAddNome`, 
			`ManutencaoValorAddCep`, 
			`ManutencaoValorAddCpfCnpj`, 
			`ManutencaoValorAddAtivo`, 
			`OficinaId`, 
			`ManutencaoValorAddDataCadastro`, 
			`ManutencaoValorAddNumeroCasa`,
			`ManutencaoValorAddObs`,
			`ManutencaoValorAddEndereco`,
			`ManutencaoValorAddBairro`) 
		 VALUES (NULL,
		 	 'ManutencaoValorAddNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('ManutencaoValorAddNome'));
		$stmt->bindValue(2, $this->tarefa->__get('ManutencaoValorAddCep'));
		$stmt->bindValue(3, $this->tarefa->__get('ManutencaoValorAddCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('ManutencaoValorAddAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('ManutencaoValorAddNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarManutencaoValorAdd($lista){
	
	$var = json_decode($lista['obj']);
	$ManutencaoValorAdd = new ManutencaoValorAdd();
	$ManutencaoValorAdd->__set('ManutencaoValorAddNome',strtoupper($var[0]->value));
	$ManutencaoValorAdd->__set('ManutencaoValorAddCep',strtoupper($var[2]->value));
	$ManutencaoValorAdd->__set('ManutencaoValorAddCpfCnpj',strtoupper($var[1]->value));
	$ManutencaoValorAdd->__set('ManutencaoValorAddAtivo','1');
	$ManutencaoValorAdd->__set('OficinaId',$_SESSION['OficinaId']);
	//$ManutencaoValorAdd->__set('ManutencaoValorAddDataCadastro',strtoupper($var[0]->value));
	$ManutencaoValorAdd->__set('ManutencaoValorAddNumeroCasa',strtoupper($var[4]->value));
	$ManutencaoValorAdd->__set('ManutencaoValorAddObs',strtoupper($var[5]->value));
	$ManutencaoValorAdd->__set('ManutencaoValorAddEndereco',strtoupper($var[3]->value));
	$ManutencaoValorAdd->__set('ManutencaoValorAddBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceManutencaoValorAdd = new ServiceManutencaoValorAdd($conexao, $ManutencaoValorAdd);
	$serviceManutencaoValorAdd->Salvar();

}






function buscarManutencaoValorAdd($texto){

	$ManutencaoValorAdd = new ManutencaoValorAdd();
	$ManutencaoValorAdd->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceManutencaoValorAdd($conexao, $ManutencaoValorAdd);
	$resultado = $service->buscarManutencaoValorAdd();
	return $resultado;
}


 ?>