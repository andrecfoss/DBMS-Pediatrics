<?php // common.php

#!Author: Bjorn

// Global variables
global $current_page; 
$current_page = get_site_url().'/'.basename(get_permalink());

function connection() 
{
    $link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if ($link == false) 
    {
        die("Connection failed.: " . mysqli_connect_error());
        exit(0);
    } 
    else 
    {
		
        if (is_user_logged_in() == false) 
        {
            echo " <p>Não tem autorização para aceder a esta página</p> ";

            # Caso o utilizador não tenha iniciado sessão no WordPress
            echo " <a href='http://localhost/sgbd/wp-login.php'>Faça login aqui para poder aceder ao conteúdo desta página.</a> ";
            exit(0);
        } 
    }
    return $link;
    //mysqli_close($link);
}
//on each PHP file: $link = connection();

// To run queries: $result = mysqli_query($link,$query);
// To define hidden input fields: echo '<input type="hidden">';
function backbutton() 
{
    echo "<script type='text/javascript'>document.write(\"<a href='javascript:history.back()' class='backLink' title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>\");</script>
    <noscript>
    <a href='".$_SERVER['HTTP_REFERER']."‘ class='backLink' title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>
    </noscript>";
}

function continuar()
{
    echo "<script type='text/javascript'>document.write(\"<a href='javascript:history.back()' class='backLink' title='Continuar'>Continuar</a>\");</script>
    <noscript>
    <a href='".$_SERVER['HTTP_REFERER']."‘ class='backLink' title='Continuar'>Continuar</a>
    </noscript>";
}

// $connection = $link
function get_enum_values($connection, $table, $column )
{
    $query = " SHOW COLUMNS FROM `$table` LIKE '$column' ";
    $result = mysqli_query($connection, $query );
    $row = mysqli_fetch_array($result , MYSQLI_NUM );
    $regex = "/'(.*?)'/";
    preg_match_all( $regex , $row[1], $enum_array );
    $enum_fields = $enum_array[1];
    return( $enum_fields );
}

$clientsideval=0;   //Inicialização da variável para a validação client-side javascript

//Função para os ficheiros CSS e JavaScripts
//	# == id
//	. == class
function styling() 
{	
	//add_theme_scripts();
	//CSS
	echo '<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/custom/css/ag.css" />';
}


?>
