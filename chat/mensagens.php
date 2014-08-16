<?php
session_start();
    
    require_once './config.php';
    require_once './BD.php';
    
    switch ($_POST['action']) {
        case 'insert':
            try{
        $C =  new BD();
        $chat = $C->conn();
        $prex = $chat->prepare("select nome from usuarios where id = :id");
        $prex->bindValue(':id',$_SESSION['id']);
        $prex->execute();
        $nx = $prex->rowCount();
        if($nx > 0){

            $ft = $prex->fetchObject();
            $prep =  $chat->prepare("insert into mensagens values(NULL,:id_de,:id_para,:msg,'" .  date("Y-m-d H:i:s") . "','')");
            $prep->bindValue(':id_de',$_SESSION['id']);
            $prep->bindValue(':id_para',$_POST['id_para']);
            $prep->bindValue(':msg',$_POST['mensagem']);
            $prep->execute();
            $nr = $prep->rowCount();
            if($nr >0){
                echo '<li><span>' . $ft->nome . ' disse:</span><p>' . $_POST['mensagem'] . '</p></li>';
                //var_dump();die();
            }else{
                echo 'erro1';
            }

        }else{
            echo 'erro2';
        }
    }  catch (PDOException $exx){
        echo $exx->getMessage();
    }

            break;
        case 'retrieve':
            if(!empty($_POST['array'])){
                
                $array = $_POST['array'];
                $C =  new BD();
                $chat = $C->conn();   
                
                foreach ($array as $index => $id) {
                 $prex = $chat->prepare("select * from mensagens where id_de = ? and id_para = ? OR  id_de = ? and id_para = ? ");
                    $prex->execute(array($_SESSION['id'],$id,$id,$_SESSION['id']));
                    $msgs ='';
                    while($ft = $prex->fetchObject()){
                        $prep = $chat->prepare("select nome from usuarios where id = ? ");
                        $prep->execute(array($ft->id_de));
                        $name = $prep->fetchObject();
                        if($ft->lido == 1){
                            $msgs .='<li class="lida"><span>' . $name->nome . ' disse:</span><p>' . $ft->mensagem . '</p></li>';
                        }else{
                            $msgs .='<li><span>' . $name->nome . ' disse:</span><p>' . $ft->mensagem . '</p></li>';
                        }
                    }
                    $new[$id] = $msgs;
                }
                $new = json_encode($new);
                echo $new;
            }else{
                
            }
            break;
        case 'verificar':
            $C =  new BD();
            $chat = $C->conn();
            $prep = $chat->prepare("select id_de from mensagens where id_para = ? AND lido = ?");
            $prep->execute(array($_SESSION['id'],0));
            if($prep->rowCount() > 0){
                $new = array();
                while($row = $prep->fetchObject()){
                    $new[] = $row->id_de;
                }
                echo json_encode($new);
            }
            break;
        case 'ler_msg': 
            
            $C =  new BD();
            $chat = $C->conn();
            $prep = $chat->prepare("update mensagens SET lido = ? where lido = ? AND id_para = ? ");
            $prep->execute(array(1,0,$_SESSION['id']));
            if($prep->rowCount() > 0){
                echo 1;
            }else{
                echo '';
            }
            
            break;
        default:
            break;
    }
    
    

