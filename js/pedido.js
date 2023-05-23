valor_total_geral = 0 //variavel global

$(document).ready(function () {
  //elemento que é o botão class adicionarProduto

  $('.adicionarProduto').click(function () {
    //executa os comando quando o botao adicionar for clicado

    codigoNovoProduto = $('#codigoNovoProduto').val()

    qtdeNovoProduto = $('#qtdeNovoProduto').val()

    valorUnitario = $('#valorUnitario').val()

    valorTotalItem = qtdeNovoProduto * valorUnitario

    if (codigoNovoProduto > 0 && qtdeNovoProduto > 0 && valorUnitario > 0) {
      adicionarProduto(
        codigoNovoProduto,
        qtdeNovoProduto,
        valorUnitario,
        valorTotalItem
      )
    } else {
      alert('Necessário informar um produto, valor e uma quantidade!')
    }
  })

  $(document).on(
    'click',
    '#tabela-produto tbody .deleta-produto',
    function (e) {
      var cod_cesta = $(this).attr('cod_cesta') //attr pega o valor que está no atributo cod_cesta

      var descricao = $(this).attr('descricao')

      var valortotal = $(this).attr('valortotal')

      var apagar = confirm('Deseja excluir o produto: ' + descricao + '?')

      if (apagar) {
        var elemento = $(this)

        $.ajax({
          //func é uma variavel

          url: 'cadastroPedido.php?func=remprod&cod_cesta=' + cod_cesta, //está passando o id do produto e a quantidade

          type: 'GET',

          success: function (resposta) {
            //para voltar no elemento anterior do elemento excluido está voltando para o <tr> para imprimir toda a linha

            elemento.parents().parents().addClass('strikeout')

            elemento.removeClass('deleta-produto') //para deletar o campo com o icone de excluir

            diminuiValorTotal(valortotal)
          },

          error: function (xhr, textStatus, erroThrown) {
            alert(xhr.responseText)
          }
        })
      } else {
        event.preventDefault()
      }
    }
  )

  $('.confere').click(function () {
    confereProduto()
  })

  $(document).on('submit', '#form-pedido', function (e) {
    e.preventDefault()

    function addZero(num) {
      return num >= 10 ? num : `0${num}`
    }

    let dataAtual = new Date()
    let dia = addZero(dataAtual.getDate())
    let mes = addZero(dataAtual.getMonth() + 1)
    let ano = addZero(dataAtual.getFullYear())

    let data = ano + '-' + mes + '-' + dia

    var vencimento = $('#vencimento').val()
    console.log(vencimento, 'data de hoje', data)

    if (vencimento < data) {
      alert('O vencimento não pode ser menor que a data do documento.')
      document.getElementById('#vencimento').innerHTML = ''
      console.log('chegou aqui')
      return error
    }

    var codigo = $('#id_fatura').val()
    var cliente = $('#cliente').val()
    var valortotal = $('#valortotal').val()
    var status = $('#status').val()
    var parcelas = $('#parcelas').val()

    $.ajax({
      //func é uma variavel

      url: 'cadastroPedido.php?func=gravapedido', //está pasando o id do produto e a quantidade

      type: 'POST',

      data: {
        id_fatura: codigo,
        vencimento: vencimento,
        cliente: cliente,
        valor_total: valortotal,
        status: status,
        parcelas: parcelas
      },

      success: function (resposta) {
        html =
          '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
          'Pedido cadastrado com <strong>sucesso!</strong>' +
          '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
          '<span aria-hidden="true">&times;</span>' +
          '</button>' +
          '</div>'

        $('#mensagem').append(html).delay()

        //alert(resposta);
      },

      error: function (xhr, textStatus, erroThrown) {
        alert(xhr.responseText)
      }
    })
  })
})

function adicionarProduto(cod_produto, quantidade, valoruni, valor_total_item) {
  $.ajax({
    url:
      'cadastroPedido.php?func=addprod&id=' +
      cod_produto +
      '&quantidade=' +
      quantidade +
      '&valoruni=' +
      valoruni +
      '&valortotal=' +
      valor_total_item,

    type: 'GET',

    dataType: 'JSON',

    success: function (resposta) {
      var tam = resposta.length

      for (var i = 0; i < tam; i++) {
        var cod_cesta = resposta[i].cod_cesta

        var codigo = resposta[i].codigo

        var descricao = resposta[i].descricao

        var preco_unitario = resposta[i].preco_unitario

        var valortotal = resposta[i].valortotal

        html =
          '<tr>' +
          '<td>' +
          codigo +
          '</td>' +
          '<td>' +
          descricao +
          '</td>' +
          '<td>' +
          quantidade +
          '</td>' +
          '<td>' +
          preco_unitario +
          '</td>' +
          '<td>' +
          valortotal.toLocaleString('pt-br', { minimumFractionDigits: 2 }) +
          '</td>' + //passando o tipo da moeda e quantas cassas descimal ela vai ter
          '<td>' +
          '<a href="#" class="deleta-produto" valortotal="' +
          valortotal +
          '" cod_cesta="' +
          cod_cesta +
          '" descricao="' +
          descricao +
          '">' +
          '<i class="fa fa-trash"></i>' +
          '</a>' +
          '</td>' +
          '</tr>'

        $('.tabela-produto tbody').append(html).delay() //O elemento é a tabela append adiciona um arquivo html (html é uma variavel que foi criada)

        somaValorTotal(valortotal)
      }
    },

    error: function (xhr, textStatus, errorThrown) {
      alert(xhr.responseText)
    }
  })
}

function somaValorTotal(valortotal) {
  valor_total_geral += valortotal

  atualizaValorTotal(valor_total_geral)
}

function diminuiValorTotal(valortotal) {
  valor_total_geral -= valortotal

  atualizaValorTotal(valor_total_geral)
}

function atualizaValorTotal(valor) {
  $('#valortotal').val(
    valor_total_geral.toLocaleString('pt-br', { minimumFractionDigits: 2 })
  )
}

function confereProduto() {
  $.ajax({
    //func é uma variavel

    url: 'cadastroPedido.php?func=confere', //está pasando o id do produto e a quantidade

    type: 'GET',

    dataType: 'JSON',

    success: function (resposta) {
      alert(resposta)
    },

    error: function (xhr, textStatus, erroThrown) {
      alert(xhr.responseText)
    }
  })
}
