-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Okt 2022 pada 08.03
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `binotify`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `album`
--

CREATE TABLE `album` (
  `album_id` int(11) NOT NULL,
  `judul` varchar(64) NOT NULL,
  `penyanyi` varchar(128) NOT NULL,
  `total_duration` int(11) NOT NULL DEFAULT 0,
  `image_path` varchar(256) NOT NULL,
  `tanggal_terbit` date NOT NULL,
  `genre` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `album`
--

INSERT INTO `album` (`album_id`, `judul`, `penyanyi`, `total_duration`, `image_path`, `tanggal_terbit`, `genre`) VALUES
(5, 'Justice', 'Justin Bieber', 2032, '../../assets/img_album/Justice.png', '2021-03-19', 'Pop'),
(6, 'Changes', 'Justin Bieber', 0, '../../assets/img_album/Changes.png', '2020-02-14', 'Pop'),
(7, 'X', 'Ed Sheeran', 0, '../../assets/img_album/X.png', '2014-06-20', 'Pop'),
(8, 'Purpose', 'Justin Bieber', 0, '../../assets/img_album/Purpose.jpeg', '2015-11-13', 'Pop'),
(9, 'World Of Walker', 'Alan Walker', 0, '../../assets/img_album/World Of Walker.jpeg', '2021-11-26', 'EDM');

--
-- Trigger `album`
--
DELIMITER $$
CREATE TRIGGER `delete_album` AFTER DELETE ON `album` FOR EACH ROW UPDATE songs
    SET songs.album_id = NULL
    WHERE OLD.album_id = songs.album_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `songs`
--

CREATE TABLE `songs` (
  `song_id` int(11) NOT NULL,
  `judul` varchar(64) NOT NULL,
  `penyanyi` varchar(126) DEFAULT NULL,
  `tanggal_terbit` date NOT NULL,
  `genre` varchar(64) DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `audio_path` varchar(256) NOT NULL,
  `image_path` varchar(256) DEFAULT NULL,
  `album_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `songs`
--

INSERT INTO `songs` (`song_id`, `judul`, `penyanyi`, `tanggal_terbit`, `genre`, `duration`, `audio_path`, `image_path`, `album_id`) VALUES
(2, '2 Much', 'Justin Bieber', '2021-04-14', 'Pop', 153, '../../assets/songs/Justin Bieber - 2 Much (Visualizer).mp3', '../../assets/img_song/2 Much.png', 5),
(3, 'As I Am', 'Justin Bieber', '2021-07-19', 'Pop', 175, '../../assets/songs/Justin Bieber - As I Am (Visualizer) ft. Khalid.mp3', '../../assets/img_song/as i am.jpg', 5),
(4, 'Deserve You', 'Justin Bieber', '2021-07-28', 'Pop', 187, '../../assets/songs/Justin Bieber - Deserve You (Visualizer).mp3', '../../assets/img_song/Deserve you.png', 5),
(5, 'Die For You', 'Justin Bieber', '2021-07-14', 'Pop', 199, '../../assets/songs/Justin Bieber - Die For You (Visualizer) ft. Dominic Fike.mp3', '../../assets/img_song/die for you.jpeg', 5),
(6, 'Ghost', 'Justin Bieber', '2021-11-08', 'Pop', 212, '../../assets/songs/Justin Bieber - Ghost.mp3', '../../assets/img_song/Ghost.jpg', 5),
(7, 'Hold On', 'Justin Bieber', '2021-06-30', 'Pop', 176, '../../assets/songs/Justin Bieber - Hold On (Live from Paris).mp3', '../../assets/img_song/hold on.jpg', 5),
(8, 'Holy ft Chance The Rapper', 'Justin Bieber', '2021-11-23', 'Pop', 329, '../../assets/songs/Justin Bieber - Holy ft. Chance The Rapper.mp3', '../../assets/img_song/Holy.png', 5),
(9, 'MLK Interlude', 'Justin Bieber', '2021-11-26', 'Pop', 104, '../../assets/songs/Justin Bieber - MLK Interlude (Visualizer).mp3', '../../assets/img_song/mlk interlude.jpeg', 5),
(10, 'Somebody', 'Justin Bieber', '2021-10-28', 'Pop', 182, '../../assets/songs/Justin Bieber - Somebody (Live from Paris).mp3', '../../assets/img_song/somebody.jpg', 5),
(11, 'Unstable ft. The Kid LAROI', 'Justin Bieber', '2021-06-23', 'Pop', 158, '../../assets/songs/Justin Bieber - Unstable (Visualizer) ft. The Kid LAROI.mp3', '../../assets/img_song/unstable.jpg', 5),
(12, 'Off My Face', 'Justin Bieber', '2021-11-01', 'Pop', 157, '../../assets/songs/Justin Bieber - Off My Face (Visualizer).mp3', '../../assets/img_song/off my face.jpg', 5);

--
-- Trigger `songs`
--
DELIMITER $$
CREATE TRIGGER `delete_song` AFTER DELETE ON `songs` FOR EACH ROW IF OLD.album_id IS NOT NULL THEN
        IF OLD.album_id in (Select album_id FROM album) THEN
            UPDATE album
            SET total_duration = (SELECT SUM(Duration) FROM songs WHERE album_id = OLD.album_id)
            WHERE album_id = OLD.album_id;
        END IF;
    END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_song` AFTER INSERT ON `songs` FOR EACH ROW IF NEW.album_id IS NOT NULL THEN
        IF NEW.album_id in (Select album_id FROM album) THEN
            UPDATE album
            SET total_duration = (SELECT SUM(Duration) FROM songs WHERE album_id = NEW.album_id)
            WHERE album_id = NEW.album_id;
        END IF;
    END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_song` AFTER UPDATE ON `songs` FOR EACH ROW IF OLD.album_id IS NOT NULL THEN
        IF OLD.album_id in (Select album_id FROM album) THEN
            UPDATE album
            SET total_duration = (SELECT SUM(Duration) FROM songs WHERE album_id = OLD.album_id)
            WHERE album_id = NEW.album_id;
        END IF;
    END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `username` varchar(256) NOT NULL,
  `isAdmin` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `username`, `isAdmin`) VALUES
(1, 'admin1@admin.com', '$2y$10$3Mlk8rI78zadfIWBPFaD3.Xl3yMo5ONZn8IppPODMXJGNzUZfYjB6', 'admin1', 1),
(2, 'user1@user.com', '$2y$10$BEGXAgffYdzicxE6ODolguTgigDlk7ZS7x53f/pkWCb9WoTfcJftK', 'user1', 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`album_id`);

--
-- Indeks untuk tabel `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`song_id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `album`
--
ALTER TABLE `album`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `album` (`album_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
