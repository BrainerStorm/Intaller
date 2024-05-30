<?php
/*
        ╔═══╗╔══╗╔═══╗╔═══╗╔═══╗╔═══╗───╔╗──╔╗╔══╗╔═══╗╔══╗╔═══╗╔══╗
        ║╔═╗║╚╗╔╝║╔══╝║╔═╗║║╔═╗║║╔══╝───║║──║║║╔╗║║╔═╗║║╔╗║║╔══╝║╔═╝
        ║╚═╝║─║║─║╚══╗║╚═╝║║╚═╝║║╚══╗───║╚╗╔╝║║║║║║╚═╝║║╚╝║║╚══╗║╚═╗
        ║╔══╝─║║─║╔══╝║╔╗╔╝║╔╗╔╝║╔══╝───║╔╗╔╗║║║║║║╔╗╔╝║╔╗║║╔══╝╚═╗║
        ║║───╔╝╚╗║╚══╗║║║║─║║║║─║╚══╗───║║╚╝║║║╚╝║║║║║─║║║║║╚══╗╔═╝║
        ╚╝───╚══╝╚═══╝╚╝╚╝─╚╝╚╝─╚═══╝───╚╝──╚╝╚══╝╚╝╚╝─╚╝╚╝╚═══╝╚══╝
    Modelo de instalador de Banco de dados.
    Nesse modelo você pode redirecionar após a instalação e configuraçao do bando de dados 
    para a página de configuração e estilização do seu site.



*/
if (isset($_POST['submit'])) 
{    
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_name = $_POST['db_name'];

    // Gerar conteúdo de conexão ao DB no arquivo config.php
    $configContent = "<?php\n";
    $configContent .= "define('DB_HOST', '$db_host');\n";
    $configContent .= "define('DB_USER', '$db_user');\n";
    $configContent .= "define('DB_PASS', '$db_pass');\n";
    $configContent .= "define('DB_NAME', '$db_name');\n";
    $configContent .= "\n";
    $configContent .= "// Conexão com o banco de dados\n";
    $configContent .= "\$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);\n";
    $configContent .= "\n";
    $configContent .= "// Checar conexão\n";
    $configContent .= "if (\$conn->connect_error) {\n";
    $configContent .= "    die('Conexão falhou: ' . \$conn->connect_error);\n";
    $configContent .= "}\n";
    $configContent .= "?>";

    // Escrever no arquivo de configuração
    // Aqui voce pode criar o arquivo em determinada pasta caso queira!
    $configFile = fopen('config.php', 'w');
    if ($configFile) {
        fwrite($configFile, $configContent);
        fclose($configFile);
        echo "Arquivo de configuração criado com sucesso.\n<br>";
    } else {
        echo "Erro ao criar o arquivo de configuração.<br>";
    }

    

    $conn = new new PDO('mysql:host='. $this->db_host .';dbname='.$this->db_name, $this->db_user, $this->db_pass);
    
    // Checar conexão
    if ($conn->connect_error) 
    {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Criar banco de dados
    $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
    if ($conn->query($sql) === TRUE) 
    {
        echo "Banco de dados criado com sucesso\n\n<br>";
    } 
    else 
    {
        echo "Erro ao criar banco de dados: " . $conn->error . "\n<br>";
    }    
    ///////////////////// USUARIO  \\\\\\\\\\\\\\\\\\\\\
    // Cria o usuario para login!
    $sql = "CREATE TABLE IF NOT EXISTS users_id (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        usern VARCHAR(50) NOT NULL,
        user_pass VARCHAR(255) NOT NULL,
        pname VARCHAR(50),
        sname VARCHAR (50),
        gender VARCHAR (50),
        email VARCHAR(50),
        foreign_key VARCHAR(50) NOT NULL,
        hwid VARCHAR(50),
        photo VARCHAR(50),
        discord VARCHAR(255),
        instagram VARCHAR(255),
        github VARCHAR(255),
        avatar VARCHAR(255),
        banner VARCHAR(255),
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci";
    if ($conn->query($sql) === TRUE) 
    {
        echo "Tabela de Users Criado!\n<br>";
    } 
    else 
    {
        echo "Erro ao criar banco de dados: " . $conn->error . "\n<br>";
    }  
    // cria o album para adicionar fotos!
    $sql = "CREATE TABLE IF NOT EXISTS users_album (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        foreign_key VARCHAR(50) NOT NULL,
        album_name VARCHAR(50),
        album_photo VARCHAR(255),
        album_description VARCHAR(255),
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci";
    if ($conn->query($sql) === TRUE) 
    {
        echo "Tabela de Users Album Criado!\n<br>";
    } 
    else 
    {
        echo "Erro ao criar banco de dados: " . $conn->error . "\n<br>";
    }  
    // salva foto a foto vinculado ao nome do album! ilimitada quantidade de fotos por album
    $sql = "CREATE TABLE IF NOT EXISTS users_photos (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        foreign_key VARCHAR(50) NOT NULL,
        album_name VARCHAR(50) NOT NULL,
        photo VARCHAR(50) NOT NULL,
        photo_description VARCHAR(255),
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci";
    if ($conn->query($sql) === TRUE) 
    {
        echo "Tabela de Users Photos Criado!\n<br>";
    } 
    else 
    {
        echo "Erro ao criar banco de dados: " . $conn->error . "\n<br>";
    }

    

    // Fechar conexão
    $conn->close();

    // Agora basta chamar os arquivos da seguinte maneira....
    // Agora você pode usar $conn para interagir com o banco de dados
    // Lmbrando de sempre da require_once no arquivo criado.
    //require_once 'config.php';
    
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Instalação e Configuração do Banco de Dados</title>
    </head>
    <body>
        <h2>Configuração do Banco de Dados</h2>
        <form action="" method="post">
            <label for="db_host">Host do Banco de Dados:</label>
            <input type="text" id="db_host" name="db_host" required><br><br>
            <label for="db_user">Usuário do Banco de Dados:</label>
            <input type="text" id="db_user" name="db_user" required><br><br>
            <label for="db_pass">Senha do Banco de Dados:</label>
            <input type="password" id="db_pass" name="db_pass"><br><br>
            <label for="db_name">Nome do Banco de Dados:</label>
            <input type="text" id="db_name" name="db_name" required><br><br>
            <input type="submit" name="submit" value="Instalar">
        </form>
    </body>
</html>
