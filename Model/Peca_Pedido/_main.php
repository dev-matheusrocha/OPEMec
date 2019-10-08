<?php 





class Peca_Pedido{
	private $PecaId;
	private $ManutencaoId;
	private $PecaValorVenda;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServicePeca_Pedido{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Peca_Pedido $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	public function getPecas(){
		$quary = "SELECT p.*, pa.* FROM `Peca_Pedido` as p 
		INNER JOIN Peca as pa
		 on (pa.PecaId = p.PecaId ) WHERE p.ManutencaoId = ?  ";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('PecaId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	
	public function buscarPeca_Pedido(){
		$quary = "SELECT * FROM `tb_Peca_Pedido` 
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
		$quary = "INSERT INTO `Peca_Pedido` 
			(`Peca_PedidoId`, 
			`Peca_PedidoNome`, 
			`Peca_PedidoCep`, 
			`Peca_PedidoCpfCnpj`, 
			`Peca_PedidoAtivo`, 
			`OficinaId`, 
			`Peca_PedidoDataCadastro`, 
			`Peca_PedidoNumeroCasa`,
			`Peca_PedidoObs`,
			`Peca_PedidoEndereco`,
			`Peca_PedidoBairro`) 
		 VALUES (NULL,
		 	 'Peca_PedidoNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('Peca_PedidoNome'));
		$stmt->bindValue(2, $this->tarefa->__get('Peca_PedidoCep'));
		$stmt->bindValue(3, $this->tarefa->__get('Peca_PedidoCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('Peca_PedidoAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('Peca_PedidoNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarPeca_Pedido($lista){
	
	$var = json_decode($lista['obj']);
	$Peca_Pedido = new Peca_Pedido();
	$Peca_Pedido->__set('Peca_PedidoNome',strtoupper($var[0]->value));
	$Peca_Pedido->__set('Peca_PedidoCep',strtoupper($var[2]->value));
	$Peca_Pedido->__set('Peca_PedidoCpfCnpj',strtoupper($var[1]->value));
	$Peca_Pedido->__set('Peca_PedidoAtivo','1');
	$Peca_Pedido->__set('OficinaId',$_SESSION['OficinaId']);
	//$Peca_Pedido->__set('Peca_PedidoDataCadastro',strtoupper($var[0]->value));
	$Peca_Pedido->__set('Peca_PedidoNumeroCasa',strtoupper($var[4]->value));
	$Peca_Pedido->__set('Peca_PedidoObs',strtoupper($var[5]->value));
	$Peca_Pedido->__set('Peca_PedidoEndereco',strtoupper($var[3]->value));
	$Peca_Pedido->__set('Peca_PedidoBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$servicePeca_Pedido = new ServicePeca_Pedido($conexao, $Peca_Pedido);
	$servicePeca_Pedido->Salvar();

}






function buscarPeca_Pedido($texto){

	$Peca_Pedido = new Peca_Pedido();
	$Peca_Pedido->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServicePeca_Pedido($conexao, $Peca_Pedido);
	$resultado = $service->buscarPeca_Pedido();
	return $resultado;
}


function getPecas($idpedido){
	$tt = new Peca_Pedido();
	$tt->__set('PecaId', $idpedido );
	$conexao = new Conexao();
	$service = new ServicePeca_Pedido($conexao, $tt);
	$ls = $service->getPecas();
	return $ls;
	


}


 ?>