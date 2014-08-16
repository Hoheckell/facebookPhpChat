<?php
session_start();
include_once 'config.php';
require_once 'BD.php';
$C =  new BD();
$L = $C->conn();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CHAT</title>
        <script src="js/jquery-1.11.1.js" type="text/javascript" ></script>
        <script src="js/scripts.js" type="text/javascript" ></script>
        <link rel="stylesheet" href="css/style.css" type="text/css" />
    </head>
    <body>
        <div class="left">
            <ul>
            <?php

        if(isset($_SESSION['logado']) && $_SESSION['logado'] === TRUE){
            try{

                $prep =  $L->prepare("Select id,nome from usuarios where id != :id ");
                $prep->bindValue(':id',$_SESSION['id']);
                $prep->execute();
                $nr = $prep->rowCount();

                if($nr > 0){
                   while($row = $prep->fetchObject()){
                       echo '<li  class="useritem"><a class="comecar" style="cursor:pointer" id="' . $row->id . '" >' . $row->nome .'</a></li>';
                   }
                }else{
                    echo '<li>Nenhum contato</li>';
                }

            } catch (PDOException $ex) {
                die($ex->getMessage());
            }

        }

            ?>
            </ul>
        </div>
        <div class="right">
            <h1>Teste</h1>
            <div id='janelas'>
                
            </div>
        </div>
    </body>
</html>
