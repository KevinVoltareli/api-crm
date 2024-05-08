<?php

// Configurações de conexão ao banco de dados

try {
    // Conectar ao banco de dados
    $conexao = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB", "SYSDBA", "masterkey");
    
    // Definir o modo de erro do PDO para exceção
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lista de números
   $numeros = [
    "16993803003",
    "11962559097",
    "21989137099",
    "24988248110",
    "4198899562",
    "11986783633",
    "4988386369",
    "21968572900",
    "21967225387",
    "21988077274",
    "18996119425",
    "3195397025",
    "22999556652",
    "4498393954",
    "5491424149",
    "7381183807",
    "3892099793",
    "5491424149",
    "21982180119",
    "555499193284",
    "554999317601",
    "559391713322",
    "11947046222",
    "24988149555",
    "8899734021",
    "19996378455",
    "6799775617",
    "5399731295",
    "584735570",
    "21988395475",
    "5197173407",
    "4599867375",
    "6492217550",
    "4499569448",
    "19992936552",
    "555491353661",
    "558881302199",
    "554799239241",
    "5519983105098",
    "554196646954",
    "553185931016",
    "27998264529",
    "4484035854",
    "3299850818",
    "15997707592",
    "19981209777",
    "11956763577",
    "19997767126",
    "5197580116",
    "554599616073",
    "19991269176",
    "11972265025",
    "15997486766",
    "3799862779",
    "11947198141",
    "24988499798",
    "5596536561",
    "24992615272",
    "3982066587",
    "3498826624",
    "11983490161",
    "4196646954",
    "11973013866",
    "16997501241",
    "27999998049",
    "12997662008",
    "5599069245",
    "24993297781",
    "5499173953",
    "5496558401",
    "15997237741",
    "4899612754",
    "21987473136",
    "4299240406",
    "11984763082",
    "18997085148",
    "5180627572",
    "4985055943",
    "21964331477",
    "24992719824",
    "4498317510",
    "11984368648",
    "5391353594",
    "27997650312",
    "24992168861",
    "198466308",
    "4599125219",
    "11973670548",
    "6799734938",
    "22998183253",
    "4796450937",
    "11940489972",
    "24998146580",
    "5591274720",
    "11945069405",
    "11981391324",
    "3597337497",
    "19991558669",
    "17992235333",
    "11999706985",
    "13991211751",
    "21991189494",
    "11964668133",
    "22981442279",
    "15996471277",
    "11994692270",
    "16996330439",
    "13997836607",
    "16991068227",
    "5599148658",
    "21965094192",
    "6592101285",
    "11957863153",
    "24999014230",
    "5195208385",
    "7597071806",
    "6992625742",
    "5599090992",
    "11996063216",
    "19999661462",
    "17997599696",
    "21965605774",
    "4298349999",
    "21984446370",
    "5192004657",
    "4599530796",
    "17992696992",
    "11986658567",
    "5591518376",
    "19999875441",
    "21980659792",
    "5597233009",
    "16993592624",
    "21964470247",
    "18997332697",
    "11971718536",
    "22988033716",
    "3598450670",
    "22997672722",
    "21999627397",
    "3192276114",
    "5195811169",
    "7597133411",
    "13991474001",
    "19997223639",
    "3187972842",
    "5499371257"
];

    // Preparar a consulta SQL
    $consulta = "
        SELECT a.CLIID AS CLID, c.TELNUMERO AS NUMERO, c.TELDDD AS DDD
        FROM TB_CLI_CLIENTE a
        INNER JOIN TB_PES_PESSOA b ON b.PESID = a.PESID 
        INNER JOIN TB_TEL_TELEFONE c ON c.PESID = a.PESID 
    ";

    // Executar a consulta
    $resultado = $conexao->query($consulta);

    // Verificar correspondências
    foreach ($numeros as $numero) {
        while ($linha = $resultado->fetch(PDO::FETCH_ASSOC)) {
            if ($linha['DDD'].$linha['NUMERO'] == $numero) {
                echo "Número: $numero está associado ao CLID: " . $linha['CLID'] . "<br>";
            }
        }
        // Reiniciar o ponteiro de resultados para a próxima iteração
        $resultado->execute();
    }
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}

// Fechar a conexão
$conexao = null;

?>