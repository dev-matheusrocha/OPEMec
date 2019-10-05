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

<? function input($name, $textlabel, $placeholder, $class , $type, $required){ ?>
    <label class="col-sm-12" for="<?= $name ?>"><?= $textlabel ?></label>
    <input type="<?= $type ?>" class="col-sm-12 <?= $class ?>" id="<?= $name ?>" placeholder="<?= $placeholder ?>" name="<?= $name ?>" <?= $required ?>>
    <br>
<? } ?>


<?php
function select($ls, $label, $lsatt, $default, $name, $boot, $idreferencia, $funcao)
{ ?>
    <label for="<? echo $name; ?>"><? echo $label; ?></label>

    <select onchange="<? echo $funcao; ?>" id="<? echo $name; ?>" name="<? echo $idreferencia; ?>" class="btn col-md-12 <? echo $boot; ?>">
    
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