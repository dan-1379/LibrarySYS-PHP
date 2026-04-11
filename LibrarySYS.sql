-- MySQL dump 10.13  Distrib 5.7.24, for osx11.1 (x86_64)
--
-- Host: localhost    Database: LibrarySYS
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Books`
--

DROP TABLE IF EXISTS `Books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Books` (
  `BookID` tinyint(3) NOT NULL AUTO_INCREMENT,
  `Title` varchar(30) NOT NULL,
  `Author` varchar(25) NOT NULL,
  `Description` varchar(30) NOT NULL,
  `ISBN` char(17) NOT NULL,
  `Genre` varchar(20) NOT NULL,
  `Publisher` varchar(25) NOT NULL,
  `PublicationDate` date DEFAULT NULL,
  `Status` enum('A','U') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`BookID`),
  UNIQUE KEY `ISBN` (`ISBN`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Books`
--

LOCK TABLES `Books` WRITE;
/*!40000 ALTER TABLE `Books` DISABLE KEYS */;
INSERT INTO `Books` VALUES (1,'The Maze Runner 1','James Dashner','Dystopian Novel','978-0-385-73794-4','sci-fi','Delacorte Press','2009-10-06','A'),(2,'The Hunger Games','Suzanne Collins','Dystopian Novel','978-0-439-02352-8','sci-fi','Scholastic Press','2008-09-14','A'),(3,'Divergent','Veronica Roth','Science Fiction Novel','978-0-06-202403-9','sci-fi','Katherine Tegen Books','2011-04-25','A'),(4,'Harry Potter','J.K. Rowling','Fantasy Novel','978-0-7475-3269-9','fantasy','Bloomsbury','1997-06-26','A'),(5,'Percy Jackson','Rick Riordan','Fantasy Adventure','978-0-7868-5629-9','fantasy','Disney Hyperion','2005-06-28','A'),(6,'Dune','Frank Herbert','A desert planet','978-0-4410-1359-3','sci-fi','Chilton Books','1965-08-01','A'),(7,'The Hobbit','J.R.R. Tolkien','A hobbit adventure','978-0-6180-0221-4','fantasy','Allen and Unwin','1937-09-21','A'),(8,'The Shining','Stephen King','Haunted hotel','978-0-3850-0751-1','horror','Doubleday','1977-01-28','A'),(9,'Dracula','Bram Stoker','Classic vampire','978-0-1430-5459-7','horror','Archibald Constable','1897-05-26','A'),(10,'Gone Girl','Gillian Flynn','A vanishing wife','978-0-3075-8836-4','thriller','Crown Publishing','2012-06-05','A');
/*!40000 ALTER TABLE `Books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Fines`
--

DROP TABLE IF EXISTS `Fines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Fines` (
  `FineID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `FineAmount` decimal(5,2) NOT NULL,
  `Status` enum('U','P') NOT NULL DEFAULT 'U',
  `LoanID` tinyint(4) NOT NULL,
  `BookID` tinyint(3) NOT NULL,
  PRIMARY KEY (`FineID`),
  KEY `fk_fines_loanitems` (`LoanID`,`BookID`),
  CONSTRAINT `fk_fines_loanitems` FOREIGN KEY (`LoanID`, `BookID`) REFERENCES `LoanItems` (`LoanID`, `BookID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Fines`
--

LOCK TABLES `Fines` WRITE;
/*!40000 ALTER TABLE `Fines` DISABLE KEYS */;
INSERT INTO `Fines` VALUES (1,1.40,'U',4,6),(2,1.40,'U',4,3),(3,0.80,'P',5,6),(4,0.20,'U',6,4);
/*!40000 ALTER TABLE `Fines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LoanItems`
--

DROP TABLE IF EXISTS `LoanItems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LoanItems` (
  `LoanID` tinyint(4) NOT NULL,
  `BookID` tinyint(3) NOT NULL,
  `ReturnDate` date DEFAULT NULL,
  PRIMARY KEY (`LoanID`,`BookID`),
  KEY `fk_loanitems_books` (`BookID`),
  CONSTRAINT `fk_loanitems_books` FOREIGN KEY (`BookID`) REFERENCES `Books` (`BookID`),
  CONSTRAINT `fk_loanitems_loans` FOREIGN KEY (`LoanID`) REFERENCES `Loans` (`LoanID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LoanItems`
--

LOCK TABLES `LoanItems` WRITE;
/*!40000 ALTER TABLE `LoanItems` DISABLE KEYS */;
INSERT INTO `LoanItems` VALUES (1,1,'2025-06-05'),(1,2,'2025-06-05'),(2,4,'2025-07-15'),(3,7,'2025-10-21'),(4,3,'2026-01-22'),(4,5,'2026-01-14'),(4,6,'2026-01-22'),(5,6,'2026-03-01'),(6,1,'2026-03-24'),(6,2,'2026-03-24'),(6,3,'2026-03-21'),(6,4,'2026-03-26'),(6,5,'2026-03-20');
/*!40000 ALTER TABLE `LoanItems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Loans`
--

DROP TABLE IF EXISTS `Loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Loans` (
  `LoanID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `LoanDate` date NOT NULL,
  `DueDate` date NOT NULL,
  `MemberID` tinyint(3) NOT NULL,
  PRIMARY KEY (`LoanID`),
  KEY `fk_loans_members` (`MemberID`),
  CONSTRAINT `fk_loans_members` FOREIGN KEY (`MemberID`) REFERENCES `Members` (`MemberID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Loans`
--

LOCK TABLES `Loans` WRITE;
/*!40000 ALTER TABLE `Loans` DISABLE KEYS */;
INSERT INTO `Loans` VALUES (1,'2025-06-01','2025-06-06',1),(2,'2025-07-10','2025-07-15',2),(3,'2025-10-20','2025-10-25',3),(4,'2026-01-10','2026-01-15',1),(5,'2026-02-20','2026-02-25',4),(6,'2026-03-20','2026-03-25',1);
/*!40000 ALTER TABLE `Loans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Members`
--

DROP TABLE IF EXISTS `Members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Members` (
  `MemberID` tinyint(3) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(30) NOT NULL,
  `DOB` date NOT NULL,
  `Phone` varchar(12) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `AddressLine1` varchar(30) NOT NULL,
  `AddressLine2` varchar(30) DEFAULT NULL,
  `City` varchar(30) NOT NULL,
  `County` varchar(10) NOT NULL,
  `Eircode` char(7) NOT NULL,
  `RegistrationDate` date NOT NULL,
  `Status` enum('A','I') DEFAULT 'A',
  PRIMARY KEY (`MemberID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Members`
--

LOCK TABLES `Members` WRITE;
/*!40000 ALTER TABLE `Members` DISABLE KEYS */;
INSERT INTO `Members` VALUES (1,'Dan','Courtney','2004-02-12','0874032491','dancourtney@gmail.com','14 Rockfeller Road','Rockfeller Lane','Killorglin','Kerry','V93V2F8','2025-05-01','A'),(2,'Sarah','Obrien','1999-05-23','0865127784','sarahobrien@gmail.com','7 Oakwood Drive','Oakwood Estate','Tralee','Kerry','V93A3F2','2025-06-01','A'),(3,'Michael','Horgan','1985-11-11','0853349012','michael.horgan@yahoo.ie','22 Riverside View','Riverside','Listowel','Galway','V31K2X9','2025-09-01','A'),(4,'Eoin','Mannix','2005-05-02','0872345678','eoin.mannix22@gmail.com','1 Orchard Way','Ballydribeen','Killarney','Kerry','V93H5X0','2026-01-01','A'),(5,'Andrew','Johnson','2005-03-01','0873241529','drewJones13@gmail.com','1 Hemmington Way','Varlington Road','Drumcondra','Dublin','V24F568','2026-03-20','A'),(6,'James','Murphy','1990-05-14','0871452684','James.murphy@gmail.com','12 Oak Street','Apt 3','Dublin','Dublin','D01AB23','2022-03-10','A'),(7,'Emma','Walsh','1978-08-03','0874567890','emma.walsh@outlook.com','23 Birch Lane','Block B','Limerick','Limerick','V94GH89','2020-05-05','A'),(8,'Patrick','Fitzgerald','1972-12-09','0878903286','pat.fitz@gmail.com','34 Spruce Walk','Ground Floor','Wexford','Wexford','Y35MN45','2021-02-28','A'),(9,'Dua','Lipa','1982-01-01','0871273246','dula.peep@gmail.com','1 Angel Way','PatricksTown','Dundalk','Limerick','V93H792','2026-01-02','A'),(10,'Jamie','Courtney','2000-04-24','0874567143','Jamiecourtney8@gmail.com','Ownagarry','Killorglin','Killorglin','Kerry','V93H756','2025-06-12','A');
/*!40000 ALTER TABLE `Members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `UserID` tinyint(2) NOT NULL AUTO_INCREMENT,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'manager','$2y$10$CGzrGchuHnB6t4ebQ936lOvT7WL9YW6YPnzGl6hSv9aVCJx3UCmji'),(2,'librarian','$2y$10$6TUl9q1r8AMrTENPcT1cxeErhOOX3QzquX5oyZCiKsJ0COlPbqg/.'),(3,'reception','$2y$10$T5gRir3zI.7NRHzwMCiSaewrkHdJcHuXsEfbdTikrRLzXG0JIq5ny');
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-05 11:17:38
