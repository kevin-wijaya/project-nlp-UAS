-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2023 at 05:48 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_uas_nlp`
--

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `judul` varchar(512) NOT NULL,
  `stopwords` varchar(512) NOT NULL,
  `stemming` varchar(512) NOT NULL,
  `tokenize` varchar(512) NOT NULL,
  `lsa_id` int(11) NOT NULL,
  `lda_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `tanggal`, `judul`, `stopwords`, `stemming`, `tokenize`, `lsa_id`, `lda_id`) VALUES
(1, '2023-07-19', 'gempa m guncang jayapura papua', 'gempa m guncang jayapura papua', 'gempa m guncang jayapura papua', 'gempa, m, guncang, jayapura, papua', 1, 1),
(2, '2023-07-19', 'babak baru kecelakaan ka brantas vs truk kini diusut kepolisian', 'babak baru kecelakaan ka brantas truk kini diusut kepolisian', 'babak baru celaka ka brantas truk kini usut polisi', 'babak, baru, celaka, ka, brantas, truk, kini, usut, polisi', 1, 1),
(3, '2023-07-19', 'zelensky berang rusia serang situs penyimpanan biji bijian ukraina', 'zelensky berang rusia serang situs penyimpanan biji bijian ukraina', 'zelensky berang rusia serang situs simpan biji biji ukraina', 'zelensky, berang, rusia, serang, situs, simpan, biji, biji, ukraina', 1, 2),
(4, '2023-07-19', 'belum berakhir upaya pita limjaroenrat menuju kursi pm thailand', 'berakhir upaya pita limjaroenrat menuju kursi pm thailand', 'akhir upaya pita limjaroenrat tuju kursi pm thailand', 'akhir, upaya, pita, limjaroenrat, tuju, kursi, pm, thailand', 1, 2),
(5, '2023-07-19', 'gempa m terjadi di maluku tengah', 'gempa m terjadi maluku tengah', 'gempa m jadi malu tengah', 'gempa, m, jadi, malu, tengah', 1, 2),
(6, '2023-07-19', 'palsukan domisili siswa di cianjur dicoret dari ppdb sma smk', 'palsukan domisili siswa cianjur dicoret ppdb sma smk', 'palsu domisili siswa cianjur coret ppdb sma smk', 'palsu, domisili, siswa, cianjur, coret, ppdb, sma, smk', 1, 2),
(7, '2023-07-19', 'relawan ganjar sebut bakal pakai baju garis hitam putih setiap rabu', 'relawan ganjar sebut bakal pakai baju garis hitam putih rabu', 'rawan ganjar sebut bakal pakai baju garis hitam putih rabu', 'rawan, ganjar, sebut, bakal, pakai, baju, garis, hitam, putih, rabu', 2, 1),
(8, '2023-07-19', 'terima kunjungan mahasiswa mpr diskusi soal peran komunikasi publik', 'terima kunjungan mahasiswa mpr diskusi soal peran komunikasi publik', 'terima kunjung mahasiswa mpr diskusi soal peran komunikasi publik', 'terima, kunjung, mahasiswa, mpr, diskusi, soal, peran, komunikasi, publik', 2, 1),
(9, '2023-07-19', 'buka bukaan menpora dito soal asal harta hadiah di lhkpn', 'buka bukaan menpora dito soal asal harta hadiah lhkpn', 'buka buka menpora dito soal asal harta hadiah lhkpn', 'buka, buka, menpora, dito, soal, asal, harta, hadiah, lhkpn', 1, 2),
(10, '2023-07-19', 'bpip gembleng wawasan kebangsaan calon paskibraka nasional', 'bpip gembleng wawasan kebangsaan calon paskibraka nasional', 'bpip gembleng wawas bangsa calon paskibraka nasional', 'bpip, gembleng, wawas, bangsa, calon, paskibraka, nasional', 1, 1),
(11, '2023-07-19', 'abg di sulteng dipaksa nikah demi lunasi utang ortu ngadu malah dianiaya', 'abg sulteng dipaksa nikah lunasi utang ortu ngadu malah dianiaya', 'abg sulteng paksa nikah lunas utang ortu ngadu malah aniaya', 'abg, sulteng, paksa, nikah, lunas, utang, ortu, ngadu, malah, aniaya', 1, 1),
(12, '2023-07-19', 'pujian budiman sudjatmiko ke prabowo berujung panggilan dari pdip', 'pujian budiman sudjatmiko prabowo berujung panggilan pdip', 'puji budiman sudjatmiko prabowo ujung panggil pdip', 'puji, budiman, sudjatmiko, prabowo, ujung, panggil, pdip', 1, 1),
(13, '2023-07-19', 'erick thohir zulhas azwar anas hingga mahfud md kumpul di tim ada apa ', 'erick thohir zulhas azwar anas hingga mahfud md kumpul tim apa ', 'erick thohir zulhas azwar anas hingga mahfud md kumpul tim apa', 'erick, thohir, zulhas, azwar, anas, hingga, mahfud, md, kumpul, tim, apa', 1, 2),
(14, '2023-07-19', 'arahan ganjar ke relawan usai baliho dicopot tni tak boleh tersinggung', 'arahan ganjar relawan usai baliho dicopot tni boleh tersinggung', 'arah ganjar rawan usai baliho copot tni boleh singgung', 'arah, ganjar, rawan, usai, baliho, copot, tni, boleh, singgung', 2, 1),
(15, '2023-07-19', 'momen ahy bertolak ke yogya sebagai pembicara kuliah umum ugm', 'momen ahy bertolak yogya pembicara kuliah umum ugm', 'momen ahy tolak yogya bicara kuliah umum ugm', 'momen, ahy, tolak, yogya, bicara, kuliah, umum, ugm', 1, 2),
(16, '2023-07-19', 'tanda tanya nasib prajurit as yang hilang usai nyelonong ke korea utara', 'tanda tanya nasib prajurit as hilang usai nyelonong korea utara', 'tanda tanya nasib prajurit as hilang usai nyelonong korea utara', 'tanda, tanya, nasib, prajurit, as, hilang, usai, nyelonong, korea, utara', 2, 2),
(17, '2023-07-19', 'kpk bicara peluang panggil menpora dito terkait harta hadiah rp m', 'kpk bicara peluang panggil menpora dito terkait harta hadiah m', 'kpk bicara peluang panggil menpora dito kait harta hadiah m', 'kpk, bicara, peluang, panggil, menpora, dito, kait, harta, hadiah, m', 1, 2),
(18, '2023-07-19', 'tahun baru semangat baru untuk berkhidmah untuk bangsa', 'tahun baru semangat baru berkhidmah bangsa', 'tahun baru semangat baru khidmah bangsa', 'tahun, baru, semangat, baru, khidmah, bangsa', 1, 2),
(19, '2023-07-19', 'ganjar ungkap kedekatan dengan jokowi ngomongin soal relawan juga', 'ganjar ungkap kedekatan jokowi ngomongin soal relawan', 'ganjar ungkap dekat jokowi ngomongin soal rawan', 'ganjar, ungkap, dekat, jokowi, ngomongin, soal, rawan', 2, 1),
(20, '2023-07-19', 'relawan prabowo kita nggak mau overheat seperti', 'relawan prabowo nggak mau overheat', 'rawan prabowo nggak mau overheat', 'rawan, prabowo, nggak, mau, overheat', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `lda`
--

CREATE TABLE `lda` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `keyword` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lda`
--

INSERT INTO `lda` (`id`, `name`, `keyword`) VALUES
(1, '1', 'rawan, ganjar, gempa, soal, jayapura, m, bpip, baru, gembleng, nasional'),
(2, '2', 'baru, m, buka, biji, bicara, harta, dito, prabowo, hadiah, menpora');

-- --------------------------------------------------------

--
-- Table structure for table `lsa`
--

CREATE TABLE `lsa` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `keyword` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lsa`
--

INSERT INTO `lsa` (`id`, `name`, `keyword`) VALUES
(1, '1', 'm, buka, menpora, harta, hadiah, dito, gempa, panggil, kait, kpk'),
(2, '2', 'rawan, ganjar, gempa, jokowi, ngomongin, dekat, ungkap, m, prabowo, mau');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lda`
--
ALTER TABLE `lda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lsa`
--
ALTER TABLE `lsa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lda`
--
ALTER TABLE `lda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lsa`
--
ALTER TABLE `lsa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
