<?php  //Gestao de registoss
require_once("custom/php/common.php");

#! Author: Diogo

$link=connection( );
global $current_page;



if(!(current_user_can('manage_records'))) 
{
    echo "Não tem autorização para aceder a esta página";
    exit(0);
}
else
{
    //Verifica se o formulario foi submetido
    if ( isset($_REQUEST['estado'])) //isset determina se a variavel é declaravel e tem valor != null
    {
        switch ($_REQUEST['estado'])
        {
            case 'validar':

                if ($_REQUEST["submit"])
                {
                    //Sessions para a implementação na outra pagina não falhar
                    $_SESSION["nome"] = $_REQUEST["nome"];
                    $_SESSION["dataNascimento"] = $_REQUEST["dataNascimento"];
                    $_SESSION["encEducacao"] = $_REQUEST["encEducacao"];
                    $_SESSION["telefoneEnc"] = $_REQUEST["telefoneEnc"];
                    $_SESSION["email"] = $_REQUEST["email"];

                    echo "<h3>Dados de registo - validação</h3>";
                    echo "<p>Confirma que quer inserir os dados na base de dados?</p>";

                    if (empty($_REQUEST["nome"]))
                    {
                        echo  "O nome está vazio, escreva um novo nome.";
                        backbutton();
                        exit;
                    }
                    if(preg_match('/^[ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ]+$/',  $_REQUEST["nome"])) 
                    {
                        echo "O nome tem letras especiais, Reescreva novamente sem os mesmos.";
                        backbutton();
                        exit;
                    }
                    echo "Nome:<strong> " . $_REQUEST["nome"]. "</strong>";
                    echo " <br> ";

                    if (empty($_REQUEST["dataNascimento"])) 
                    {
                        echo  "A data está vazia, escreva uma Data.";
                        backbutton();
                        exit;
                    }
                    if ( !preg_match("/^\d{4}-\d{2}-\d{2}$/",  $_REQUEST["dataNascimento"]) )
                    {
                        echo "A data de nascimento deve estar no formato YYYY-MM-DD.";
                        backbutton();
                        exit;
                    }
                    echo "Data de nascimento: <strong> " .$_REQUEST["dataNascimento"]. "</strong>";
                    echo " <br> ";

                    if (empty($_SESSION["encEducacao"])) 
                    {
                        echo  "O nome do Encarregado de educação está vazio, Escreva o nome do Encarregado .";
                        backbutton();
                        exit;
                    }
                    if(preg_match('/^[ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ]+$/', $_REQUEST["encEducacao"])) 
                    {
                        echo "O nome do Encarregado tem letras especiais, Reescreva novamente sem os mesmos.";
                        backbutton();
                        exit;
                    }
                    else if (!preg_match('/[a-zA-Z]/', $_REQUEST["encEducacao"]))
                    {

                        echo  "O Encarregado de Educação não pode ter numeros, escreva novamente.";
                        backbutton();
                        exit;
                    }
                    echo "Encarregado de Educação: <strong>" .$_REQUEST["encEducacao"]. "</strong>";
                    echo " <br> ";

                    if  (empty($_SESSION["telefoneEnc"]))
                    {
                        echo  "O numero de telefone está vazio, escreva o número.";
                        backbutton();
                        exit;
                    }
                    if(!preg_match('/^[0-9]{9}$/', $_REQUEST["telefoneEnc"]))
                    {
                        echo"O número de telefone tem de estar no formato correto e ter obrigatoriamente apenas 9 numeros, escreva o número novamente";
                        backbutton();
                        exit;

                    }
                    else if(preg_match('/[a-zA-Z]/', $_REQUEST["telefoneEnc"]))
                    {
                        echo "O numero de telefone não pode ter letras, apenas números";
                        backbutton();
                        exit;
                    }
                    echo  "Telefone do Encarregado:<strong> " . $_REQUEST["telefoneEnc"]. "</strong>";
                    echo "<br>";

                    if (empty($_REQUEST["email"]))
                    {
                        echo  "O email está vazio, escreva um novo nome.";
                        backbutton();
                        exit;
                    }

                    if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL))
                    {
                        echo "O email não está no formato válido.";
                        backbutton();
                        exit;
                    }
                    if(preg_match('/^[ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇÑ]+$/', $_REQUEST["email"]))
                    {
                        echo "O email tem letras especiais, Reescreva novamente sem os mesmos.";
                        backbutton();
                        exit;
                    }
                    echo "Email: <strong>" .$_REQUEST["email"]. "</strong>";
                    echo "<br>";


                    echo "<form method='POST' action=''>
                    <input type='hidden' name= 'estado' value='inserir'>
                    <input type='submit' name='submit_form' value='Inserir registo'>
                    </form>";
                    backbutton();



                }

                break;
            case 'inserir': //form

                if ($_REQUEST["submit_form"])
                {

                    print("<h3>Gestão de registos - inserção</h3>");


                    // Construir o comando SQL para inserção
                    $nome = isset($_SESSION["nome"]) ? mysqli_real_escape_string($link,$_SESSION["nome"]) : "";
                    $dataNascimento =isset($_SESSION["dataNascimento"]) ? mysqli_real_escape_string($link,  $_SESSION["dataNascimento"] ): "dataNascimento"; 
                    $encEducacao = isset($_SESSION["encEducacao"]) ?mysqli_real_escape_string($link,$_SESSION["encEducacao"] ): "encEducacao";  
                    $telefoneEnc = isset($_SESSION["telefoneEnc"]) ? mysqli_real_escape_string($link, $_SESSION["telefoneEnc"] ): "telefoneEnc" ; 
                    $email =  isset($_SESSION["email"])? mysqli_real_escape_string($link,  $_SESSION["email"] ): "email"; 


                    $queryInserir = "INSERT INTO child  (name, birth_date, tutor_name, tutor_phone, tutor_email) 
                    VALUES ('". $_SESSION["nome"]. "', '".$_SESSION["dataNascimento"]."',  '".$_SESSION["encEducacao"]."', 
                    '". $_SESSION["telefoneEnc"]."',  '". $_SESSION["email"]. "')" ;  


                    $resultInserir = mysqli_query($link, $queryInserir);

                    // Verificar se a inserção foi bem-sucedida
                    if ($resultInserir)
                    {
                        echo "Inseriu os dados de registo com sucesso. Clique em <a href='http://localhost/sgbd/gestao-de-registos/'>Continuar</a> para avançar.";
                    }
                    else
                    {
                        echo "Erro ao inserir os dados de registo.";

                        echo mysqli_error($link);
                    }
                }
                backbutton();
                break;

            default:
                echo "Estado inválido";
                break;

        }
    }
    else //disply d  tabela
    {

        echo '<table cellspacing= "2"  cellpadding="2 " border= "1" width="100%" class="mytable">
    <tbody>
    <tr> 
        <th><b>Nome</b></th> 
        <th><b>Data de nascimento</b></th>
        <th><b>Enc. De Educacao</b></th> 
        <th><b>Telefone do Enc.</b></th>
        <th><b>e-mail</b></th> 
        <th><b>registos</b></th>
    </tr>';

        $Query_BuscarCrianca= 'SELECT DISTINCT * FROM child  ORDER BY name ';
        $resultado_Criancas=mysqli_query($link,$Query_BuscarCrianca) ;
        if (mysqli_num_rows($resultado_Criancas) > 0 )
        {
            while ($child = mysqli_fetch_assoc($resultado_Criancas))
            {

                echo "<tr>
                <td>{$child['name']} </td>
                <td>{$child['birth_date']} </td>
                <td>{$child['tutor_name']} </td>
                <td>{$child['tutor_phone']} </td>
                <td>{$child['tutor_email']} </td>";


                $query_registos = "SELECT DISTINCT subitem.id AS subitem_id, item.name AS item_name, value.date, value.producer
            FROM child, value, item, subitem 
            WHERE value.child_id = child.id 
            AND value.subitem_id = subitem.id 
            AND subitem.item_id = item.id
            AND child.id = {$child['id']}";

                $result_registos = mysqli_query($link, $query_registos);   

                if (mysqli_num_rows($result_registos) > 0 ) 
                { 

                    echo "<td>";

                    $current_item_name = null;    
                    while ($records_row = mysqli_fetch_assoc($result_registos)) 
                    { 


                        if (mysqli_num_rows($result_registos) > 0)
                        {  
                            //  concatenação dos atributos  para a colun dos registoss

                            if ($records_row["item_name"] !== $current_item_name)
                            { 
                                //para colocar a variável a letras maiúsculas:
                                printf("<br>");  
                                print(strtoupper($records_row["item_name"])) .  ":";
                                printf("<br>"); 
                                $current_item_name = $records_row["item_name"];


                                $query_registos2 = "SELECT DISTINCT value.value, subitem.name AS subitem_name, value.date, value.producer 
                                                FROM value
                                                JOIN subitem ON value.subitem_id = subitem.id
                                                JOIN child ON value.child_id = child.id
                                                JOIN item ON subitem.item_id = item.id
                                                WHERE value.child_id = {$child['id']}
                                                AND item.name = '{$records_row['item_name']}'
                                                ORDER BY value.date, value.producer, subitem.name";  // Order by date, p roducer, and subitem name" isto po melhor display na tabela


                                $result_registos2 = mysqli_query($link, $query_registos2);
                                if (mysqli_num_rows($result_registos2) > 0)
                                {
                                    $previousDate = null;
                                    $previousProducer = null;
                                    $output = "";
                                    while ($records_row2 = mysqli_fetch_assoc($result_registos2))
                                    {

                                        if ($records_row2['date'] != $previousDate || $records_row2['producer'] != $previousProducer)
                                        {
                                            // Display  tipo editar/apagar,   date,  producer
                                            if ($output != "" )
                                            {
                                                echo rtrim($output, "; ") .   "<br>";  // Print newline after each subitem group
                                                $output = "";   // resetar o output para outro  grup
                                            }

                                            echo "<a href=http://localhost/sgbd/edicao-de-dados?estado=editar&nome=gestao-de-registos&id=".$child['id']."&item_name=".$records_row['item_name']."&date=".$records_row2['date']."&producer=".$records_row2['producer'].">  [editar]</a> 
                                                    <a href=http://localhost/sgbd/edicao-de-dados?estado=apagar&nome=gestao-de-registos&id=".$child['id']."&item_name=".$records_row['item_name']."&date=".$records_row2['date']."&producer=".$records_row2['producer']." >  [apagar]</a> - "
                                                . "<strong>" ."{$records_row2['date']} </strong> ({$records_row2['producer']}) - ";
                                            // atualiza   os valores anteriores da data e producer
                                            $previousDate =$records_row2['date'];
                                            $previousProducer =  $records_row2['producer'];
                                        }
                                        // Aqui eu  dou append da info do subitem po output

                                        if  ($records_row2['value'] != "" && $records_row2["subitem_name"] != ""  &&  $records_row2["value"] !== "" )
                                        {
                                            $output .= "<strong>".  $records_row2["subitem_name"].  "</strong> (". $records_row2["value"] . "); " ;
                                        }
                                    }
                                    echo  rtrim($output, "; ") .  "<br>";

                                }
                                else
                                {
                                    echo "";
                                }
                            }



                        }
                        else
                        {
                            // handle no   item_ name
                        }

                    }

                    echo  "</td>";
                }
                else
                {
                    // Se n existir registros
                    echo "<td> <em>Criança sem registos</em> </td>";
                }

                echo "</td>  </tr>"; 
            }
        } // Fecha a condição if (mysqli_num_r...($res.._Cr...) > 0 )
        else
        {
            echo"Não há crianças" ;
        }

        echo"</tbody> </table>" ;


        echo "<h3>Dados de registo - introdução</h3>";
        echo '<form method="post" action="">
        Nome Completo (Obrigatório):  <input type ="text" name="nome"> <br>
        Data de Nascimento: <input type="date" name= "dataNascimento" > <br>
        Enc. De Educacao:   <input type="text" name= "encEducacao"  ><br>
        Telefone do Enc.:   <input type="text" name= "telefoneEnc"  ><br>
        E-mail:             <input type="text" name="email"  ><br>
                            <input type="hidden" name= "estado" value="validar" >
                            <input type= "submit" name="submit" value="Submeter" >
      </form>';

    }
}



?>