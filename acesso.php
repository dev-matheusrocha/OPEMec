<?php


try {
    if (!isset($_SESSION['FuncionarioAtivo']) or ($_SESSION['FuncionarioAtivo'] != 1)) {
        header("Location: login.php");
    }

} catch (\Throwable $th) {
    echo "";
}



?>