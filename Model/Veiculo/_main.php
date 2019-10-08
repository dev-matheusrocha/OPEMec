<?php 





class Veiculo{
	private $VeiculoId;
	private $VeiculoPlaca;
	private $ClienteId;
	private $VeiculoAno;
	private $ModeloId;



	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceVeiculo{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Veiculo $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}


	
	public function inserirVeiculo(){
		$quary = " INSERT INTO `Veiculo` (`VeiculoPlaca`, 
		`ClienteId`, 
		`VeiculoAno`, 
		`ModeloId`, 
		`VeiculoRenavam`) 

		VALUES ( ? , 
		? , 
		? , 
		? , 
		? )	";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('VeiculoPlaca'));
		$stmt->bindValue(2, $this->tarefa->__get('ClienteId'));
		$stmt->bindValue(3, $this->tarefa->__get('VeiculoAno'));
		$stmt->bindValue(4, $this->tarefa->__get('ModeloId'));
		$stmt->bindValue(5, $this->tarefa->__get('VeiculoRenavam'));
		$stmt->execute();


		$quary = "SELECT * FROM `Veiculo` ORDER BY VeiculoId DESC LIMIT 1";

		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		$aux = $stmt->fetchAll(PDO::FETCH_OBJ);
		if (isset($aux[0]->VeiculoPlaca) && $aux[0]->VeiculoPlaca == $this->tarefa->__get('VeiculoPlaca')) {
			echo '1';
		}else{
			echo '0';
		}	
	
	}

	public function buscarVeiculo(){
		$quary = "SELECT * FROM `Veiculo` WHERE ClienteId = ? ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}


	public function Salvar(){
		$quary = "INSERT INTO `Veiculo` 
			(`VeiculoId`, 
			`VeiculoNome`, 
			`VeiculoCep`, 
			`VeiculoCpfCnpj`, 
			`VeiculoAtivo`, 
			`OficinaId`, 
			`VeiculoDataCadastro`, 
			`VeiculoNumeroCasa`,
			`VeiculoObs`,
			`VeiculoEndereco`,
			`VeiculoBairro`) 
		 VALUES (NULL,
		 	 'VeiculoNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('VeiculoNome'));
		$stmt->bindValue(2, $this->tarefa->__get('VeiculoCep'));
		$stmt->bindValue(3, $this->tarefa->__get('VeiculoCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('VeiculoAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('VeiculoNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarVeiculo($lista){
	
	$var = json_decode($lista['obj']);
	$Veiculo = new Veiculo();
	$Veiculo->__set('VeiculoNome',strtoupper($var[0]->value));
	$Veiculo->__set('VeiculoCep',strtoupper($var[2]->value));
	$Veiculo->__set('VeiculoCpfCnpj',strtoupper($var[1]->value));
	$Veiculo->__set('VeiculoAtivo','1');
	$Veiculo->__set('OficinaId',$_SESSION['OficinaId']);
	//$Veiculo->__set('VeiculoDataCadastro',strtoupper($var[0]->value));
	$Veiculo->__set('VeiculoNumeroCasa',strtoupper($var[4]->value));
	$Veiculo->__set('VeiculoObs',strtoupper($var[5]->value));
	$Veiculo->__set('VeiculoEndereco',strtoupper($var[3]->value));
	$Veiculo->__set('VeiculoBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceVeiculo = new ServiceVeiculo($conexao, $Veiculo);
	$serviceVeiculo->Salvar();

}



function inserirVeiculo($v){
	$Veiculo = new Veiculo();
	
	$Veiculo->__set('VeiculoPlaca',$v->VeiculoPlaca);
	$Veiculo->__set('ClienteId',$v->ClienteId);
	$Veiculo->__set('VeiculoAno',$v->VeiculoAno);
	$Veiculo->__set('ModeloId',$v->ModeloId);
	$Veiculo->__set('VeiculoRenavam',$v->VeiculoRenavam);

	$conexao = new Conexao();
	$service = new ServiceVeiculo($conexao, $Veiculo);
	$resultado = $service->inserirVeiculo();


}


function veiculosCliente($texto){

	$Veiculo = new Veiculo();
	
	$Veiculo->__set('ClienteId',  str_replace('"', '', $texto));
	$conexao = new Conexao();
	$service = new ServiceVeiculo($conexao, $Veiculo);
	$resultado = $service->buscarVeiculo();
	select($resultado, "Selecionar veiculo", array("VeiculoPlaca") , "Selecione o veiculo", "VeiculoId", "btn btn-light", "VeiculoId", "");

	
}


 ?>