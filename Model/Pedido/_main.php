<?php 





class Pedido{

	private $VeiculoId;
	private $ManutencaoId;
	private $ManutencaoInicio;
	private $ManutencaoFim;
	private $ManutencaoFinalizada;
	private $ManutencaoPaga;
	private $OficinaId;
	private $PedidoObs;



	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServicePedido{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Pedido $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	
	
	public function listar(){
		session_start();
		$id = 	$_SESSION['OficinaId'];
		$quary = "SELECT c.ClienteNome, v.VeiculoPlaca, p.ManutencaoId from Cliente as c
		INNER JOIN Veiculo as v ON(v.ClienteId = c.ClienteId)
		INNER JOIN Pedido as p ON(p.VeiculoId= v.VeiculoId)
		INNER JOIN Oficina as o ON(o.OficinaId = c.OficinaId)
		WHERE (p.ManutencaoFinalizada = 0 and o.OficinaId = $id )
		 ";
		$stmt = $this->conexao->prepare($quary);
	

		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function Salvar(){
		$quary = "INSERT INTO `Pedido` (`VeiculoId`, 
		`ManutencaoId`, 
		`ManutencaoInicio`, 
		`ManutencaoFim`, 
		`ManutencaoFinalizada`, 
		`ManutencaoPaga`, 
		`OficinaId`, 
		`PedidoObs`) 
		VALUES ( ? , 
			NULL, 
			CURRENT_TIMESTAMP , 
			NULL, 
			'0', 
			'0', 
			 ? , 
			? ); ";
			  
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('VeiculoId'));
		$stmt->bindValue(2, $this->tarefa->__get('OficinaId'));
		$stmt->bindValue(3, $this->tarefa->__get('PedidoObs'));
		$stmt->execute();
		$stquary ="SELECT * FROM `Pedido` ORDER by ManutencaoId DESC LIMIT 1 ";
		$st = $this->conexao->prepare($stquary);
		$st->execute();
		return $st->fetchAll(PDO::FETCH_OBJ);

	}
}

function iniciarManutencao($lista){
	$var = json_decode($lista);
	$Pedido = new Pedido();
	$Pedido->__set('VeiculoId',strtoupper($var->VeiculoId));
	$Pedido->__set('PedidoObs',strtoupper($var->PedidoObs));
	session_start();
	$Pedido->__set('OficinaId',$_SESSION['OficinaId']);
	$servicePedido = new ServicePedido(new Conexao(), $Pedido);
	$pedidoCadastrado = $servicePedido->Salvar();
	if ($pedidoCadastrado[0]->VeiculoId == $var->VeiculoId) {
		echo "1";
	}else{
		echo "0";
	}
}


function getManutencaoAndamento(){
	$Pedido = new Pedido();


	$conexao = new Conexao();
	$service = new ServicePedido($conexao, $Pedido);
	$ls = $service->listar();
	//print_r($ls);
	header('Content-Type: application/json');
	echo json_encode($ls);


}



 ?>