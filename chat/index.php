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
        <title></title>
    </head>
    <body>
    <?php
    if(isset($_POST['email']) && isset($_POST['senha'])){
        try{
            
            $prep =  $L->prepare("Select * from usuarios where email = :email and senha = :senha ");
            $prep->bindValue(':email',$_POST['email']);
            $prep->bindValue(':senha',$_POST['senha']);
            $prep->execute();
            $row = $prep->fetchObject();
            //var_dump($row);die();
            if(!empty($row)){
                foreach ($row as $key=>$value){
                    $_SESSION[$key] = $value;
                }
                $_SESSION['logado'] = TRUE;
                header("Location:chat.php");
            }else{
                echo 'Usuário inexistente';
            }
            
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
       
    }
    
    ?>
        <form action="" method="post" id="formlogin">
            <label for="email">
                Email:<br />
                <input type="email" name="email" id="email" required />
            </label>
            <br />
            <br />
            <label for="senha">
                Senha:<br />
                <input type="password" name="senha" id="senha" required />
            </label>
            <br />
            <br />
            <input type="submit" value="Login" />
        </form>
        
        
    </body>
</html>
