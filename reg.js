$("#login-button").on("click", () => {
    console.log({
        login: $("#val-login").val(),
        password: $("#val-password").val()
    })
    $.post("./index.php", {
        isRegister: true,
        login: $("#val-login").val(),
        password: $("#val-password").val()
    }, (data)=>{
        console.log(data)
        if(data == "success") window.location.replace("./")
    })
})