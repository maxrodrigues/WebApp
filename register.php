<?php
// Inclui o arquivo de configuração
require_once "config.php";

// Definição de variáveis ​​e inicia com valores vazios
$name = $email = $password = "";
$name_err = $email_err = $password_err = "";

// Processando dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validando nome de usuário
    if (empty(trim($_POST["name"]))) {
        $name_err = "Por favor, digite seu nome.";
    } else {
        // instrução SELECT
        $sql = "SELECT id FROM users WHERE name = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            mysqli_stmt_bind_param($stmt, "s", $param_name);

            $param_name = trim($_POST["name"]);

            // Tenta executar instrução preparada
            if (mysqli_stmt_execute($stmt)) {
                // guarda o resultado
                mysqli_stmt_store_result($stmt);

                $name = trim($_POST["name"]);
            } else {
                echo "Ops! Algo deu errado. Tente novamente mais tarde.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Por favor, digite seu e-mail.";
    } else {
        // instrução SELECT
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = trim($_POST["email"]);

            // Tenta executar instrução preparada
            if (mysqli_stmt_execute($stmt)) {
                // guarda o resultado
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "Este e-mail já foi utilizado.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Ops! Algo deu errado. Tente novamente mais tarde.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validando a senha
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Verificando os erros de entrada antes de inserir no banco de dados
    if (empty($name_err) && empty($email_err) && empty($password_err)) {

        // instrução INSERT
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {

            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_password);

            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // criptografa a senha

            // Tenta executar a instrução preparada
            if (mysqli_stmt_execute($stmt)) {
                // Redireciona a pagina de login
                header("location: login.php");
            } else {
                echo "Algo deu errado. Tente novamente mais tarde.";
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
    <title>Cadastro</title>
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
        <h2>Cadastro</h2>
        <p>Por favor, preencha este formulário para criar uma conta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Nome</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>E-mail (Login)</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Concluir">
                <input type="reset" class="btn btn-default" value="Limpar">
            </div>
            <p>Já tem uma conta? <a href="login.php">Faça o login aqui</a>.</p>
        </form>
    </div>
</body>

</html>