-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 08 2023 г., 14:49
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tkvbd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `insurance_history`
--

CREATE TABLE `insurance_history` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `insurance_type` varchar(255) NOT NULL,
  `status` enum('active','completed') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `insurance_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payout_amount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `insurance_history`
--

INSERT INTO `insurance_history` (`id`, `user_id`, `insurance_type`, `status`, `start_date`, `end_date`, `insurance_amount`, `payout_amount`) VALUES
(12, 8, 'Автомобильная страховка', 'active', '2023-07-05', '2023-08-05', '150000.00', '120000.00'),
(13, 8, 'Страхование от клеща', 'active', '2023-07-07', '2023-08-06', '2000.00', '1000.00'),
(14, 8, 'Страхование недвижимости', 'active', '2023-07-05', '2023-07-29', '15555.00', '23332.50'),
(15, 6, 'Страхование жизни', 'active', '2023-07-04', '2023-08-04', '34235432.00', '34235432.00'),
(16, 8, 'Страхование недвижимости', 'active', '2023-10-04', '2023-10-28', '324234.00', '486351.00'),
(17, 8, 'Медицинская страховка', 'active', '2023-09-26', '0004-01-04', '12.00', '14.40'),
(18, 1, 'Автомобильная страховка', 'active', '2023-10-01', '2023-10-31', '123213.00', '98570.40');

-- --------------------------------------------------------

--
-- Структура таблицы `insurance_types`
--

CREATE TABLE `insurance_types` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `coefficient` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `insurance_types`
--

INSERT INTO `insurance_types` (`id`, `name`, `coefficient`) VALUES
(1, 'Автомобильная страховка', '0.80'),
(2, 'Медицинская страховка', '1.20'),
(3, 'Страхование недвижимости', '1.50'),
(4, 'Страхование жизни', '1.00'),
(5, 'Страхование путешествий', '1.30');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `name`, `email`, `password`, `phone_number`, `date_of_birth`) VALUES
(1, 'admin', 'Admin', 'admin@admin.admin', '$2y$10$9FPll9Lbf2A/VgqK6lDh5uK/LUZqBHeo8C.zvf3CZW2.iOgN7NlMK', '+79999999999', '2011-11-11');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `insurance_history`
--
ALTER TABLE `insurance_history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `insurance_types`
--
ALTER TABLE `insurance_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `insurance_history`
--
ALTER TABLE `insurance_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `insurance_types`
--
ALTER TABLE `insurance_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
