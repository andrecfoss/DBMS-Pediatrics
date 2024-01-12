<?php
require_once("custom/php/common.php");

#! Author: Júlio

global $current_page;
connection();

$verifica_primeiro_inserido = $n_id = 0;
$verifica_se_tem_numero = "";
$confirma_numero =  $unidade_tem_error = 0;

//Verificação da capabilidade
if(is_user_logged_in())
{
    if (current_user_can('manage_unit_types')) {
        if (!isset($_REQUEST['change_state'])) {
            $query_ID_Unidade = "SELECT id, name FROM subitem_unit_type ORDER BY id";
            $result = mysqli_query(connection(), $query_ID_Unidade);
            if (mysqli_num_rows($result) > 0) {
                echo "
                 <table style='width: 100%'> 
                      <tr>
                          <th style='width: 10%'>id</th>
                          <th>Unidades</th>
                          <th>subitem</th>
                          <th>ação</th>
                      </tr>
                ";
                while ($row_id_name = mysqli_fetch_assoc($result)) {
                    $query_subitem = "SELECT subitem.name AS subitem_name, item.name AS item_name FROM subitem JOIN item ON subitem.item_id = item.id WHERE subitem.unit_type_id = " . $row_id_name['id'] ;
                    $result_2 = mysqli_query(connection(), $query_subitem);
                    echo "
                      <tr>
                        <td > " . $row_id_name["id"] . "      </td>
                        <td > " . $row_id_name["name"] . "    </td>
                      <td>
                    ";
                    while ($row_subitem_dados = mysqli_fetch_assoc($result_2)) {
                        if ($verifica_primeiro_inserido == 0) {
                            echo "" . $row_subitem_dados["subitem_name"] . "(" . $row_subitem_dados["item_name"] . ")";
                            $verifica_primeiro_inserido = 1;
                        } else {
                            echo ", " . $row_subitem_dados["subitem_name"] . "(" . $row_subitem_dados["item_name"] . ")";
                        }
                    }
                    echo "</td>";
                    echo "<td>
                            <a href=http://localhost/sgbd/edicao-de-dados?estado=editar&nome=gestao-de-unidades&id=".$row_id_name["id"].">[editar]</a>
                            <a href=http://localhost/sgbd/edicao-de-dados?estado=apagar&nome=gestao-de-unidades&id=".$row_id_name["id"].">[apagar]</a>
                        </td>";
                    $verifica_primeiro_inserido = 0;
                    $n_id = $row_id_name["id"] + 1;
                }
                echo "</table>";
            } else {
                echo "Não há tipos de unidades";
            }

            ######################################################################################################

            echo " <h3> Gestão de unidades - introdução </h3>";

            echo "
                <p <span style='color: red'>* Obrigatório</span></p>
                <form method='post' action= ''>
                    Nome: <span style='color: red'>*</span> <input type='text' name ='nome_unidade'> 
                    <br><br>
                    <input type='submit' name='unidade_inserida' value='Submit'>
                    <input type='hidden' name='change_state' value='inserir'>
                </form>
            ";
        }

        ######################################################################################################

        if ($_REQUEST['change_state'] == "inserir") {

            echo "<h3>Gestão de unidades - inserção </h3>";

            if(!empty($_REQUEST['nome_unidade'])) {
                $verifica_se_tem_numero = $_REQUEST['nome_unidade'][0];
                if (is_numeric($verifica_se_tem_numero)) {
                    $confirma_numero = 1;
                }
            }

            $query_unidade = "SELECT name FROM subitem_unit_type";
            $result_3 = mysqli_query(connection(), $query_unidade);
            while($verifica = mysqli_fetch_assoc($result_3)){
                if( strcmp($verifica['name'], $_REQUEST['nome_unidade']) == 0 && !empty($_REQUEST['nome_unidade'])){
                    echo "<h4><b>Já existe uma unidade com esse nome, escolha outro</b></h4>";
                    backbutton();
                    $unidade_tem_error = 1;
                    break;
                }
            }

            if(!preg_match("/^[\p{Latin}0-9\/]*$/",$_REQUEST['nome_unidade'])){
                echo "
                     <h4><b>O campo não pode conter caracteres especiais ou espaços em branco</b></h4>
                ";
                backbutton();
                $unidade_tem_error = 1;
            }

            elseif (empty($_REQUEST['nome_unidade'])) {
                echo" <h4><b> O campo não pode estar vazio</b></h4>";
                backbutton();
                $unidade_tem_error = 1;
            }
            elseif ($confirma_numero == 1){
                echo "
                <h4><b> O campo não pode conter números</b></h4>";
                backbutton();
            }

            elseif ( $unidade_tem_error == 0) {
                $insere_nova_unidade = "INSERT INTO subitem_unit_type ( name, id ) VALUES ('" . $_REQUEST["nome_unidade"] . "', '" . $n_id . "');";
                $result_inserir = mysqli_query(connection(), $insere_nova_unidade);

                echo "
                    <p> Inseriu os dados de novo tipo de unidade com sucesso</p>
                    <p>Clique em <strong>Continuar</strong> para avançar</p>
                ";
                print("<a style='color: blue;' href='http://localhost/sgbd/gestao-de-unidades'>Continuar</a>");
            }
        }
    }else {
        echo "Não tem autorização para aceder a esta página";
    }
}
?>