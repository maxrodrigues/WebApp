<?php
session_start();

// Verifica se o usuário já está logado, se sim, redireciona para a página inicial (index.php)
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

// Inclui arquivo de configuração
require_once "config.php";

// Define variáveis ​​e inicia com valores vazios
$email = $password = "";
$email_err = $password_err = "";

// Processa dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // checa se o nome de usuario(email) está vazio
    if (empty(trim($_POST["email"]))) {
        $email_err = "Por favor, digite o email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // checa se a senha está vazia
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, digite a senha.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validando os dados digitados
    if (empty($email_err) && empty($password_err)) {

        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Vincula as variáveis ​​a instrução
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // define parametros
            $param_email = $email;

            // Tenta executar a instrução
            if (mysqli_stmt_execute($stmt)) {
                // Guarda o resultado
                mysqli_stmt_store_result($stmt);

                // Checa se o email já existe, se sim verifica a senha
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Senha está correta, então inicia a sessão
                            session_start();

                            // Guarda dados nas variaveis da sessão
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;

                            // Redireciona o usuário a pagina inicial (index.php)
                            header("location: index.php");
                        } else {
                            // Se a senha for inválida, mensagem de erro será exibida
                            $password_err = "A senha digitada não é válida";
                        }
                    }
                } else {
                    // Exibe erro se o usuário não existe
                    $email_err = "Não foi encontrado conta com esse e-mail";
                }
            } else {
                echo "Ops! Algo deu errado. Tente novamente mais tarde.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Por favor, preencha abaixo para fazer o login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>E-mail</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Não tem conta ainda? <a href="register.php">Cadastre-se agora</a>!</p>
        </form>
    </div>
</body>

</html>