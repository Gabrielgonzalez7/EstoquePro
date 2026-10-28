<?php

// Se ja estiver logado, manda direto para a pagina inicial/produtos

class LoginController{
    public function index(){
        if(isset($_SESSION['usuario_id'])){
            header("Location: index.php?acao=produtos");
            exit();
}

    require_once "views/login.php";
    }

    public function autenticar(){
        global $pdo;

        $email = $_POST['email'] ??'';
        $senha = $_POST['senha'] ??'';

        if(empty($email) || empty($senha)){

        $_SESSION['erro'] = "Preencha o E-mail e a senha!";
        header("Location: index.php?cao=login");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->execute(['email' =>$email]);
    $usuario = $stmt->fetch();

    if($usuario && password_verify($senha, $usuario['senha'])){
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_perfil'] = $usuario['perfil'];

        header("Location: index.php?acao=produtos");
        exit();
    }else{
        $_SESSION['erro'] = "E-mail ou senha incorretos";
        header("Location: index.php?acao=login");
        exit();
    }
    }
    public function logout(){
        session_destroy();
        header("Location: index.php?acao=login");
        exit(); 
    }
}