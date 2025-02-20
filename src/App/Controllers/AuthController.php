<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action
{
    public function autenticar()
    {
        $usuario = Container::getModel('Usuario');

        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', $_POST['senha']); 
        
        $usuario->autenticar();

        // Verifica se o usuário foi autenticado com sucesso
        if ($usuario->__get('id') != '' && $usuario->__get('nome')) {
            session_start();

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');

            header('location: /timeline');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function sair()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }
}
