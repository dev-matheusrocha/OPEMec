<?php

	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$p = explode("/", $actual_link);
			array_pop($p);
			$url = '';
			foreach ($p as $k => $v) {
				$url = $url . $v . '/';
			} 

require_once 'View/_main.php';
require_once '../../001Mecanica/Model/_main.php';
// se $_GET['acao'] não existe entao use $acao
$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;
(!isset($_SESSION['perfil'])) ? session_unset() : '';

($acao == 'vc') ? veiculosCliente($_REQUEST['obj']) : '';

if($acao == 'icv') { 

	$veiculo = json_decode($_REQUEST['obj']);
	inserirVeiculo($veiculo);

}

($acao == 'listarCliente') ? createTable(listarCliente(),'detalheCliente','Detalhe', "ClienteId", array('ClienteNome','ClienteCep','ClienteCpfCnpj','ClienteDataCadastro','ClienteNumeroCasa','ClienteObs','ClienteEndereco','ClienteBairro'), array('Cliente','CEP','CPF / CNPJ','Data de cadastro','Número','OBS','Endereco','Bairro')) : '';
($acao == 'ma') ? getManutencaoAndamento() : '';
($acao == 'iniciarManutencao') ? iniciarManutencao($_REQUEST['obj']) : '';
($acao == 'pesquisarCliente') ? pesquisarCliente($_REQUEST['obj']) : '';
($acao == 'listarProdutosComEstoque') ? listarProdutosComEstoque($_POST['id_tipo_produto']) : '';
($acao == 'telaCadastarEntradaDeProduto') ? entradaDeProdutof() : '';
($acao == 'removeItemTmp') ? removeItemTmp($_POST['codProduto'], $_POST['comanda']) :'';
($acao == 'AddProdutoTmp') ? AddProdutoTmp($_POST['codProduto'], $_POST['comanda']) :'';
($acao == 'removeProdutoTmp') ? removeProdutoTmp($_POST['codProduto'], $_POST['comanda']): '';
($acao == 'salvarCliente') ? salvarCliente($_POST):'';
($acao == 'cadastraTipoDeProdutoInsert') ? cadastraTipoDeProdutoInsert($_POST['nome_tipo_produto'], $_POST['estoque']):'';
($acao == 'motoboyLevaPedidoConfirmado') ? motoboyLevaPedidoConfirmado($_POST):'';	




if ($acao == 'excluirContato') {
	excluirContato($_REQUEST['obj']);
}

if ($acao == 'contato') {
	$ls = contato($_REQUEST['obj']);
	createTable($ls,
	"excluirContato",
	'Excluir',
	"Cliente_ContatoId", 
	array('ClienteApelido','ClienteEmail','ClienteNumeroContato'),
	array('Nome ','Email','Contato'));
}

if ($acao == 'AtualizarCliente') {
	AtualizarCliente($_REQUEST);
}

if ($acao == 'salvarNovoContato') {
	salvarNovoContato($_REQUEST);
}


if ($acao == 'excluirCliente') {
	excluirCliente($_REQUEST);
}

if ($acao == 'SalvarCliente') {
	salvarCliente($_REQUEST);
}else if ($acao == 'login') {

	$funcionario = getFuncionario($_POST['FuncionarioCep'], $_POST['FuncionarioSenha'] );
	// print_r($funcionario);
	// die();

	session_start();
	
	if ( isset($funcionario[0]->FuncionarioAtivo) and ($funcionario[0]->FuncionarioAtivo == 1)) {
		subirSESSION($funcionario[0]);
		header("Location: index.php");
			
		exit();
	}else{
		session_abort();
		header("Location: login.php");
		exit();	
	}
	
}else if ($acao == 'alterarQtdoProduto') {
	$qtdoReal = qtdoProduto($_POST['id_produto']);
	$novaQtdo = $qtdoReal + $_POST['qtdo'];
	alterarQtdoProduto($_POST['id_produto'], $novaQtdo);
	echo '<div id="exibeInfo"  role="alert" >Estoque atualizado!</div>';
}

if ($acao == 'pedidoFinalizado') {
	$comandaValida = getComadaValida($_POST['id_card']);
	$id_loja = $comandaValida[0]->loja;
	$id_cliente = $_POST['id_cliente'];
	if ($_POST['id_cliente'] == 0 ) {
		$id_cliente = NULL;
	}
	session_start();
	$id_funcionario = $_SESSION['id_funcionario'];
	$valor = $_POST['valorTotal'];
	PedidoFinalizado($id_cliente, $id_loja, $id_funcionario, $valor, $_POST['id_card'], $_POST['frete'], $_POST['formapgto'] , $_POST['valorTroco'], $_POST['trocoPara'], $_POST['obspedido']);

}

if ($acao == 'buscarCliente') {
	$listaClientes = buscarCliente("%".$_POST['texto']."%");
	selectClientes($listaClientes);
}





if ($acao == "inserevalor") {
	$var = json_decode($_POST['obj']);
	
	inserevalor($var->idpedido, $var->valor );
	//select($ls, "Selecionar modelo", array("ModeloNome") , "Selecione o modelo", "ModeloId", "btn text-white -primary", "ModeloId", "");
}


if ($acao == "pmv") {
	$ls = buscarModelo(str_replace($_POST['obj'],"\"","%"));
	select($ls, "Selecionar modelo", array("ModeloNome") , "Selecione o modelo", "ModeloId", "btn btn-light", "ModeloId", "");
}

if($acao == 'cpp'){ ?>

<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button {
     -webkit-appearance: none;
}
</style>




<div class="row">
<div class="col-12">
<label class="col-sm-12" for="valor">Valor da parcela</label>
<input type="text" class="col-4 ml-3" id="np" placeholder="Exeplo: 50,00" name="valor">
<br>

<div class="col-12 mt-3">
<button    onclick="salvarparcela(<? echo  $_POST['obj']; ?>)" href="buttons.html" class="btn btn-light collapse-item  menuItem cadastrarVeiculo">
  Cadastrar parcela
</button>
</div>
</div>


<? die(); } 








if ($acao == 'Cadastar') {
	$comandaValida = getComadaValida($_POST['Comanda']);
	if(!isset($comandaValida[0]->loja)){
		echo '<div role="alert" id="alertboot" >Comanda invalida! </div>';
	}else{
		retornarInputAddProtudo($comandaValida[0]);
		printarTodosOsProdutosTmp($comandaValida[0]);
	}	
}






if ($acao == "relatorioFornecedores") {
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$db = "025";
	
		$con = mysqli_connect($servername, $username, $password, $db);
		 
		  header('Content-Type: text/csv; charset=utf-8');  
		  header('Content-Disposition: attachment; filename=Cliente_veiculo.csv');  
		  $output = fopen("php://output", "w");  
		  fputcsv($output, array('NOME','ID','CNPJ','DATA DE CADASTRO','OBS','CEP','Endereço','Número'));  
		  
		  $query = "SELECT * FROM `Fornecedor`";  
	
		  $result = mysqli_query($con, $query);  
		  while($row = mysqli_fetch_assoc($result))  
		  {  
			   fputcsv($output, $row);  
		  }  
		  fclose($output); 

}


if ($acao == "Addcontatocliente") {
		echo "<form id='Addcontatocliente' class='row'>";


		
			echo "<div class='col-3 '>";
				inputValue("", 'ClienteApelido', 'Nome', '', '', 'text','');
				inputValue($_REQUEST['obj'], 'ClienteId', '', '', '', 'hidden','');
			echo "</div>";
			echo "<div class='col-3 '>";
				inputValue("", 'ClienteEmail', 'Email', '', '', 'text','');
			echo "</div>";
			echo "<div class='col-3 '>";
				inputValue("", 'ClienteNumeroContato', 'Tel ou Cel', '', '', 'text','');
			echo "</div>";
			echo "<div class='col-3 '>";
				echo '<button class="btn btn-light " onclick="salvarNovoContato()">Salvar contato</button>';
			echo "</div>";
		echo "</form>";
}


if ($acao == "detalheCliente") {
	$detalheCliente = detalheCliente($_POST['obj']);
	foreach ($detalheCliente as $k => $v) {
		echo "<form id='clienteupdate' class='row'>";
		echo "<div class='col-6 '>";
		inputValue($v->ClienteId, 'ClienteId', '', '', '', 'hidden','');

		inputValue($v->ClienteNome, 'ClienteNome', 'Nome', '', '', 'text','');
		inputValue($v->ClienteCep, 'ClienteCep', 'CEP', '', '', 'number','');
		inputValue($v->ClienteCpfCnpj, 'ClienteCpfCnpj', 'CPF / CNPJ', '', '', 'text','');
		echo "</div>";
		
		echo "<div class='col-6 '>";
		inputValue($v->ClienteNumeroCasa, 'ClienteNumeroCasa', 'Número casa', '', '', 'text','');
		inputValue($v->ClienteObs, 'ClienteObs', 'OBS', '', '', 'text','');
		inputValue($v->ClienteEndereco, 'ClienteEndereco', 'Endereço', '', '', 'text','');
		inputValue($v->ClienteBairro, 'ClienteBairro', 'Bairro', '', '', 'text','');
		echo "</div>";
		echo "<div class='row col-12 mt-3'>";
		echo '<div class="col-3"><button class="btn-block btn btn-light" onclick="AtualizarCliente('.$v->ClienteId.')">Atualizar cadastro</button></div>';
		echo '<div class="col-3"><button class="btn-block btn btn-light" onclick="contato('.$v->ClienteId.')">Listar contatos</button></div>';
		echo '<div class="col-3"><button class="btn-block btn btn-light" onclick="Addcontatocliente('.$v->ClienteId.')">ADD contato</button></div>';
		echo '<div class="col-3"><button class="btn-block btn btn-light" onclick="excluirCliente('.$v->ClienteId.')">Excluir cliente</button></div>';
		echo "</div>";
		echo "</form>";
	}

	//exibirDetalheCliente($detalheCliente);
}

if ($acao == "confirmadoExcluir") {

	$idParcela = excluirParcela($_POST['obj']);
	echo $idParcela[0]->ParcelaId ;
}

if ($acao == "listarfornecedores") {
	$ls = listarfornecedores();
	createTable($ls,
	"detalheFornecedor",
	'Detalhe',
	"FornecedorId", 
	array('FornecedorNome','FornecedorCnpj','FornecedorCadastro','FornecedorObs'),
	array('Fornecedor','CNPJ','DATA DE CADASTRO','OBS.')
	
	);
}


if ($acao == "cadastrarFornecedor") {
	
	
	$class  = sv('Fornecedor', $_POST);
	salvarFornecedor($class);
	echo '1';



}



if ($acao == "relatoriogeralemail") {
	
	
	
	$to      = 'developerbreno@gmail.com';
	$subject = 'Relatórios';
	$message = $url.'/controller.php?acao=relatoriogeral';
	$headers = 'From: developerbreno@gmail.com' . "\r\n" .
		'Reply-To: developerbreno@gmail.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);


}



if ($acao == "relatoriogeral") {
	
$servername = "localhost";
$username = "root";
$password = "";
$db = "025";

	$con = mysqli_connect($servername, $username, $password, $db);
     
      header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=Cliente_veiculo.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array('VeiculoId','ManutencaoId','ManutencaoInicio','ManutencaoFim','ManutencaoFinalizada','ManutencaoPaga','OficinaId','PedidoObs','PecaId','ManutencaoId','PecaValorVenda','PecaId','PecaNome','PecaEstoque','PecaValor','PecaObs','FornecedorId','VeiculoId','VeiculoPlaca','ClienteId','VeiculoAno','ModeloId','VeiculoRenavam'));  
      
      $query = "SELECT p.*, pp.*, pa.*, ve.* FROM Pedido as p

		INNER JOIN Peca_Pedido as pp 
		on (pp.ManutencaoId = p.ManutencaoId)
		
		INNER JOIN Peca as pa 
		on (pa.PecaId = pp.PecaId)
		
		
		INNER JOIN Veiculo as ve 
		on (ve.VeiculoId = p.VeiculoId)
		
		INNER JOIN Cliente as cl 
		on (cl.ClienteId = ve.ClienteId)";  

      $result = mysqli_query($con, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  

}
if ($acao == "info") {
	$info = info($_POST['obj']);
	//print_r($info);
	listInfo($info);

}

if ($acao == "pesquisarFornecedorParaCadastroDeProduto") {
	echo "pesquisarFornecedorParaCadastroDeProduto";
}

if ($acao == 'pesquisarProduto') {
	$resultadoDaPesquisaLike = sqlLike('%'.$_POST['nome'].'%');
	if (isset($resultadoDaPesquisaLike[0]->id_produto_venda)) {
		listarEmHtml($resultadoDaPesquisaLike);
	}else{
		echo "Produto não encontrado";
	}	
}

if ($acao == 'motoboyLevaPedido') { 
	session_start();
	if ($_SESSION['perfil'] == "M") {
		printar(listarPedidosSemMotoboy());
	}else{
		if (isset(listarPedidosSemMotoboy()[0]->id_pedido_finalizado)) {
			printar(listarPedidosSemMotoboy());
		}else{
			echo '<div role="alert" style="color: #000;" >Não há pedidos para entrega</div>';
		}
	}	
	echo '<button   id="frete" onclick="frete()" class="btn text-white  btn text-white -success" >Mapa</button>';
}
?>

<?php 
if ($acao == 'getTiposProdutos') {
	$listaTipoProduto = listaTipoProduto(); ?>
	
<select id="tipo_produto_cad_pro" onchange="tipo_produto_cad_pro()" >
	<option value="null" >Tipo do produto</option>
	<?php 
		$listaTipoProduto = listaTipoProduto();
		foreach ($listaTipoProduto as $key => $v) { ?>
			<option value="<?= $v->id_tipo_produto ?>|<?= $v->estoque ?>" ><?= $v->tipo_produto ?></option>
	<? } ?>
</select>

<? } ?>


<? if ($acao == 'editarPedido') { ?>

	<div class="row">
		<div class="col-xl-12 col-lg-12">
			
			<div class="col-xl-12 col-lg-12">
				   <!-- Begin Page Content -->
				   <div class="container-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
	<h6 class="m-0 font-weight-bold text-primary">Informações</h6>
  </div>
  <div class="card-body">
	<div class="table-responsive">
	  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		<thead>
		  <tr>
			<th>Cliente</th>
			<th>Cep</th>
			<th>Rua</th>
			<th>Bairro</th>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<?
				$idpedido = json_decode($_REQUEST['obj']);
				$clienteonj = getidCliente($idpedido);
		
			?>
			<td><? echo $clienteonj[0]->ClienteNome; ?></td>
			<td><? echo $clienteonj[0]->ClienteCep; ?></td>
			<td><? echo $clienteonj[0]->ClienteEndereco; ?></td>
			<td><? echo $clienteonj[0]->ClienteBairro; ?></td>
		</tr>
		</tbody>
	  </table>
	</div>
  </div>
</div>




<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
	<h6 class="m-0 font-weight-bold text-primary">
		Parcelas 
		<button   class="btn btn-light" onclick="assparcela(<? echo $idpedido; ?>)" >ADD Parcela</button>

		Total: R$  <? echo total($idpedido)[0]->total ; ?>

	</h6>
  </div>
  <div class="card-body">
	<div class="table-responsive">
	  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		<thead>
		  <tr>
			<th>data</th>
			<th>Valor</th>
			<th></th>
			
		  </tr>
		</thead>
		<tbody>
		
			<?
				$parcelas = getparcelas($idpedido);
				foreach ($parcelas as $p => $pv) {
					echo "<tr>";
					echo "<td>" . $pv->ParcelaData . "</td>";
					echo "<td> R$: " . $pv->ParcelaValor . "</td>";
					echo "<td><button   onclick=\"removerParcela(".$pv->ParcelaId.")\" class=\"btn btn-light \">Excluir</button></td>";
					echo "</tr>";
					
				}		
			?>
	
		</tbody>
	  </table>
	</div>
  </div>
</div>


<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
	<h6 class="m-0 font-weight-bold text-primary">
		Peças
		<button   class="btn btn-light" onclick="addPeca(<? echo $idpedido; ?>)" >ADD Peça</button>


	</h6>
  </div>
  <div class="card-body">
	<div class="table-responsive">
	  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		<thead>
		  <tr>
			<th>Peça</th>
			<th>Valor</th>
			
			
		  </tr>
		</thead>
		<tbody>
		<?
				$parcelas = getPecas($idpedido);
				foreach ($parcelas as $p => $pv) {
					echo "<tr>";
					echo "<td>" . $pv->PecaNome . "</td>";
					echo "<td> R$: " . $pv->PecaValorVenda . "</td>";
					echo "<td><button   onclick=\"removerParcela("./*$pv->ParcelaId.*/")\" class=\"btn btn-light\">Excluir</button></td>";
					echo "</tr>";
					
				}		
			?>
	
		</tbody>
	  </table>
	</div>
  </div>
</div>



<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
	<h6 class="m-0 font-weight-bold text-primary">
		Serviços
		<button   class="btn btn-light " onclick="addServico(<? echo $idpedido; ?>)" >ADD Serviço</button>

	</h6>
  </div>
  <div class="card-body">
	<div class="table-responsive">
	  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		<thead>
		<tr>
			<th>Serviço</th>
			<th>Valor</th>
	
			
		  </tr>
		</thead>
		<tbody>
			<?
				$servico = getservicos($idpedido);
				foreach ($servico as $p => $pv) {
					echo "<tr>";
					echo "<td>" . $pv->ManutencaoValorDescricao . "</td>";
					echo "<td> R$: " . $pv->ManutencaoValorAdd . "</td>";
					echo "<td><button   onclick=\"removerParcela(".$pv->Manutencaoid.")\" class=\"btn btn-light\">Excluir</button></td>";
					echo "</tr>";
					
				}		
			?>
		</tbody>
	  </table>
	</div>
  </div>
</div>

</div>
<!-- /.container-fluid -->

			</div>	
		</div>
	</div>


<?php } ?>