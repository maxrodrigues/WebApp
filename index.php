<?php
// Inicia
session_start();

// Checa se o usuario está logado, senão será redirecionado a página de login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Bem vindo!</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="page-header">
        <p style="text-align: right; width: 99%; padding-top: 0.5%;">
            <a href="reset-password.php" class="btn btn-warning">Alterar senha</a>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </p>
        <h1 style="font-size:large;">CADASTRO DE PRODUTOS</h1>
    </div>
    <div>
        <form name="form1" action="" method="post">
            <table>
                <tbody>
                    <tr>
                        <td>PRODUTO</td>
                        <td><input type="text" name="produto" size="20" /></td>
                    </tr>
                    <tr>
                        <td>QUANTIDADE</td>
                        <td><input type="text" min="0" name="quantidade" /></td>
                    </tr>
                    <tr>
                        <td>PREÇO (R$)</td>
                        <td><input type="text" name="preco" size="20" /></td>
                    </tr>
                    <tr>
                        <td>&nbsp</td>
                        <td><input type="submit" name="button" id="button" value="CADASTRAR" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>

</html>