$(document).ready(function () {
	$(".enviar-email").click(function (e) {
		e.preventDefault()
		var idFatura = $(this).data("id")
		$.ajax({
			url: "enviaEmail.php",
			type: "GET",
			data: { id: idFatura },
			dataType: "json",
			success: function (response) {
				if (response.success) {
					alert(response.message)
				} else {
					alert("Erro: " + response.message)
				}
			},
			error: function () {
				alert("Erro ao conectar ao servidor.")
			},
		})
	})
})
