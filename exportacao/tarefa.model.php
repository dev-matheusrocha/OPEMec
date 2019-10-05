<?php




class Parcela {
	private $id_parcela;
	private $valor;
	private $data;
	private $id_pedido;
	private $id_cliente;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
}


class Pedido {
	private $id_pedido;
	private $id_cliente;
	private $id_veiculo;
	private $id_status;
	private $valor;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
}

class Tarefa {
	private $id;
	private $id_status;
	private $tarefa;
	private $data_cadastro;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
}



class ClienteVeiculo {
	private $id_cliente;
	private $id_veiculo;
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
}


class Veiculo {
	private $id_veiculo;
	private $placa;
	private $marca;
	private $modelo;
	private $ano;
	private $km;


	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		// strtoupper para caixa alta
		if ($atributo == 'placa') {
			$this->$atributo = strtoupper($valor);
		}else{
			$this->$atributo = $valor;
		}
	}
}


 	 	 	 	 	 	 	 	 	 	 	 	 	
class Cliente {
	private $id_cliente; 
	private $cpf;
	private $data_nascimento;
	private $nome;
	private $email;
	private $senha;
	private $celular;
	private $telefone;
	private $cep;
	private $endereco;
	private $bairro;
	private $cidade;
	private $complemento;
	private $uf;
	private $numero_casa;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		// strtoupper para caixa alta
		if ($atributo == 'nome') {
			$this->$atributo = strtoupper($valor);
		}else{
			$this->$atributo = $valor;
		}
		
	}
}

?>