-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Okt 2021 pada 08.47
-- Versi server: 10.4.19-MariaDB
-- Versi PHP: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pesanan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `Id` int(11) NOT NULL,
  `Nama` varchar(25) NOT NULL,
  `Pesanan` varchar(25) NOT NULL,
  `No Meja` varchar(25) NOT NULL,
  `Jumlah Pesanan` varchar(25) NOT NULL,
  `Tanggal` date NOT NULL,
  `Status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`Id`, `Nama`, `Pesanan`, `No Meja`, `Jumlah Pesanan`, `Tanggal`, `Status`) VALUES
(3, 'nano', 'Es Jeruk', '04', '1', '2021-10-05', 'Belum Bayar'),
(4, 'M.Faisal', 'Pempek Kulit', '02', '7', '2021-10-05', 'Belum Bayar'),
(5, 'Irfan', 'Jus Jeruk', '09', '2', '2021-10-27', 'Lunas'),
(10, 'Alex', 'Pempek Panggang', '12', '2', '2021-10-27', 'Lunas'),
(12, 'Balqis', 'Jus Alpukat', '08', '1', '2021-10-28', 'Lunas');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Nama` (`Nama`) USING BTREE;

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
