<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Carrinho</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="index.php"><i class="fas fa-mobile-alt"></i>&nbsp;&nbsp;Loja Móvel</a>
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
          <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div style="display:<?php if (isset($_SESSION['showAlert'])) {
  echo $_SESSION['showAlert'];
} else {
  echo 'none';
} unset($_SESSION['showAlert']); ?>" class="alert alert-success alert-dismissible mt-3">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong><?php if (isset($_SESSION['message'])) {
  echo $_SESSION['message'];
} unset($_SESSION['showAlert']); ?></strong>
        </div>
        <div class="table-responsive mt-2">
          <table class="table table-bordered table-striped text-center">
            <thead>
              <tr>
                <td colspan="7">
                  <h4 class="text-center text-info m-0">Produtos no seu carrinho!</h4>
                </td>
              </tr>
              <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Preço Total</th>
                <th>
                  <a href="action.php?limpar=all" class="badge-danger badge p-1" onclick="return confirm('Você tem certeza que quer esvaziar o carrinho?');"><i class="fas fa-trash"></i>&nbsp;&nbsp;Esvaziar Carrinho</a>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
                require 'config.php';
                $stmt = $conn->prepare('SELECT * FROM carrinho');
                $stmt->execute();
                $result = $stmt->get_result();
                $grand_total = 0;
                while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?= $row['pid'] ?></td>
                <input type="hidden" class="pid" value="<?= $row['pid'] ?>">
                <td><img src="<?= $row['pimagem'] ?>" width="50"></td>
                <td><?= $row['pnome'] ?></td>
                <td>
                &nbsp;&nbsp;<?= number_format($row['ppreço'],2, ",", "."); ?> <b>R$</b>
                </td>
                <input type="hidden" class="ppreço" value="<?= $row['ppreço'] ?>">
                <td>
                  <input type="number" class="form-control pquantidade" value="<?= $row['pquantidade'] ?>" style="width:75px;">
                </td>
                <td>&nbsp;&nbsp;<?= number_format($row['ptotalpreço'],2, ",", "."); ?> <b>R$</b></td>
                <td>
                  <a href="action.php?remover=<?= $row['pid'] ?>" class="text-danger lead" onclick="return confirm('Você tem certeza que quer excluir esse item?');"><i class="fas fa-trash-alt"></i></a>
                </td>
              </tr>
              <?php $grand_total += $row['ptotalpreço']; ?>
              <?php endwhile; ?>
              <tr>
                <td colspan="3">
                  <a href="index.php" class="btn btn-success"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Continue
                Comprando</a>
                </td>
                <td colspan="2"><b>Valor Total</b></td>
                <td><b>&nbsp;&nbsp;<?= number_format($grand_total,2, ",", "."); ?> R$</b></td>
                <td>
                  <a href="checkout.php" class="btn btn-info <?= ($grand_total > 1) ? '' : 'disabled'; ?>"><i class="far fa-credit-card"></i>&nbsp;&nbsp;Checkout</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

  <script type="text/javascript">
  $(document).ready(function() {

    // Change the item quantity
    $(".pquantidade").on('change', function(e) {
      e.preventDefault();
      var $el = $(this).closest('tr');
      console.log($el);
      var pid = $el.find(".pid").val();
      var ppreço = $el.find(".ppreço").val();
      var pquantidade = $el.find(".pquantidade").val();
      console.log(pid, ppreço, pquantidade)
      location.reload(true);
      $.ajax({
        url: 'action.php',
        method: 'post',
        cache: false,
        data: {
          pquantidade: pquantidade,
          pid: pid,
          ppreço: ppreço
        },
        success: function(response) {
          console.log(response);
        }
      });
    });

    // Load total no.of items added in the cart and display in the navbar
    load_cart_item_number();

    function load_cart_item_number() {
      $.ajax({
        url: 'action.php',
        method: 'get',
        data: {
          carrinhoItem: "carrinho_item"
        },
        success: function(response) {
          $("#cart-item").html(response);
        }
      });
    }
  });
  </script>
</body>
</html>