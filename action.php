<?php
	session_start();
	require 'config.php';

	// Adicionar produtos na tabela do carrinho
	if (isset($_POST['pid'])) {
	  $pid = $_POST['pid'];
	  $pnome = $_POST['pnome'];
	  $ppreço = $_POST['ppreço'];
	  $pimagem = $_POST['pimagem'];
	  $pcodigo = $_POST['pcodigo'];
	  $pquantidade = $_POST['pquantidade'];
	  $ptotalpreço = $ppreço * $pquantidade;

	  $stmt = $conn->prepare('SELECT pcodigo FROM carrinho WHERE pcodigo=?');
	  $stmt->bind_param('s',$pcodigo);
	  $stmt->execute();
	  $res = $stmt->get_result();
	  $r = $res->fetch_assoc();
	  $codigo = $r['pcodigo'] ?? '';

	  $_SESSION['message'] = $codigo;

	  if (!$codigo) {
	    $query = $conn->prepare('INSERT INTO carrinho (pnome, ppreço, pimagem, pquantidade, ptotalpreço, pcodigo) VALUES (?,?,?,?,?,?)');
	    $query->bind_param('ssssss', $pnome, $ppreço, $pimagem, $pquantidade, $ptotalpreço, $pcodigo);
	    $query->execute();

	    echo '<div class="alert alert-success alert-dismissible mt-2">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>item adicionado ao seu carrinho!</strong>
						</div>';
	  } else {
	    echo '<div class="alert alert-danger alert-dismissible mt-2">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Item já adicionado ao seu carrinho!</strong>
						</div>';
	  }
	}

	// Obter nº de itens disponíveis na tabela do carrinho
	if (isset($_GET['carrinhoItem']) && isset($_GET['carrinhoItem']) == 'carrinho_item') {
	  $stmt = $conn->prepare('SELECT * FROM carrinho');
	  $stmt->execute();
	  $stmt->store_result();
	  $rows = $stmt->num_rows;

	  echo $rows;
	}

	// remover itens únicos do carrinho
	if (isset($_GET['remover'])) {
	  $pid = $_GET['remover'];

	  $stmt = $conn->prepare('DELETE FROM carrinho WHERE pid=?');
	  $stmt->bind_param('i',$pid);
	  $stmt->execute();

	  $_SESSION['showAlert'] = 'block';
	  $_SESSION['message'] = 'Item removido do carrinho!';
	  header('location:cart.php');
	}

	// removerr todos os itens de uma vez do carrinho
	if (isset($_GET['limpar'])) {
	  $stmt = $conn->prepare('DELETE FROM carrinho');
	  $stmt->execute();
	  $_SESSION['showAlert'] = 'block';
	  $_SESSION['message'] = 'Todos os itens foram removidos do carrinho!';
	  header('location:cart.php');
	}

	// Definir o preço total do produto na tabela do carrinho
	if (isset($_POST['pquantidade'])) {
	  $pquantidade = $_POST['pquantidade'];
	  $pid = $_POST['pid'];
	  $ppreço = $_POST['ppreço'];

	  $ptotalpreço = $pquantidade* $ppreço;

	  $stmt = $conn->prepare('UPDATE carrinho SET pquantidade=?, ptotalpreço=? WHERE pid=?');
	  $stmt->bind_param('isi',$pquantidade, $ptotalpreço, $pid);
	  $stmt->execute();
	}

	// Finalizar e salvar as informações do cliente na tabela de pedidos
	if (isset($_POST['action']) && isset($_POST['action']) == 'pedido') {
	  $nome = $_POST['nome'];
	  $email = $_POST['email'];
	  $telefone = $_POST['telefone'];
	  $endereço = $_POST['endereço'];
	  $pmodo = $_POST['pmodo'];
	  $produtos = $_POST['produtos'];
	  $quantia_paga = $_POST['quantia_paga'];

	  $data = '';

	  $stmt = $conn->prepare('INSERT INTO pedido (nome,email,telefone,endereço,pmodo,produtos,quantia_paga)VALUES(?,?,?,?,?,?,?)');
	  $stmt->bind_param('sssssss',$nome,$email,$telefone,$endereço,$pmodo,$produtos,$quantia_paga);
	  $stmt->execute();
	  $stmt2 = $conn->prepare('DELETE FROM carrinho');
	  $stmt2->execute();
	  $data .= '<div class="text-center">
								<h1 class="display-4 mt-2 text-danger">Obrigado!</h1>
								<h2 class="text-success">Seu pedido foi feito com sucesso!</h2>
								<h4 class="bg-danger text-light rounded p-2">Itens comprados : ' . $produtos . '</h4>
								<h4>Seu nome : ' . $nome . '</h4>
								<h4>Seu e-mail : ' . $email . '</h4>
								<h4>Seu telefone : ' . $telefone . '</h4>
								<h4>Valor total pago : ' . number_format($quantia_paga,2, ",", ".") . 'R$' .'</h4>
								<h4>Forma de pagamento : ' . $pmodo . '</h4>
						  </div>';
	  echo $data;
	}
?>