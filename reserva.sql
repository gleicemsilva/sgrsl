-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/10/2024 às 04:06
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `reserva`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atribuicoes`
--

CREATE TABLE `atribuicoes` (
  `id` int(11) NOT NULL,
  `recurso_id` int(11) DEFAULT NULL,
  `turno_id` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `atribuicoes`
--

INSERT INTO `atribuicoes` (`id`, `recurso_id`, `turno_id`, `data`) VALUES
(1, 7, 1, '2024-06-19'),
(2, 18, 2, '2024-06-20'),
(3, NULL, 1, '2024-06-17'),
(4, NULL, 1, '2024-06-17'),
(5, NULL, 2, '2024-06-20'),
(6, NULL, 3, '2024-06-26'),
(7, NULL, 2, '2024-06-30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `coordenacao`
--

CREATE TABLE `coordenacao` (
  `cpf` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `coordenacao`
--

INSERT INTO `coordenacao` (`cpf`, `nome`, `email`, `senha`) VALUES
(147, 'coordenador', 'teste@gmail.com', '123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`id`, `nome`) VALUES
(1, 'Técnico em Automação Industrial'),
(2, 'Técnico em Computação Gráfica'),
(3, 'Técnico em Desenvolvimento de Sistemas\r\n'),
(4, 'Técnico em Eletrônica'),
(5, 'Técnico em Informática'),
(6, 'Técnico em Manutenção e Suporte em Informática'),
(7, 'Técnico em Qualidade'),
(8, 'Técnico em Redes de Computadores'),
(9, 'Técnico em Segurança do Trabalho');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `feedback`
--

INSERT INTO `feedback` (`id`, `nome`, `email`, `mensagem`, `data_envio`) VALUES
(12, 'Nicolle Beatriz', 'nicolle@gmail.com', 'A lâmpada da sala 1 está queimada. ', '2024-09-30 20:09:30'),
(13, 'Vitória Alves', 'vitoria@gmail.com', 'No laboratorio 2, estamos tendo dificuldade de utilizar a internet.', '2024-09-30 20:11:09'),
(14, 'Gleiceanne Silva', 'gleiceanne@gmail.com', 'Os computadores dos alunos da sala 10 não estão ligando, por favor providenciem uma equipe de TI.', '2024-09-30 20:12:29'),
(15, 'Thamires ', 'thamires@gmail.com', 'O ar-condicionado do laboratorio 2 não está gelando, por favor, busquem uma manuntenção', '2024-09-30 20:13:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores`
--

CREATE TABLE `professores` (
  `cpf` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` int(100) NOT NULL,
  `senha` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `professores`
--

INSERT INTO `professores` (`cpf`, `nome`, `email`, `telefone`, `senha`) VALUES
(123, 'teste', 'teste@gmail.com', 123, '123'),
(11111, 'Vitória Alves', 'vitoria@gmail.com', 929999999, '123'),
(12345, 'Thamires ', 'thamires@gmail.com', 9299219, '123'),
(1234567, 'Gleiceanne Silva', 'gleiceanne@gmail.com', 929999999, '123'),
(123456789, 'Nicolle Beatriz', 'nicolle@gmail.com', 92999999, '123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `recursos`
--

CREATE TABLE `recursos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `recursos`
--

INSERT INTO `recursos` (`id`, `nome`) VALUES
(1, 'Laboratório 1'),
(2, 'Laboratório 2'),
(3, 'Laboratório 3'),
(4, 'Laboratório 4'),
(5, 'Laboratório 5'),
(6, 'Sala 1'),
(7, 'Sala 2'),
(8, 'Sala 3'),
(9, 'Sala 4'),
(10, 'Sala 5'),
(11, 'Sala 6'),
(12, 'Sala 7'),
(13, 'Sala 8'),
(14, 'Sala 9'),
(15, 'Sala 10'),
(16, 'Sala 11'),
(17, 'Sala 12'),
(18, 'Sala 13'),
(19, 'Sala 14'),
(20, 'Sala 15'),
(21, 'Sala 16'),
(22, 'Auditório');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `recurso_id` int(11) DEFAULT NULL,
  `turno_id` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `curso_id` int(11) NOT NULL,
  `turma` int(1) DEFAULT NULL,
  `professor_nome` varchar(100) DEFAULT NULL,
  `professor_cpf` varchar(11) DEFAULT NULL,
  `data_hora_reserva` datetime DEFAULT NULL,
  `status` enum('Pendente','Aprovado','Negado') DEFAULT 'Pendente',
  `professor_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `reservas`
--

INSERT INTO `reservas` (`id`, `recurso_id`, `turno_id`, `data`, `curso_id`, `turma`, `professor_nome`, `professor_cpf`, `data_hora_reserva`, `status`, `professor_email`) VALUES
(46, 1, 3, '2024-11-11', 5, 1, 'Nicolle Beatriz', '123456789', NULL, 'Pendente', 'nicolle@gmail.com'),
(47, 22, 1, '2024-10-25', 6, 2, 'Gleiceanne Silva', '1234567', NULL, 'Pendente', 'gleiceanne@gmail.com'),
(48, 8, 2, '2024-10-16', 3, 2, 'Vitória Alves', '11111', NULL, 'Pendente', 'vitoria@gmail.com'),
(49, 15, 1, '2024-10-17', 6, 1, 'Thamires ', '12345', NULL, 'Pendente', 'thamires@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turnos`
--

CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `turnos`
--

INSERT INTO `turnos` (`id`, `nome`) VALUES
(1, 'Manhã'),
(2, 'Tarde'),
(3, 'Noite');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atribuicoes`
--
ALTER TABLE `atribuicoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recurso_id` (`recurso_id`),
  ADD KEY `turno_id` (`turno_id`);

--
-- Índices de tabela `coordenacao`
--
ALTER TABLE `coordenacao`
  ADD PRIMARY KEY (`cpf`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`cpf`);

--
-- Índices de tabela `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recurso_id` (`recurso_id`),
  ADD KEY `turno_id` (`turno_id`);

--
-- Índices de tabela `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atribuicoes`
--
ALTER TABLE `atribuicoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `coordenacao`
--
ALTER TABLE `coordenacao`
  MODIFY `cpf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `cpf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123456790;

--
-- AUTO_INCREMENT de tabela `recursos`
--
ALTER TABLE `recursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `atribuicoes`
--
ALTER TABLE `atribuicoes`
  ADD CONSTRAINT `atribuicoes_ibfk_1` FOREIGN KEY (`recurso_id`) REFERENCES `recursos` (`id`),
  ADD CONSTRAINT `atribuicoes_ibfk_2` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`);

--
-- Restrições para tabelas `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`recurso_id`) REFERENCES `recursos` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
