-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-04-20 23:32:31
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `course_selection`
--

-- --------------------------------------------------------

--
-- 資料表結構 `courses`
--

CREATE TABLE `courses` (
  `Cid` int(4) UNSIGNED NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Department` varchar(255) NOT NULL,
  `Members` int(255) UNSIGNED NOT NULL DEFAULT 0,
  `Capacity` int(255) UNSIGNED NOT NULL,
  `Credit` int(11) UNSIGNED NOT NULL,
  `Week` int(11) UNSIGNED NOT NULL,
  `Start` int(11) UNSIGNED NOT NULL,
  `End` int(11) UNSIGNED NOT NULL,
  `Is_required` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `courses`
--

INSERT INTO `courses` (`Cid`, `Name`, `Department`, `Members`, `Capacity`, `Credit`, `Week`, `Start`, `End`, `Is_required`) VALUES
(1001, '程式設計', '資訊系', 0, 2, 2, 1, 1, 2, 1),
(1002, '資料庫系統', '資訊系', 0, 60, 3, 2, 1, 3, 1),
(1003, '系統程式', '資訊系', 0, 60, 3, 5, 2, 4, 1),
(1004, '數位系統設計', '資訊系', 0, 60, 2, 1, 6, 7, 1),
(1005, '密碼學', '資訊系', 0, 1, 3, 3, 5, 7, 0),
(1006, '互聯網路', '資訊系', 0, 60, 3, 4, 5, 7, 1),
(1007, '離散數學', '資訊系', 0, 60, 2, 1, 1, 2, 0),
(1008, '機率與統計', '資訊系', 0, 60, 2, 1, 2, 3, 0),
(1009, '邏輯設計', '資訊系', 0, 60, 20, 5, 6, 7, 0),
(1010, '電子學', '電機系', 0, 60, 3, 3, 6, 8, 1),
(1011, '電磁學', '電機系', 0, 60, 3, 1, 2, 4, 1),
(1012, '工程數學', '電機系', 0, 60, 3, 1, 6, 8, 1),
(1013, '電路學', '電機系', 0, 60, 3, 4, 6, 8, 1),
(1014, '向量分析', '電機系', 0, 60, 3, 3, 2, 4, 0),
(1015, '電波工程概論', '電機系', 0, 60, 2, 4, 1, 2, 0),
(2001, '程式設計', '資訊系', 0, 60, 2, 1, 3, 4, 1),
(2002, '線性代數', '資訊系', 0, 60, 2, 2, 1, 2, 1),
(2003, '通訊與網路概論', '資訊系', 0, 60, 2, 3, 1, 2, 1),
(2004, '計算機概論', '資訊系', 0, 60, 3, 1, 6, 8, 1),
(2005, '資料結構', '資訊系', 0, 60, 3, 4, 6, 8, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `students`
--

CREATE TABLE `students` (
  `Sid` varchar(8) NOT NULL,
  `Grade` varchar(1) NOT NULL,
  `Credit` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `Department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `timetable`
--

CREATE TABLE `timetable` (
  `Sid` varchar(8) NOT NULL,
  `Cid` int(4) NOT NULL,
  `Course_name` varchar(255) NOT NULL,
  `Week` int(11) NOT NULL,
  `Start` int(11) NOT NULL,
  `End` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`Cid`);

--
-- 資料表索引 `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`Sid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
