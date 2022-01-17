<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Loja</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body>
  <!-- Navbar start -->
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="index.php"><i class="fas fa-mobile-alt"></i>&nbsp;&nbsp;Loja</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
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
  <!-- Fim da barra de navegação -->

  <!-- Início da exibição dos produtos -->
  <div class="container">
    <div id="message"></div>
    <div class="row mt-2 pb-3">
      <?php
  			include 'config.php';
  			$stmt = $conn->prepare('SELECT * FROM produto');
  			$stmt->execute();
  			$resultado = $stmt->get_result();
  			while ($row = $resultado->fetch_assoc()):
  		?>
      <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
        <div class="card-deck">
          <div class="card p-2 border-secondary mb-2">
            <img src="<?= $row['pimagem'] ?>" class="card-img-top" height="250">
            <div class="card-body p-1">
              <h4 class="card-title text-center text-info"><?= $row['pnome'] ?></h4>
              <h5 class="card-text text-center text-danger">&nbsp;&nbsp;<?= number_format($row['ppreço'],2, ",", ".") ?> <b>R$</b></h5>

            </div>
            <div class="card-footer p-1">
              <form action="" class="form-submit">
                <div class="row p-2">
                  <div class=" py-1 pl-4">
                    <b>Quantitade:</b>
                  </div>
                  <div class="col-md-6">
                    <input type="number" class="form-control pquantidade"value="<?= $row['pquantidade'] ?>">
                  </div>
                </div>
                <input type="hidden" class="pid" value="<?= $row['pid'] ?>">
                <input type="hidden" class="pnome" value="<?= $row['pnome'] ?>">
                <input type="hidden" class="ppreço" value="<?= $row['ppreço'] ?>">
                <input type="hidden" class="pimagem" value="<?= $row['pimagem'] ?>">
                <input type="hidden" class="pcodigo" value="<?= $row['pcodigo'] ?>">
                <button class="btn btn-info btn-block adicionaritem"n><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;adicionar ao
                    carrinho</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
  <!-- Fim dos Produtos Exibidos -->

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

  <script type="text/javascript">
  $(document).ready(function() {

    // Enviar detalhes do produto para o servidor
    $(".adicionaritem").click(function(e) {
      e.preventDefault();
      var $form = $(this).closest(".form-submit");
      var pid = $form.find(".pid").val();
      var pnome = $form.find(".pnome").val();
      var ppreço = $form.find(".ppreço").val();
      var pimagem = $form.find(".pimagem").val();
      var pcodigo = $form.find(".pcodigo").val();
      
      var pquantidade = $form.find(".pquantidade").val();

      $.ajax({
        url: 'action.php',
        method: 'post',
        data: {
          pid: pid,
          pnome: pnome,
          ppreço: ppreço,
          pquantidade: pquantidade,
          pimagem: pimagem,
          pcodigo: pcodigo
        },
        success: function(resposta) {
          $("#message").html(resposta);
          window.scrollTo(0, 0);
          número_do_item_do_carrinho_de_carga();
        }
      });
    });

    // Carregar o número total de itens adicionados no carrinho e exibir na barra de navegação
    número_do_item_do_carrinho_de_carga();

    function número_do_item_do_carrinho_de_carga() {
      $.ajax({
        url: 'action.php',
        method: 'get',
        data: {
          carrinhoItem: "carrinho_item"
        },
        success: function(resposta) {
          $("#carrinho_item").html(resposta);
        }
      });
    }
 
  });
  </script>
</body>

</html>