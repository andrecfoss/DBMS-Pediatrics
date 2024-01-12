
<?php

#Author: Júlio

require_once("custom/php/common.php");

$link = connection();

global $current_page;

//Gestão de Unidades
$unidade_tem_erro =$confirma_numero = 0;

//Gestao de Itens
$caracter_do_item = "";
$confirma_numero_item = $tem_erro_item = 0;

//Gestao de Valores Permitidos
$confirma_numero_valor = $tem_erro_valor = 0;

//Gestao de Subitens
$tem_erro_subitem = $verifica_numero_subitem = $confirma_numero_subitem = 0;

//Gestão de Registo

$repete_dados =$repete_dados_registo_apaga=$numero_id= 0;
$guarda_date=$guarda_time = "";


if (is_user_logged_in()) {

        //Secção Editar

        if ($_REQUEST['estado'] == "editar") {

            ################################## GESTAO DE UNIDADES ##########################################

            if ($_REQUEST['nome'] == "gestao-de-unidades") {
                if (!isset($_REQUEST['editar_unidade'])) {

                    $query_chama_nome_unidade = "SELECT * FROM subitem_unit_type WHERE id = " . $_REQUEST['id'];
                    $result_chama_nome_unidade = mysqli_query($link, $query_chama_nome_unidade);

                    while ($mostra_tabela_unidade = mysqli_fetch_assoc($result_chama_nome_unidade)) {
                        echo "
                            <p>
                            <table>
                                <tr>
                                    <td style='width: 10%'> <strong> id     </td>
                                    <td>             <strong> name   </td>
                                </tr>
                                <tr>
                                    <td>" . $mostra_tabela_unidade['id'] . "</td>
                                    <td>
                                        <form method='post' action=''>
                                            <input type='text' value='" . $mostra_tabela_unidade['name'] . "' name='nova_unidade'>                             
                                    </td>
                                </tr>
                            <table>
                            <input type='submit' name='submeter_tipo' value='SUBMETER'>
                            <input type='hidden' name='editar_unidade' value='inserir_nome'>
                            </form>
                            <p>
                        ";
                        backbutton();
                    }
                } elseif ($_REQUEST['editar_unidade'] == "inserir_nome") {

                    $query_verifica_nome_gestao_unidades = "SELECT name FROM subitem_unit_type";
                    $result_verifica_nome_gestao_unidades = mysqli_query($link, $query_verifica_nome_gestao_unidades);

                    if(!empty($_REQUEST['nome_unidade'])) {
                        $verifica_se_tem_numero = $_REQUEST['nome_unidade'][0];
                        if (is_numeric($verifica_se_tem_numero)) {
                            $confirma_numero = 1;
                        }
                    }

                    while ($row_verifica_unidade = mysqli_fetch_assoc($result_verifica_nome_gestao_unidades)) {
                        if (strcmp($row_verifica_unidade['name'], $_REQUEST['nova_unidade']) == 0 && !empty($_REQUEST['nova_unidade'])) {
                            echo "<h4><b>Já existe uma unidade com esse nome, escolha outro</b></h4>";
                            backbutton();
                            $unidade_tem_erro = 1;
                            break;
                        }
                    }

                    if(!preg_match("/^[\p{Latin}0-9\/']*$/u",$_REQUEST['nova_unidade'])){
                        echo "
                            <h4><b>O campo não pode conter caracteres especiais ou espaços em branco</b></h4>
                        ";
                        backbutton();
                        $unidade_tem_error = 1;
                    }

                    elseif (empty($_REQUEST['nova_unidade'])) {
                        echo" <h4><b> O campo não pode estar vazio</b></h4>";
                        backbutton();
                        $unidade_tem_error = 1;
                    }
                    elseif ($confirma_numero == 1){
                        echo "
                            <h4><b> O campo não pode conter números</b></h4>";
                        backbutton();
                    }

                    elseif ($unidade_tem_erro == 0) {
                        $insere_nova_unidade = "UPDATE subitem_unit_type  SET  name ='" . $_REQUEST["nova_unidade"] . "' WHERE id =" . $_REQUEST['id'];
                        $result_nova_unidade = mysqli_query($link, $insere_nova_unidade);
                        echo "<h4><p> Atualizações realizadas com sucesso</p>";
                        print("<a style='color: blue;' href='http://localhost/sgbd/gestao-de-unidades'>Continuar</a>");
                    }
                }
            } ################################## GESTAO DE VALORES PERMITIDOS ##########################################

            elseif ($_REQUEST['nome'] == "gestao-de-valores-permitidos") {
                if (!isset($_REQUEST['editar_valor_permitido'])) {

                    $query_chama_valor_permitido_dados = "SELECT * FROM subitem_allowed_value WHERE id =" . $_REQUEST['id'];
                    $result_chama_valor_permitido_dados = mysqli_query($link, $query_chama_valor_permitido_dados);

                    while ($row_cham_valor_permitido = mysqli_fetch_assoc($result_chama_valor_permitido_dados)) {
                        echo "
                            <p>
                            <table>
                                <tr>
                                    <td style='width: 10%'>        <strong> id         </td>
                                    <td>                    <strong>subitem_id  </td>
                                    <td>                    <strong>value       </td>
                                    <td>                    <strong>state       </td>
                                </tr>
                                <tr>
                                    <td>" . $row_cham_valor_permitido['id'] . "</td>
                                    <td> <form method='post' action=''>
                                        <select id='subitem' name='subitem_escolha'>";

                        $query_num_valores = "SELECT subitem.id as subitem_id FROM item, subitem_allowed_value, subitem 
                                                              WHERE  subitem.item_id = item.id and subitem.value_type = 'enum' GROUP BY subitem.id";
                        $result_num_valores = mysqli_query($link, $query_num_valores);

                        while ($row = mysqli_fetch_assoc($result_num_valores)) {
                            echo "<option> " . $row['subitem_id'] . "</option>";
                        }

                        echo "          </select>                               
                                    </td>
                                    <td>
                                        <input type='text' value='" . $row_cham_valor_permitido['value'] . "' name='novo_valor'>
                                    </td>
                                    <td> " . $row_cham_valor_permitido['state'] . "</td>
                                </tr>
                            </table>
                         <input type='submit' name='submeter_valor' value='SUBMETER'>
                         <input type='hidden' name='editar_valor_permitido' value='inserir_novo_valor'>
                         </form>
                         <p>
                        ";
                        backbutton();
                    }
                } elseif ($_REQUEST['editar_valor_permitido'] == "inserir_novo_valor") {

                    $query_verifica_valor_permitido = "SELECT subitem_allowed_value.value as value_id FROM item, subitem_allowed_value, subitem 
                                             WHERE subitem.id = subitem_allowed_value.subitem_id GROUP BY subitem_allowed_value.id ";
                    $result_verifica_valor_permitido = mysqli_query($link, $query_verifica_valor_permitido);

                    while ($row_verifica_valor_permitido = mysqli_fetch_assoc($result_verifica_valor_permitido)) {
                        if (strcmp($row_verifica_valor_permitido['value_id'], $_REQUEST['novo_valor']) == 0 && !empty($_REQUEST['novo_valor'])) {
                            echo "<h4><b>Já existe um valores permitidos com esse nome, escolha outro</b></h4>";
                            backbutton();
                            $tem_erro_valor = 1;
                        }
                    }

                    if (!preg_match("/^[\\p{Latin}'\\s-]*$/u", $_REQUEST['novo_valor'])) {
                        echo "<h4><b>O campo não pode conter caracteres especiais ou números</b></h4>";
                        backbutton();
                        $tem_erro_valor = 1;
                    }
                    elseif (empty($_REQUEST['novo_valor'])) {
                        echo "<h4><b> O campo não pode estar vazio</b></h4>";
                        backbutton();
                        $tem_erro_valor = 1;
                    }
                    elseif ($tem_erro_valor == 0) {

                        $insere_novo_valor = "UPDATE subitem_allowed_value  SET  value ='" . $_REQUEST["novo_valor"] . "', id = '" . $_REQUEST['id'] . "',subitem_id = '" . $_REQUEST['subitem_escolha'] . "' WHERE id =" . $_REQUEST['id'];
                        $result_novo_valor = mysqli_query($link, $insere_novo_valor);

                        echo "<h4><p> Atualizações realizadas com sucesso</p>";
                        print("<a style='color: blue;' href='http://localhost/sgbd/gestao-de-valores-permitidos'>Continuar</a>");
                    }
                }
            } ################################## GESTAO DE ITENS ##########################################

            elseif ($_REQUEST['nome'] == "gestao-de-itens") {
                if (!isset($_REQUEST['editar_item'])) {

                    $quey_chama_dados_gestao_de_item = "SELECT * FROM item WHERE id=" . $_REQUEST['id'];
                    $result_chama_dados_gestao_de_item = mysqli_query($link, $quey_chama_dados_gestao_de_item);

                    while ($row_chama_dados_gestao_de_item = mysqli_fetch_assoc($result_chama_dados_gestao_de_item)) {
                        echo " 
                            <table>
                                <tr>
                                    <td style='width: 10%'> <strong>id              </td>
                                    <td>                    <strong>name            </td>
                                    <td>                    <strong>item type id    </td>
                                    <td>                    <strong>state           </td>
                                </tr>
                                <tr>
                                    <td>" . $_REQUEST['id'] . "</td>
                                    <td> <form method='post' action=''>   
                                        <input type='text' value='" . $row_chama_dados_gestao_de_item['name'] . "' name='novo_item'>                          
                                    </td>
                                    <td>
                                        <select id='subitem' name='subitem_escolha'>";
                        $query_item_id = "SELECT id FROM item_type";
                        $result_item_id = mysqli_query($link, $query_item_id);
                        while ($row = mysqli_fetch_assoc($result_item_id)) {
                            echo "<option> " . $row['id'] . "</option>";
                        }
                        echo "          </select>  
                                    </td>
                                    <td> " . $row_chama_dados_gestao_de_item['state'] . "</td >
                                </tr >
                            </table >
                            <input type = 'submit' name = 'submeter_item' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'editar_item' value = 'inserir_item' >
                            </form >
                            <p >
                        ";
                        backbutton();
                    }
                } elseif ($_REQUEST['editar_item'] == "inserir_item") {

                    $query_item = "SELECT name FROM item";
                    $result_item = mysqli_query($link, $query_item);
                    while ($verifica = mysqli_fetch_assoc($result_item)) {
                        if (strcmp($verifica['name'], $_REQUEST['novo_item']) == 0 && !empty($_REQUEST['novo_item'])) {
                            echo "<h4 ><b > Já existe uma unidade com esse nome, escolha outro </b ></h4 > ";
                            backbutton();
                            $tem_erro_item = 1;
                            break;
                        }
                    }

                    if (!preg_match("/^[\\p{Latin}'\\s-]*$/u", $_REQUEST['novo_item'])) {
                        echo "<h4 ><b > O campo não pode conter caracteres especiais ou números</b ></h4 >";
                        backbutton();
                        $tem_erro_item = 1;
                    } elseif (empty($_REQUEST['novo_item'])) {
                        echo " <h4 ><b > O campo não pode estar vazio </b ></h4 > ";
                        backbutton();
                        $tem_erro_item = 1;
                    } elseif ($tem_erro_item == 0) {

                        $insere_novo_item = "UPDATE item  SET  name ='" . $_REQUEST["novo_item"] . "', id = '" . $_REQUEST['id'] . "',item_type_id = '" . $_REQUEST['subitem_escolha'] . "' WHERE id =" . $_REQUEST['id'];
                        $result_novo_item = mysqli_query($link, $insere_novo_item);

                        echo "<p > <strong> Atualizações realizadas com sucesso</p>";

                        print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-itens' > Continuar</a > ");
                    }
                }

                ################################## GESTAO DE SUBITENS ##########################################

            } elseif ($_REQUEST['nome'] == "gestao-de-subitens") {
                if (!isset($_REQUEST['editar_subitem'])) {

                    $query_chama_dados_gestao_subitens = "SELECT * FROM subitem WHERE id= " . $_REQUEST['id'];
                    $result_chama_dados_gestao_subitens = mysqli_query($link, $query_chama_dados_gestao_subitens);

                    while ($row_chama_dados_gestao_subitens = mysqli_fetch_assoc($result_chama_dados_gestao_subitens)) {

                        echo "<table >
                            <tr > 
                                <td><strong> id                 </td >
                                <td><strong> name               </td >
                                <td><strong> item_id            </td >
                                <td><strong> value_type         </td >
                                <td><strong> form_field_name    </td >
                                <td><strong> form_field_type    </td >
                                <td><strong> unit_type_id       </td >
                                <td><strong> form_field_order   </td >
                                <td><strong> mandatory          </td >
                                <td><strong> state              </td >
                            </tr >
                            <tr >
                                <td ><strong > " . $row_chama_dados_gestao_subitens['id'] . "</strong ></td >
                                <td >
                                    <form method='post' action=''>
                                        <input type = 'text' value = '" . $row_chama_dados_gestao_subitens['name'] . "' name = 'editar_nome_subitens' >
                                </td>
                                <td >
                                    <select id = 'seleciona_id' name = 'seleciona_id' > ";
                        $chama_item_ids = "SELECT item_id FROM subitem ";
                        $result_chama_ids = mysqli_query($link, $chama_item_ids);
                        while ($chama_id = mysqli_fetch_assoc($result_chama_ids)) {
                            echo " <option> " . $chama_id['item_id'] . "</option > ";
                        }
                        echo "
                                    </select >
                                </td >
                            <td><strong > " . $row_chama_dados_gestao_subitens['value_type'] . "         </strong ></td >
                            <td><strong > " . $row_chama_dados_gestao_subitens['form_field_name'] . "    </strong ></td >
                            <td><strong > " . $row_chama_dados_gestao_subitens['form_field_type'] . "    </strong ></td >
                            <td>
                               <select id = 'unit_type' name = 'unit_type_id' >
                                  <option> Selecione uma das opções </option > ";
                        $chama_unit_type = "SELECT DISTINCT unit_type_id FROM subitem";
                        $result_chama_unit_type = mysqli_query($link, $chama_unit_type);
                        while ($chama_unit = mysqli_fetch_assoc($result_chama_unit_type)) {
                            echo " <option>" . $chama_unit['unit_type_id'] . "</option > ";
                        }
                        echo "
                               </select >
                            </td >
                            <td> <input name='form_field_order' pattern='[0-9' value='" . $row_chama_dados_gestao_subitens['form_field_order'] . "'></td >
                            <td >
                                <select id = 'select_mandatory' name = 'mandatory' >
                                    <option>1</option >
                                    <option>0</option >
                                </select >
                            </td >
                            <td> <strong > " . $row_chama_dados_gestao_subitens['state'] . "</strong></td >              
                            </tr >
                            </table >
                                            
                            <input type = 'submit' name = 'editar_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'editar_subitem' value = 'editar_subitem_valores' >
                        </form >
                        <p>";
                        backbutton();
                        break;
                    }
                } elseif ($_REQUEST['editar_subitem'] == "editar_subitem_valores") {

                    $query_subitem = "SELECT name FROM subitem";
                    $result_subitem = mysqli_query($link, $query_subitem);

                    while ($verifica_subitem = mysqli_fetch_assoc($result_subitem)) {
                        if (strcmp($verifica_subitem['name'], $_REQUEST['editar_nome_subitens']) == 0 && !empty($_REQUEST['editar_nome_subitens'])) {
                            echo "<h4 ><b > Já existe um subitem com esse nome, escolha outro </b ></h4 > ";
                            backbutton();
                            $tem_erro_subitem = 1;
                            break;
                        }
                    }

                    if (!preg_match("/^[\\p{Latin}'\\s-]*$/u", $_REQUEST['editar_nome_subitens'])) {
                        echo "<h4 ><b > O campo não pode conter caracteres especiais ou números</b ></h4 >";
                        backbutton();
                        $tem_erro_subitem = 1;
                    } elseif (!is_numeric($_REQUEST['form_field_order']) && !empty($_REQUEST['form_field_order'])) {
                        echo "<h4 ><b > O campo 'form_field_order' não pode conter letras</b ></h4 >";
                        backbutton();
                        $tem_erro_subitem = 1;
                    } elseif (empty($_REQUEST['editar_nome_subitens'])) {
                        echo " <h4 ><b > O campo 'name' não pode estar vazio </b ></h4 > ";
                        backbutton();
                        $tem_erro_subitem = 1;

                    } elseif ($_REQUEST['unit_type_id'] == "Selecione uma das opções") {
                        echo " <h4 ><b > O campo 'unit_type_id' não pode estar vazio </b ></h4 > ";
                        backbutton();
                        $tem_erro_subitem = 1;

                    } elseif (empty($_REQUEST['form_field_order'])) {
                        echo " <h4 ><b > O campo 'form_field_order' não pode estar vazio </b ></h4 > ";
                        backbutton();
                        $tem_erro_subitem = 1;

                    } elseif ($tem_erro_subitem == 0) {

                        $insere_novo_subitem = "UPDATE subitem  SET  name ='" . $_REQUEST["editar_nome_subitens"] . "', item_id = '" . $_REQUEST['seleciona_id'] . "',unit_type_id = '" . $_REQUEST['unit_type_id'] . "', form_field_order = '" . $_REQUEST['form_field_order'] . "', mandatory = '" . $_REQUEST['mandatory'] . "' 
                                                WHERE id =" . $_REQUEST['id'];
                        $result_novo_subitem = mysqli_query($link, $insere_novo_subitem);

                        echo "
                            <p > Atualizações realizadas com sucesso </p >
                        ";
                        print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-subitens' > Continuar</a > ");
                    }
                }
            } ################################## GESTAO DE REGISTOS ##########################################

            elseif ($_REQUEST['nome'] == "gestao-de-registos") {
                if (!isset($_REQUEST['editar_registo'])) {
                    echo "
                            <table>
                                <tr>
                                    <td> <strong> id                </td>
                                    <td> <strong> child_id          </td>
                                    <td> <strong> subitem_id        </td>
                                    <td> <strong> value             </td>
                                    <td> <strong> date              </td>
                                    <td> <strong> time              </td>
                                    <td> <strong> producer          </td>
                                </tr>
                                <tr>";


                    $query_chama_dados_iguais = "SELECT DISTINCT value.value,value.id as value_id, subitem.id as subitem_id,value.child_id as child_id,
                                                value.date as date, value.time as time, value.producer as producer, subitem.name as subitem_name
                                                FROM value
                                                JOIN subitem ON value.subitem_id = subitem.id
                                                JOIN child ON value.child_id = child.id
                                                JOIN item ON subitem.item_id = item.id
                                                WHERE value.child_id = '{$_REQUEST['id']}'
                                                AND item.name = '{$_REQUEST['item_name']}'
                                                AND value.value <> ''
                                                AND value.date ='{$_REQUEST['date']}'
                                                AND (value.producer ='{$_REQUEST['producer']}'";
                    $result_chama_dados_iguais = mysqli_query($link, $query_chama_dados_iguais);


                    while ($row_chama_dados_gestao_de_registo = mysqli_fetch_assoc($result_chama_dados_iguais)) {
                        echo "
                                    <td><strong>                           " . $row_chama_dados_gestao_de_registo['value_id'] . "         </td>
                                    <td><strong>                           " . $row_chama_dados_gestao_de_registo['child_id'] . "         </td>
                                    <td><strong>                           " . $row_chama_dados_gestao_de_registo['subitem_id'] . "       </td>";

                        $query_insere_dados_registo = "SELECT DISTINCT value FROM value WHERE child_id = " . $row_chama_dados_gestao_de_registo['child_id'] . " 
                                                                AND subitem_id = " . $row_chama_dados_gestao_de_registo['subitem_id'];
                        $result_insere_dados_registo = mysqli_query($link, $query_insere_dados_registo);

                        if (is_numeric($row_chama_dados_gestao_de_registo['value'])) {
                            echo "<td> <input type='number' name='novo_valor'   value='" . $row_chama_dados_gestao_de_registo['value'] . "' </td>";
                        }

                        elseif ($row_chama_dados_gestao_de_registo['subitem_id'] == "69") {
                            $query_insere_dados_registo = "SELECT DISTINCT value FROM value WHERE child_id = " . $row_chama_dados_gestao_de_registo['child_id'] . " 
                                                                AND subitem_id = '69'";
                            $result_insere_dados_registo = mysqli_query($link, $query_insere_dados_registo);
                            echo "<td>";
                            while ($row_insere_dados_registo = mysqli_fetch_assoc($result_insere_dados_registo)) {
                                echo " <input type='radio' name='novo_valor_six_nine'>" . $row_insere_dados_registo['value'] . "    <br> ";
                            }
                            echo "</td>";
                        }

                        elseif ($row_chama_dados_gestao_de_registo['subitem_id'] == "5") {
                            $query_insere_dados_registo = "SELECT DISTINCT value FROM value WHERE child_id = " . $row_chama_dados_gestao_de_registo['child_id'] . " 
                                                                AND subitem_id = '5'";
                            $result_insere_dados_registo = mysqli_query($link, $query_insere_dados_registo);
                            echo "<td>";
                            while ($row_insere_dados_registo = mysqli_fetch_assoc($result_insere_dados_registo)) {
                                if (preg_match("/^[\\p{Latin}'\\s-]*$/u", $row_insere_dados_registo['value'])) {
                                    echo " <input type='radio' name='novo_valor'>" . $row_insere_dados_registo['value'] . "    <br> ";
                                }
                            }
                            echo "</td>";
                        }

                        else {
                            echo " <td><input type='text' name='novo_valor' value='" . $row_chama_dados_gestao_de_registo['value'] . "'</td> ";
                        }

                        echo "
                               <td><strong>                           " . $row_chama_dados_gestao_de_registo['date'] . "             </td>
                               <td><strong>                           " . $row_chama_dados_gestao_de_registo['time'] . "             </td>
                               <td><strong>                           " . $row_chama_dados_gestao_de_registo['producer'] . "         </td>
                             </tr>    
                        ";
                    }

                    echo "</table>
                    <form method='post' action=''>
                          <input type='submit' name='submit' value='SUBMETER' >
                          <input type='hidden' name='editar_registo' value='editar_registo' >
                    </form>";

                }
                elseif ($_REQUEST['editar_registo'] == "editar_registo") {
                    backbutton();
                }
            }
        }

        ####################################################################################################################################################################################################################

        //SECCAO APAGAR

        #########################################################################################################################################################################################################

        elseif ($_REQUEST['estado'] == "apagar") {

            ################################## GESTAO DE UNIDADES ##########################################

            if ($_REQUEST['nome'] == "gestao-de-unidades") {
                if (!isset($_REQUEST['apaga_unidade'])) {

                    $query_apaga_nome_unidade = "SELECT * FROM subitem_unit_type WHERE id = " . $_REQUEST['id'];
                    $result_apaga_nome_unidade = mysqli_query($link, $query_apaga_nome_unidade);

                    while ($mostra_tabela_unidade_apaga = mysqli_fetch_assoc($result_apaga_nome_unidade)) {
                        echo "
                        <p > Estamos prestes a apagar os dados abaixo da base de dados . Confirma que pretende apagar mesmo os dados ?</p >
                        <table >
                            <tr >
                                <td style = 'width: 10%' >      <strong > id        </td >
                                <td >                    <strong > name      </td >
                            </tr >
                            <tr >
                                <td > " . $mostra_tabela_unidade_apaga['id'] .   "</td >
                                <td > " . $mostra_tabela_unidade_apaga['name'] . "</td >
                            </tr >
                        </table >
                        <form method = 'post' action = '' >
                            <input type = 'submit' name = 'apaga_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'apaga_unidade' value = 'apaga_unidade' >       
                        </form >
                        <p > 
                        ";
                        backbutton();
                    }
                }
                elseif ($_REQUEST['apaga_unidade'] == "apaga_unidade") {

                    $insere_nova_unidade = "DELETE FROM subitem_unit_type WHERE id = " . $_REQUEST['id'];
                    $result_inserir = mysqli_query($link, $insere_nova_unidade);

                    echo "<h4 ><p ><strong> Eliminações realizadas com sucesso </p >";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-unidades' > Continuar</a > ");
                }
            } ################################## GESTAO DE VALORES PERMITIDOS ##########################################

            elseif ($_REQUEST['nome'] == "gestao-de-valores-permitidos") {
                if (!isset($_REQUEST['apaga_valor_permitido'])) {

                    $query_apaga_valor_permitido_dados = "SELECT * FROM subitem_allowed_value WHERE id =" . $_REQUEST['id'];
                    $result_apaga_valor_permitido_dados = mysqli_query($link, $query_apaga_valor_permitido_dados);

                    echo " 
                    <p > Estamos prestes a apagar os dados abaixo da base de dados . Confirma que pretende apagar mesmo os dados ?</p >
                    <table >
                    <tr >
                        <td style = 'width: 10%' > <strong > id</strong ></td >
                        <td > <strong > subitem_id</strong ></td >
                        <td > <strong > value</strong ></td >
                        <td > <strong > state</strong ></td >
                    </tr >
                    <tr >
                    </tr >
                    <tr >";
                    while ($row_apaga_valor_permitido = mysqli_fetch_assoc($result_apaga_valor_permitido_dados)) {
                        echo "
                        <td > " . $row_apaga_valor_permitido['id'] . "         </td >
                        <td > " . $row_apaga_valor_permitido['subitem_id'] . " </td >
                        <td > " . $row_apaga_valor_permitido['value'] . "      </td >
                        <td > " . $row_apaga_valor_permitido['state'] . "      </td >                       
                    </tr >";
                    }
                    echo "
                    </table >
                    <form method = 'post' action = '' >
                            <input type = 'submit' name = 'apaga_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'apaga_valor_permitido' value = 'apaga_valor' >       
                    </form >
                    <p > ";
                    backbutton();
                } elseif ($_REQUEST['apaga_valor_permitido'] == "apaga_valor") {
                    $insere_nova_unidade = "DELETE FROM subitem_allowed_value WHERE id = " . $_REQUEST['id'];
                    $result_inserir = mysqli_query($link, $insere_nova_unidade);

                    echo "
                        <h4 ><p ><strong> Eliminações realizadas com sucesso </p >
                    ";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-valores-permitidos' > Continuar</a > ");
                }
            }

            ################################## GESTAO DE ITENS ##########################################

            if ($_REQUEST['nome'] == "gestao-de-itens") {
                if (!isset($_REQUEST['apaga_item'])) {

                    $apaga_gestao_de_item = "SELECT * FROM item WHERE id=" . $_REQUEST['id'];
                    $result_apaga_gestao_de_item = mysqli_query($link, $apaga_gestao_de_item);

                    echo " 
                    <p > Estamos prestes a apagar os dados abaixo da base de dados . Confirma que pretende apagar mesmo os dados ?</p >
                    <table >
                    <tr >
                        <td style = 'width: 10%' >      <strong > id                </td >
                        <td >                           <strong > name              </td >
                        <td >                           <strong > item type id      </td >
                        <td >                           <strong > state             </td >
                    </tr >";
                    while ($row_apaga_gestao_de_item = mysqli_fetch_assoc($result_apaga_gestao_de_item)) {
                        echo "
                    <tr >
                        <td > " . $row_apaga_gestao_de_item['id'] . "</td >
                        <td > " . $row_apaga_gestao_de_item['name'] . "</td >
                        <td > " . $row_apaga_gestao_de_item['item_type_id'] . "</td >
                        <td > " . $row_apaga_gestao_de_item['state'] . "</td >
                    </tr >";
                    }
                    echo "
                    </table >
                    <form method = 'post' action = '' >
                            <input type = 'submit' name = 'apaga_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'apaga_item' value = 'apaga_item' >       
                    </form >
                    <p > ";
                    backbutton();
                } elseif ($_REQUEST['apaga_item'] == "apaga_item") {
                    $apaga_nova_unidade = "DELETE FROM item WHERE id = " . $_REQUEST['id'];
                    $result_apaga_unidade = mysqli_query($link, $apaga_nova_unidade);

                    echo "
                        <h4 ><p><strong> Eliminações realizadas com sucesso </p >
                    ";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-itens' > Continuar</a > ");
                }
            }

            ################################## GESTAO DE SUBITENS ##########################################

            if ($_REQUEST['nome'] == "gestao-de-subitens") {
                if (!isset($_REQUEST['apaga_subitem'])) {

                    $chama_apaga_gestao_subitens = "SELECT * FROM subitem WHERE id= " . $_REQUEST['id'];
                    $result_apaga_gestao_subitens = mysqli_query($link, $chama_apaga_gestao_subitens);
                    while ($row_apaga_gestao_subitens = mysqli_fetch_assoc($result_apaga_gestao_subitens)) {
                        echo "
                        <p > Estamos prestes a apagar os dados abaixo da base de dados. Confirma que pretende apagar mesmo os dados?</p >
                        <table >
                            <tr > 
                                <td><strong> id                 </td >
                                <td><strong> name               </td >
                                <td><strong> item_id            </td >
                                <td><strong> value_type         </td >
                                <td><strong> form_field_name    </td >
                                <td><strong> form_field_type    </td >
                                <td><strong> unit_type_id       </td >
                                <td><strong> form_field_order   </td >
                                <td><strong> mandatory          </td >
                                <td><strong> state              </td >
                            </tr >
                            <tr >
                                <td > " . $row_apaga_gestao_subitens['id'] . "               </td >
                                <td > " . $row_apaga_gestao_subitens['name'] . "             </td >
                                <td > " . $row_apaga_gestao_subitens['item_id'] . "          </td >
                                <td > " . $row_apaga_gestao_subitens['value_type'] . "       </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_name'] . "  </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_type'] . "  </td >
                                <td > " . $row_apaga_gestao_subitens['unit_type_id'] . "     </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_order'] . " </td >
                                <td > " . $row_apaga_gestao_subitens['mandatory'] . "        </td >
                                <td > " . $row_apaga_gestao_subitens['state'] . "            </td >
                            </tr >
                        </table >
                        <form method='post' action=''>
                            <input type = 'submit' name = 'apagar_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'apaga_subitem' value = 'apaga_subitem' >
                        </form>
                        <p>
                        ";

                        backbutton();
                        break;
                    }

                } elseif ($_REQUEST['apaga_subitem'] == "apaga_subitem") {


                    $apaga_value_subitem_id = "DELETE FROM value WHERE subitem_id= " . $_REQUEST['id'];
                    $apaga_subitem_id ="DELETE FROM subitem WHERE id =" . $_REQUEST['id'];
                    $apaga_item_id = "DELETE FROM item WHERE id IN (SELECT item_id FROM subitem WHERE id = " . $_REQUEST['id'].")";

                    $result_apaga_value_subitem_id = mysqli_query($link, $apaga_value_subitem_id);
                    $result_apaga_subitem_id = mysqli_query($link, $apaga_subitem_id);
                    $result_apaga_item_id= mysqli_query($link, $apaga_item_id);

                    echo "
                        <h4 ><p ><strong> Eliminações realizadas com sucesso </p >
                    ";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-subitens' > Continuar</a > ");
                }
            }

            ################################## GESTAO DE REGISTO ##########################################

            elseif($_REQUEST['nome'] == "gestao-de-registos"){
                if(!isset($_REQUEST['apagar_registo'])) {
                    echo "
                            <table>
                                <tr>
                                    <td> <strong> id                </td>
                                    <td> <strong> child_id          </td>
                                    <td> <strong> subitem_id        </td>
                                    <td> <strong> value             </td>
                                    <td> <strong> date              </td>
                                    <td> <strong> time              </td>
                                    <td> <strong> producer          </td>
                                </tr>
                                <tr>";

                    $query_apaga_gestao_registo = "SELECT DISTINCT value.value as value, value.subitem_id AS subitem_id, value.date as date, value.producer as producer, value.id as value_id, 
                                                value.time as time, value.child_id as child_id
                                                FROM value
                                                JOIN subitem ON value.subitem_id = subitem.id
                                                JOIN child ON value.child_id = child.id
                                                JOIN item ON subitem.item_id = item.id
                                                WHERE value.child_id = {$_REQUEST['id']}
                                                AND item.name = '{$_REQUEST['item_name']}'
                                                AND value.value <> ''
                                                AND value.date ='{$_REQUEST['date']}'
                                                AND value.producer ='{$_REQUEST['producer']}'";
                    $result_apaga_gestao_registo = mysqli_query($link, $query_apaga_gestao_registo);

                    while ($row_apaga_dados_gestao_de_registo = mysqli_fetch_assoc($result_apaga_gestao_registo)) {
                                echo "<tr>
                                        <td> <strong> " . $row_apaga_dados_gestao_de_registo['value_id'] . "  </td>
                                        <td> <strong> " . $row_apaga_dados_gestao_de_registo['child_id'] . "  </td>
                                        <td> <strong> " . $row_apaga_dados_gestao_de_registo['subitem_id'] . "  </td>
                                        <td> <strong> " . $row_apaga_dados_gestao_de_registo['value'] . "  </td>
                                        <td> <strong> " . $row_apaga_dados_gestao_de_registo['date'] . "  </td>
                                        <td> <strong> " . $row_apaga_dados_gestao_de_registo['time'] . "  </td>
                                        <td> <strong> " . $row_apaga_dados_gestao_de_registo['producer'] . "  </td>
                                    </tr>
                                ";


                    }
                    echo "</table>
                        <form method = 'post' action = '' >
                            <input type = 'submit' name = 'apaga_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'apagar_registo' value = 'apaga_registo' >       
                        </form >
                        <p >
                    ";
                    backbutton();
                }
                elseif($_REQUEST['apagar_registo'] == "apaga_registo"){

                    $apaga_registo = "DELETE FROM value WHERE child_id = '{$_REQUEST['id']}' AND date = '{$_REQUEST['date']}' AND producer ='{$_REQUEST['producer']}'";
                    $result_apaga_registo = mysqli_query($link, $apaga_registo);

                    echo "
                        <h4 ><p ><strong> Eliminações realizadas com sucesso </p >
                    ";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-registo' > Continuar</a > ");
                }
            }
        }

        ####################################################################################################################################################################################################################

        //SECCAO ATIVAR

        #########################################################################################################################################################################################################


        elseif ($_REQUEST['estado'] == "ativar") {

            ################################## GESTAO DE VALORES PERMITIDOS ##########################################

            if ($_REQUEST['nome'] == "gestao-de-valores-permitidos") {
                if (!isset($_REQUEST['ativar_valor_permitido'])) {
                    $query_ativa_valor_permitido_dados = "SELECT * FROM subitem_allowed_value WHERE id =" . $_REQUEST['id'];
                    $result_ativa_valor_permitido_dados = mysqli_query($link, $query_ativa_valor_permitido_dados);

                    echo " 
                    <p > Pretende ativar o item?</p >
                    <table >
                    <tr >
                        <td style = 'width: 10%' > <strong > id             </strong ></td >
                        <td >                      <strong > subitem_id     </strong ></td >
                        <td >                      <strong > value          </strong ></td >
                        <td >                      <strong > state          </strong ></td >
                    </tr >
                    <tr >
                    </tr >
                    <tr >";
                    while ($row_ativa_valor_permitido = mysqli_fetch_assoc($result_ativa_valor_permitido_dados)) {
                        echo "
                        <td > " . $row_ativa_valor_permitido['id'] . "            </td >
                        <td > " . $row_ativa_valor_permitido['subitem_id'] . "    </td >
                        <td > " . $row_ativa_valor_permitido['value'] . "         </td >
                        <td > " . $row_ativa_valor_permitido['state'] . "         </td >                       
                    </tr >";
                    }
                    echo "
                    </table >
                    <form method = 'post' action = '' >
                            <input type = 'submit' name = 'apaga_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'ativar_valor_permitido' value = 'ativar' >       
                    </form >
                    <p > ";
                    backbutton();
                } elseif ($_REQUEST['ativar_valor_permitido'] == "ativar") {
                    $query_ativa = "UPDATE subitem_allowed_value SET state = 'active' WHERE id = " . $_REQUEST['id'];
                    $result_ativa = mysqli_query($link, $query_ativa);
                    echo "
                        <h4 ><p ><strong> Atualizações realizadas com sucesso </p >
                    ";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-valores-permitidos' > Continuar</a > ");
                }
            } ################################## GESTAO DE ITENS ##########################################

            elseif ($_REQUEST['nome'] == "gestao-de-itens") {
                if (!isset($_REQUEST['ativar_item'])) {
                    $ativa_gestao_de_item = "SELECT * FROM item WHERE id=" . $_REQUEST['id'];
                    $result_ativa_gestao_de_item = mysqli_query($link, $ativa_gestao_de_item);

                    echo " 
                    <p > Pretende ativar o item?</p >
                    <table >
                    <tr >
                        <td style = 'width: 10%' > <strong > id              </strong ></td >
                        <td >                      <strong > name            </strong ></td >
                        <td >                      <strong > item type id    </strong ></td >
                        <td >                      <strong > state           </strong ></td >
                    </tr >";
                    while ($row_ativa_gestao_de_item = mysqli_fetch_assoc($result_ativa_gestao_de_item)) {
                        echo "
                    <tr >
                        <td > " . $row_ativa_gestao_de_item['id'] . "               </td >
                        <td > " . $row_ativa_gestao_de_item['name'] . "             </td >
                        <td > " . $row_ativa_gestao_de_item['item_type_id'] . "     </td >
                        <td > " . $row_ativa_gestao_de_item['state'] . "            </td >
                    </tr >";
                    }
                    echo "
                    </table >
                    <form method = 'post' action = '' >
                            <input type = 'submit' name = 'ativar' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'ativar_item' value = 'ativar_item' >       
                    </form >
                    <p > ";
                    backbutton();
                } elseif ($_REQUEST['ativar_item'] == "ativar_item") {
                    $insere_ativa_item = "UPDATE item SET state = 'active' WHERE id = " . $_REQUEST['id'];
                    $result_ativa_item = mysqli_query($link, $insere_ativa_item);

                    echo "
                        <h4 ><p><strong> Atualizações realizadas com sucesso </p >
                    ";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-itens' > Continuar</a > ");
                }
            } ################################## GESTAO DE SUBITENS ##########################################

            elseif ($_REQUEST['nome'] == "gestao-de-subitens") {
                if (!isset($_REQUEST['ativar_subitem'])) {

                    $chama_apaga_gestao_subitens = "SELECT * FROM subitem WHERE id= " . $_REQUEST['id'];
                    $result_apaga_gestao_subitens = mysqli_query($link, $chama_apaga_gestao_subitens);
                    while ($row_apaga_gestao_subitens = mysqli_fetch_assoc($result_apaga_gestao_subitens)) {
                        echo "
                       <p > Pretende ativar o subitem?</p >
                        <table >
                            <tr > 
                                <td><strong> id                 </td >
                                <td><strong> name               </td >
                                <td><strong> item_id            </td >
                                <td><strong> value_type         </td >
                                <td><strong> form_field_name    </td >
                                <td><strong> form_field_type    </td >
                                <td><strong> unit_type_id       </td >
                                <td><strong> form_field_order   </td >
                                <td><strong> mandatory          </td >
                                <td><strong> state              </td >
                            </tr >
                            <tr >
                                <td > " . $row_apaga_gestao_subitens['id'] . "               </td >
                                <td > " . $row_apaga_gestao_subitens['name'] . "             </td >
                                <td > " . $row_apaga_gestao_subitens['item_id'] . "          </td >
                                <td > " . $row_apaga_gestao_subitens['value_type'] . "       </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_name'] . "  </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_type'] . "  </td >
                                <td > " . $row_apaga_gestao_subitens['unit_type_id'] . "     </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_order'] . " </td >
                                <td > " . $row_apaga_gestao_subitens['mandatory'] . "        </td >
                                <td > " . $row_apaga_gestao_subitens['state'] . "            </td >
                            </tr >
                        </table >
                        <form method='post' action=''>
                            <input type = 'submit' name = 'ativa_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'ativar_subitem' value = 'ativa_subitem' >
                        </form>
                        <p>
                        ";

                        backbutton();
                        break;
                    }

                } elseif ($_REQUEST['ativar_subitem'] == "ativa_subitem") {

                    $apaga_subitem = "UPDATE subitem SET state = 'active' WHERE id = " . $_REQUEST['id'];
                    $result_apaga_subitem = mysqli_query($link, $apaga_subitem);

                    echo "<h4 ><p><strong> Atualizações realizadas com sucesso</p>";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-subitens' > Continuar</a > ");
                }
            }
        }

        ####################################################################################################################################################################################################################

        //SECCAO DESATIVAR

        #########################################################################################################################################################################################################


        elseif ($_REQUEST['estado'] == "desativar") {

            ################################## GESTAO DE VALORES PERMITIDOS ##########################################

            if ($_REQUEST['nome'] == "gestao-de-valores-permitidos") {
                if (!isset($_REQUEST['desativar_valor_permitido'])) {
                    $query_desativa_valor_permitido_dados = "SELECT * FROM subitem_allowed_value WHERE id =" . $_REQUEST['id'];
                    $result_desativar_valor_permitido_dados = mysqli_query($link, $query_desativa_valor_permitido_dados);

                    echo " 
                    <p > Pretende desativar o item?</p >
                    <table >
                    <tr >
                        <td style = 'width: 10%' > <strong > id             </strong ></td >
                        <td >                      <strong > subitem_id     </strong ></td >
                        <td >                      <strong > value          </strong ></td >
                        <td >                      <strong > state          </strong ></td >
                    </tr >
                    <tr >
                    </tr >
                    <tr >";
                    while ($row_desativa_valor_permitido = mysqli_fetch_assoc($result_desativar_valor_permitido_dados)) {
                        echo "
                        <td > " . $row_desativa_valor_permitido['id'] . "         </td >
                        <td > " . $row_desativa_valor_permitido['subitem_id'] . " </td >
                        <td > " . $row_desativa_valor_permitido['value'] . "      </td >
                        <td > " . $row_desativa_valor_permitido['state'] . "      </td >                       
                    </tr >";
                    }
                    echo "
                    </table >
                    <form method = 'post' action = '' >
                            <input type = 'submit' name = 'apaga_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'desativar_valor_permitido' value = 'desativar' >       
                    </form >
                    <p > ";
                    backbutton();
                } elseif ($_REQUEST['desativar_valor_permitido'] == "desativar") {

                    $query_desativa = "UPDATE subitem_allowed_value SET state = 'inactive' WHERE id = " . $_REQUEST['id'];
                    $result_ativa = mysqli_query($link, $query_desativa);

                    echo "<h4 ><p><strong>Atualizações realizadas com sucesso</p>";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-valores-permitidos' > Continuar</a > ");
                }

            } ################################## GESTAO DE ITENS ##########################################

            elseif ($_REQUEST['nome'] == "gestao-de-itens") {
                if (!isset($_REQUEST['desativar_item'])) {
                    $desativa_gestao_de_item = "SELECT * FROM item WHERE id=" . $_REQUEST['id'];
                    $result_desativa_gestao_de_item = mysqli_query($link, $desativa_gestao_de_item);

                    echo " 
                    <p > Pretende desativar o item?</p >
                    <table >
                    <tr >
                        <td style = 'width: 10%' >      <strong > id                </td >
                        <td >                           <strong > name              </td >
                        <td >                           <strong > item type id      </td >
                        <td >                           <strong > state             </td >
                    </tr >";
                    while ($row_desativa_gestao_de_item = mysqli_fetch_assoc($result_desativa_gestao_de_item)) {
                        echo "
                    <tr >
                        <td > " . $row_desativa_gestao_de_item['id'] . "           </td >
                        <td > " . $row_desativa_gestao_de_item['name'] . "         </td >
                        <td > " . $row_desativa_gestao_de_item['item_type_id'] . " </td >
                        <td > " . $row_desativa_gestao_de_item['state'] . "        </td >
                    </tr >";
                    }
                    echo "
                    </table >
                    <form method = 'post' action = '' >
                            <input type = 'submit' name = 'ativar' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'desativar_item' value = 'desativar_item' >       
                    </form >
                    <p > ";
                    backbutton();
                } elseif ($_REQUEST['desativar_item'] == "desativar_item") {
                    $desativa_item = "UPDATE item SET state = 'inactive' WHERE id = " . $_REQUEST['id'];
                    $result_desativa_item = mysqli_query($link, $desativa_item);

                    echo "<h4 ><p><strong> Atualizações realizadas com sucesso </p>";

                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-itens' > Continuar</a > ");
                }
            } ################################## GESTAO DE SUBITENS ##########################################

            elseif ($_REQUEST['nome'] == "gestao-de-subitens") {
                if (!isset($_REQUEST['desativar_subitem'])) {

                    $chama_apaga_gestao_subitens = "SELECT * FROM subitem WHERE id= " . $_REQUEST['id'];
                    $result_apaga_gestao_subitens = mysqli_query($link, $chama_apaga_gestao_subitens);
                    while ($row_apaga_gestao_subitens = mysqli_fetch_assoc($result_apaga_gestao_subitens)) {
                        echo "
                       <p > Pretende desativar o subitem?</p >
                        <table >
                            <tr > 
                                <td><strong> id                 </td >
                                <td><strong> name               </td >
                                <td><strong> item_id            </td >
                                <td><strong> value_type         </td >
                                <td><strong> form_field_name    </td >
                                <td><strong> form_field_type    </td >
                                <td><strong> unit_type_id       </td >
                                <td><strong> form_field_order   </td >
                                <td><strong> mandatory          </td >
                                <td><strong> state              </td >
                            </tr >
                            <tr >
                                <td > " . $row_apaga_gestao_subitens['id'] . "               </td >
                                <td > " . $row_apaga_gestao_subitens['name'] . "             </td >
                                <td > " . $row_apaga_gestao_subitens['item_id'] . "          </td >
                                <td > " . $row_apaga_gestao_subitens['value_type'] . "       </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_name'] . "  </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_type'] . "  </td >
                                <td > " . $row_apaga_gestao_subitens['unit_type_id'] . "     </td >
                                <td > " . $row_apaga_gestao_subitens['form_field_order'] . " </td >
                                <td > " . $row_apaga_gestao_subitens['mandatory'] . "        </td >
                                <td > " . $row_apaga_gestao_subitens['state'] . "            </td >
                            </tr >
                        </table >
                        <form method='post' action=''>
                            <input type = 'submit' name = 'desativa_submeter' value = 'SUBMETER' >
                            <input type = 'hidden' name = 'desativar_subitem' value = 'desativa_subitem' >
                        </form>
                        <p>
                        ";

                        backbutton();
                        break;
                    }

                } elseif ($_REQUEST['desativar_subitem'] == "desativa_subitem") {

                    $apaga_subitem = "UPDATE subitem SET state = 'inactive' WHERE id = " . $_REQUEST['id'];
                    $result_apaga_subitem = mysqli_query($link, $apaga_subitem);

                    echo "<h4 ><p><strong> Atualizações realizadas com sucesso</p>";
                    print("<a style = 'color: blue;' href = 'http://localhost/sgbd/gestao-de-subitens' > Continuar</a > ");
                }
            }
        }


}


?>