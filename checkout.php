<?php
	require 'config.php';

	$grand_total = 0;
	$allItems = '';
	$items = [];

	$sql = "SELECT CONCAT(pnome, '(',pquantidade,')') AS ItemQty, ptotalpreço FROM carrinho;";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
	  $grand_total += $row['ptotalpreço'];
	  $items[] = $row['ItemQty'];
	}
	$allItems = implode(', ', $items);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Checkout</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="index.php"><i class="fas fa-mobile-alt"></i>&nbsp;&nbsp;Mobile Store</a>
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php"><i class="fas fa-mobile-alt mr-2"></i>Produtos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="checkout.php"><i class="fas fa-money-check-alt mr-2"></i>Checkout</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id="carrinho_item" class="badge badge-danger"></span></a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 px-4 pb-4" id="order">
        <h4 class="text-center text-info p-2">Complete seu pedido!</h4>
        <div class="jumbotron p-3 mb-2 text-center">
          <h6 class="lead"><b>Produto(s) : </b><?= $allItems; ?></h6>
          <h6 class="lead"><b>Taxa de entrega : </b>Gratuito</h6>
          <h5><b>Valor total a pagar : </b><?= number_format($grand_total,2, ",", ".") ?>R$</h5>
        </div>
        <form action="" method="post" id="placeOrder">
          <input type="hidden" name="produtos" value="<?= $allItems; ?>">
          <input type="hidden" name="quantia_paga" value="<?= $grand_total; ?>">
          <div class="form-group">
            <input type="text" name="nome" class="form-control" placeholder=" Nome" required>
          </div>
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder=" E-Mail" required>
          </div>
          <div class="form-group">
            <input type="tel" name="telefone" class="form-control" placeholder=" telefone" required>
          </div>
          <div class="form-group">
            <textarea name="endereço" class="form-control" rows="3" cols="10" placeholder="Endereço de entrega"></textarea>
          </div>
          <h6 class="text-center lead">Selecione a forma de pagamento</h6>
          <div class="form-group">
            <select name="pmodo" class="form-control">
              <option value="" selected disabled>-Forma de pagamento-</option>
              <option value="pix">Pix</option>
              <option value="dinheiro">Dinheiro na entrega</option>
              <option value="boleto">Boleto. OBS: só será enviado após a confirmação do pagamento!</option>
              <option value="cartão">Cartão de crédito/débito</option>
            </select>
          </div>
          <div class="form-group">
            <input type="submit" name="submit" value="Confirmar Pedido" class="btn btn-danger btn-block">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

  <script type="text/javascript">
  $(document).ready(function() {

    // Sending Form data to the server
    $("#placeOrder").submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: 'action.php',
        method: 'post',
        data: $('form').serialize() + "&action=pedido",
        success: function(response) {
          $("#order").html(response);
        }
      });
    });

    // Carregar o número total de itens adicionados no carrinho e exibir na barra de navegação
    load_cart_item_number();

    function load_cart_item_number() {
      $.ajax({
        url: 'action.php',
        method: 'get',
        data: {
          carrinhoItem: "carrinho_item"
        },
        success: function(response) {
          $("#carrinho_item").html(response);
        }
      });
    }
  });
  </script>
</body>

</html>