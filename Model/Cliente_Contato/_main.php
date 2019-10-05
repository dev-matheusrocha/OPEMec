<?php 





class Cliente_Contato{
	
	private $ClienteId;
	private $ClienteNumeroContato;
	private $ClienteEmail;
	private $ClienteApelido;
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceCliente_Contato{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Cliente_Contato $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	public function Salvar(){
	
		$quary = "INSERT INTO `Cliente_Contato` (
			`ClienteId`, 
			`ClienteNumeroContato`, 
			`ClienteEmail`, 
			`ClienteApelido`)
			VALUES (
				? , 
				? , 
				? , 
				? );";
				
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ClienteId'));
		$stmt->bindValue(2, $this->tarefa->__get('ClienteNumeroContato'));
		$stmt->bindValue(3, $this->tarefa->__get('ClienteEmail'));
		$stmt->bindValue(4, $this->tarefa->__get('ClienteApelido'));
		$stmt->execute();
	}
}

function salvarCliente_Contato($lista){
	
	$var = json_decode($lista['obj']);
	$Cliente_Contato = new Cliente_Contato();
	$Cliente_Contato->__set('Cliente_ContatoNome',strtoupper($var[0]->value));
	$Cliente_Contato->__set('Cliente_ContatoCep',strtoupper($var[2]->value));
	$Cliente_Contato->__set('Cliente_ContatoCpfCnpj',strtoupper($var[1]->value));
	$Cliente_Contato->__set('Cliente_ContatoAtivo','1');
	$Cliente_Contato->__set('OficinaId',$_SESSION['OficinaId']);
	//$Cliente_Contato->__set('Cliente_ContatoDataCadastro',strtoupper($var[0]->value));
	$Cliente_Contato->__set('Cliente_ContatoNumeroCasa',strtoupper($var[4]->value));
	$Cliente_Contato->__set('Cliente_ContatoObs',strtoupper($var[5]->value));
	$Cliente_Contato->__set('Cliente_ContatoEndereco',strtoupper($var[3]->value));
	$Cliente_Contato->__set('Cliente_ContatoBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceCliente_Contato = new ServiceCliente_Contato($conexao, $Cliente_Contato);
	$serviceCliente_Contato->Salvar();

}






function buscarCliente_Contato($texto){

	$Cliente_Contato = new Cliente_Contato();
	$Cliente_Contato->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceCliente_Contato($conexao, $Cliente_Contato);
	$resultado = $service->buscarCliente_Contato();
	return $resultado;
}


 ?>