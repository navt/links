-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 27 2018 г., 16:46
-- Версия сервера: 5.5.50
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `egarant`
--

-- --------------------------------------------------------

--
-- Структура таблицы `egarant_links`
--

CREATE TABLE IF NOT EXISTS `egarant_links` (
  `id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `addition_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `egarant_links`
--

INSERT INTO `egarant_links` (`id`, `link`, `addition_date`) VALUES
(2, 'https://yandex.ru/', '2018-05-27 16:43:48'),
(3, 'https://translate.google.ru/#en/ru/salt', '2018-05-27 16:43:48');

-- --------------------------------------------------------

--
-- Структура таблицы `egarant_workers`
--

CREATE TABLE IF NOT EXISTS `egarant_workers` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `hash` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `egarant_workers`
--

INSERT INTO `egarant_workers` (`id`, `email`, `hash`) VALUES
(4, 'user@yandex.ru', '$1$user@yan$Bqu6gO2BS4F/FZeGOG43E.');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `egarant_links`
--
ALTER TABLE `egarant_links`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `egarant_workers`
--
ALTER TABLE `egarant_workers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `egarant_links`
--
ALTER TABLE `egarant_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `egarant_workers`
--
ALTER TABLE `egarant_workers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
