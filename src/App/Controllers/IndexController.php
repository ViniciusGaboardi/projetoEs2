<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action
{

	public function index()
	{
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}

	public function inscreverse()
	{
		$this->view->usuario = array(
			'nome' => '',
			'email' => '',
			'senha' => '',
		);

		$this->view->erroCadastro = false;
		$this->render('inscreverse');
	}

	public function registrar()
	{
		$usuario = Container::getModel('Usuario');

		$nome = trim($_POST['nome']);
		$email = trim($_POST['email']);
		$senha = $_POST['senha'];

		// Validar o e-mail
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->view->usuario = array(
				'nome' => $nome,
				'email' => $email,
				'senha' => $senha,
			);

			$this->view->erroCadastro = true;
			$this->render('inscreverse');
			return;
		}

		// Criptografar a senha
		$senhaCriptografada = password_hash($senha, PASSWORD_BCRYPT);

		$usuario->__set('nome', $nome);
		$usuario->__set('email', $email);
		$usuario->__set('senha', $senhaCriptografada);

		if ($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {
			$usuario->salvar();
			$this->render('cadastro');
		} else {
			$this->view->usuario = array(
				'nome' => $nome,
				'email' => $email,
				'senha' => $senha,
			);

			$this->view->erroCadastro = true;
			$this->render('inscreverse');
		}
	}
}
