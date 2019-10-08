<?php 





class Servico{
	
	private $ServicoId;
	private $ManutencaoValorDescricao;
	private $ManutencaoValorAdd;
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceServico{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Servico $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	public function buscarServico(){
		$quary = "SELECT s.*, mv.Manutencaoid 
		FROM `ManutencaoValorAdd` as mv 
		INNER JOIN Servico as s on (s.ServicoId = mv.ServicoId) 
		WHERE mv.Manutencaoid = ?  ";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ServicoId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}



	public function Salvar(){
		$quary = "INSERT INTO `Servico` 
			(`ServicoId`, 
			`ServicoNome`, 
			`ServicoCep`, 
			`ServicoCpfCnpj`, 
			`ServicoAtivo`, 
			`OficinaId`, 
			`ServicoDataCadastro`, 
			`ServicoNumeroCasa`,
			`ServicoObs`,
			`ServicoEndereco`,
			`ServicoBairro`) 
		 VALUES (NULL,
		 	 'ServicoNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('ServicoNome'));
		$stmt->bindValue(2, $this->tarefa->__get('ServicoCep'));
		$stmt->bindValue(3, $this->tarefa->__get('ServicoCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('ServicoAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('ServicoNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarServico($lista){
	
	$var = json_decode($lista['obj']);
	$Servico = new Servico();
	$Servico->__set('ServicoNome',strtoupper($var[0]->value));
	$Servico->__set('ServicoCep',strtoupper($var[2]->value));
	$Servico->__set('ServicoCpfCnpj',strtoupper($var[1]->value));
	$Servico->__set('ServicoAtivo','1');
	$Servico->__set('OficinaId',$_SESSION['OficinaId']);
	//$Servico->__set('ServicoDataCadastro',strtoupper($var[0]->value));
	$Servico->__set('ServicoNumeroCasa',strtoupper($var[4]->value));
	$Servico->__set('ServicoObs',strtoupper($var[5]->value));
	$Servico->__set('ServicoEndereco',strtoupper($var[3]->value));
	$Servico->__set('ServicoBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceServico = new ServiceServico($conexao, $Servico);
	$serviceServico->Salvar();

}






function getservicos($id){

	$Servico = new Servico();
	$Servico->__set('ServicoId', $id );
	$conexao = new Conexao();
	$service = new ServiceServico($conexao, $Servico);
	$resultado = $service->buscarServico();
	return $resultado;
}


 ?>