<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model
{
    private $id;
    private $id_usuario;
    private $tweet;
    private $date;
    private $id_ia;

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
    $idIA = $_POST['id_ia'];
    $query = "INSERT INTO tweets(id_usuario, tweet, id_ia) VALUES(:id_usuario, :tweet, :id_ia)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->bindValue(':tweet', $this->__get('tweet'));
    $stmt->bindValue(':id_ia', $idIA);
    $stmt->execute();
    $tweet = $this->__get('tweet');
    $this->__set('id_ia',$idIA);
    // Consulta para obter a personalidade da IA com base no ID
    $queryIA = "select personalidade from ias where id = :id_ia";
    $stmtIA = $this->db->prepare($queryIA);
    $stmtIA->bindValue(':id_ia', $idIA);
    $stmtIA->execute();
    $resultadoIA = $stmtIA->fetch(\PDO::FETCH_ASSOC);

    // Verifica se a IA foi encontrada
    if ($resultadoIA) {
        $personalidade = $resultadoIA['personalidade'];
        error_log('tweet: ' . $tweet . ' pers: ' . $personalidade);
        // Envia o tweet e a personalidade para o ChatGPT
        $response = $this->sendPromptToChatGPT($tweet, $personalidade);
        if($response){
        // Verifica se a resposta da IA foi obtida corretamente
            $respostaIA = $response['choices'][0]['message']['content'];            
            error_log('EUUUUU' . $respostaIA);
            // Insere a resposta da IA como um novo tweet
            $queryResposta = "INSERT INTO tweets (id_usuario, tweet, id_ia) VALUES (:id_usuario, :tweet, :id_ia)";
            $stmtResposta = $this->db->prepare($queryResposta);
            $stmtResposta->bindValue(':id_usuario', $this->__get('id_usuario')); // Pode ser o mesmo id_usuario ou um diferente, dependendo do contexto
            $stmtResposta->bindValue(':tweet', $respostaIA);
            $stmtResposta->bindValue(':id_ia', $idIA);
            $stmtResposta->execute();
        }else{
            error_log("no response");
        }
    } else {
        error_log("IA não encontrada.");
    }

    return $this;
}

private function sendPromptToChatGPT($prompt, $personality) {
    error_log('START API ' . $prompt . $personality);
        $apiKey = ''; // Substitua pela sua chave da API$url = 'https://api.openai.com/v1/chat/completions';

    // Monta o payload
    $data = [
        'model' => 'gpt-3.5-turbo', // Ou outro modelo disponível
        'messages' => [
            [
                'role' => 'system',
                'content' => "Você é uma IA com a personalidade de: $personality."
            ],
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ]
    ];

    // Inicializa a sessão cURL
    $ch = curl_init($url);
    
    // Configura as opções da requisição
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Executa a requisição
    $response = curl_exec($ch);
    error_log($response);
    // Verifica se houve erro
    if (curl_errno($ch)) {
        error_log('Error:' . curl_error($ch));
    }

    // Fecha a sessão cURL
    curl_close($ch);

    // Retorna a resposta
    return json_decode($response, true);
}


    public function getAll()
    {
        $query = "
            select 
                t.id, 
                t.id_usuario, 
                u.nome, 
                t.tweet, 
                DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
            from 
                tweets as t 
                left join usuarios as u on (t.id_usuario = u.id)
            where 
                t.id_usuario = :id_usuario
                or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
            order by
                t.data desc
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPorPagina($limit, $offset)
    {
        $query = "
            select 
                t.id, 
                t.id_usuario, 
                u.nome, 
                t.tweet, 
                DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
            from 
                tweets as t 
                left join usuarios as u on (t.id_usuario = u.id)
            where 
                t.id_usuario = :id_usuario
                or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
            order by
                t.data desc
            limit
                $limit
            offset
                $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTotalRegistros()
    {
        $query = "
            select 
                count(*) as total
            from 
                tweets as t 
                left join usuarios as u on (t.id_usuario = u.id)
            where 
                t.id_usuario = :id_usuario
                or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
