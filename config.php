<?php
/* Credenciais do banco de dados. Supondo que você esteja executando o MySQL
server com configuração padrão (usuário 'root' sem senha) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mysql_database');
 
/* Tentativa de conexão com o banco de dados MySQL */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
/* Checando a conexão */
if($link === false){
    die("ERRO: Não foi possível conectar. " . mysqli_connect_error());
}
?>