-- 
-- Banco de Dados: `usuarios`
-- 

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `usuarios`
-- 

CREATE TABLE `usuarios` (
  `id` smallint(4) NOT NULL auto_increment,
  `nome` varchar(30) collate latin1_general_ci NOT NULL,
  `login` varchar(15) collate latin1_general_ci NOT NULL,
  `senha` varchar(32) collate latin1_general_ci NOT NULL,
  `email` varchar(25) collate latin1_general_ci NOT NULL,
  `telefone` varchar(13) collate latin1_general_ci NOT NULL,
  `cpf` varchar(14) collate latin1_general_ci default NULL,
  `sexo` varchar(1) collate latin1_general_ci default NULL,
  `empresa` varchar(25) collate latin1_general_ci default NULL,
  `cnpj` varchar(18) collate latin1_general_ci default NULL,
  `tipo` varchar(4) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=18 ;