<?php
require_once("custom/php/common.php"); 

#! Author: Diogo

$link = connection();
global $current_page;

if (!current_user_can('insert_values')) 
{
    echo  "Não tem autorização para aceder a esta página";
    exit(0);
} 
else 
{
    // Verifica se o formulário deu submi t
    if (isset($_REQUEST['estado'])) 
    {
        //Criava aqui uma função obterSubitem, para buscar o que é pedido no estado inserir
        switch ($_REQUEST['estado']) 
        {
            case 'escolher_crianca':
                echo "<h3> Inserção de valores - criança - escolher </h3>";

                
                $queryProcura = "SELECT id, name, birth_date
                                FROM child" ;

                $nome ='%' .$_POST['nome'] . '%';
                $data_nascimento =  $_POST['data_nascimento'] ;

                if (!empty($nome) &&  empty($data_nascimento))  
                {
                    $queryProcura .= " WHERE name LIKE ?";
                } 
                elseif (empty($nome) && !empty($data_nascimento))  
                { 
                    $queryProcura .= " WHERE birth_date = ?";
                } 
                 elseif (!empty($nome) && !empty($data_nascimento))  
                 {
                    $queryProcura .= " WHERE name LIKE ? AND birth_date = ?"; 
                } 

                $stmt = mysqli_prepare($link, $queryProcura);  

                // Verificar se a preparação do statement n deu
                if ($stmt === false) 
                {
                    die(mysqli_error($link));
                }

                //Verifica os 2 valores estao preenchidos
                if (!empty($nome)  && !empty($data_nascimento))  
                {
                    //o ss significa se é string type para os 2 parametros, ou seja para nome e data nascimento
                    mysqli_stmt_bind_param($stmt, "ss", $nome ,$data_nascimento );  
                } 

                elseif (!empty($nome)) 
                {
                    mysqli_stmt_bind_param($stmt,  "s",$nome );
                } 

                elseif (!empty($data_nascimento)) 
                {
                    mysqli_stmt_bind_param($stmt, "s" , $data_nascimento);
                }

                // Executar o  statement
                mysqli_stmt_execute($stmt);

                // Obter o s resultados 
                $result_queryProcura = mysqli_stmt_get_result($stmt);

                while ($Procura1 = mysqli_fetch_assoc($result_queryProcura)) 
                {
                    //igações para o endereço como descrito no enunciado
                    echo "<a href='insercao-de-valores? estado=escolher_item&crianca= " .   $Procura1['id'] .  " ' >"  . $Procura1['name'].  " </a> (".  $Procura1['birth_date']  .") 
                          <br>" ; 
                }

                backbutton();

                break;

            case 'escolher_item': 
                
                echo "<h3> Inserção de valores - escolher item </h3>";

                if (isset($_REQUEST['crianca'])) 
                {
                    $child_id =  $_REQUEST['crianca'];
                    $_SESSION["child_id"] =  $child_id;
  
                    $queryProcura2 = "SELECT DISTINCT item_type.name AS tipo_nome, item.name AS nome_item, item.id AS id_item
                                    FROM item_type
                                    LEFT JOIN item ON item_type.id = item.item_type_id  
                                    WHERE item.state =  'active' 
                                    ORDER BY item_type.name, item.name";

                    $queryResultadoitens = mysqli_query($link, $queryProcura2);
                    $itemAtual =null;  
                    $SeparadorTopico = " • "; 

                    while ($row = mysqli_fetch_assoc($queryResultadoitens)) 
                    {
                        // checka se o tipo de nome foi alterado
                        if ( $itemAtual != $row['tipo_nome'] ) 
                         {
                            if ($itemAtual !== null ) 
                            {
                                //Fecha o tipo anterior
                                echo " </ul>" ;
                             }

                            // Começa um novo tipo
                            echo "<strong>" . $SeparadorTopico .  $row['tipo_nome'] . "</strong><br>";
                            echo " <ul>";
                            $itemAtual =  $row['tipo_nome'];
                        }

                        //Display do item e o devido hyperlink associado ao estado "introducao"
                        echo "<li>
                            <a href=  'insercao-de-valores?estado=introducao&item="  . $row['id_item'] .   "'>[". $row['nome_item'] . "]</a>
                             </li>"; 

                    }
 
                    if ($itemAtual !== null) 
                    { 
                        // Close the last type
                        echo "  </ul> "; 
                    }  
                    backbutton();
                } 
                else 
                { 
                    // If 'crianca' n está no request
                    echo "Erro: ID da criança não encontrado."; 
                }
                break;
            case 'introducao':
                $item_id = $_REQUEST['item']; 

                // Realizar uma pequena query para buscar o nome e o id do tipo de item
                $queryItemInfo = "SELECT item.name, item.item_type_id
                                FROM item
                                INNER JOIN item_type ON item.item_type_id = item_type.id
                                WHERE item.id = ?";
                                //quando vinculamos os parâmetros usando mysqli_stmt_bind_param, ele substituirá o "?" com o valor real.
                $stmtItemInfo = mysqli_prepare($link, $queryItemInfo);

                if ($stmtItemInfo) 
                { 
                    mysqli_stmt_bind_param($stmtItemInfo, "i", $item_id);
                    mysqli_stmt_execute($stmtItemInfo);
                    mysqli_stmt_store_result($stmtItemInfo);

                    // se encontra resultad
                    if (mysqli_stmt_num_rows($stmtItemInfo) > 0) 
                    {
                        // Bind the result columns to variables
                        mysqli_stmt_bind_result($stmtItemInfo, $item_name, $item_type_id);

                        mysqli_stmt_fetch($stmtItemInfo);

                        $_SESSION["item_id"] = $item_id;
                        $_SESSION["item_name"] =$item_name;
                        $_SESSION["item_type_id"]= $item_type_id;

                        
                        echo "<h3>Inserção de valores - $item_name</h3>";
                        echo "<strong><p style='color: red;'>* Obrigatório </strong> </p>"; 

                        $baseUrl = "http://localhost/sgbd/insercao-de-valores/";
                        // aqui o caso do enunciado como o prof pedia n estava a funcionar, ele n mudava a pagina, criei uma base url e ele começou a mudar a pagina
                        echo  "<form method='post' action= '{$baseUrl}?estado=validar&item=$item_id'>"; 
                        
                        

                        $querySubitems = "SELECT *  
                                            FROM subitem 
                                            WHERE item_id = ? AND  state = 'active' 
                                            ORDER BY form_field_order"; 
                        $stmtSubitems= mysqli_prepare($link, $querySubitems);
                        mysqli_stmt_bind_param($stmtSubitems ,"i" ,$item_id );
                        mysqli_stmt_execute($stmtSubitems);
                        $resultSubitems= mysqli_stmt_get_result($stmtSubitems);
                        
                        while ($subitem = mysqli_fetch_assoc($resultSubitems)) 
                        {
                            $formFieldName =$subitem['name'];
                            $formFieldType= $subitem['form_field_type'];
                            $unitName = isset($subitem['unit_type_id']) ?$subitem['unit_type_id'] :  '';
                            
                            echo "<strong><label for='$formFieldName'>$formFieldName*</strong></label><br>";
                        
                            switch ($formFieldType) 
                            {
                                case 'text':
                                    echo "<input type='text' name='$formFieldName'>";
                                    break;
                                case 'bool':
                                    echo "<input type='radio' name='$formFieldName' value='true'>True";
                                    echo "<input type='radio' name='$formFieldName' value='false'>False";
                                    break;
                                case 'textbox':
                                    echo "<textarea name= '$formFieldName'> </textarea>" ; 
                                    break;
                                case 'int': 
                                case 'double': 
                                    echo "<input type='text' name='$formFieldName'>";  
                                    break;
                                case 'radio':
                                    $optionsQuery = "SELECT id, value 
                                    FROM subitem_allowed_value WHERE subitem_id = ?"; 
                                    $optionsStmt = mysqli_prepare($link, $optionsQuery);   
                                    mysqli_stmt_bind_param($optionsStmt, "i", $subitem['id']); 
                                    mysqli_stmt_execute($optionsStmt) ;
                                    $optionsResult = mysqli_stmt_get_result($optionsStmt); 
                                
                                    while ($option = mysqli_fetch_assoc($optionsResult)) 
                                    {
                                        echo "<input type='radio' name='$formFieldName'   value='{$option['value']}'>  {$option['value']}  
                                        <br>";
                                    }
                                    break;
                                case 'selectbox':
                                    $optionsQuery = "SELECT value 
                                                    FROM subitem_allowed_value WHERE subitem_id = ?"; 
                                    $optionsStmt = mysqli_prepare($link, $optionsQuery);
                                    mysqli_stmt_bind_param($optionsStmt, "i" , $subitem['id']) ;
                                    mysqli_stmt_execute($optionsStmt);  
                                    $optionsResult=mysqli_stmt_get_result($optionsStmt); 
                    
                                    echo "<select name='$formFieldName'>";
                                    while ($option = mysqli_fetch_assoc($optionsResult)) 
                                    {
                                        echo "<option value=  '" . $option['value'] .  "'>" .  $option['value'] .  "</option>";
                                        
                                    }
                                    echo "</select>";
                                    break;
                                
                                case 'checkbox':
                                    $optionsQuery = "SELECT value 
                                                    FROM subitem_allowed_value WHERE subitem_id = ?" ;
                                    $optionsStmt = mysqli_prepare($link, $optionsQuery);
                                    mysqli_stmt_bind_param($optionsStmt, "i", $subitem['id']);
                                    mysqli_stmt_execute($optionsStmt);
                                    $optionsResult = mysqli_stmt_get_result($optionsStmt);

                                    
                                    while ($option = mysqli_fetch_assoc($optionsResult)) 
                                    {
                                        $checkboxName= "{$formFieldName}[]";
                                        $checkboxValue =$option['value'];
                                    
                                        echo "<input type='hidden' name= '{$checkboxName}_hidden'  value='0'>";  // Hidden field for unchecked checkbox
                                        echo  "<input type='checkbox' name=  '{$checkboxName}'  value= '{$checkboxValue}'> {$checkboxValue}  <br>";
                                    }
                                    break;
                                default:
                                    echo "Unsupported field type: $formFieldType";
                            }
                        
 
                            echo "<br>"; // quebra cada linha po subitems
                        }
                        echo "<input type='hidden' name='estado'  value='validar'>" ;
                        //Nao PRECISO? 
                        
                        
                        echo "<input type='submit' name='submit' value='Submeter'>";
                        
                        echo   "</form>";
                    } 
                    else 
                    {
                        echo "Item não encontrado.";
                    }

                    mysqli_stmt_close($stmtItemInfo);
                } 
                else 
                {
                    echo "Erro na preparação do statement.";
                }
                
                break;
            
            case 'validar':
                if (isset($_REQUEST["submit"])) 
                {
                    
                    
                    $item_id = $_SESSION['item_id'];
                    $item_name = $_SESSION['item_name'];

                    echo "<h3>Inserção de valores - $item_name - validar</h3>";

                    
                    $error =false;
                    $entered_values = array();

                    // Da verify nos input relaciond ao
                    foreach ($_REQUEST as $key => $value)  
                    {
                        //aqui ele verifica apenas se ele recebe estes casos e ignora pois o que é mostrado nas imagens do prof não requer estes valores e ele tava a dar
                        if ($key == 'estado' || $key == 'submit' || $key == 'item')  
                        {
                            continue;
                        }

                        $formFieldName = htmlspecialchars($key);

                        
                        // campos que sao precisos(obrigatorioss)
                        if (is_array($value)) 
                        {
                            
                            $inputName = $formFieldName .  "[]"; 
                            $inputHiddenName ="{$formFieldName}_hidden"; 
                            
                            // aqui vou tentar filtrar para que apenas os valores do input aparecerem e não os 0s do array
                            
                            $filteredValues = array_filter($value, function ($v) { return $v !== '0'; });
                            if (!isset($_REQUEST[$inputHiddenName]) && empty($filteredValues)) 
                            {
                                echo "Campo obrigatório não preenchido: $formFieldName";
                                backbutton(); 
                                $error= true;
                                exit;
                            }

                            $entered_values[] = "$formFieldName: " . implode(', ', $filteredValues);
                        } 
                        else 
                        {
                            if (empty($value)) 
                            {
                                echo "Campo obrigatório não preenchido: $formFieldName"; 
                                backbutton(); 
                                $error=true;
                                exit;
                            }
                            
                            $entered_values[] =  "$formFieldName: $value"; 
                        }
                    }

                    // Se n tiv erro
                    if (!$error) 
                    {
                        echo "<p>Estamos prestes a inserir os dados abaixo na base de dados. Confirma que os dados estão corretos e pretende submeter os mesmos?  </p>";

                        // disply 
                        echo  "<ul>";
                        foreach ($entered_values as $entered_value) 
                        {  
                            echo "<li>  $entered_value  </li>";
                        }
                        echo  "</ul>";
 
                        
                        $hidden_fields =http_build_query($_REQUEST);
                        
                        $baseUrl = "http://localhost/sgbd/insercao-de-valores/";

                        echo "<form method='post' action='{$baseUrl}?estado=inserir&item=$item_id'>"; 
                        echo   "<input type='hidden' name='hidden_fields' value='$hidden_fields'>" ;
                        //passa po oturo estado
                        echo    "<input type='submit' name='submit' value='Submeter'>";  
                        echo      "</form>";
                    }

                    backbutton();
                }
                break;


            case 'inserir':
                if ($_REQUEST["submit"])
                { 
                    $item_id = $_SESSION['item_id']; 
                    $item_name = $_SESSION['item_name']; 

                    echo "<h3>Inserção de valores - $item_name - inserção</h3>";
 
                    //vai buscar o id da criança com o session
                    $child_id  =isset($_SESSION['child_id']) ?   $_SESSION['child_id'] :null; 

                    if ($child_id === null)   
                     {
                        echo "Erro: Não foi possível obter o ID da criança";
                        backbutton();
                        exit;
                    } 
                    //pa cada campo, busc o id do subiten e constroi a string do sql
                    foreach  ($_POST as $key => $value)  
                    {
                        if  ($key == 'hidden_fields' || $key == 'submit') 
                         {
                            continue;
                        }
 
                        $formFieldName  =htmlspecialchars($key);

                        // Buscaria o id do subitem, utilizando uma funçao obtersubitem que seria criada na parte inicial antes dos switch case
                        $subitem_id =obterSubitemId($formFieldName, $item_id, $link); 

                        if  ($subitem_id === null)  
                        {
                            echo "Erro: Não foi possível obter o subitem_id para $formFieldName";
                            
                            continue;
                        }

                        // Constroi a string  do sqL E da exec na query( com o prepare)
                        $producer =  isset($_SESSION['user_name']) ?   $_SESSION['user_name'] :'default_user' ;  
                        $date= date('Y-m-d');
                        $time =date('H:i:s');
                        //? sao valores que serão posteriormente vinculados no statement.
                        $sql = "INSERT INTO value (child_id, subitem_id, value, date, time, producer) 
                                VALUES (?, ?, ?, ?, ?, ?)";  
                        //prepare aqui usei para evitar ataques na base dadso com injection
                        $stmt=  mysqli_prepare($link, $sql); 

                        if ($stmt)  
                        {  //O iissss significa integer, integer, string, stringer, etc
                            mysqli_stmt_bind_param($stmt, "iissss",  $child_id, $subitem_id,$value,  $date,$time, $producer);
                            mysqli_stmt_execute($stmt); 
                            mysqli_stmt_close($stmt) ; 
                        }
                    }

                    echo  "Inseriu o(s) valor(es) com sucesso. <br>";
                    echo  "Clique em Voltar para voltar ao início da inserção de valores ou em Escolher item se quiser continuar a inserir valores associados a esta criança";

                    // Botoes para voltar e escolher outro item caso queira
                    $baseUrl = "http://localhost/sgbd/insercao-de-valores/";  
                    echo  "<p><a href='{$baseUrl}'>Voltar</a></p>" ; 
                    echo  "<p> <a href='{$baseUrl}?estado=escolher_item&crianca=$child_id'> Escolher item</a></p>"; 
                }  
                break; 
                    
        }
        
    } 
    else 
    {
        // Se não vier nenhum valor na variável (REQUEST) sobre o estado de execução
        echo "<h3>  Inserção de valores - criança - procurar  </h3> ";
        echo "<p>Introduza um dos nomes da criança a encontrar e/ou a data de nascimento dela </p> "; 
        echo    "<form method='post' action=''>" ;
        echo " <label for='nome' ><strong> Nome </strong></label> " ; 
        echo "  <input type='text' name='nome' id='nome'><br> "; 

        echo " <label for='data_nascimento'> <strong> Data de nascimento(AAAA-MM-DD)</strong> </label> " ;
        //inputs
        echo  "<input type='date' name='data_nascimento' id='data_nascimento'> <br> "; 
        echo   "<input type='hidden' value='escolher_crianca' name='estado'> ";
        echo    "<input type='submit' name='validar' value='Submeter'>";
        echo    "</form> "; 
    }
}
?>