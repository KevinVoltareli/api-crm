
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';



$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT e.NOME_APELIDO as APELIDO, sum(a.MECQUANTIDADE1) AS QTD
            FROM TB_MEC_MATESTCONTROLE a 
            INNER JOIN TB_MAT_MATERIAL b ON b.MATID = a.MATID
            INNER JOIN TB_FIL_FILIAL c ON c.FILID = a.FILID
            INNER JOIN TB_PES_PESSOA d ON d.PESID = c.PESID 
            INNER JOIN TB_DRIP_APELIDO e ON e.MATFANTASIA = b.MATFANTASIA 
            WHERE
            a.MECDATALOTE >= '2010-01-01' and a.MECDATALOTE <= '2024-12-31'
            AND NOT b.NCMID = '56' 
            AND NOT b.NCMID = '65'
            AND NOT b.NCMID = '66'
            AND NOT b.NCMID = '68'
            AND NOT b.NCMID = '71'
            AND NOT b.NCMID = '75'
            AND NOT e.NOME_APELIDO = 'XEV'
            AND e.MATFANTASIA NOT LIKE '%OUTLET%'
            AND e.ARMDESCRICAO NOT LIKE '%OUTLET%'
            GROUP BY e.NOME_APELIDO
            ORDER BY QTD desc");

$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $APELIDO = $dados["APELIDO"];  
                   $QTD = $dados["QTD"];  

           

                   extract($dados);


                   $estoque["ESTOQUE"][] = [

                     'APELIDO' => $APELIDO, 
                     'QTD' => number_format($QTD,0,",",""),  
 

                  
                   ];
      endforeach;   

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($estoque);  

                //echo "Total geral: {$qtd}";