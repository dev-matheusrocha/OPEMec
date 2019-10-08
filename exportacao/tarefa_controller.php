<?php 

require_once '../../mec/tarefa.model.php';
require_once '../../mec/tarefa.service.php';
require_once '../../mec/conexao.php';

// se $_GET['acao'] não existe entao use $acao
$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;

if ($acao == 'inserirParcela') {
	$tarefa = new Parcela();
	$tarefa->__set('id_pedido', $_GET['id_pedido']);
	$tarefa->__set('valor', $_GET['valor']);
	$tarefa->__set('id_cliente', $_GET['id_cliente']);
	$conexao = new Conexao();
	$tarefaService = new TarefaServiceParcela($conexao, $tarefa);
	$tarefas = $tarefaService->inserirParcela();
	header('Location: pedidos.php');

}

function data($data) {
	$lista = explode("-",$data);
	$lista2 = explode(" ",$lista[2]);
	$lista3 = explode(":",$lista2[1]);
	switch ($lista[1]) {
		case 1:
			$mesD = 'JANEIRO';
			break;
		case 2:
			$mesD = 'FEVEREIRO';
			break;
		case 3:
			$mesD = 'MARÇO';
			break;
		case 4:
			$mesD = 'ABRIL';
			break;
		case 5:
			$mesD = 'MAIO';
			break;
		case 6:
			$mesD = 'JUNHO';
			break;
		case 7:
			$mesD = 'JULHO';
			break;
		case 8:
			$mesD = 'AGOSTO';
			break;
		case 9:
			$mesD = 'SETEMBRO';
			break;
		case 10:
			$mesD = 'OUTUBRO';
			break;
		case 11:
			$mesD = 'NOVEMBRO';
			break;
		case 12:
			$mesD = 'DEZEMBRO';
			break;
		}

	if (date("Y") != $lista[0]) {
		return ($lista2[0].'/'.$mesD.'/'.$lista[0].' '.$lista3[0].':'.$lista3[1]);
	}else if (date("Y") == $lista[0] and date("m") == $lista[1] and $lista2[0] == date("d")){
		return ('Hoje '.$lista3[0].':'.$lista3[1]);	
	} 

	return ($lista2[0].'/'.$mesD.' '.$lista3[0].':'.$lista3[1]);
}

if ($acao == 'recuperar_pedidos_a_receber') {
	$tarefa = new Pedido();
	$conexao = new Conexao();
	$tarefaService = new TarefaServicePedido($conexao, $tarefa);
	// parai aqui temos que dar baixa como pago os pedidos se o total de parcelar for igual ou maior que pedido total
	$validarPedidoPago = $tarefaService->recuperar_pedidos_a_receber();
	foreach ($validarPedidoPago as $key => $v) {
		$p = new Parcela();
		$p->__set('id_pedido', $v->id_pedido);
		$c = new Conexao();
		$valida = new TarefaServiceParcela($c, $p);
		$total = $valida->recuperarSomaDoPedido();
		if ($total[0]->total >= $v->valor) {
			$valida->darBaixaNoPedidoComoPago();
		}
	}
	$tarefas = $tarefaService->recuperar_pedidos_a_receber();
}
if ($acao == 'iniciarpedido') {
	$tarefa = new Pedido();
	$tarefa->__set('id_veiculo', $_POST['id_veiculo']);
	$tarefa->__set('id_cliente', $_POST['id_cliente']);
	$tarefa->__set('valor', $_POST['valor']);
	$conexao = new Conexao();
	$tarefaService = new TarefaServicePedido($conexao, $tarefa);
	$tarefas = $tarefaService->cadastrarPedido();
	header('Location: pedidos.php?p=1');
}
if ($acao == 'buscaCpf') {
	$tarefa = new Cliente();
	$tarefa->__set('cpf', $_POST['cpf']);
	$conexao = new Conexao();
	$tarefaService = new TarefaServiceCliente($conexao, $tarefa);
	$tarefas = $tarefaService->buscaCpf();
	return $tarefas;
}

if ($acao == 'baixar_relatorio') {
	$tarefa = new Veiculo();
	$conexao = new Conexao();
	$tarefaService = new TarefaService($conexao, $tarefa);
	$tarefas = $tarefaService->baixar_relatorio();
}
if ($acao == 'recuperar_UmCliente') {
	$tarefa = new Cliente();
	$tarefa->__set('id_cliente', $id);
	$conexao = new Conexao();
	$tarefaService = new TarefaServiceCliente($conexao, $tarefa);
	$tarefas = $tarefaService->recuperarUmcliente();
}
if ($acao == 'recuperar_cliente_veiculo_all') {
	$tarefa = new Cliente();
	$tarefa->__set('id_cliente', $_POST['id_cliente']);
	$conexao = new Conexao();
	$tarefaService = new TarefaServiceCliente($conexao, $tarefa);
	$tarefas = $tarefaService->recuperarUmcliente();
	$Veiculo = new Veiculo();
	$Veiculo->__set('id_veiculo', $_POST['id_cliente']);
	$tarefaService = new TarefaService($conexao, $Veiculo);
	$veiculos = $tarefaService->recuperar_veiculos();
}
if ($acao == 'recuperar_cliente_veiculo') {
	$tarefa = new Cliente();
	$conexao = new Conexao();
	$tarefaService = new TarefaServiceCliente($conexao, $tarefa);
	$tarefas = $tarefaService->recuperar();
}
if ($acao == 'recuperar_cliente') {
	$tarefa = new Cliente();
	$conexao = new Conexao();
	$tarefaService = new TarefaServiceCliente($conexao, $tarefa);
	$tarefas = $tarefaService->recuperar();
}
if ($acao == 'recuperar_relatorios') {
	$tarefa = new Veiculo();
	$conexao = new Conexao();
	$tarefaService = new TarefaService($conexao, $tarefa);
	$tarefas = $tarefaService->recuperar_relatorios();
}
if ($acao == 'alterar_cliente') {
	$tarefa = new Cliente();
	$tarefa->__set('id_cliente', $_POST['id_cliente']);
	$tarefa->__set('cpf', $_POST['cpf']);
	$tarefa->__set('data_nascimento', $_POST['data_nascimento']);
	$tarefa->__set('nome', $_POST['nome']);
	$tarefa->__set('email', $_POST['email']);
	// $tarefa->__set('senha', $_POST['senha']);
	$tarefa->__set('celular', $_POST['celular']);
	$tarefa->__set('telefone', $_POST['telefone']);
	$tarefa->__set('cep', $_POST['cep']);
	$tarefa->__set('endereco', $_POST['endereco']);
	$tarefa->__set('bairro', $_POST['bairro']);
	$tarefa->__set('cidade', $_POST['cidade']);
	$tarefa->__set('complemento', $_POST['complemento']);
	$tarefa->__set('uf', $_POST['uf']);
	$tarefa->__set('numero_casa', $_POST['numero_casa']);
	$conexao = new Conexao(); 
	$tarefaService = new TarefaServiceCliente($conexao, $tarefa);
	$tarefaService->alterar_cliente();
	header('Location: cadastrar_cliente.php?id=' . $_POST['id_cliente']);
}
if ($acao == 'inserir_cliente') {
	$tarefa = new Cliente();
	$tarefa->__set('cpf', $_POST['cpf']);
	$tarefa->__set('data_nascimento', $_POST['data_nascimento']);
	$tarefa->__set('nome', $_POST['nome']);
	$tarefa->__set('email', $_POST['email']);
	// $tarefa->__set('senha', $_POST['senha']);
	$tarefa->__set('celular', $_POST['celular']);
	$tarefa->__set('telefone', $_POST['telefone']);
	$tarefa->__set('cep', $_POST['cep']);
	$tarefa->__set('endereco', $_POST['endereco']);
	$tarefa->__set('bairro', $_POST['bairro']);
	$tarefa->__set('cidade', $_POST['cidade']);
	$tarefa->__set('complemento', $_POST['complemento']);
	$tarefa->__set('uf', $_POST['uf']);
	$tarefa->__set('numero_casa', $_POST['numero_casa']);
	$conexao = new Conexao(); 
	$tarefaService = new TarefaServiceCliente($conexao, $tarefa);
	$tarefaService->inserir_cliente();
	header('Location: cadastrar_veiculo.php?inclusao=2&desc=' . $_POST['nome']);
}
// controller é para definir o fluxo da aplicação
if ($acao == 'inserir') {
	$tarefa = new Veiculo();
	$tarefa->__set('placa', $_POST['placa']);
	$tarefa->__set('marca', $_POST['marca']);
	$tarefa->__set('modelo', $_POST['modelo']);
	$tarefa->__set('ano', $_POST['ano']);
	$tarefa->__set('km', $_POST['km']);
	$conexao = new Conexao(); 
	$tarefaService = new TarefaService($conexao, $tarefa);
	$tarefaService->inserir();
	$ClienteVeiculo = new ClienteVeiculo();
	$ClienteVeiculo->__set('id_cliente', $_POST['id_cliente']);
	$ultimoId = $tarefaService->recuperarUltimoId();
	$ClienteVeiculo->__set('id_veiculo', $ultimoId[0]->id_veiculo);
	$tarefaServiceClienteVeiculo = new TarefaServiceClienteVeiculo($conexao, $ClienteVeiculo);
	$tarefaServiceClienteVeiculo->inserir();
	header('Location: cadastrar_veiculo.php?inclusao=1&desc=' . $_POST['placa']);
}else if ($acao == 'recuperar') {
	$tarefa = new Veiculo();
	$conexao = new Conexao();
	$tarefaService = new TarefaService($conexao, $tarefa);
	$tarefas = $tarefaService->recuperar();
}else if ($acao == 'atualizar') {
	$tarefa = new Veiculo();
	$tarefa->__set('id_veiculo', $_POST['id']);
	$tarefa->__set('placa', $_POST['tarefa']);
	$conexao = new Conexao();
	$tarefaService = new TarefaService($conexao, $tarefa);
	if ($tarefaService->atualizar()) {
		header('Location: veiculos.php');
	}
}if ($acao == 'remover_veiculo') {
	$tarefa = new Veiculo();
	$tarefa->__set('id_veiculo', $_POST['id']);
	// $tarefa->__set('placa', $_POST['tarefa']);
	$conexao = new Conexao();
	$tarefaService = new TarefaService($conexao, $tarefa);
	$aux = $tarefaService->remover();
	if ($aux) {
		header('Location: veiculos.php?excluido=1');
	} else{
		header('Location: veiculos.php?excluido=0');
	}
}
if (isset($_GET['acao']) &&  $_GET['acao'] == 'remover'){
	$tarefa = new Veiculo();
	$tarefa->__set('id_veiculo', $_GET['id']);
	$conexao = new Conexao();
	$tarefaService = new TarefaService($conexao, $tarefa);
	$aux = $tarefaService->remover();
	$acao == 'recuperar';
	if ($aux) {
		header('Location: veiculos.php?acao=recuperar');
	}
}else if ($acao == 'marcarRealizada'){
	$tarefa = new Tarefa();
	$tarefa->__set('id', $_GET['id']);
	$tarefa->__set('id_status', 2);
	$conexao = new Conexao();
	$tarefaService = new TarefaService($conexao, $tarefa);
	$aux = $tarefaService->marcarRealizada();
	if ($aux) {
		header('Location: todas_tarefas.php');
	}
}

?>

<?php 
if ($acao == 'detalhepedido') {
	$tarefa = new Pedido();
	$tarefa->__set('id_pedido', $_GET['id_pedido']);
	$conexao = new Conexao();
	$tarefaService = new TarefaServicePedido($conexao, $tarefa);
	$pedido_cliente = $tarefaService->recuperarTudoSobrePedido();
	// print_r($pedido_cliente);
	if (isset($pedido_cliente[0]->id_pedido)) {?>
		<style type="text/css">
			.campop{
				width: 600px;
				margin: auto;
				box-shadow: 0px 0px 100px #000;
				padding: 20px;
				border-radius: 10px;
				background: #fff;
			}
			#customers {
				font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
				border-collapse: collapse;
				width: 100%;
			}
		</style>
		<div class="campop">
			<div onclick="fechar()" ><span class="close">&times;</span></div>
			<p>
				Pedido: <?= $pedido_cliente[0]->id_pedido ?> | Cliente: <strong><?= $pedido_cliente[0]->nome ?></strong> | 
				Placa: <strong><?= $pedido_cliente[0]->placa ?></strong> | Cel: <?= $pedido_cliente[0]->celular ?> | 
				Total: <strong><?= $pedido_cliente[0]->valor ?></strong>
			</p>
	<? } ?>
	<?php 
	$parcelas = $tarefaService->parcelas();
	if (isset($parcelas[0]->id_parcela)) { ?>
<table id="customers">
  <tr>
    <th>Parcelas</th>
    <th> </th>
  </tr>
  <?php $total = 0;
  foreach ($parcelas as $key => $i){ 
		$total = $i->valor + $total; ?>
  	<tr>
	    <td> <?= data($i->data) ?> </td>
	    <td> R$ <?= $i->valor ?>,00 </td>
  	</tr>
  <? } ?>
</table>

<p>Total pago: R$ <?= $total ?>,00 A Receber <strong> R$: <?= $pedido_cliente[0]->valor - $total ?>,00</strong></p>
<input type="hidden" id="areceber" value="<?= $pedido_cliente[0]->valor - $total ?>">

	<? } else{  ?>
		
		<input type="hidden"  id="areceber" value="<?= $pedido_cliente[0]->valor ?>">
		<label>Nenhuma pagamento registrado</label>
	<? } ?>





<div style="margin: auto;width: 341px;background: #cadef3;height: 88px;padding-left: 10px;">
<label style="width: 100% !important; " for="valor">Digite o valor da nova parcela</label>
<input style="float: left; width: 100px; margin-right: 10px; " type="number" id="valor" required="" class="form-control" name="valor">

<input type="hidden" id="id_pedido" name="id_pedido" value="<?= $_GET['id_pedido'] ?>">
<input type="hidden" id="id_clientepop" name="id_clientepop" value="<?= $pedido_cliente[0]->id_cliente ?>">
<input type="hidden" id="totalpedido" name="totalpedido" value="<?= $pedido_cliente[0]->valor ?>">



<button   style="float: left;" onclick="validarValorParcela()" type="submit" class="btn btn-light">Registrar novo pagamento</button>
</div> 

</div>


<? } ?>