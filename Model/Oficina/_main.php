<?php 





class Empresa{
	private $EmpresaId;
	private $EmpresaNome;
	private $EmpresaCnpj;
	private $EmpresaAtiva;	
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceEmpresa{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Empresa $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarEmpresa(){
		$quary = "SELECT * FROM `tb_Empresa` 
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
		$quary = "INSERT INTO `Empresa` 
			(`EmpresaId`, 
			`EmpresaNome`, 
			`EmpresaCep`, 
			`EmpresaCpfCnpj`, 
			`EmpresaAtivo`, 
			`OficinaId`, 
			`EmpresaDataCadastro`, 
			`EmpresaNumeroCasa`,
			`EmpresaObs`,
			`EmpresaEndereco`,
			`EmpresaBairro`) 
		 VALUES (NULL,
		 	 'EmpresaNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('EmpresaNome'));
		$stmt->bindValue(2, $this->tarefa->__get('EmpresaCep'));
		$stmt->bindValue(3, $this->tarefa->__get('EmpresaCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('EmpresaAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('EmpresaNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarEmpresa($lista){
	
	$var = json_decode($lista['obj']);
	$Empresa = new Empresa();
	$Empresa->__set('EmpresaNome',strtoupper($var[0]->value));
	$Empresa->__set('EmpresaCep',strtoupper($var[2]->value));
	$Empresa->__set('EmpresaCpfCnpj',strtoupper($var[1]->value));
	$Empresa->__set('EmpresaAtivo','1');
	$Empresa->__set('OficinaId',$_SESSION['OficinaId']);
	//$Empresa->__set('EmpresaDataCadastro',strtoupper($var[0]->value));
	$Empresa->__set('EmpresaNumeroCasa',strtoupper($var[4]->value));
	$Empresa->__set('EmpresaObs',strtoupper($var[5]->value));
	$Empresa->__set('EmpresaEndereco',strtoupper($var[3]->value));
	$Empresa->__set('EmpresaBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceEmpresa = new ServiceEmpresa($conexao, $Empresa);
	$serviceEmpresa->Salvar();

}






function buscarEmpresa($texto){

	$Empresa = new Empresa();
	$Empresa->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceEmpresa($conexao, $Empresa);
	$resultado = $service->buscarEmpresa();
	return $resultado;
}


 ?>