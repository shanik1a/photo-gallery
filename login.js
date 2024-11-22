$("#login-button").on("click", () => {
    console.log({
        login: $("#val-login").val(),
        password: $("#val-password").val()
    })
    $.post("./login.php", {
        login: $("#val-login").val(),
        password: $("#val-password").val()
    }, (data, status)=>{
        if (typeof data === 'string' && data.trim().startsWith('<!DOCTYPE html>')) {
            // Если сервер возвращает HTML-страницу
            document.open();
            document.write(data);
            document.close();
        }
    })
})