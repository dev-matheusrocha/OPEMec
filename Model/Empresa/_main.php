<?php 

class Oficina{

	private $OficinaId;
	private $OficinaNome;
	private $OficinacoCep;
	private $EmpresaId;
	private $OficinaAtiva;
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceOficina{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Oficina $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarOficina(){
		$quary = "SELECT * FROM `tb_Oficina` 
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
		$quary = "INSERT INTO `Oficina` 
			(`OficinaId`, 
			`OficinaNome`, 
			`OficinaCep`, 
			`OficinaCpfCnpj`, 
			`OficinaAtivo`, 
			`OficinaId`, 
			`OficinaDataCadastro`, 
			`OficinaNumeroCasa`,
			`OficinaObs`,
			`OficinaEndereco`,
			`OficinaBairro`) 
		 VALUES (NULL,
		 	 'OficinaNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('OficinaNome'));
		$stmt->bindValue(2, $this->tarefa->__get('OficinaCep'));
		$stmt->bindValue(3, $this->tarefa->__get('OficinaCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('OficinaAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('OficinaNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarOficina($lista){
	
	$var = json_decode($lista['obj']);
	$Oficina = new Oficina();
	$Oficina->__set('OficinaNome',strtoupper($var[0]->value));
	$Oficina->__set('OficinaCep',strtoupper($var[2]->value));
	$Oficina->__set('OficinaCpfCnpj',strtoupper($var[1]->value));
	$Oficina->__set('OficinaAtivo','1');
	$Oficina->__set('OficinaId',$_SESSION['OficinaId']);
	//$Oficina->__set('OficinaDataCadastro',strtoupper($var[0]->value));
	$Oficina->__set('OficinaNumeroCasa',strtoupper($var[4]->value));
	$Oficina->__set('OficinaObs',strtoupper($var[5]->value));
	$Oficina->__set('OficinaEndereco',strtoupper($var[3]->value));
	$Oficina->__set('OficinaBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceOficina = new ServiceOficina($conexao, $Oficina);
	$serviceOficina->Salvar();

}






function buscarOficina($texto){

	$Oficina = new Oficina();
	$Oficina->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceOficina($conexao, $Oficina);
	$resultado = $service->buscarOficina();
	return $resultado;
}


 ?>