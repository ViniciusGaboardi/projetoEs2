<?php

namespace App\Models;

use MF\Model\Model;

class IA extends Model
{
    private $id;
    private $nome;
    private $personalidade;
    private $id_criador;

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function salvar()
    {
        $query = "insert into ias(nome, personalidade, id_criador) values(:nome, :personalidade, :id_criador)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':personalidade', $this->__get('personalidade'));
        $stmt->bindValue(':id_criador', $this->__get('id_criador'));
        $stmt->execute();

        return $this;
    }

    public function validarCadastro()
    {
        $valido = true;
        if (strlen($this->__get('nome')) < 3) {
            $valido = false;
        }

        if (strlen($this->__get('personalidade')) < 3) {
            $valido = false;
        }

        if (strlen($this->__get('id_criador')) < 1) {
            $valido = false;
        }

        return $valido;
    }
    public function getPersonalidadePorIA()
    {
        $query = "
            select 
                nome, personalidade, id_criador
            from 
                ias 
            where 
                nome = :nome
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $query = "
            select 
                i.id, 
                i.nome, 
                i.personalidade,
                i.id_criador
            from 
                ias as i
            where 
                i.nome like :nome and i.id != :id_ias
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%' . $this->__get('nome') . '%');
        $stmt->bindValue(':id_ias', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getInfoIA()
    {
        $query = "select nome from ias where id = :id_ias";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_ias', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getNomesIAs()
    {
        $query = "SELECT id, nome, personalidade FROM ias";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getIAsPorUsuario()
    {
        $query = "
            SELECT id, nome, personalidade
            FROM ias 
            WHERE id_criador = :id_criador
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_criador', $this->__get('id_criador'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
