<?php

session_start(); // Iniciar a sessão

// Limpar o buffer de saída
ob_start();

// Incluir a conexão com banco de dados
include_once "../conexao.php";


$id = filter_input(INPUT_GET, 'id');


// Receber o arquivo do formulário
$arquivo = $_FILES['arquivo'];
//var_dump($arquivo);

// Variáveis de validação
$primeira_linha = true;
$linhas_importadas = 0;
$linhas_nao_importadas = 0;
$usuarios_nao_importado = "";

// Verificar se é arquivo csv
if($arquivo['type'] == "text/csv"){

    // Ler os dados do arquivo
    $dados_arquivo = fopen($arquivo['tmp_name'], "r");

    // Percorrer os dados do arquivo
    while($linha = fgetcsv($dados_arquivo, 10000, ";")){

        // Como ignorar a primeira linha do Excel
        if($primeira_linha){
            $primeira_linha = false;
            continue;
        }

        // Usar array_walk_recursive para criar função recursiva com PHP
        array_walk_recursive($linha, 'converter');
        //var_dump($linha);

        // Criar a QUERY para salvar o usuário no banco de dados
        $query_usuario = "INSERT INTO TB_CAM_CLIENTE (ID_CLIENTE,STATUS, CRIADO, CAMPANHA_ID) VALUES (:ID_CLIENTE, :STATUS, Current_timestamp, :CAMPANHA_ID)";

        // Preparar a QUERY
        $cad_usuario = $conn->prepare($query_usuario);

        // Substituir os links da QUERY pelos valores
        $cad_usuario->bindValue(':ID_CLIENTE', ($linha[0] ?? "NULL"));
        $cad_usuario->bindValue(':STATUS', ($linha[1] ?? "NULL"));
        $cad_usuario->bindValue(':CAMPANHA_ID', ($linha[2] ?? "NULL"));

        // Executar a QUERY
        $cad_usuario->execute();

        // Verificar se cadastrou corretamente no banco de dados
        if($cad_usuario->rowCount()){
            $linhas_importadas++;
        }else{
            $linhas_nao_importadas++;
            $usuarios_nao_importado = $usuarios_nao_importado . ", " . ($linha[0] ?? "NULL");
        }
    }

    // Criar a mensagem com os CPF dos usuários não cadastrados no banco de dados
    if(!empty($usuarios_nao_importado)){
        $usuarios_nao_importado = "Usuários não importados: $usuarios_nao_importado.";
    }

    // Mensagem de sucesso
    $_SESSION['msg'] = "<p style='color: green;'>$linhas_importadas linha(s) importa(s), $linhas_nao_importadas linha(s) não importada(s). $usuarios_nao_importado</p>";

    // Redirecionar o usuário
    header("Location: index.php");
}else{

    // Mensagem de erro
    $_SESSION['msg'] = "<p style='color: #f00;'>Necessário enviar arquivo csv!</p>";

    // Redirecionar o usuário
    header("Location: index.php");
}

// Criar função valor por referência, isto é, quando alter o valor dentro da função, vale para a variável fora da função.
function converter(&$dados_arquivo)
{
    // Converter dados de ISO-8859-1 para UTF-8
    $dados_arquivo = mb_convert_encoding($dados_arquivo, "UTF-8", "ISO-8859-1");
}