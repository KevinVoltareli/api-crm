
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';


$dataInicial = filter_input(INPUT_GET, 'dataInicial');
$dataFinal = filter_input(INPUT_GET, 'dataFinal');

               $read = $conn->prepare("SELECT  h.NOME_APELIDO AS NOME, sum(l.PICQTDE) AS TOTAL 
                            FROM TB_VEN_VENDA a
                            INNER JOIN TB_VPE_VENDAPEDIDOS b ON b.VENID_VENDA = a.VENID 
                            INNER JOIN TB_IPD_ITEMPEDIDO c ON c.PEDID_PEDIDO = b.PEDID_PEDIDO 
                            INNER JOIN TB_MAT_MATERIAL d ON c.MATID_PRODUTO = d.MATID 
                            INNER JOIN TB_NCM_NCM e ON e.NCMID = d.NCMID 
                            INNER JOIN TB_AAT_ATRIBUTOS f ON f.MATID = d.MATID  
                            INNER JOIN TB_DRIP_APELIDO h ON h.MATFANTASIA  = d.MATFANTASIA
                            INNER JOIN TB_PED_PEDIDO i ON i.PEDID = c.PEDID_PEDIDO
                            INNER JOIN TB_TVN_TIPOVENDA j ON j.TVNID = i.TVNID
                            INNER JOIN TB_PIC_PEDIDOITEMCLIENTE l ON l.IPDID  = c.IPDID
                            WHERE a.VENDATAHORAFATURAMENTO >= {$dataInicial} AND a.VENDATAHORAFATURAMENTO <= {$dataFinal} 
                            AND i.PEDDATACANCELAMENTO IS NULL
                            AND NOT e.NCMID = '56' 
                            AND NOT e.NCMID = '65'
                            AND NOT e.NCMID = '66'
                            AND NOT e.NCMID = '68'
                            AND NOT e.NCMID = '71'
                            AND NOT e.NCMID = '73'  
                            AND NOT e.NCMID = '75'
                            GROUP BY  h.NOME_APELIDO, l.PICQTDE
                            ORDER BY total DESC ");

$read->setFetchMode(PDO::FETCH_ASSOC);        
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 
           
                   $NOME = $dados["NOME"];  
                   $TOTAL = $dados["TOTAL"];  

            extract($dados);


                   $VENDA["VENDA"][] = [

                     'NOME' => $NOME, 
                     'TOTAL' => number_format($TOTAL,0,",",""),  

                   ];
      endforeach;   

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($VENDA);  

                //echo "Total geral: {$qtd}";