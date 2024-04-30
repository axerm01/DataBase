$(document).ready(function() {
    // Effettua una richiesta AJAX per verificare lo stato della sessione
    $.ajax({
        type: "POST",
        url: "../controllers/utils/check_session.php",
        success: function(response) {
            console.log(response);
            // Se l'utente non è loggato, lo reindirizza alla pagina di login
            if (response === "not_logged_in") {
                window.location.href = "login.html";
            }
        }
    });
});