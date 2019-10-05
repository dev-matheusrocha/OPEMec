<?php 





class TarefaServiceParcela{

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao,Parcela $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	

	public function darBaixaNoPedidoComoPago(){ // READY
		$quary = 'UPDATE tb_pedido SET pago = 1 WHERE id_pedido = ? ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('id_pedido'));
		$stmt->execute();
		

		

		
	}

	public function inserirParcela(){ // READY
		$quary = 'INSERT INTO tb_parcelas (valor,id_pedido,id_cliente) 
		VALUES ( ? , ? , ? )';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('valor'));
		$stmt->bindValue(2, $this->tarefa->__get('id_pedido'));
		$stmt->bindValue(3, $this->tarefa->__get('id_cliente'));
		$stmt->execute();
		

		

		
	}

	
	public function recuperarSomaDoPedido(){ // READY
		$quary = 'SELECT sum(valor) as total FROM `tb_parcelas` WHERE id_pedido = ?';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('id_pedido'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);

	}





}

class TarefaServicePedido{

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao,Pedido $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}


	public function cadastrarPedido(){ // READY
		$quary = 'INSERT INTO `tb_pedido` (`id_cliente`, `id_veiculo`, `id_status`, `valor`, pago) VALUES ( ? , ? , 1 , ?, 0 ) ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(2, $this->tarefa->__get('id_veiculo'));
		$stmt->bindValue(1, $this->tarefa->__get('id_cliente'));
		$stmt->bindValue(3, $this->tarefa->__get('valor'));
		$stmt->execute();
		


		
	}

	
	public function parcelas(){ // READY
		$quary = 'SELECT * FROM `tb_parcelas` WHERE id_pedido = ? ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('id_pedido'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
		


		
	}


	public function recuperarTudoSobrePedido(){ // READY
		$quary = 'SELECT p.*, c.id_cliente, c.nome, v.placa, c.celular FROM `tb_pedido` as p inner JOIN tb_cliente as c on (c.id_cliente = p.id_cliente) INNER JOIN tb_veiculo as v on (v.id_veiculo = p.id_veiculo) WHERE p.id_pedido = ? ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('id_pedido'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
		


		
	}

	
	public function recuperar_pedidos_a_receber(){ // READY
		$quary = 'SELECT p.*, c.id_cliente, c.nome, v.placa FROM `tb_pedido` as p inner JOIN tb_cliente as c on (c.id_cliente = p.id_cliente) INNER JOIN tb_veiculo as v on (v.id_veiculo = p.id_veiculo) WHERE pago = 0 ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
		


		
	}


} 

// CRUD
class TarefaService{

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao,Veiculo $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	} 

	public function inserir(){ // CREATE
		$quary = 'INSERT INTO `tb_veiculo` (`placa`, `marca`, `modelo`, `ano`, `km`) 
				  VALUES (:placa, :marca, :modelo, :ano, :km) ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(':placa', $this->tarefa->__get('placa'));
		$stmt->bindValue(':marca', $this->tarefa->__get('marca'));
		$stmt->bindValue(':modelo', $this->tarefa->__get('modelo'));
		$stmt->bindValue(':ano', $this->tarefa->__get('ano'));
		$stmt->bindValue(':km', $this->tarefa->__get('km'));
		$stmt->execute();
	}

	public function recuperar(){ // READY
		$quary = 'SELECT id_veiculo, placa, marca, modelo, ano, km FROM `tb_veiculo`';
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);


		
	}

	public function recuperar_veiculos(){ // READY
		$quary = '

		SELECT v.* 
		FROM tb_cliente as c 
		left JOIN tb_cliente_veiculo as cv on (c.id_cliente = cv.id_cliente) 
		LEFT JOIN tb_veiculo as v on (cv.id_veiculo = v.id_veiculo) 
		WHERE c.id_cliente = :id_cliente


		';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(':id_cliente', $this->tarefa->__get('id_veiculo'));

		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);


		
	}
	

	public function recuperar_relatorios(){ // READY
		$quary = '
			SELECT c.*, cv.*, v.* 
			FROM tb_cliente as c 
			inner JOIN tb_cliente_veiculo as cv on (c.id_cliente = cv.id_cliente) 
			inner JOIN tb_veiculo as v on (cv.id_veiculo = v.id_veiculo)
		';

		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);


		
	}


	

	public function recuperarUltimoId(){ // READY
		$quary = 'SELECT MAX(id_veiculo) as id_veiculo FROM tb_veiculo';
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);


		
	}
	public function atualizar(){ // UPDATE
		$quary = 'UPDATE tb_veiculo SET placa = ? WHERE id_veiculo = ?';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('placa'));
		$stmt->bindValue(2, $this->tarefa->__get('id_veiculo'));
		return $stmt->execute();



		
	}
	public function remover(){ // REMOVE

		
		$quary = 'DELETE FROM tb_veiculo WHERE id_veiculo = ?';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('id_veiculo'));
		$stmt->execute();


		
	}


	public function marcarRealizada(){ // UPDATE
		$quary = 'UPDATE tb_tarefas SET id_status = 2 WHERE id = ? ';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(1, $this->tarefa->__get('id'));
		$stmt->execute();



		



		
	}





}



class TarefaServiceCliente{

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao,Cliente $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	} 
	

	
	public function alterar_cliente(){ // CREATE

		$quary ='UPDATE `tb_cliente` 
		SET `cpf` = :cpf,
			`data_nascimento` = :data_nascimento,
			`nome` = :nome,
			`email` = :email,
			`celular` = :celular,
			`telefone` = :telefone,
			`cep` = :cep,
			`endereco` = :endereco,
			`bairro` = :bairro,
			`cidade` = :cidade,
			`complemento` = :complemento,
			`uf` = :uf,
			`numero_casa` = :numero_casa 
			WHERE `tb_cliente`.`id_cliente` = :id_cliente ';


		
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(':id_cliente', $this->tarefa->__get('id_cliente'));
		$stmt->bindValue(':cpf', $this->tarefa->__get('cpf'));
		$stmt->bindValue(':data_nascimento', $this->tarefa->__get('data_nascimento'));
		$stmt->bindValue(':nome', $this->tarefa->__get('nome'));
		$stmt->bindValue(':email', $this->tarefa->__get('email'));

		$stmt->bindValue(':celular', $this->tarefa->__get('celular'));
		$stmt->bindValue(':telefone', $this->tarefa->__get('telefone'));
		$stmt->bindValue(':cep', $this->tarefa->__get('cep'));
		$stmt->bindValue(':endereco', $this->tarefa->__get('endereco'));
		$stmt->bindValue(':bairro', $this->tarefa->__get('bairro'));
		$stmt->bindValue(':cidade', $this->tarefa->__get('cidade'));
		$stmt->bindValue(':complemento', $this->tarefa->__get('complemento'));
		$stmt->bindValue(':uf', $this->tarefa->__get('uf'));
		$stmt->bindValue(':numero_casa', $this->tarefa->__get('numero_casa'));

		$stmt->execute();
	}
	public function inserir_cliente(){ // CREATE

		$quary ='INSERT INTO `tb_cliente` ( `cpf`, `data_nascimento`, `nome`, `email`, `celular`, `telefone`, `cep`, `endereco`, `bairro`, `cidade`, `complemento`, `uf`, `numero_casa`) 	
		VALUES (:cpf, :data_nascimento, :nome, :email, :celular, :telefone, :cep, :endereco, :bairro, :cidade, :complemento, :uf, :numero_casa)';


		
		$stmt = $this->conexao->prepare($quary);
		
		$stmt->bindValue(':cpf', $this->tarefa->__get('cpf'));
		$stmt->bindValue(':data_nascimento', $this->tarefa->__get('data_nascimento'));
		$stmt->bindValue(':nome', $this->tarefa->__get('nome'));
		$stmt->bindValue(':email', $this->tarefa->__get('email'));

		$stmt->bindValue(':celular', $this->tarefa->__get('celular'));
		$stmt->bindValue(':telefone', $this->tarefa->__get('telefone'));
		$stmt->bindValue(':cep', $this->tarefa->__get('cep'));
		$stmt->bindValue(':endereco', $this->tarefa->__get('endereco'));
		$stmt->bindValue(':bairro', $this->tarefa->__get('bairro'));
		$stmt->bindValue(':cidade', $this->tarefa->__get('cidade'));
		$stmt->bindValue(':complemento', $this->tarefa->__get('complemento'));
		$stmt->bindValue(':uf', $this->tarefa->__get('uf'));
		$stmt->bindValue(':numero_casa', $this->tarefa->__get('numero_casa'));

		$stmt->execute();
	}




	public function buscaCpf(){ // READY
		$quary = 'SELECT cv.*, v.*
		FROM tb_cliente as c 
		inner JOIN tb_cliente_veiculo as cv on (c.id_cliente = cv.id_cliente) 
		LEFT JOIN tb_veiculo as v on (cv.id_veiculo = v.id_veiculo)
		WHERE c.cpf = :cpf';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(':cpf', $this->tarefa->__get('cpf'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);


		
	}

	public function recuperar(){ // READY
		$quary = 'SELECT id_cliente, nome FROM `tb_cliente` ORDER BY id_cliente DESC';
		$stmt = $this->conexao->prepare($quary);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);


		
	}
	public function recuperarUmcliente(){ // READY
		$quary = 'SELECT * FROM `tb_cliente` WHERE id_cliente = :id';
		$stmt = $this->conexao->prepare($quary);
		$stmt->bindValue(':id', $this->tarefa->__get('id_cliente'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);


		
	}






}

class TarefaServiceClienteVeiculo{

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao,ClienteVeiculo $tarefa){
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	} 
	
	public function inserir(){ // CREATE

		$quary ='INSERT INTO `tb_cliente_veiculo` (`id_cliente`, `id_veiculo`) 
		VALUES ( :id_cliente , :id_veiculo) ';


		
		$stmt = $this->conexao->prepare($quary);
		

		$stmt->bindValue(':id_cliente', $this->tarefa->__get('id_cliente'));
		$stmt->bindValue(':id_veiculo', $this->tarefa->__get('id_veiculo'));

		$stmt->execute();
	}










} 
 ?>