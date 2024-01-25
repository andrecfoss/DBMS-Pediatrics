<?php

#! Author: Bjorn
# Items Management - 95% completed

require_once("custom/php/common.php");
$link = connection();
global $current_page;
styling();
$report_errors = mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$capability = 'manage_items';
$user_capability = current_user_can($capability);
if ( $user_capability == false )
{

    printf("<p>@sgbd não possui capabilidade para poder editar esta página.</p>");
    backbutton();
}
else if ( $user_capability == true )
{
    echo "<div class='clockcap'>";
    //Display current date & time
    $date = date(get_option('date_format'));
    $time = date(get_option('time_format'));
    echo "UTC: <strong>" . $date . "</strong> ".$time ."<br/>"; //mostra o dia e hora atual

    //Display user current capability
    print("@sgbd capability: <strong>$capability</strong> <br> </div>");

    /////// Definição dos estados de execução nesta componente /////

    if ( !isset( $_REQUEST["state_exec"]) ) //melhor usar $_REQUEST do que $_POST
    {
        global $msg;
        printf("<h3 class='headingstyle'>Gestão de itens - Tabela</h3>");

        echo
        "
		<table class='mytable'>
		<tr class='tableheader'>
			<th> tipo de item</th> 
				<th>id</th> <th> nome do item</th> 
				<th> estado</th> <th>ação</th>
		</tr>";
        $search_item_types = "SELECT item_type.id, item_type.name 
		FROM item_type ORDER BY item_type.name";
        $query_search_item_types = mysqli_query( $link, $search_item_types );
        $query_check_item_types = mysqli_num_rows( $query_search_item_types );
        if ( $query_check_item_types == NULL ) //0 == NULL
        {
            print("Não existem tuplos na tabela 'item_type'. Insira tuplos no phpmyadmin.");
        }
        else if ( $query_check_item_types > 0)
        {

            while ( $rows_item_type = mysqli_fetch_assoc($query_search_item_types) )
            {
                $item_type_rowspan = "SELECT item_type.*, item.* FROM item_type INNER JOIN item ON item.item_type_id = item_type.id 
				WHERE item_type.id = " . $rows_item_type["id"];
                $query_item_type_rowspan = mysqli_query( $link, $item_type_rowspan );
                $rowspan = mysqli_num_rows($query_item_type_rowspan );
                if ($rowspan == NULL )
                {

                    echo "<tr>
					<td colspan='1' rowspan='1'> {$rows_item_type["name"]} </td>
					<td colspan='4' rowspan='1'> <strong>este tipo de item não possui itens.</strong> </td>
					</tr>";
                }
                else if ( $rowspan > 0)
                {
                    //neste caso já consegue executar o rowspan
                    echo "<tr>
							<td colspan='1' rowspan=".  $rowspan .">" . $rows_item_type["name"] . "</td>";

                    $search_items = "SELECT item.* FROM item WHERE item.item_type_id = " . $rows_item_type["id"];
                    $query_search_items = mysqli_query( $link, $search_items );
                    $query_check_items = mysqli_num_rows($query_search_items);
                    if ( $query_check_items == null)
                    {
                        print("Não existem tuplos na tabela 'item'.");
                    }

                    //caso já hajam tuplos na tabela 'item' na base de dados
                    else if ($query_check_items > 0)
                    {

                        while ($rows_item = mysqli_fetch_assoc($query_search_items))
                        {
                            //busca os tuplos da tabela 'item'
                            if ($rows_item['id'] > NULL)
                            {
                                echo "<td     rowspan='1'     >  {$rows_item["id"]}         </td>";
                            }
                            else if ($rows_item['id'] == 0) {
                                echo "<td rowspan='1' colspan='1'>-</td>";
                            }

                            if ($rows_item['name'] > NULL)
                            {
                                echo "<td     rowspan='1'     >  {$rows_item["name"]}   </td>";
                            }
                            else if ($rows_item['name'] == 0) {
                                echo "<td rowspan='1' colspan='1'>-</td>";
                            }

                            if ($rows_item['state'] > NULL)
                            {
                                echo " <td     rowspan='1'     >  {$rows_item["state"]}    </td>";
                            }
                            else if ($rows_item['state'] == 0) {
                                echo "<td rowspan='1' colspan='1'>-</td>";
                            }

                            //ação - links que redirecionam à edição de dados
                            echo "<td colspan='1' rowspan='1'>
							<a href=http://localhost/sgbd/edicao-de-dados?estado=editar&nome=gestao-de-itens&id=" . $rows_item["id"] . ">                [editar]         </a>";
                            if($rows_item["state"] == "active"){
                                echo"  <a href=http://localhost/sgbd/edicao-de-dados/?&estado=desativar&nome=gestao-de-itens&id=".$rows_item["id"] .">   [desativar]      </a>";
                            }
                            elseif ($rows_item['state'] == "inactive")
                                echo"  <a href=http://localhost/sgbd/edicao-de-dados/?&estado=ativar&nome=gestao-de-itens&id=".$rows_item['id'].">       [ativar]         </a>";
                            echo"
							<a href=http://localhost/sgbd/edicao-de-dados/?&estado=apagar&nome=gestao-de-itens&id=".$rows_item['id'].">                  [apagar]         </a>
						</td> </tr>";   //termina o fetch dos dados
                        }
                    }
                }
            }
        }
        echo "</table> "; //fecha a tabela

        $query_adicional = "SELECT item_type.name AS item_type_name, COUNT(item.name) AS item_name FROM item_type JOIN item ON item.item_type_id = item_type.id";
        $sql_query = mysqli_query($link, $query_adicional);
        $sql_num_rows = mysqli_num_rows($sql_query);

        echo "<h3 class='headingstyle'> Gestão de itens - introdução</h3>";
        print("<legend'>Formulário para inserir um novo item na base de dados:</legend> \n");
        print("<span style='color:#ff0000;'>* Obrigatório</span>")
        ;
        //__FORMULÁRIO__
        echo "
		<div class='formcontainer'>
		<form action='' method='POST'>
		<lable for='nome'><strong>Nome do item:</strong>
		<span style='color:#ff0000;'>*</span></lable>
		<input type='text' name='nome' placeholder='Escreva o nome do novo item que pretende inserir'>
		<br> $msg
		<br>
		<lable for='tipo'> <strong>Tipo de item:</strong> <span style='color:#ff0000;'>*</span> </lable>";
        //listar todos os tipos de itens existentes na base de dados
        $list_item_types = "SELECT item_type.* FROM item_type ORDER BY id";
        //print_r($list_item_types);
        $query_list = mysqli_query( $link, $list_item_types);
        $numrows_list = mysqli_num_rows($query_list);
        if ( $numrows_list == 0)
        {
            print("Não foi possível listar os tipos de itens. Verifique se a tabela 'item_type' tem tuplos.");
        }

        else if ( $numrows_list > null)
        {
            //mysqli_fetch_assoc($query) == mysqli_fetch_array($query, MYSQLI_ASSOC)
            while ( $list_rows = mysqli_fetch_array( $query_list, MYSQLI_ASSOC))
            {
                $_REQUEST['display_list_item_types'] = $list_rows;
                //print_r($list_item_types);
                echo "<ul>
					<li style='line-height: 1em;'> 
					<input type='radio' value={$list_rows["id"]} name='tipo'> {$list_rows["name"]} </li>
					</ul>"; //termina lista
            }
        }

        //mostra os valores para um certo atributo de uma Tabela
        $show_item_state = "SHOW COLUMNS FROM item LIKE 'state'";
        $query_show_item_state = mysqli_query($link,$show_item_state);
        $rows_LIKE_item_state = mysqli_num_rows($query_show_item_state);
        print("<br>");
        echo "<lable for='estado'><strong>Estado do item:</strong> <span style='color:#ff0000;'>*</span> </lable>";
        //recorrendo à função get_enum_values($link, $item, $state);
        $get_item_state_values = get_enum_values( $link,'item','state');
        foreach
        ( $get_item_state_values AS $state_values_array)
        {       //listar o estado para escolher no novo item a inserir
            echo "<ul>
				<li style='line-height: 1em;'> 
				<input type='radio' name='estado' 
						value={$state_values_array}> {$state_values_array}</li>
				</ul>"; //termina lista
        }

        //estado de execução vai receber como parâmetro na variável $_REQUEST 'state_exec'
        echo " <br>
		<input type='hidden' name='state_exec' value='inserir'>
		<input type='submit' name='submit' value='Inserir item'>
		</form>
		  </div>       ";      //termina construção do formulário
    }

    else if ( isset($_REQUEST["state_exec"]) && $_REQUEST["state_exec"] == "inserir" )
    {

        if ( isset($_REQUEST["submit"]) )
        {


            //validação do campo 'Nome'
            if (empty($_REQUEST['nome']))
            {
                echo "<p> Preencha o campo para o nome do item que deseja inserir. </p>";

                backbutton();
                print_r($_REQUEST);
            }

            //verifica se foram inseridos caratéres especiais no nome do item
            else if (!preg_match("/^[a-zA-Z\s]+$/", $_REQUEST['nome']))
            {
                $msg_letras_nome = print("<p> O nome do item apenas pode conter letras e espaços. </p>");
                backbutton();
                print_r($_REQUEST);
            }

            //validação do campo 'Tipo'
            else if (!isset($_REQUEST['tipo'])) {

                $msg_tipo = "<p> O campo <strong>Tipo de item</strong> é de preenchimento Obrigatório </p>";
                print_r($msg_tipo);
                print("<br>");
                backbutton();
                print_r($_REQUEST);
            }
            //validação do campo 'Estado'
            else if (!isset($_REQUEST['estado'])) {

                $msg_estado = "O campo <strong>Estado do item</strong> é de preenchimento Obrigatório";
                print_r($msg_estado);
                print("<br>");
                backbutton();
                print_r($_REQUEST);
            }
            //SQL INSERT
            else
            {
                $items_transaction = mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);
                echo $items_transaction;

                try
                {
                    $input_request_nome = $_REQUEST['nome'];
                    $input_request_tipo = $_REQUEST['tipo'];
                    $input_request_estado = $_REQUEST['estado'];

                    //proteger contra SQL Injection
                    $nome = mysqli_real_escape_string($link, $input_request_nome);
                    $tipo = mysqli_real_escape_string($link, $input_request_tipo);
                    $estado = mysqli_real_escape_string($link, $input_request_estado);

                    //verifica se o item inserido é repetido na base de dados
                    $verify_duplicate_insert = "
					SELECT * FROM item 
					WHERE name='$nome' AND item_type_id='$tipo' AND state='$estado' ";

                    $query_verify_insert = mysqli_query($link, $verify_duplicate_insert);
                    $verify_item_duplicate_rows = mysqli_num_rows($query_verify_insert);

                    if ($verify_item_duplicate_rows == 0)
                    {
                        $INSERT = "INSERT INTO item (name, item_type_id, state) VALUES ('$nome','$tipo','$estado')";
                        $SQL_INSERT = mysqli_query($link,$INSERT);

                        mysqli_commit($link);

                        //verifica se a inserção dos dados de novo item foi realizada com sucesso.
                        if ($SQL_INSERT)
                        {
                            print("<h3 class='headingstyle'>Gestão de itens - inserção</h3>");
                            print("<h3>Introduziu os dados de novo item com sucesso</h3>");

                            //Dados inseridos:
                            print("<strong>Nome do item:</strong> ");
                            print_r($_REQUEST['nome']);
                            print("<br>");
                            print("<strong>Tipo de item:</strong> ");
                            print_r($_REQUEST['tipo']);
                            print("<br>");
                            print("<strong>Estado do item:</strong> ");
                            print_r($_REQUEST['estado']);
                            print("<br>");

                            print("<a style='color: darkblue;' href='http://localhost/sgbd/gestao-de-itens'>Continuar</a>");
                        }
                    }

                    //no caso do item a ser inserido na base de dados já existe
                    else if ($verify_item_duplicate_rows > 0)
                    {
                        print("<h3 class='headingstyle'>Gestão de itens - inserção</h3>");
                        echo "<h4>Este item já existe na base de dados. Insira um item diferente.</h4>";
                        backbutton();
                        print ("<br>");
                        print_r($_REQUEST);
                    }

                }

                    //caso tenha havido uma exceção na transação executada
                catch (mysqli_sql_exception $exception)
                {
                    mysqli_rollback($link);
                    throw $exception;
                }
            }
        }
    }
}

mysqli_close($link);    //fecha a ligação à base de dados

?>
