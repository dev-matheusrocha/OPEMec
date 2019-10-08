<?php 





class Parcela{

	private $ParcelaId;
	private $ParcelaValor;
	private $ParcelaData;
	private $ManutencaoId;



	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
} 



class ServiceParcela{
	private $conexao;
	private $tarefa;
	public function __construct(Conexao $conexao,Parcela $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}



	
	
	public function idParcela(){
		$quary = "SELECT * FROM `Parcela` WHERE ParcelaId = ? ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ParcelaId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);


	}


	public function excluirParcela(){
		$quary = "DELETE FROM `Parcela` WHERE ParcelaId = ? ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ParcelaId'));
		$stmt->execute();
	}


	public function inserevalor(){
		$quary = "INSERT INTO `Parcela` (`ParcelaId`, 
		`ParcelaValor`, 
		`ParcelaData`, 
		`ManutencaoId`) 
		VALUES (NULL,
		 ? ,
		 CURRENT_TIMESTAMP,
		 ? ) ";
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ParcelaValor'));
		$stmt->bindValue(2, $this->tarefa->__get('ManutencaoId'));
		$stmt->execute();

		$quary =  "SELECT * FROM `Parcela` ORDER BY ParcelaId DESC LIMIT 1";
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		$aux = $stmt->fetchAll(PDO::FETCH_OBJ);
		if ( isset($aux[0]->ParcelaValor) &&  $aux[0]->ParcelaValor ==  $this->tarefa->__get('ParcelaValor')) {
			echo '1';
		}else{
			echo '0';
		}	

	}
	
	public function getparcelas(){
		$quary = "SELECT * FROM `Parcela` WHERE ManutencaoId = ? ";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ManutencaoId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}



	public function total(){
		$quary = "SELECT SUM(p.ParcelaValor) as total FROM `Parcela` as p WHERE p.ManutencaoId = ? ";

		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('ManutencaoId'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function Salvar(){
		$quary = "INSERT INTO `Parcela` 
			(`ParcelaId`, 
			`ParcelaNome`, 
			`ParcelaCep`, 
			`ParcelaCpfCnpj`, 
			`ParcelaAtivo`, 
			`OficinaId`, 
			`ParcelaDataCadastro`, 
			`ParcelaNumeroCasa`,
			`ParcelaObs`,
			`ParcelaEndereco`,
			`ParcelaBairro`) 
		 VALUES (NULL,
		 	 'ParcelaNome',
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
		$stmt->bindValue(1, $this->tarefa->__get('ParcelaNome'));
		$stmt->bindValue(2, $this->tarefa->__get('ParcelaCep'));
		$stmt->bindValue(3, $this->tarefa->__get('ParcelaCpfCnpj'));
		$stmt->bindValue(4, $this->tarefa->__get('ParcelaAtivo'));
		$stmt->bindValue(5, $this->tarefa->__get('ParcelaNumeroCasa'));
		$stmt->bindValue(6, $this->tarefa->__get('obs'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

function salvarParcela($lista){
	
	$var = json_decode($lista['obj']);
	$Parcela = new Parcela();
	$Parcela->__set('ParcelaNome',strtoupper($var[0]->value));
	$Parcela->__set('ParcelaCep',strtoupper($var[2]->value));
	$Parcela->__set('ParcelaCpfCnpj',strtoupper($var[1]->value));
	$Parcela->__set('ParcelaAtivo','1');
	$Parcela->__set('OficinaId',$_SESSION['OficinaId']);
	//$Parcela->__set('ParcelaDataCadastro',strtoupper($var[0]->value));
	$Parcela->__set('ParcelaNumeroCasa',strtoupper($var[4]->value));
	$Parcela->__set('ParcelaObs',strtoupper($var[5]->value));
	$Parcela->__set('ParcelaEndereco',strtoupper($var[3]->value));
	$Parcela->__set('ParcelaBairro',strtoupper($var[0]->value));

	$conexao = new Conexao();
	$serviceParcela = new ServiceParcela($conexao, $Parcela);
	$serviceParcela->Salvar();

}




function total($idpedido){
	$tt = new Parcela();
	$tt->__set('ManutencaoId', $idpedido );
	$conexao = new Conexao();
	$service = new ServiceParcela($conexao, $tt);
	$ls = $service->total();
	return $ls;
	


}
function getparcelas($idpedido){
	$tt = new Parcela();
	$tt->__set('ManutencaoId', $idpedido );
	$conexao = new Conexao();
	$service = new ServiceParcela($conexao, $tt);
	$ls = $service->getparcelas();
	return $ls;
	


}


function inserevalor($idpedido, $valor){

	$Pedido = new Parcela();
	$Pedido->__set('ManutencaoId',$idpedido);
	$Pedido->__set('ParcelaValor',$valor);
	$conexao = new Conexao();
	$service = new ServiceParcela($conexao, $Pedido);
	$service->inserevalor();
	
}


function buscarParcela($texto){

	$Parcela = new Parcela();
	$Parcela->__set('nome', strtoupper($texto));
	$conexao = new Conexao();
	$service = new ServiceParcela($conexao, $Parcela);
	$resultado = $service->buscarParcela();
	return $resultado;
}


function excluirParcela($id){

	$Parcela = new Parcela();
	$Parcela->__set('ParcelaId', $id);
	$service = new ServiceParcela(new Conexao(), $Parcela);
	$idParcela = $service->idParcela();

	$resultado = $service->excluirParcela();
	return $idParcela;
}




 ?>