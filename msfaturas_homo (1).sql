-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25-Maio-2023 às 21:19
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `msfaturas_homo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(19) NOT NULL,
  `telefone` varchar(17) NOT NULL,
  `inscricao_municipal` varchar(11) DEFAULT NULL,
  `inscricao_estadual` varchar(9) DEFAULT NULL,
  `endereco` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `entidade`
--

CREATE TABLE `entidade` (
  `id_entidade` int(11) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(19) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `inscricao_municipal` varchar(11) DEFAULT NULL,
  `inscricao_estadual` varchar(9) DEFAULT NULL,
  `endereco` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fatura`
--

CREATE TABLE `fatura` (
  `id_fatura` int(11) NOT NULL,
  `status` varchar(15) NOT NULL,
  `vencimento` date NOT NULL,
  `doc_date` timestamp NULL DEFAULT current_timestamp(),
  `parcelas` int(11) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `cliente` int(11) NOT NULL,
  `entidade` int(11) NOT NULL,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item`
--

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `service_code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_fatura`
--

CREATE TABLE `item_fatura` (
  `id_item_fatura` int(11) NOT NULL,
  `cod_fatura` int(11) NOT NULL,
  `cod_item` int(11) NOT NULL,
  `quantidade` decimal(10,2) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `valortotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `user_id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'sistemas@ativusgestao.com.br', 'eaeac56c1cc44beff20ec6ebe843d0e7');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices para tabela `entidade`
--
ALTER TABLE `entidade`
  ADD PRIMARY KEY (`id_entidade`);

--
-- Índices para tabela `fatura`
--
ALTER TABLE `fatura`
  ADD PRIMARY KEY (`id_fatura`),
  ADD KEY `cliente` (`cliente`);

--
-- Índices para tabela `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`);

--
-- Índices para tabela `item_fatura`
--
ALTER TABLE `item_fatura`
  ADD PRIMARY KEY (`id_item_fatura`),
  ADD KEY `cod_item` (`cod_item`),
  ADD KEY `cod_fatura` (`cod_fatura`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `entidade`
--
ALTER TABLE `entidade`
  MODIFY `id_entidade` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fatura`
--
ALTER TABLE `fatura`
  MODIFY `id_fatura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `item_fatura`
--
ALTER TABLE `item_fatura`
  MODIFY `id_item_fatura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `fatura`
--
ALTER TABLE `fatura`
  ADD CONSTRAINT `fatura_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Limitadores para a tabela `item_fatura`
--
ALTER TABLE `item_fatura`
  ADD CONSTRAINT `item_fatura_ibfk_1` FOREIGN KEY (`cod_item`) REFERENCES `item` (`id_item`),
  ADD CONSTRAINT `item_fatura_ibfk_2` FOREIGN KEY (`cod_fatura`) REFERENCES `fatura` (`id_fatura`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
