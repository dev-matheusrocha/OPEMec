<?php





// sv = setValue
function sv($c, $ls){
    $vc = new $c();
    
    $var = json_decode($ls['obj']);
    foreach ($var as $key => $value) {
		try {
			$vc->__set($value->name,strtoupper($value->value));
		} catch (\Throwable $th){}
    }
    return $vc;
}

// sv = setValue
function ca($c, $ls, $clienteId){
    $listaContatos = array();
    $len = 0;
    $var = json_decode($ls['obj']);
    foreach ($var as $key => $value) {
        if ($value->name == "apelido" || 
            $value->name == "email" ||
            $value->name == "telefone") {
            array_push($listaContatos, $value->value);
            $len++;
        }
    }
    
    $maisUm = $len / 3;
    $p = 0;
 
    $listaContatosComAtt = array();
    for ($i=0; $i < $maisUm ; $i++) { 
        $vc = new $c();
        $vc->__set("ClienteId", $clienteId);
        $vc->__set("ClienteApelido", $listaContatos[$p]);
        $p++;
        $vc->__set("ClienteNumeroContato", $listaContatos[$p]);
        $p++;
        $vc->__set("ClienteEmail", $listaContatos[$p]);
        $p++;
        array_push($listaContatosComAtt, $vc);

    }
    
    return $listaContatosComAtt;
}


function validar($o, $ls){
    foreach ($ls as $k => $v) {
        foreach ($v as $k2 => $va) {
            if ($k2.'('.$o->$k.')' != $va) {
                return $k;
            }
        }
    }
    return true;
}



?>


<? function inputValue($value ,$name, $textlabel, $placeholder, $class , $type, $required){ ?>
    <label class=" mt-3 " for="<?= $name ?>" style="font-size: 20px;"><?= $textlabel ?></label>
    <input type="<?= $type ?>" value="<?= $value ?>" style="font-size: 20px;" class="col-12 border-0 text-white bg-transparent <?= $class ?>" id="<?= $name ?>" placeholder="<?= $placeholder ?>" name="<?= $name ?>" <?= $required ?>>
    <br>
<? } ?>

<? function input($name, $textlabel, $placeholder, $class , $type, $required){ ?>
    <label class="col-sm-12" for="<?= $name ?>"><?= $textlabel ?></label>
    <input type="<?= $type ?>" class="col-sm-12 border-0 text-white bg-transparent <?= $class ?>" id="<?= $name ?>" placeholder="<?= $placeholder ?>" name="<?= $name ?>" <?= $required ?>>
    <br>
<? } ?>


<?php
function select($ls, $label, $lsatt, $default, $name, $boot, $idreferencia, $funcao)
{ ?>
    <label for="<? echo $name; ?>"><? echo $label; ?></label>

    <select onchange="<? echo $funcao; ?>" id="<? echo $name; ?>" name="<? echo $idreferencia; ?>" class="btn text-white  col-md-12 <? echo $boot; ?>">
    
    <option value="null"><? echo $default; ?></option>
    
    <? foreach ($ls as $k => $v) { ?>
        <option value="<? echo $v->$idreferencia ?>">
        <? foreach ($lsatt as $kk => $i) { echo $v->$i." "; } ?>
        </option>
    <? } ?>
       
    </select>

<? } ?>


<? function listInfo($ls) { ?> 

    <table class="table p-3" style="widht: 200px !important;">
  <thead>
    <tr>
      
      <th scope="col">Placa</th>
      <th scope="col">Peça</th>
      
    </tr>
  </thead>
  <tbody>

       <? foreach ($ls as $k => $v) { ?>
            <tr>
      
            <td><? echo $v->VeiculoPlaca; ?></td>
            <td><? echo $v->PecaNome; ?></td>
            
            </tr>
    
       
       
       
            <? } ?>

        
    
  </tbody>
</table>

  <?  } ?>


<? function createTable($ls,$funcao,$namebtn, $id, $exibir, $colunas){ ?>
    <div style="scroll-behavior: auto !important; ">
    <table class="table table-bordered " style="background-color:rgba(81, 81, 81, 0.71); color: #fff;" >
		<thead>
		  <tr>
                <? foreach ($colunas as $c => $cv) {
                    echo '<th>' . $cv . '</th>';
                } 
                echo '<th></th>';
                ?>		
		  </tr>
		</thead>
		<tbody>           
            <? foreach ($ls as $lsk => $lsv) {
                echo '<tr>';
                foreach ($exibir as $exibirk => $exibirv) { ?>
                    <? echo "<td>" . $lsv->$exibirv . "</td>" ?>
            <? }
            echo "<td>" . '<button class="btn btn-light" onclick="' . $funcao .'(' . $lsv->$id . ')">' . $namebtn . '</button>' . "</td>";
            echo '<tr>';  
            } ?>  
		</tbody>
      </table>
      </div>
<? } ?>