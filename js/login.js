document.login_form.id.focus();

function check_input(){
    if(!document.login_form.id.value){
        alert("아이디를 입력하세요");
        document.login_form.id.focus();
        return;
    }
    if(!document.login_form.pass.value){
        alert("패스워드를 입력하세요");
        document.login_form.pass.focus();
        return;
    }
    document.login_form.submit();
}

$(document).ready(function(){
    $(".login_input").keydown(function(e){
        if(e.keyCode == "13"){
            $("#login_excute").submit();
        }
    });
});