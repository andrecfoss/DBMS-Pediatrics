<?php

require_once("custom/php/common.php");
$link = connection();
global $current_page;

#! Author: Júlio

//variáveis de suporte
$conta_num_rows = $conta_valores_permitidos = $conta_valores_permitidos_2 = $tem_erro = $valor_id = 0;

if(is_user_logged_in()){
    if(current_user_can('values_import')){
        if(!isset($_REQUEST['estado'])){

            //query inicia que preenche a coluna com os itens disponiveis
            $query_item_nome = "SELECT DISTINCT item.name as item_name, item.id as item_id FROM item, subitem_allowed_value, subitem WHERE
                         subitem.item_id = item.id and subitem.value_type = 'enum' GROUP BY item.name";
            $result_item_nome = mysqli_query($link, $query_item_nome);

            echo"<table> 
                <tr>
                    <td><strong>item</strong>  </td>
                    <td><strong>id</strong>  </td>
                    <td> <strong>subitem</strong> </td>
                    <td> <strong>id</strong> </td>
                    <td> <strong>valores primitivos</strong> </td>
                    <td> <strong>estado</strong> </td>
                    <td> <strong>ação</strong> </td>
                </tr>";

            if(mysqli_num_rows($result_item_nome) > 0) {
                while ($item_nome = mysqli_fetch_assoc($result_item_nome)) {

                    //query que chama o numero de valores permitidos de cada item
                    $query_conta_rowspan = "SELECT item.name, subitem.name, subitem_allowed_value.value FROM item, subitem, subitem_allowed_value 
                                                            WHERE item.id = " . $item_nome['item_id'] . " and subitem.item_id= item.id and subitem_allowed_value.subitem_id = subitem.id";
                    $result_conta_rowspan = mysqli_query($link, $query_conta_rowspan);

                    //variavel que conta o numero de valores permitidos
                    $conta_num_rows = mysqli_num_rows($result_conta_rowspan);

                    //query que preenche na coluna os subitens disponiveis para cada item
                    $query_num_subitens = "SELECT subitem.id as subitem_id FROM item, subitem_allowed_value, subitem 
                     WHERE " . $item_nome['item_id'] . " = item.id and subitem.item_id = item.id and subitem.value_type = 'enum' GROUP BY subitem.id";
                    $result_num_subitens = mysqli_query($link, $query_num_subitens);

                    //Verifica se tem valores permitidos
                    while ($subitens = mysqli_fetch_assoc($result_num_subitens)) {

                        //query que chama os valores permitidos de cada subitem
                        $query_num_valores = "SELECT * FROM item, subitem_allowed_value, subitem 
                                                   WHERE " . $subitens['subitem_id'] . " = subitem_allowed_value.subitem_id GROUP BY subitem_allowed_value.id ";
                        $result_num_valores = mysqli_query($link, $query_num_valores);

                        //variavel para contar o numero de rowspan em cada subitem
                        $conta_valores_permitidos = mysqli_num_rows($result_num_valores);

                        if ($conta_valores_permitidos == 0) {
                            $conta_num_rows = $conta_num_rows + 1;
                        }
                    }

                    echo "
                        <tr>
                            <td rowspan='$conta_num_rows'> " . $item_nome['item_name'] . "</td>
                        
                    ";

                    //query que preenche na coluna os subitens disponiveis para cada item
                    $query_subitem_dados = "SELECT  subitem.name as subitem_name, subitem.id as subitem_id FROM item, subitem_allowed_value, subitem 
                     WHERE " . $item_nome['item_id'] . " = item.id and subitem.item_id = item.id and subitem.value_type = 'enum' GROUP BY subitem.id";
                    $result_subitem_dados = mysqli_query($link, $query_subitem_dados);


                    while ($subitem_dados = mysqli_fetch_assoc($result_subitem_dados)) {

                        //query para preencher na coluna os valores permitidos em cada subitem
                        $query_subitem_allowed_value = "SELECT subitem_allowed_value.value as value_id, subitem_allowed_value.id as id, subitem_allowed_value.state as state FROM item, subitem_allowed_value, subitem 
                                                   WHERE " . $subitem_dados['subitem_id'] . " = subitem_allowed_value.subitem_id GROUP BY subitem_allowed_value.id ";
                        $result_subitem_allowed_value = mysqli_query($link, $query_subitem_allowed_value);

                        //variavel para contar o numero de rowspan em cada subitem
                        $conta_valores_permitidos_2 = mysqli_num_rows($result_subitem_allowed_value);

                        if($conta_valores_permitidos_2 == 0){
                            echo "
                             <td rowspan='1'> " . $subitem_dados['subitem_id'] . "</td>
                             <td rowspan='1'> <a href='http://localhost/sgbd/gestao-de-valores-permitidos/?estado=introducao&subitem=" . $subitem_dados['subitem_id']."'> [" . $subitem_dados['subitem_name'] . "]</a></td>                            
                             <td colspan='4'>	Não há valores permitidos definidos  </td>
                             </tr>";
                        }
                        else {
                            echo "
                             <td rowspan='$conta_valores_permitidos_2'> " . $subitem_dados['subitem_id'] . "</td>
                             <td rowspan='$conta_valores_permitidos_2'> <a href='http://localhost/sgbd/gestao-de-valores-permitidos/?estado=introducao&subitem=" . $subitem_dados['subitem_id']."'> [" . $subitem_dados['subitem_name'] . "]</a></td>                            
                            ";
                            while ($subitem_allowed_value = mysqli_fetch_assoc($result_subitem_allowed_value)) {

                                if ($conta_valores_permitidos_2 != 0) {
                                    echo "   
                                <td>" . $subitem_allowed_value['id'] . "</td>
                                <td>" . $subitem_allowed_value['value_id'] . "</td>
                                <td>" . $subitem_allowed_value['state'] . " </td>
                                <td>
                                    <a href=http://localhost/sgbd/edicao-de-dados/?estado=editar&nome=gestao-de-valores-permitidos&id=".$subitem_allowed_value['id'].">[editar]</a>";
                                    if($subitem_allowed_value['state'] == "active"){
                                        echo"  <a href=http://localhost/sgbd/edicao-de-dados/?&estado=desativar&nome=gestao-de-valores-permitidos&id=".$subitem_allowed_value['id'].">[desativar]</a>";
                                    }
                                    elseif ($subitem_allowed_value['state'] == "inactive")
                                        echo"  <a href=http://localhost/sgbd/edicao-de-dados/?&estado=ativar&nome=gestao-de-valores-permitidos&id=".$subitem_allowed_value['id'].">[ativar]</a>";
                                    echo"
                                    <a href=http://localhost/sgbd/edicao-de-dados?estado=apagar&nome=gestao-de-valores-permitidos&id=".$subitem_allowed_value['id'].">[apagar]</a>
                                    </td>
                                </tr>
                                ";
                                    $valor_id = $subitem_allowed_value["id"] + 1;
                                }
                            }
                        }
                    }
                }
            }
            echo"</table>";

        }
        elseif($_REQUEST['estado'] == "introducao"){

            echo"<h2> Gestão de valores permitidos - Introdução </h2>";

            echo "
                <p <span style='color: red'>* Obrigatório</span></p>
                <form method='post' action= ''>
                    Valor: <span style='color: red'>*</span> <input type='text' name ='valor_inserido'> 
                    <br><br>
                    <input type='submit' name='valor_inserida' value='INSERIR VALOR PERMITIDO'>
                    <input type='hidden' name='estado' value='inserir'>
                </form>
            ";
            backbutton();
        }
        elseif($_REQUEST['estado'] == "inserir"){
            echo"<h2> Gestão de valores permitidos - Inserção </h2>";

            $query_valor = "SELECT subitem_allowed_value.value as value_id FROM item, subitem_allowed_value, subitem 
                            WHERE subitem.id = subitem_allowed_value.subitem_id GROUP BY subitem_allowed_value.id ";
            $result_3 = mysqli_query($link, $query_valor);

            while($verifica = mysqli_fetch_assoc($result_3)){
                if( strcmp($verifica['value_id'], $_REQUEST['valor_inserido']) == 0 && !empty($_REQUEST['valor_inserido'])){
                    echo "<h4><b>Já existe um valores permitidos com esse nome, escolha outro</b></h4>";
                    backbutton();
                    $tem_erro = 1;
                    break;
                }
            }

            if (!preg_match("/^[\\p{Latin}'\\s-]*$/u", $_REQUEST['valor_inserido'])) {
                echo "<h4><b>O campo não pode conter caracteres especiais ou números</b></h4>";
                backbutton();
                $tem_erro = 1;
            }

            elseif (empty($_REQUEST['valor_inserido'])) {
                echo "<h4><b> O campo não pode estar vazio</b></h4>";
                backbutton();
                $tem_erro = 1;
            }

            elseif ( $tem_erro == 0) {
                $insere_novo_valor_permitido = "INSERT INTO subitem_allowed_value ( value, id, subitem_id ) VALUES ('" . $_REQUEST["valor_inserido"] . "', '" . $valor_id . "', '".$_REQUEST['subitem']."');";
                $result_inserir = mysqli_query($link, $insere_novo_valor_permitido);

                echo "
                    <h4><p> Inseriu os dados de um novo valore permitido com sucesso</p>
                    <p>Clique em Continuar para avançar</h4>
                ";
                print("<a style='color: blue;' href='http://localhost/sgbd/gestao-de-valores-permitidos'>Continuar</a>");
            }
        }
    }
}
else{
    echo"Não tem autorização para aceder a esta página";
}

?>