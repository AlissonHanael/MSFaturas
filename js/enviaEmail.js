$(document).ready(function () {
	$(".enviaEmail").click(function (e) {
		e.preventDefault()
		var idFatura = $(this).data("id")
		$.ajax({
			
			url: "enviaEmail.php",
			type: "GET",
			data: { id: idFatura },
			dataType: "json"		
		}).done(function (response) {
			alert(response.message);
		}).fail(function (xhr, status, error) {
            console.error("Erro na requisição:", xhr.responseText);
            alert("Erro ao conectar ao servidor.");
        })
	})
})
