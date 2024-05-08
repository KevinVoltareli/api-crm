<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../../conexao.php';



$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT  f.AS2DESCRICAO AS SUB2, sum(a.MECQUANTIDADE1) AS QTD
										FROM TB_MEC_MATESTCONTROLE a 
										INNER JOIN TB_MAT_MATERIAL b ON b.MATID = a.MATID
										INNER JOIN TB_FIL_FILIAL c ON c.FILID = a.FILID
										INNER JOIN TB_PES_PESSOA d ON d.PESID = c.PESID 
										INNER JOIN TB_AAT_ATRIBUTOS e ON e.MATID = b.MATID 
										INNER JOIN TB_AS2_ATRSUBLINHA2 f ON f.AS2ID = e.AS2ID 
										WHERE a.MECDATALOTE  >= '2010-01-01' AND a.MECDATALOTE <= '2024-12-31'
										AND NOT b.NCMID = '56' 
										AND NOT b.NCMID = '65'
										AND NOT b.NCMID = '66'
										AND NOT b.NCMID = '68'	
										AND NOT b.NCMID = '71'
										AND NOT b.NCMID = '75'
										AND a.FILID = '5'
										AND a.MECQUANTIDADE1 > 0
										GROUP BY SUB2
										ORDER BY QTD desc");

$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $SUB2 = $dados["SUB2"];  
                   $QTD = $dados["QTD"];  

           

                   extract($dados);


                   $estoque["ESTOQUE"][] = [

                     'SUB2' => $SUB2, 
                     'QTD' => number_format($QTD,0,",",""),  
 

                  
                   ];
      endforeach;   

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($estoque);  

                //echo "Total geral: {$qtd}";








