$(function(){
    //default values
    var field_values = {
            'username'  : 'usuario',
            'password'  : 'password',
    };

    //inputfocus
    $('input#username').inputfocus({ value: field_values['username'] });
    $('input#password').inputfocus({ value: field_values['password'] });

    // $('input#cpassword').inputfocus({ value: field_values['cpassword'] }); 
    // $('input#lastname').inputfocus({ value: field_values['lastname'] });
    // $('input#firstname').inputfocus({ value: field_values['firstname'] });
    // $('input#email').inputfocus({ value: field_values['email'] }); 

    //reset progress bar
    // $('#progress').css('width','0');
    // $('#progress_text').html('0% Complete');

    //count fails = 0

    $('#mdl_blocker').click(hideOwnerMessage);

    //first_step
    $('form').submit(function(){ return false; });
    $('#submit_login').click(function(){
        //remove classes
        $('#login_step input').removeClass('error').removeClass('valid');
        $('#login_messages').removeClass('error').removeClass('valid');

        //ckeck if inputs aren't empty
        var fields = $('#login_step input[type=text], #login_step input[type=password]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
            if( value.length<4 || value==field_values[$(this).attr('id')] ) {
                $(this).addClass('error');

                $('#login_messages').html("Ingrese usuario y/o contraseña");
                $('#login_messages').addClass('error');
                //$(this).effect("shake", { times:3 }, 5);
                error++;
            } else {
                $(this).addClass('valid');
            }
        });        
        
        if(!error) {
            //update progress bar
            // $('#progress_text').html('33% Complete');
            // $('#progress').css('width','113px');


            // $('#login_messages').html("Verificando credenciales...");
            // $('#login_messages').addClass('valid');

            showOwnerMessage("Verificando credenciales<br/>Espere un momento");

            // alert("SEND -->");

            var params = {pm:"mLogin/login", dt:{user:clearStr($('#username').val()), pass:$.md5($('#password').val())}, isu:'0'};
            //ajax to check user and password
            $.ajax({ 
                data: jQuery.param(params), 
                type: "POST", 
                url: 'services/controller.php', 
                dataType: 'json', 
                success: function(data){ 
                    // // alert("<RESPUESTAS>"+data.length+"</RESPUESTAS>");
                    // alert("OK");

                    // alert(data[0].action);

                    // //$("#welcome_step").html(''); 
                    // $.each(data,function(index,value) { 
                    //     $("#welcome_step").append("<div class=\"resultado\">"); 
                    //     $("#welcome_step").append("ID: "+data[index].id); 
                    //     $("#welcome_step").append("Respuesta: "+data[index].data[0]); 
                    //     $("#welcome_step").append("Screen Name: "+data[index].sname[0]); 
                    //     $("#welcome_step").append("Nivel: "+data[index].nivel[0]); 
                    //     $("#welcome_step").append("Accion: "+data[index].action); 
                    //     $("#welcome_step").append("</div>"); 
                    // });

                    //if ok
                    if(data[0].action=='errorInfo'){
                        hideOwnerMessage();
                        $('#login_messages').html("Problemas:<br/>"+data[index].data[0].dt[0]);
                        $('#login_messages').addClass('error');    
                    }

                    if(data[0].action=='skiplogin'){
                        $('#login_step input[type=text]').removeClass('valid').addClass('error');
                        $('#login_step input[type=password]').removeClass('valid').addClass('error');
                        $('#login_messages').html("Acceso no permitido.<br/>No podra continuar intentandolo mas.");
                        $('#login_messages').addClass('error');
                        $('#submit_login').unbind();
                        hideOwnerMessage();
                    }

                    if(data[0].action=='bad_login'){
                        $('#login_step input[type=text]').removeClass('valid').addClass('error');
                        $('#login_step input[type=password]').removeClass('valid').addClass('error');
                        $('#login_messages').html("Usuario y/o contraseña incorrectos");
                        $('#login_messages').addClass('error');
                        hideOwnerMessage();
                    }

                    //draw ui
                    if(data[0].action=='draw_ui'){
                        hideOwnerMessage();
                        $('#login_step').slideUp();
                        $('#welcome_step').slideDown();
                        alert(data[0].data.sunat[0]);
                    }
                } 
            });          
        } else return false;
    });

    $('#submit_second').click(function(){
        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');

        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
        var fields = $('#second_step input[type=text]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
            if( value.length<1 || value==field_values[$(this).attr('id')] || ( $(this).attr('id')=='email' && !emailPattern.test(value) ) ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 5);
                
                error++;
            } else {
                $(this).addClass('valid');
            }
        });

        if(!error) {
                //update progress bar
                $('#progress_text').html('66% Complete');
                $('#progress').css('width','226px');
                
                //slide steps
                $('#second_step').slideUp();
                $('#third_step').slideDown();     
        } else return false;

    });


    $('#submit_third').click(function(){
        //update progress bar
        $('#progress_text').html('100% Complete');
        $('#progress').css('width','339px');

        //prepare the fourth step
        var fields = new Array(
            $('#username').val(),
            $('#password').val(),
            $('#email').val(),
            $('#firstname').val() + ' ' + $('#lastname').val(),
            $('#age').val(),
            $('#gender').val(),
            $('#country').val()                       
        );
        var tr = $('#fourth_step tr');
        tr.each(function(){
            //alert( fields[$(this).index()] )
            $(this).children('td:nth-child(2)').html(fields[$(this).index()]);
        });
                
        //slide steps
        $('#third_step').slideUp();
        $('#fourth_step').slideDown();            
    });


    $('#submit_fourth').click(function(){
        //send information to server
        alert('Data sent');
    });
});

/*some end user functions*/
function showOwnerMessage(mensaje,tipo,withoutframe,estilo){
    showTitle = 'block';
    showWindow = 'block';

    changemensaje = true;
    
    if(mensaje == undefined){
        changemensaje = false;
    };
    
    if(tipo == undefined){
        tipo = 'default';
    };

    if(tipo == 'untitled'){
        showTitle = 'none';
    }
    
    if(withoutframe!=true){
        showWindow = 'block';
    }else{
        showWindow = 'none';
    }

    if(estilo != undefined){
        estilo  = 'default';
    }

    $('#mdl_bg > #mdl_windows > #mtitle').addClass(estilo);

    if(changemensaje == true){
        $('#mdl_bg > #mdl_windows > #mcontent').html(mensaje);   
    }
    
    $('#mdl_bg').css('display','block');
    $('#mdl_bg > #mdl_windows').css('display',showWindow);
    $('#mdl_bg > #mdl_windows > #mtitle').css('display',showTitle);
    }

function hideOwnerMessage(){
    $('#mdl_bg').css('display','none');
    $('#mdl_bg > #mdl_windows').css('display','none');
    $('#mdl_bg > #mdl_windows > #mtitle').css('display','none');
    $('#mdl_bg > #mdl_windows > #mcontent').html('<img class="loading" src="images/loading.gif" border=0/>');   
}

function clearStr(cadena){
    rpta = replacestr(cadena,"'",'');
    rpta = replacestr(rpta,'"','');
    rpta = replacestr(rpta,"--",'');
    rpta = replacestr(rpta,"\\",'');
    return rpta;
}

function replacestr(original,quita,pone){
    return original.split(quita).join(pone);
}