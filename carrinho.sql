-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Jan-2022 às 17:29
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.1

SET SQL_mode = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

--
-- Banco de dados: `sistema_carrinho`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `pnome` varchar(100) NOT NULL,
  `ppreço` varchar(50) NOT NULL,
  `pimagem` varchar(255) NOT NULL,
  `pquantidade` int(10) NOT NULL,
  `total_preço` varchar(100) NOT NULL,
  `pcodigo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ordens`
--

CREATE TABLE `ordens` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `endereço` varchar(255) NOT NULL,
  `pmodo` varchar(50) NOT NULL,
  `produtos` varchar(255) NOT NULL,
  `quantia_paga` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `ordens`
--

INSERT INTO `ordens` (`id`, `nome`, `email`, `phone`, `endereço`, `pmodo`, `produtos`, `quantia_paga`) VALUES
(12, 'Marcos Vinicius Ferreira da Silva', 'neliofsilva37@gmail.com', '98985212954', 'Avenida Tiradentes', 'cod', 'Huawei 10 Pro(2)', '150000');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `pnome` varchar(255) NOT NULL,
  `ppreço` varchar(100) NOT NULL,
  `ppquantidade` int(11) NOT NULL DEFAULT 1,
  `pimagem` varchar(255) NOT NULL,
  `pcodigo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id`, `pnome`, `ppreço`, `ppquantidade`, `pimagem`, `pcodigo`) VALUES
(1, 'Apple iPhone X', '90000', 1, 'imagem/iphone_x.jpg', 'p1000'),
(2, 'Huawei 10 Pro', '75000', 1, 'imagem/huawei_mate10_pro.jpg', 'p1001'),
(3, 'LG v30', '65000', 1, 'imagem/lg_v30.jpg', 'p1002'),
(4, 'MI Note 5 Pro', '15000', 1, 'imagem/mi_note_5_pro.jpg', 'p1003'),
(5, 'Nokia 7 Plus', '25000', 1, 'imagem/nokia_7_plus.jpg', 'p1004'),
(6, 'One Plus 6', '35000', 1, 'imagem/one_plus_6.jpg', 'p1005'),
(7, 'Zenfone Max Pro', '15000', 1, 'imagem/zenfone_m1.jpg', 'p1006'),
(9, 'Samsung A50', '25000', 1, 'imagem/samsung_a50.jpg', 'p1007');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ordens`
--
ALTER TABLE `ordens`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pcodigo_2` (`pcodigo`),
  ADD KEY `pcodigo` (`pcodigo`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `ordens`
--
ALTER TABLE `ordens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;