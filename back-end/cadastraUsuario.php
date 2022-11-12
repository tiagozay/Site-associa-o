<?php

    use APBPDN\Helpers\EntityManagerCreator;
    use APBPDN\Models\Usuario;

    require_once 'vendor/autoload.php';

    session_start();

    $_SESSION['nivel'] = 'admin';

    if($_SESSION['nivel'] != 'admin'){
        header('HTTP/1.1 403 Forbidden');
        echo "Acesso negado!";
        exit();
    }

    try{
        $nome =  isset($_POST['nome']) ? $_POST['nome'] : throw new Exception('Campo nome não informado');
        $email = isset($_POST['email']) ? $_POST['email'] : throw new Exception('Campo email não informado');
        $nivel = isset($_POST['nivel']) ? $_POST['nivel'] : throw new Exception('Campo nivel não informado');
        $senha = isset($_POST['senha']) ? $_POST['senha'] : throw new Exception('Campo senha não informado');
        $confSenha = isset($_POST['confSenha']) ? $_POST['confSenha'] : throw new Exception('Campo confSenha não informado');        
    }catch( Exception $e){
        header('HTTP/1.1 500 Internal Server Error');
        echo $e->getMessage();
        exit();
    }

    $entityManeger = EntityManagerCreator::create();

    $userRepository = $entityManeger->getRepository(Usuario::class);

    $usuarioComEmail = $userRepository->findOneBy(['email' => $email]);

    if($usuarioComEmail){
        header('HTTP/1.1 500 Internal Server Error');
        echo "usuario_ja_cadastrado";
        exit();
    }

    //Instância e validação das regras de negócio
    try{
        $usuario = new Usuario($nome, $email, $nivel, $senha, $confSenha);
    }catch(DomainException $e){
        header('HTTP/1.1 500 Internal Server Error');
        echo $e->getMessage();
        exit();
    }

    try{

        $entityManeger = EntityManagerCreator::create();

        $entityManeger->persist($usuario);
    
        $entityManeger->flush();

        header('HTTP/1.1 200 OK');

    }catch(Exception){
        header('HTTP/1.1 500 Internal Server Error');
        echo "Erro inesperado";
    }


?>