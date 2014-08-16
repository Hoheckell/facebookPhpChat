$(document).ready(function(){
    
    var janelas =  new Array();
    
    function abrir_janelas(x){
        $(".left li.useritem a ").each(function(){
           var link = $(this);
           var id = link.attr('id');
           if(id === x){
               link.click();
           }
        });
    }
    
    function verificar_msgs(){
        setInterval(function(){
            
            $.post('mensagens.php',{action:'verificar'},function(data){
               if(data !== ''){
                   for(i in data){
                        abrir_janelas(data[i]);
                   }                   
               }else{} 
            },'Json');
            
        },2000);
    }
    verificar_msgs();
    $("#janelas").on('click',function(){
        $.post('mensagens.php',{action:'ler_msg'},function(data){
               if(data !== ''){
                   $(".mensagens ul.lista li").addClass('lida') ;                 
               }else{} 
            },'Json');
    });
    function add_janelas(uid,nome){
        var html_add='<div class="miniwindow" id="jan_' + uid + '"><div class="usernametop" id="' + uid + '"><a href="javascript:void(0);">' + nome + '</a>&nbsp;<a href="javascript:void(0);" class="fechar" title="fechar janela">X</a></div><div class="corpo"><div class="mensagens"><ul class="listar"></ul></div></div><input type="text" class="msgbox" id="' + uid + '"  maxlength="255" /></div></div>';
        $("div.right #janelas").append(html_add);
    }
    //var miniwindow = 
    
    $(".useritem").on('click',function(){
        if($(this).children('a').attr('class') === 'comecar'){
            var uid = $(this).children('a').attr('id');
            var nome = $(this).children('a').html();            
            janelas.push(uid);
            for (var i = 0; i < janelas.length; i++) {
                if (janelas[i] === undefined) {         
                  janelas.splice(i, 1);
                  i--;
                }
            }
            $(this).children('a').removeClass('comecar');
            add_janelas(uid,nome);
            return false;
        }
        
    });
    
    $("body").delegate('a.fechar','click',function(){
       
        $(this).parent().parent().hide();
         var id = $(this).parent().attr('id');
          $(".useritem").children('a#'+id).addClass('comecar')
         $(".miniwindow #" + id).hide();
         var n = janelas.length;
         for(i=0;i<n;i++){
             if(janelas[i] !== undefined){
                 if(janelas[i] === id){
                     delete janelas[i];
                 }
             }
         }
          
    });
    
    $("body").delegate('.usernametop','click',function(){
        var pai = $(this).parent();
        var child = pai.children('.corpo');
        var isto = $(this);
        if(child.is(':hidden')){
            isto.removeClass('fixar');
            child.toggle(100);
        }else{
          isto.addClass('fixar');
            child.toggle(100);  
        }
    });
    
     $('body').delegate(".msgbox",'keydown',function(e){
         //alert(e.keyCode);
        var campo = $(this);
        var mensagem  =  campo.val();
        var to = $(this).attr('id');
        if(e.keyCode === 13){
            if(mensagem !== ''){
                $.post('mensagens.php',{
                    mensagem: mensagem,
                    id_para: to,
                    action:'insert'
                },function(data){
                  //console.log("--- " + data + " ---");
                    $("#jan_"+to+" .mensagens ul.listar").append(data);
                    campo.val('');
                });
            }else{
                alert('Digite uma mensagem');
            }
        }
    });
    
    setInterval(function(){
        $.post('mensagens.php',{
            action:'retrieve',
            array:janelas
        },function(data){
            if(data !== ''){
                for(i in data){
                    $("#jan_"+i+" .mensagens ul.listar").html(data[i]);
                }
            }else{
                console.log(data + "ERRRO");
            }
        },'Json');
    },2000);
    
    
    
});
