<?php

#! Author: Bjorn
# Subitems Management - 80% completed

require_once("custom/php/common.php");
$link = connection();
$report_errors = mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
styling();

$capability = 'manage_subitems';
$user_capability = current_user_can($capability);
if ($user_capability==false) {
    print("Utilizador não possui capabilidade");
}

else if ($user_capability==true)
{
    echo "<div class='clockcap'>";
    $date = date(get_option('date_format'));
    $time = date(get_option('time_format'));
    echo "UTC: <strong>" . $date . "</strong> ".$time ."<br/>";
    echo "</div>";

    print("@sgbd capability: <strong>$capability</strong> <br>");

    if (!isset($_REQUEST["estado"]))
    {
        //verifica se existem tuplos na tabela 'subitem'
        $subitem = "SELECT subitem.* FROM subitem ORDER BY subitem.name";
        $query = mysqli_query($link,$subitem);
        $check_subitem_table = mysqli_num_rows($query);
        if ($check_subitem_table == 0)  {

            print ("Não existem subitens");
        }
        else if ($check_subitem_table > 0)
        {

            print("<h3 class='headingstyle'>Gestão de subitens - Tabela</h3>");

            echo "<table class='mytable'> <tr>
			<th>item</th>
			<th>id</th>
			<th>subitem</th>
			<th>tipo de valor</th>
			<th>nome do campo no formulário</th>
			<th>tipo do campo no formulário</th>
			<th>tipo de unidade</th>
			<th>ordem do campo no formulário</th>
			<th>obrigatório</th>
			<th>estado</th>
			<th>ação</th> </tr>";
            $sql_item = "SELECT item.id,item.name FROM item ORDER BY item.id";
            $query_item = mysqli_query($link,$sql_item);
            $rows_item = mysqli_num_rows($query_item);
            if ($rows_item == null) {
                print ("Não há itens");
            }
            else if ($rows_item > 0)     {

                while ($row_item_fetch = mysqli_fetch_array($query_item,MYSQLI_ASSOC))
                {		//using JOIN or INNER JOIN -> o output da query é o mesmo
                    $SQL_rowspan_item = "SELECT * 
						FROM item JOIN subitem ON subitem.item_id = item.id AND item.id =".$row_item_fetch["id"];
                    $query_rowspan = mysqli_query($link,$SQL_rowspan_item);
                    $rowspan = mysqli_num_rows($query_rowspan);

                    //executa o colspan
                    if ($rowspan == null) {
                        //10 células que pertencem a 'subitem'
                        echo "<tr>	
						<td colspan='1' rowspan='1'> ".$row_item_fetch["name"]." </td>
						
						<td style='text-align:center;' colspan='10' rowspan='1'> 
							<strong>este item não possui subitens.</strong> </td>
						</tr>";
                    }

                    //executa o rowspan
                    else if ($rowspan > NULL)
                    {

                        echo "<tr>
						<td colspan='1' rowspan={$rowspan}> ".$row_item_fetch["name"]." </td>";

                        $sql_subitem = "SELECT subitem.* FROM subitem WHERE subitem.item_id =".$row_item_fetch["id"];
                        $query_subitem = mysqli_query($link,$sql_subitem);
                        $rows_subitem = mysqli_num_rows($query_subitem);
                        if ($rows_subitem == null)
                        {
                            print("0 results");
                        }

                        else if ($rows_subitem > 0)
                        {
                            while ($row_subitem_fetch = mysqli_fetch_array($query_subitem,MYSQLI_ASSOC))
                            {
                                //imprime os dados obtidos na tabela 'subitem'
                                //para cada item correspondente
                                $id = $row_subitem_fetch["id"];
                                $name = $row_subitem_fetch["name"];
                                $value_type = $row_subitem_fetch["value_type"];
                                $form_field_name = $row_subitem_fetch["form_field_name"];
                                $form_field_type = $row_subitem_fetch["form_field_type"];
                                $unit_type_id = $row_subitem_fetch["unit_type_id"];
                                $form_field_order = $row_subitem_fetch["form_field_order"];
                                $mandatory = $row_subitem_fetch["mandatory"];
                                $state = $row_subitem_fetch["state"];

                                //PK
                                if ( $id > NULL )
                                {
                                    print("<td rowspan='1' colspan='1'>{$id}</td>");
                                }

                                //nome
                                if ( $name > NULL )
                                {
                                    print ("<td rowspan='1' colspan='1'>{$name}</td>");
                                }

                                //tipo de valor
                                if ( $value_type > NULL )
                                {
                                    print ("<td rowspan='1' colspan='1'>{$value_type}</td>");
                                }

                                //nome do campo no formulário
                                if ( $form_field_name > NULL )
                                {
                                    print ("<td rowspan='1' colspan='1'>{$form_field_name}</td>");
                                }

                                //tipo do campo no formulário
                                if ( $form_field_type > NULL )
                                {
                                    print ("<td rowspan='1' colspan='1'>{$form_field_type}</td>");
                                }

                                //tipo de unidade
                                if ( $unit_type_id > NULL )
                                {
                                    print ("<td rowspan='1' colspan='1'>{$unit_type_id}</td>");
                                }
                                else if ( $unit_type_id == NULL )
                                {	//no caso de não existir tipo de unidade atribuído a um certo subitem
                                    print ("<td rowspan='1' colspan='1'> - </td>");
                                }

                                //ordem do campo no formulário
                                if ( $form_field_order > NULL )
                                {
                                    print ("<td rowspan='1' colspan='1'>{$form_field_order}</td>");
                                }

                                //obrigatório
                                if ( $mandatory == 0)
                                {
                                    echo "<td rowspan='1' colspan='1'> não </td>";
                                }
                                else if ( $mandatory == 1)
                                {
                                    echo "<td rowspan='1' colspan='1'> sim </td>";
                                }

                                //estado
                                if ( $state > NULL)
                                {
                                    print ("<td rowspan='1' colspan='1'>{$state}</td>");
                                }

                                //ação - links que redirecionam ao componente edição de dados
                                if ($state == "active" )
                                {
                                    echo "<td>
									<a href=http://localhost/sgbd/edicao-de-dados/?estado=editar&nome=gestao-de-subitens&id=".$row_subitem_fetch['id'].">		[editar]	</a>
									
									<a href=http://localhost/sgbd/edicao-de-dados/?estado=desativar&nome=gestao-de-subitens&id=".$row_subitem_fetch['id'].">	[desativar]	</a>
									
									<a href=http://localhost/sgbd/edicao-de-dados/?estado=apagar&nome=gestao-de-subitens&id=".$row_subitem_fetch['id'].">		[apagar]	</a>
									</td> 
									</tr>";	//termina o fetch dos dados
                                }
                                else if ( $state == 'inactive' )
                                {
                                    echo "<td style='text-align:center;' colspan='1' rowspan='1'>
									
									<a href=http://localhost/sgbd/edicao-de-dados/?estado=editar&nome=gestao-de-subitens&id=".$row_subitem_fetch['id'].">		[editar]	</a>
									
									<a href=http://localhost/sgbd/edicao-de-dados/?estado=ativar&nome=gestao-de-subitens&id=".$row_subitem_fetch['id'].">		[ativar]	</a>
									
									<a href=http://localhost/sgbd/edicao-de-dados/?estado=apagar&nome=gestao-de-subitens&id=".$row_subitem_fetch['id'].">		[apagar]	</a>
									</td> 
									</tr>";	//termina o fetch dos dados
                                }
                            }
                        }
                    }
                }
            }
            echo "</table>";
        }

        //COUNTING
        $COUNT_subitem = "SELECT COUNT(subitem.name) AS total_subitems
		FROM item, subitem
		WHERE subitem.item_id = item.id";
        $SQL_COUNT_subitem = mysqli_query($link,$COUNT_subitem);
        $ROW_COUNT_subitem = mysqli_num_rows($SQL_COUNT_subitem);
        if ($ROW_COUNT_subitem == 0)
        {
            print("<p>Não existem tuplos na tabela 'subitem'</p>");
        }
        else if ($ROW_COUNT_subitem > 0){

            //imprime o resultado da contagem
            $fetch_count = mysqli_fetch_array($SQL_COUNT_subitem,MYSQLI_ASSOC);
            print("<span>Total de subitens:<strong>".$fetch_count['total_subitems']."</strong></span>");
        }

        //criação do formulário
        print("<h3 class='headingstyle'>Gestão de subitens - introdução</h3>");
        echo "<legend>Formulário que possibilita a inserção de um novo subitem:</legend>
		<span style='color:#ff0000;'><strong>* Obrigatório</strong></span>
		
		<div class='formcontainer'>
		
		<form action='' method='POST'>";	//inicia formulário

        //text - Nome do subitem - (obrigatório)
        echo "<lable for='nome'><strong>Nome do subitem:</strong></lable>
		<span style='color:#ff0000;'>*</span>
		<input type='text' name='nome' placeholder='Escreva o nome do novo subitem..'> <br><br/>";

        $show_value_type = "SHOW COLUMNS FROM subitem LIKE 'value_type'";
        $query_show_value_type = mysqli_query($link, $show_value_type);
        $check_value_type = mysqli_num_rows($query_show_value_type);

        //radio - Tipo de valor - (obrigatório) - function get_enum_values($connection, $table, $column )
        $get_value_type = get_enum_values($link,'subitem','value_type');
        echo "<lable for='tipo_de_valor'><strong>Tipo de valor:</strong></lable>
		<span style='color:#ff0000;'><strong>*</strong></span>
		<ul>";
        foreach ($get_value_type AS $enum_value_type_array){
            echo "
			<li>
			<input type='radio' name='tipo_de_valor' value={$enum_value_type_array}> ".$enum_value_type_array." </li>";
        }
        echo "</ul>";

        //selectbox - Item - (obrigatório)
        $list_items = "SELECT id, name FROM item"; // query para buscar todos os itens
        $query_items = mysqli_query($link, $list_items);
        $check_list_rows = mysqli_num_rows($query_items);

        if ($check_list_rows == 0)
        {
            print("Não há itens. Insira tuplos na tabela 'item' no phpmyadmin.");

        }
        else if ($check_list_rows > 0)
        {
            echo "<label><strong>Selecione um item para o novo subitem:</strong></label>
			<span style='color:#ff0000;'><strong>*</strong></span>
			
			<select id='escolher_item' name='escolher_item'>
			<option value=''>Escolha um item</option>";
            while ($fetch_items_list = mysqli_fetch_assoc($query_items))
            {
                echo "<option value={$fetch_items_list["id"]}> {$fetch_items_list["name"]} </option>";
            }

            echo "</select>";
        }

        $show_form_field_type = "SHOW COLUMNS FROM subitem LIKE 'form_field_type'";
        $query_show_form_field_type = mysqli_query($link,$show_form_field_type);
        $check_form_field_type = mysqli_num_rows($query_show_form_field_type);

        //radio - Tipo do campo no formulário - (Obrigatório) - function get_enum_values($connection, $table, $column )
        $get_form_field_type = get_enum_values($link,'subitem','form_field_type');
        print("<br> <br/>");
        echo "<lable><strong>Tipo do campo no formulário:</strong></lable>
		<span style='color:#ff0000;'><strong>*</strong></span>
		<ul>";
        foreach($get_form_field_type AS $enum_form_field_type) {

            echo "<li><input type='radio' name='tipo_campo_form' value={$enum_form_field_type}  > {$enum_form_field_type} </li>";
        }
        echo "</ul>";

        //selectbox - Tipo de unidade - (Opcional)
        print("<lable><strong>Tipo de unidade:</strong></lable>");
        $list_units = "SELECT id, name FROM subitem_unit_type ORDER BY name";
        $query_units = mysqli_query($link,$list_units);
        $check_units_rows = mysqli_num_rows($query_units);
        if ($check_units_rows == 0) {
            print("Não existem tuplos na tabela 'subitem_unit_type'.");
        }
        else if ($check_units_rows > 0)
        {
            echo "<select name='escolher_tipo_unidade'> <br>
			<option value=''>Escolha um tipo de unidade</option>";
            while ($fetch_unit_types = mysqli_fetch_assoc($query_units)) {

                echo "<li>
				<option value={$fetch_unit_types["id"]}> ".$fetch_unit_types["name"]." </li>";
            }
            echo "</select>";
        }

        //text - Ordem do campo no formulário - (obrigatório e >0) minlength=1?
        //não podemos inserir strings neste input
        print(" <br> <br/>
		<lable><strong>Ordem do campo no formulário:</strong></lable>
		<span style='color:#ff0000;'><strong>*</strong></span>
		<input type='text' name='ordem_campo_form' minlength='1' placeholder='Digite o valor para a ordem do campo no formulário'>
		<br/>");

        echo "<lable><strong>Obrigatório:</strong></lable> 
		<span style='color:#ff0000;'><strong>*</strong></span><br>";

        // Obrigatório
        echo "
		<input type='radio' name='subitem_estado' value=0> não <br>
				<input type='radio' name='subitem_estado' value=1> sim <br>";

        //Submissão do formulário
        echo "<input type='hidden' name='estado' value='inserir'>";
        echo "<input type='submit' name='submit' value='Inserir subitem'>";

        echo "</form></div>";	//termina formulário
    }

    //se o estado de execução é 'inserir'
    else if (isset($_REQUEST["estado"]) && $_REQUEST["estado"] == "inserir")
    {

        if (isset($_REQUEST["submit"]))
        {

            if (empty($_REQUEST['nome']))
            {
                $msg_missing_nome = print("<h4>Insira o nome do novo subitem.</h4>");
                print("<hr>");
                print("Array que verifica o valor introduzido em cada input: <br>");
                print_r($_REQUEST);
                print("<br>");
                backbutton();
            }

            //verifica se foram inseridos caratéres especiais no nome do item como letras ou acentos
            else if (!preg_match("/^[a-zA-Z\s]+$/", $_REQUEST['nome']))
            {
                $msg_letras_nome = print("<h4>O nome do item apenas pode conter letras e espaços.</h4>");
                print("<hr>");

                print_r($_REQUEST);
                print("<br>");
                backbutton();
            }

            else if (!isset($_REQUEST["tipo_de_valor"]))
            {
                $missing_tipo_de_valor = print("
				<p>O campo <strong>Tipo de valor</strong> é de preenchimento obrigatório.</p>");
                print("<hr>");

                print_r($_REQUEST);
                print("<br>");
                backbutton();
            }

            else if (!isset($_REQUEST["escolher_item"]) || empty($_REQUEST["escolher_item"]))
            {
                $missing_escolher_item = print("
				<p>O campo <strong>Escolher item</strong> é de preenchimento obrigatório.</p>");
                print("<hr>");

                print_r($_REQUEST);
                print("<br>");
                backbutton();
            }

            else if (!isset($_REQUEST["tipo_campo_form"]))
            {
                $missing_tipo_campo_form = print("
				<p>O campo <strong>Tipo do campo no formulário</strong> é de preenchimento obrigatório.</p>");
                print("<hr>");

                print_r($_REQUEST);
                print("<br>");
                backbutton();
            }

            else if (!isset($_REQUEST["escolher_tipo_unidade"]) || empty($_REQUEST["escolher_tipo_unidade"]))
            {
                $missing_escolher_tipo_unidade = print("
				<p>O campo <strong>Tipo de unidade</strong> é de preenchimento obrigatório.</p>");
                print ("<hr>");

                //print_r($missing_escolher_tipo_unidade);
                print_r($_REQUEST);
                print("<br>");
                backbutton();

            }

            //verifica se o campo 'ordem do campo do formulário' foi preenchido
            else if (empty($_REQUEST["ordem_campo_form"]))
            {
                //print("O campo <strong>Ordem do campo no formulário</strong> é de preenchimento obrigatório e um número superior a 0.");
                $missing_ordem_campo_form = print("
				<p>O campo <strong>Ordem do campo no formulário</strong> é de preenchimento obrigatório.</p>");
                print("<hr>");

                //print_r($missing_ordem_campo_form);
                print_r($_REQUEST);
                print("<br>");
                backbutton();
            }

            //Verifica se apenas insere números na ordem do campo no formulário
            else if (!preg_match("/^[0-9]+$/",$_REQUEST["ordem_campo_form"]))
            {
                $form_field_order_has_only_numbers = print("
				O campo <strong>ordem do campo no formulário</strong> apenas pode conter números.");
                print("<hr>");

                print_r($_REQUEST);
                print("<br>");
                backbutton();
            }

            //Validação do campo 'Obrigatório'
            else if (!isset($_REQUEST["subitem_estado"]))
            {
                $missing_mandatory = print("
				<p>O campo <strong>obrigatório</strong> é de preenchimento obrigatório.</p>");
                print("<hr>");

                //print_r($missing_mandatory);

                //Array que verifica o valor introduzido em cada input
                print_r($_REQUEST);
                print("<br>");
                backbutton();
            }

            //Após verificar se todos os inputs do formulário estão preenchidos
            //prossegue à inserção dos dados na tabela 'subitem'
            else
            {

                //Para evitar injeções SQL na base de dados
                $nome = mysqli_real_escape_string($link, $_REQUEST["nome"]);
                $tipo_de_valor = mysqli_real_escape_string($link, $_REQUEST["tipo_de_valor"]);

                $concatenate_form_field_name = "";	//Inicializa a variável com uma string vazia

                $escolher_item = mysqli_real_escape_string($link, $_REQUEST["escolher_item"]);
                $tipo_campo_form = mysqli_real_escape_string($link, $_REQUEST["tipo_campo_form"]);
                $escolher_tipo_unidade = mysqli_real_escape_string($link, $_REQUEST["escolher_tipo_unidade"]);
                $ordem_campo_form = mysqli_real_escape_string($link, $_REQUEST["ordem_campo_form"]);
                $subitem_estado = mysqli_real_escape_string($link, $_REQUEST["subitem_estado"]);

                //form_field_name não incluída nesta query devido a ser inserida inicialmente com uma string vazia
                $sql_verify_subitem_i0 =
                    "SELECT subitem.name, subitem.value_type, subitem.item_id, subitem.form_field_type, subitem.unit_type_id, subitem.form_field_order, subitem.mandatory
				FROM subitem
				WHERE subitem.name = '$nome'
				AND subitem.value_type = '$tipo_de_valor' 
				AND subitem.item_id = '$escolher_item'
				AND subitem.form_field_type = '$tipo_campo_form'
				AND subitem.unit_type_id = '$escolher_tipo_unidade'
				AND subitem.form_field_order = '$ordem_campo_form'
				AND subitem.mandatory = '$subitem_estado'";

                $query_verify_subitem_i0 = mysqli_query($link, $sql_verify_subitem_i0);
                $verify_subitem_duplicate_rows = mysqli_num_rows($query_verify_subitem_i0);

                //verifica se o subitem inserido já existe
                // == NULL -> subitem ainda não existe na base de dados.
                if ($verify_subitem_duplicate_rows == NULL)
                {
                    //__________
                    //SQL INSERT
                    $insert_subitem = "INSERT INTO subitem (name, value_type, form_field_name, item_id, form_field_type, unit_type_id, form_field_order, mandatory)
					VALUES (
						'$nome', 
						'$tipo_de_valor',
						'$concatenate_form_field_name',
						'$escolher_item', 
						'$tipo_campo_form', 
						'$escolher_tipo_unidade', 
						'$ordem_campo_form', 
						'$subitem_estado'
					)";

                    //inserir os dados na base de dados
                    $query_insert = mysqli_query($link,$insert_subitem);
                    if ($query_insert)
                    {
                        print("<h3 class='headingstyle'>Gestão de subitens - inserção</h3>");
                        print("<h3>Introduziu os dados de novo subitem com sucesso</h3>");

                        print("<a href='http://localhost/sgbd/gestao-de-subitens'>Continuar</a>");
                    }
                    else {
                        print_r(mysqli_error($link));	//caso haja erro na inserção dos dados
                    }


                    //Para atualizarmos o valor do atributo 'form_field_name'

                    //buscar o id do item selecionado para o atributo 'form_field_name'
                    $fetch_id = mysqli_insert_id($link);

                    $i2 = "SELECT DISTINCT item.name FROM item
					INNER JOIN subitem ON subitem.item_id = item.id 
					WHERE subitem.item_id = ".$escolher_item;	// busca o id obtido ao selecionar na selectbox do formulário o nome do item
                    $s2 = mysqli_query($link,$i2);
                    $rowi2 = mysqli_fetch_assoc($s2);

                    //$fetch_id = mysqli_insert_id($link);
                    $item_substring = iconv('UTF-8', 'ASCII//TRANSLIT', substr($rowi2["name"], 0, 3));
                    $query_i0 = $item_substring . "-" . $fetch_id . "-" . $_REQUEST["nome"];
                    $query_i0 = str_replace(' ', '_', $query_i0);

                    // converte letras com acentos e outros caratéres para letras sem acentos
                    $query_i0 = iconv('UTF-8', 'ASCII//TRANSLIT', $query_i0);
                    $string = preg_replace('/[^a-z0-9_]/i', '-', $query_i0);
                    echo "<br>";
                    print_r($string);	//Imprime a atualização do atributos

                    //SQL Query para atualizar o atributo 'form_field_name' após a inserção dos dados
                    $UPDATE_form_field_name = "UPDATE subitem SET form_field_name = '". $string."' WHERE id =".$fetch_id;
                    $SQL_form_field_name = mysqli_query($link, $UPDATE_form_field_name);
                    if ($SQL_form_field_name) {
                        print("<br>
						O atributo 'form_field_name' foi atualizado com sucesso na base de dados.");
                    } else {
                        print_r(mysqli_error($link));	//verifica se ocorreu erros na atualização dos dados do atributo
                    }

                    echo "<br>";
                }

                else if ($verify_subitem_duplicate_rows > NULL)
                {

                    print("<h4>Este subitem já existe na base de dados. Insira outro subitem.</h4>");
                    backbutton();
                }
            }
        }
    }


}

?>
