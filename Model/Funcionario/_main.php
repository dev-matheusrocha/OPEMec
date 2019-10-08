<?php 





class Funcionario{
	private $FuncionarioId;
	private $FuncionarioNome;
	private $FuncionarioCep;
	private $FuncionarioSenha;
	private $FuncionarioCpf;
	private $OficinaId;
	private $FuncionarioAtivo;
	private $FuncionarioAdm;
	
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceFuncionario{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Funcionario $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarFuncionario(){
		$quary = "SELECT * FROM `tb_Funcionario` 
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
		$quary = "INSERT INTO `Funcionario` 
			(`FuncionarioId`, 
			`FuncionarioNome`, 
			`FuncionarioCep`, 
			`FuncionarioCpfCnpj`, 
			`FuncionarioAtivo`, 
			`OficinaId`, 
			`FuncionarioDataCadastro`, 
			`FuncionarioNumeroCasa`,
			`FuncionarioObs`,
			`FuncionarioEndereco`,
			`FuncionarioBairro`) 
		 VALUES (NULL,
		 	 'FuncionarioNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('FuncionarioNome'));
		$stmt->bindValue(2, $this->tarefa->__get('FuncionarioCep'));
		$stmt->bindValue(3, $this->tarefa->__get('FuncionarioCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('FuncionarioAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('FuncionarioNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function validarFuncionario(){
		$quary = 'SELECT * FROM `Funcionario` WHERE FuncionarioCpf = ? and FuncionarioSenha =  ? and FuncionarioAtivo = 1 ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('FuncionarioCpf'));
		$stmt->bindValue(2, $this->tarefa->__get('FuncionarioSenha'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
		
	}

}

function salvarFuncionario($lista){
	
	$var = json_decode($lista['obj']);
	$Funcionario = new Funcionario();
	$Funcionario->__set('FuncionarioNome',strtoupper($var[0]->value));
	$Funcionario->__set('FuncionarioCep',strtoupper($var[2]->value));
	$Funcionario->__set('FuncionarioCpfCnpj',strtoupper($var[1]->value));
	$Funcionario->__set('FuncionarioAtivo','1');
	$Funcionario->__set('OficinaId',$_SESSION['OficinaId']);
	//$Funcionario->__set('FuncionarioDataCadastro',strtoupper($var[0]->value));
	$Funcionario->__set('FuncionarioNumeroCasa',strtoupper($var[4]->value));
	$Funcionario->__set('FuncionarioObs',strtoupper($var[5]->value));
	$Funcionario->__set('FuncionarioEndereco',strtoupper($var[3]->value));
	$Funcionario->__set('FuncionarioBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceFuncionario = new ServiceFuncionario($conexao, $Funcionario);
	$serviceFuncionario->Salvar();

}




function getFuncionario($FuncionarioCpf, $FuncionarioSenha){

	$funcionario = new Funcionario();
	$funcionario->__set('FuncionarioCpf', $FuncionarioCpf);
	$funcionario->__set('FuncionarioSenha', $FuncionarioSenha);
	$conexao = new Conexao();
	$serviceFuncionario = new ServiceFuncionario($conexao, $funcionario);
	$resultado = $serviceFuncionario->validarFuncionario();
	return $resultado;
}



function subirSESSION($ls){
	foreach ($ls as $key => $v) {
		$_SESSION[$key] = $v;
	}
}

 ?>