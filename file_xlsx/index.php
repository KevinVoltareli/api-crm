<?php
session_start(); // Iniciar a sessão

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Importar Excel </title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>


    <div class="titulo">
  <h1>Importar Excel .csv</h1>
    </div>

    <?php
    // Apresentar a mensagem de erro ou sucesso
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }


    ?>
    

    <button id="voltar">Voltar</button>


<form>

    
    <label>Caso precise de um modelo:</label>


<div class="baixar-modelo">
<a href="downloadXml.php?arquivo=teste.xlsx">Baixar modelo do Excel</a>
</div>




</form>
    



    <form method="POST" action="processa.php" enctype="multipart/form-data">

    
    

        <label>Arquivo: </label>
        <input type="file" name="arquivo" id="arquivo" accept="text/csv"><br><br>

        <input type="submit" value="Enviar"
        id="enviar-button">
        
        
        
    </form>



    <script>
     document.getElementById("voltar").addEventListener("click", function() {
  window.history.back();
});
</script>
    
    
     


     
       
</body>
</html>