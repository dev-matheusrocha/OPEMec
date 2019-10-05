<?php 





class Oficina_Cliente{

	private $OficinaId;
	private $ClienteId;
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceOficina_Cliente{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Oficina_Cliente $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarOficina_Cliente(){
		$quary = "SELECT * FROM `tb_Oficina_Cliente` 
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
		$quary = "INSERT INTO `Oficina_Cliente` 
			(`Oficina_ClienteId`, 
			`Oficina_ClienteNome`, 
			`Oficina_ClienteCep`, 
			`Oficina_ClienteCpfCnpj`, 
			`Oficina_ClienteAtivo`, 
			`OficinaId`, 
			`Oficina_ClienteDataCadastro`, 
			`Oficina_ClienteNumeroCasa`,
			`Oficina_ClienteObs`,
			`Oficina_ClienteEndereco`,
			`Oficina_ClienteBairro`) 
		 VALUES (NULL,
		 	 'Oficina_ClienteNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('Oficina_ClienteNome'));
		$stmt->bindValue(2, $this->tarefa->__get('Oficina_ClienteCep'));
		$stmt->bindValue(3, $this->tarefa->__get('Oficina_ClienteCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('Oficina_ClienteAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('Oficina_ClienteNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarOficina_Cliente($lista){
	
	$var = json_decode($lista['obj']);
	$Oficina_Cliente = new Oficina_Cliente();
	$Oficina_Cliente->__set('Oficina_ClienteNome',strtoupper($var[0]->value));
	$Oficina_Cliente->__set('Oficina_ClienteCep',strtoupper($var[2]->value));
	$Oficina_Cliente->__set('Oficina_ClienteCpfCnpj',strtoupper($var[1]->value));
	$Oficina_Cliente->__set('Oficina_ClienteAtivo','1');
	$Oficina_Cliente->__set('OficinaId',$_SESSION['OficinaId']);
	//$Oficina_Cliente->__set('Oficina_ClienteDataCadastro',strtoupper($var[0]->value));
	$Oficina_Cliente->__set('Oficina_ClienteNumeroCasa',strtoupper($var[4]->value));
	$Oficina_Cliente->__set('Oficina_ClienteObs',strtoupper($var[5]->value));
	$Oficina_Cliente->__set('Oficina_ClienteEndereco',strtoupper($var[3]->value));
	$Oficina_Cliente->__set('Oficina_ClienteBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceOficina_Cliente = new ServiceOficina_Cliente($conexao, $Oficina_Cliente);
	$serviceOficina_Cliente->Salvar();

}






function buscarOficina_Cliente($texto){

	$Oficina_Cliente = new Oficina_Cliente();
	$Oficina_Cliente->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceOficina_Cliente($conexao, $Oficina_Cliente);
	$resultado = $service->buscarOficina_Cliente();
	return $resultado;
}


 ?>