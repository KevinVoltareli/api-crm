<?php
if (isset($_GET['arquivo'])) {
    $nome_arquivo = $_GET['arquivo'];
    $caminho_arquivo = $nome_arquivo;

    if (file_exists($caminho_arquivo)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $nome_arquivo . '"');
        header('Content-Length: ' . filesize($caminho_arquivo));

        readfile($caminho_arquivo);
        exit;
    } else {
        echo 'Arquivo não encontrado.';
    }
}
?>