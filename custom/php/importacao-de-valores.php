<?php

require_once("custom/php/common.php");
$link = connection();
styling();	//CSS

#! Author: Bjorn

$report_errors = mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$capability = "values_import";
$user_capability = current_user_can($capability);
if ($user_capability == true) 
{	
	echo "<div class='clockcap'>";
    
    $date = date(get_option('date_format'));
    $time = date(get_option('time_format'));
    echo "UTC: <strong>" . $date . "</strong> ".$time ."<br/>"; //mostra o dia e hora atual

    print("@sgbd capability: <strong>$capability</strong> <br> </div>");
	
	$return_initial_state = !isset($_REQUEST["estado"]);
	
	//estado de execução inicial é vazio
	if ($return_initial_state) 
	{
		$store_child_id = null;	//inicializa a variável que guarda o id da criança escolhida
		
		print ("<h3 class='headingstyle'>Importação de valores - escolher criança</h3>");
		//inicia tabela e cabeçalho
		echo "<table class='mytable'> 
		<tr> <th>Nome</th> <th>Data de nascimento</th> 
		<th>Enc. de Educação</th> 
		<th>Telefone do Enc.</th> 
		<th>e-mail</th> 
		</tr>";
		$list_child = "SELECT child.id AS id_child, name, birth_date, tutor_name, tutor_phone, tutor_email 
		FROM child ORDER BY name";
		$query_child = mysqli_query($link,$list_child);
		$child_exists = mysqli_num_rows($query_child);
		if ($child_exists == FALSE) {
			print("Não há crianças");
		}
		else if ($child_exists > 0) {
			
			while ($row_child = mysqli_fetch_assoc($query_child)) 
			{
				
				//$store_child_id = $row_child["id_child"];
				
				// link para avançar ao estado 'escolheritem' após escolher nome da criança
				$link_child = "<a href='importacao-de-valores?estado=escolheritem&crianca={$row_child["id_child"]}'> [{$row_child["name"]}] </a>";
				
				echo "<tr>
			<td colspan='1' rowspan='1'> $link_child </td>
			<td colspan='1' rowspan='1'> {$row_child["birth_date"]} </td>
			<td colspan='1' rowspan='1'> {$row_child["tutor_name"]} </td>
			<td colspan='1' rowspan='1'> {$row_child["tutor_phone"]} </td>
			<td colspan='1' rowspan='1'> {$row_child["tutor_email"]} </td>
			</tr>";
			}
			
		}
		echo "</table>";	//fecha a tabela
		
		$COUNT_child = "SELECT COUNT(*) AS count_child FROM child";
		$SQL_COUNT_child = mysqli_query($link,$COUNT_child);
		$SQL_COUNT_fetch_child = mysqli_fetch_array($SQL_COUNT_child, MYSQLI_ASSOC);
		printf("Total de crianças: <strong>".$SQL_COUNT_fetch_child["count_child"]."</strong>");
		
		//echo "<h3 id='mytest'>Teste</h3>";	//Testar CSS..
	}
	
	// se o estado de execução é 'escolheritem'
	else if (isset($_REQUEST["estado"]) && $_REQUEST["estado"] == "escolheritem") 
	{
		//print_r($_REQUEST["row_child"]);
		
		print ("<h3 class='headingstyle'>Importação de valores - escolher item</h3>");
		
		$sql0 = "SELECT id, name FROM item_type";
		$query0 = mysqli_query($link, $sql0);
		$rows0 = mysqli_num_rows($query0);
		if ($rows0 > 0) {
			
			echo "<ul>";	
			while ($item_type = mysqli_fetch_assoc($query0)) {
				
				echo "<li>".$item_type["name"]; 
				$sql1 = "SELECT name, id FROM item WHERE item_type_id =".$item_type["id"];
				$query1 = mysqli_query($link, $sql1);
				$rows1 = mysqli_num_rows($query1);
				if ($rows1 > 0) {
					
					echo "<ul>";	
					while ($item = mysqli_fetch_assoc($query1)) 
					{
						
						 $store_item_id = $item["id"];
						 $crianca = isset($_REQUEST["crianca"]) ? "&crianca={$_REQUEST['crianca']}" : "";
						echo "<li>
						<a href='importacao-de-valores?estado=introducao&crianca={$crianca}&item={$store_item_id}'>
						[".$item["name"]."]</a>";
					}
					echo "</li></ul>";
					
				} else if ($rows1 == 0) {
					
					echo "<br> Não existem quaisquer itens para este tipo de item.";
				}
			}
			echo "</li>
			</ul>";
		} 
		else if ($rows == 0) {
			echo "Não há itens.";
		}
		
		$COUNT_item_type = "SELECT COUNT(*) AS count_item_type FROM item_type";
		$SQL_COUNT_item_type = mysqli_query($link, $COUNT_item_type);
		$SQL_COUNT_fetch_item_type = mysqli_fetch_assoc($SQL_COUNT_item_type);
		printf("Total de tipos de itens: <strong>".$SQL_COUNT_fetch_item_type["count_item_type"]."</strong>");
		echo "<br>";
		$COUNT_item = "SELECT COUNT(*) AS count_item FROM item";
		$SQL_COUNT_item = mysqli_query($link, $COUNT_item);
		$SQL_COUNT_fetch_item = mysqli_fetch_assoc($SQL_COUNT_item);
		printf("Total de itens: <strong>".$SQL_COUNT_fetch_item["count_item"]."</strong>");
		echo "<br>";
		backbutton();	
	}
	
	else if (isset($_REQUEST["estado"]) && $_REQUEST["estado"] == "introducao")
	{
		print("<h3 class='headingstyle'>Importação de valores - introdução</h3>");
		
		print ("ID da criança escolhida: <strong>" . $_REQUEST["crianca"] . "</strong><br>");
		print ("ID do item escolhido: <strong>" . $_REQUEST["item"] . "</strong><br>");
		
		
		//TABELA.......
		$query_i0 = "
		SELECT subitem.form_field_name, subitem.id, subitem_allowed_value.value FROM subitem 
		JOIN item ON subitem.item_id = item.id 
		JOIN subitem_allowed_value ON subitem_allowed_value.subitem_id = subitem.id WHERE item.id = ".$_REQUEST['item'];
		$sql_i0 = mysqli_query($link, $query_i0);
		$rows_i0 = mysqli_num_rows($sql_i0);
		if ($rows_i0 > 0) 
		{
			echo "<table>";
			
			   echo "<tr>";
			while ($rowi0 = mysqli_fetch_assoc($sql_i0)) 
			{	//echo "<tr>";
				echo "<th rowspan='1'>   {$rowi0['form_field_name']}    </th>";
				//echo "</tr>";
			}
			echo "</tr>";
			
			   $result = $sql_i0;	//busca resultado da query 
			   $offset = 0;		//coloca todos os valores dos tuplos em colunas
			
			    mysqli_data_seek($result, $offset);

			echo "<tr>";
			while ($rowi0 = mysqli_fetch_assoc($sql_i0)) 
			{
				echo "<th rowspan='1'>{$rowi0['id']}  </th>";
			}
			echo "</tr>";
			
			  $result = $sql_i0;
			 $offset = 0;
			mysqli_data_seek($result, $offset);

				echo "<tr>";
			while ($rowi0 = mysqli_fetch_assoc($sql_i0)) 
			{
				echo "<th rowspan='1'>{$rowi0['value']}</th>";
			}
			echo "</tr>";
			
			echo "</table>";
		
			echo "<legend>No caso de importar os dados da Tabela, ler o seguinte:</legend>";
			// Parágrafo a representar como importar a spreadsheet e como esta deve ser apresentada
			
			print("<p> <pre> 
			As linhas desta tabela devem ser copiadas para um ficheiro .xlsx e introduzir os valores a importar. 

			No caso dos subitens cujo tipo de valor são 'enum', deve constar: <br>
			0: quando esse valor permitido não se aplicar à instância em causa. 
			1: caso esse valor se aplique. 

			O ficheiro deve estar em (PATH), sendo o nome do ficheiro import_to_insert.xlsx.
			</pre> </p>
			");
		
			//Formulário para avançar ao próximo estado de execução
			echo "<form action='' method='POST'>
			<input type='hidden' name='estado' value='insercao'>
			<input type='submit' name='submit' value='Carregar Ficheiro'>";
			
			backbutton();
		}
		
		else if ( $rows_i0 == 0) {
			
			echo "<h4>Não existem subitens para este item escolhido.</h4>";
			backbutton();
		}
	}
	
	// se o estado de execução é 'insercao'
	else if (isset($_REQUEST["estado"]) && $_REQUEST["estado"] == "insercao")
	{
		print("<h3 class='headingstyle'>Importação de valores - inserção</h3>");
		
		//se houve falha na submissão dos dados
		if (empty($_REQUEST["submit"]))
		{
			$msg_submit = 
			print("<h4>Falha na submissão dos dados.</h4>");
			print_r($msg_submit);
		}
		
		//caso exista valor recebido na submissão
		else if (isset($_REQUEST["submit"]))
		{
			echo "<h4>Importação a ser realizada.</h4>";
			echo "<a href='http://localhost/sgbd/importacao-de-valores'>Continuar</a>";
		}
		echo "<br>";
		backbutton();
	}
}

?>