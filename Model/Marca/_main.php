<?php 





class Marca{
	private $MarcaId;
	private $MarcaNome;
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceMarca{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Marca $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarMarca(){
		$quary = "SELECT * FROM `tb_Marca` 
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
		$quary = "INSERT INTO `Marca` 
			(`MarcaId`, 
			`MarcaNome`, 
			`MarcaCep`, 
			`MarcaCpfCnpj`, 
			`MarcaAtivo`, 
			`OficinaId`, 
			`MarcaDataCadastro`, 
			`MarcaNumeroCasa`,
			`MarcaObs`,
			`MarcaEndereco`,
			`MarcaBairro`) 
		 VALUES (NULL,
		 	 'MarcaNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('MarcaNome'));
		$stmt->bindValue(2, $this->tarefa->__get('MarcaCep'));
		$stmt->bindValue(3, $this->tarefa->__get('MarcaCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('MarcaAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('MarcaNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarMarca($lista){
	
	$var = json_decode($lista['obj']);
	$Marca = new Marca();
	$Marca->__set('MarcaNome',strtoupper($var[0]->value));
	$Marca->__set('MarcaCep',strtoupper($var[2]->value));
	$Marca->__set('MarcaCpfCnpj',strtoupper($var[1]->value));
	$Marca->__set('MarcaAtivo','1');
	$Marca->__set('OficinaId',$_SESSION['OficinaId']);
	//$Marca->__set('MarcaDataCadastro',strtoupper($var[0]->value));
	$Marca->__set('MarcaNumeroCasa',strtoupper($var[4]->value));
	$Marca->__set('MarcaObs',strtoupper($var[5]->value));
	$Marca->__set('MarcaEndereco',strtoupper($var[3]->value));
	$Marca->__set('MarcaBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceMarca = new ServiceMarca($conexao, $Marca);
	$serviceMarca->Salvar();

}






function buscarMarca($texto){

	$Marca = new Marca();
	$Marca->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceMarca($conexao, $Marca);
	$resultado = $service->buscarMarca();
	return $resultado;
}


 ?>