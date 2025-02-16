<?php
namespace App\Controllers;

// os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
    public function timeline()
    {
        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);

        $total_registros_pagina = 10;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;

        $tweets = $tweet->getPorPagina($total_registros_pagina, $deslocamento);
        $total_tweets = $tweet->getTotalRegistros();
        $this->view->total_de_paginas = ceil($total_tweets['total'] / $total_registros_pagina);
        $this->view->pagina_ativa = $pagina;

        $this->view->tweets = $tweets;

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $this->listarNomesIAs();
        $this->listarIAsPorUsuario();

        $this->render('timeline');
    }

    public function tweet()
    {
        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->salvar();

        header('Location: /timeline');
    }

    public function validaAutenticacao()
    {
        session_start();

        if (!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
            header('Location: /?login=erro');
        }
    }

    public function criarIA()
    {
        $this->validaAutenticacao();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Criação da IA a partir do POST
            $ia = Container::getModel('IA');

            // Definindo os atributos da IA com os valores do formulário
            $ia->__set('nome', $_POST['nomeIA']);
            $ia->__set('personalidade', $_POST['personalidadeIA']);
            $ia->__set('id_criador', $_POST['id_criador']);

            // Valida o cadastro
            if ($ia->validarCadastro()) {
                try {
                    // Tenta salvar a IA
                    $ia->salvar();
                    // Redireciona para uma página de sucesso ou para a lista de IAs
                    header('Location: /criar_ia?success=true');
                    exit();
                } catch (Exception $e) {
                    // Caso ocorra um erro, exiba uma mensagem
                    echo "Erro ao criar IA: " . $e->getMessage();
                }
            } else {
                echo "Erro: Dados inválidos. Certifique-se de que todos os campos são preenchidos corretamente.";
            }
        }

        // Caso não seja um POST, renderiza o formulário
        $this->listarNomesIAs();
        $this->listarIAsPorUsuario();   
        $this->render('criarIA');
    }


    public function quemSeguir()
    {
        $this->validaAutenticacao();

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
        $usuarios = array();

        if ($pesquisarPor != '') {
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();
        }

        $this->view->usuarios = $usuarios;
        $this->render('quemSeguir');
    }

    public function acao()
    {
        $this->validaAutenticacao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        if ($acao == 'seguir') {
            $usuario->seguirUsuario($id_usuario_seguindo);
        } else if ($acao == 'deixar_de_seguir') {
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header('Location: /quem_seguir');
    }

    public function listarNomesIAs()
    {
        $ia = Container::getModel('IA'); // Use o Container para obter o modelo de IA
        $this->view->opcoes = $ia->getNomesIAs(); // Atribui os nomes das IAs à propriedade `opcoes`
    }

    public function listarIAsPorUsuario()
    {
        $ia = Container::getModel('IA');
        $ia->__set('id_criador', $_SESSION['id']); // Definindo o id do criador como o id do usuário logado
        $this->view->opcoes = $ia->getIAsPorUsuario(); // Chamando a função para pegar as IAs desse usuário
    }
}
