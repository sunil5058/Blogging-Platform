-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 04:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 2, 3, 'wow', '2026-02-02 07:47:10'),
(3, 6, 6, 'hello baby', '2026-02-02 10:31:36'),
(4, 10, 7, 'Boss? You literally get sliced before pie 😏', '2026-02-03 02:20:23'),
(5, 11, 7, 'That’s why people eat me as a snack 😎', '2026-02-03 02:20:57'),
(6, 11, 7, 'That’s why people eat me as a snack 😎', '2026-02-03 02:21:19'),
(7, 10, 8, 'Calm down Steve Jobs 🍏', '2026-02-03 02:27:16'),
(8, 10, 8, 'Calm down Steve Jobs 🍏', '2026-02-03 02:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `created_at`, `updated_at`) VALUES
(1, 2, 'Make money from home.', 'Blogging can be quite lucrative if done correctly. The top bloggers in the world obviously earn quite a bit, but even a part-time blogger can expect to make a nice profit if things are done correctly. The best part about it is that blogging is a form of passive income, since you can spend just a few hours a week writing a piece of content and then continue to profit from it long after the the writing is finished. I go into much more detail on how to blog for money later in this guide....!', '2026-02-01 17:48:30', '2026-02-01 17:49:18'),
(2, 2, 'Recognition for yourself or your business.', 'No, you probably won’t have paparazzi following you around because of your latest post. But a successful blog makes your idea into a reality, and can gain you a ton of recognition in your respective field. Many bloggers are known as experts just because of their blogs, and some have even gotten book and movie deals based on their blogs.', '2026-02-01 17:48:58', '2026-02-01 17:48:58'),
(3, 3, 'The Power of Small Habits', 'We often think that success comes from big decisions and dramatic changes, but in reality, it’s the small daily habits that shape our lives the most. Waking up 10 minutes earlier, drinking more water, or writing a few lines each day can slowly build momentum.\r\n\r\nSmall habits are powerful because they are easy to start and hard to quit. When a habit feels manageable, we’re more likely to stay consistent. Over time, these tiny actions compound into meaningful results—better health, stronger skills, and improved confidence.\r\n\r\nThe key is patience. Results don’t appear overnight, but if you stick with a habit long enough, progress becomes visible. Instead of trying to change everything at once, focus on improving just 1% every day.\r\n\r\nSo today, pick one small habit. Start simple. Stay consistent. Let time do the rest.', '2026-02-01 18:04:51', '2026-02-01 18:04:51'),
(4, 3, 'How Technology Is Transforming Education', 'Education is no longer limited to classrooms, chalkboards, and textbooks. With the rapid growth of technology, learning has become more accessible, interactive, and personalized than ever before. From online classes to AI-powered tools, technology is reshaping how students learn and how teachers teach.', '2026-02-02 07:31:19', '2026-02-02 07:31:19'),
(5, 3, 'How Technology Is Transforming Education', 'Education is no longer limited to classrooms, chalkboards, and textbooks. With the rapid growth of technology, learning has become more accessible, interactive, and personalized than ever before. From online classes to AI-powered tools, technology is reshaping how students learn and how teachers teach.', '2026-02-02 07:36:52', '2026-02-02 07:36:52'),
(6, 3, 'yyyyyyyyy', 'editedddddddddddddddddddddddddd', '2026-02-02 07:57:10', '2026-02-02 07:57:28'),
(8, 6, 'Wlcome to the Apple Blog', 'i am apple from mustang eat me !', '2026-02-02 10:33:13', '2026-02-02 10:33:13'),
(9, 6, 'apple second blog', 'i am so tasty so eat me plz banana attract musquito baby', '2026-02-02 10:35:09', '2026-02-03 02:42:50'),
(10, 6, 'Why Apples Think They’re the Boss', 'Apples are crunchy, shiny, and always sitting on top of the fruit basket like a CEO.\r\nDoctors fear us, pies depend on us, and gravity stories are incomplete without us.\r\nYes, we’re sweet—but also powerful. Respect the apple.', '2026-02-03 02:08:25', '2026-02-03 02:08:25'),
(11, 6, 'An Apple a Day… Still Hungry', 'People say eat an apple a day, but nobody says what to eat after that.\r\nBecause honestly, one apple is just a teaser.\r\nStill healthy though, so no regrets. 😁', '2026-02-03 02:08:52', '2026-02-03 02:56:00'),
(12, 7, 'Life Is Better When You Slip', 'Bananas are soft, sweet, and dramatic.\r\nWe turn black when ignored and cause chaos on the floor.\r\nStill, everyone loves us in smoothies.', '2026-02-03 02:21:42', '2026-02-03 02:21:42'),
(13, 7, 'Why Bananas Are Always Late', 'We start green, turn yellow, then suddenly overthink life and become black.\r\nNo one understands our timing.\r\nEat us fast or regret it.', '2026-02-03 02:22:22', '2026-02-03 02:22:22'),
(14, 8, 'Small Fruit, Big Attitude', 'Cherries are tiny but powerful.\r\nWe don’t need size—we have style.\r\nAlso, two cherries are better than one. Always.', '2026-02-03 02:23:24', '2026-02-03 02:23:24'),
(15, 8, 'Why Cherries Are Always on Top', 'Cakes, drinks, selfies—we sit on top of everything.\r\nNot bragging, just facts.\r\nLife is better with a cherry on top.', '2026-02-03 02:23:53', '2026-02-03 02:23:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(2, 'Sunil Shrestha', 'sunilshrestha2061@gmail.com', '$2y$10$yTZJ4/nYauCogCYACRCha.OEypc.2BaBFHqD6IWGJLu7musEJ6s9G', '2026-02-01 17:46:12'),
(3, 'test1', 'test1@gmail.com', '$2y$10$SPH7vD970edmTzo8UdQCMOQvXZR4YLJ13sP.HRa6zMOLdwU.sjymy', '2026-02-01 18:03:03'),
(4, 'test2', 'test2@gmail.com', '$2y$10$UbSJJIiRxczzldxyFK4J.OlJF6NPAamOUemp6Aot8mvy4nOxjxj/W', '2026-02-01 18:03:30'),
(5, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-02-02 07:43:49'),
(6, 'Apple', 'apple@gmail.com', '$2y$10$Cynd/gzDNhcDGlyDURbe0eJDsZ2L6nogQG0PuQgifRavkCjVGH1mS', '2026-02-02 10:27:56'),
(7, 'Banana', 'banana@gmail.com', '$2y$10$FtOefOHfzjqjLIiKITbDRefvoOtYJlHz8BSTm/xwZtSRk183PaLtm', '2026-02-02 10:29:30'),
(8, 'Cherry', 'cherry@gmail.com', '$2y$10$2rLv.NwV/OYyZh6qRTXoMeXwixvwYpZsqgVSTD0HCCEQ.BRSXF2yi', '2026-02-02 10:30:29'),
(9, 'Aalu', 'aalu@gmail.com', '$2y$10$Zg3n27V7LcFTaYRL0XXUYupJDCzAc/T7aDzFzY9ZGBkLCxjCMFJR2', '2026-02-02 10:30:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
