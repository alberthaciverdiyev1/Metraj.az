
const oldpassword=document.getElementById("old-password");
const newpassword=document.getElementById("new-password");
const icon=document.getElementById("old-eye");
const newicon=document.getElementById("new-eye");
const confirmpassword=document.getElementById("confirm-password");
const confirmicon=document.getElementById("confirm-eye");
icon.addEventListener("click",function(){
    if(oldpassword.type=="password"){
        oldpassword.type="text";
        icon.classList.replace("bi-eye","bi-eye-slash");
    }
    else{
        oldpassword.type="password";
        icon.classList.replace("bi-eye-slash","bi-eye");
    }
})

newicon.addEventListener("click",function(){
    if(newpassword.type=="password"){
        newicon.classList.replace("bi-eye","bi-eye-slash");
        newpassword.type="text";
    }
    else{
        newicon.classList.replace("bi-eye-slash","bi-eye");
        newpassword.type="password";
    }

});


confirmicon.addEventListener("click",function(){
    if(confirmpassword.type=="password"){
        confirmpassword.type="text";
        confirmicon.classList.replace("bi-eye-slash","bi-eye");
    }
    else{
        confirmicon.classList.replace("bi-eye","bi-eye-slash");

        confirmpassword.type="password";
    }
})