<?php 





class Modelo{

	private $ModeloId;
	private $ModeloNome;
	private $MarcaId;
	private $ModeloObs;




	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceModelo{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Modelo $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	public function buscarModelo(){
		$quary = "SELECT * FROM `Modelo` WHERE ModeloNome like ? ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ModeloNome'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	


	
	


	public function Salvar(){
		$quary = "INSERT INTO `Modelo` 
			(`ModeloId`, 
			`ModeloNome`, 
			`ModeloCep`, 
			`ModeloCpfCnpj`, 
			`ModeloAtivo`, 
			`OficinaId`, 
			`ModeloDataCadastro`, 
			`ModeloNumeroCasa`,
			`ModeloObs`,
			`ModeloEndereco`,
			`ModeloBairro`) 
		 VALUES (NULL,
		 	 'ModeloNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('ModeloNome'));
		$stmt->bindValue(2, $this->tarefa->__get('ModeloCep'));
		$stmt->bindValue(3, $this->tarefa->__get('ModeloCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('ModeloAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('ModeloNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarModelo($lista){
	
	$var = json_decode($lista['obj']);
	$Modelo = new Modelo();
	$Modelo->__set('ModeloNome',strtoupper($var[0]->value));
	$Modelo->__set('ModeloCep',strtoupper($var[2]->value));
	$Modelo->__set('ModeloCpfCnpj',strtoupper($var[1]->value));
	$Modelo->__set('ModeloAtivo','1');
	$Modelo->__set('OficinaId',$_SESSION['OficinaId']);
	//$Modelo->__set('ModeloDataCadastro',strtoupper($var[0]->value));
	$Modelo->__set('ModeloNumeroCasa',strtoupper($var[4]->value));
	$Modelo->__set('ModeloObs',strtoupper($var[5]->value));
	$Modelo->__set('ModeloEndereco',strtoupper($var[3]->value));
	$Modelo->__set('ModeloBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceModelo = new ServiceModelo($conexao, $Modelo);
	$serviceModelo->Salvar();

}





function buscarModelo($texto){

	$Modelo = new Modelo();
	$Modelo->__set('ModeloNome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceModelo($conexao, $Modelo);
	$resultado = $service->buscarModelo();
	return $resultado;
}


 ?>