<?php 





class Peca_Modelo{
	private $PecaId;
	private $ModeloId;
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServicePeca_Modelo{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Peca_Modelo $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarPeca_Modelo(){
		$quary = "SELECT * FROM `tb_Peca_Modelo` 
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
		$quary = "INSERT INTO `Peca_Modelo` 
			(`Peca_ModeloId`, 
			`Peca_ModeloNome`, 
			`Peca_ModeloCep`, 
			`Peca_ModeloCpfCnpj`, 
			`Peca_ModeloAtivo`, 
			`OficinaId`, 
			`Peca_ModeloDataCadastro`, 
			`Peca_ModeloNumeroCasa`,
			`Peca_ModeloObs`,
			`Peca_ModeloEndereco`,
			`Peca_ModeloBairro`) 
		 VALUES (NULL,
		 	 'Peca_ModeloNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('Peca_ModeloNome'));
		$stmt->bindValue(2, $this->tarefa->__get('Peca_ModeloCep'));
		$stmt->bindValue(3, $this->tarefa->__get('Peca_ModeloCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('Peca_ModeloAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('Peca_ModeloNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarPeca_Modelo($lista){
	
	$var = json_decode($lista['obj']);
	$Peca_Modelo = new Peca_Modelo();
	$Peca_Modelo->__set('Peca_ModeloNome',strtoupper($var[0]->value));
	$Peca_Modelo->__set('Peca_ModeloCep',strtoupper($var[2]->value));
	$Peca_Modelo->__set('Peca_ModeloCpfCnpj',strtoupper($var[1]->value));
	$Peca_Modelo->__set('Peca_ModeloAtivo','1');
	$Peca_Modelo->__set('OficinaId',$_SESSION['OficinaId']);
	//$Peca_Modelo->__set('Peca_ModeloDataCadastro',strtoupper($var[0]->value));
	$Peca_Modelo->__set('Peca_ModeloNumeroCasa',strtoupper($var[4]->value));
	$Peca_Modelo->__set('Peca_ModeloObs',strtoupper($var[5]->value));
	$Peca_Modelo->__set('Peca_ModeloEndereco',strtoupper($var[3]->value));
	$Peca_Modelo->__set('Peca_ModeloBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$servicePeca_Modelo = new ServicePeca_Modelo($conexao, $Peca_Modelo);
	$servicePeca_Modelo->Salvar();

}






function buscarPeca_Modelo($texto){

	$Peca_Modelo = new Peca_Modelo();
	$Peca_Modelo->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServicePeca_Modelo($conexao, $Peca_Modelo);
	$resultado = $service->buscarPeca_Modelo();
	return $resultado;
}


 ?>