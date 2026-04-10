# LibrarySYS
LibrarySYS is a web-based, intuitive library management system designed to support the daily operation of a library. The system is designed to streamline book management, member management, loan transaction and return management, and performing administration, while ensuring data integrity and strict adherence to the rules of the business.

## Features
- Authentication - Secure login/logout with bcrypt password hashing and role-based access control.
- Dashboard - Allow users to view library records at a glance.
- Book Management - Add and view all books in the library.
- Member Management - Add, Delete, Update and View all members in the library system.
- Loan Processing - Process loans for members with strict validation.
- Return Processing - Handle book returns.
- Fine Management - Track and process outstanding fines linked to member loans.

## Tech Stack
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![Xampp](https://img.shields.io/badge/Xampp-F37623?style=for-the-badge&logo=xampp&logoColor=white)
![Visual_Studio_Code](https://img.shields.io/badge/Visual_Studio_Code-0078D4?style=for-the-badge&logo=visual%20studio%20code&logoColor=white)

## Architecture
The LibrarySYS follows a Service-Repository Pattern:
```mermaid
flowchart TD
    A["UI Layer\nLogin · Dashboard · Books · Members · Loans · Fines"]
    B["Service Layer\nValidation · Business Logic"]
    C["Repository Layer\nBookRepo · MemberRepo · LoanRepo · FineRepo · AuthRepo"]
    D["MariaDB\nBooks · Members · Loans · LoanItems · Fines · Users"]
    E["Validation\nBookValidator · MemberValidator · AuthenticationValidator"]

    A <--> |LibraryService| B
    B <--> |Repository| C
    B <--> |Validation| E
    C <--> |PDO| D
```

## Entity Relationship Diagram
```mermaid
erDiagram
    MEMBERS ||--o{ LOANS : "takes out"
    LOANS ||--|{ LOANITEMS : "contains"
    BOOKS ||--o{ LOANITEMS : "included in"
    LOANITEMS ||--o| FINES : "can incur"
    BOOKS {
        TINYINT BookID PK
        VARCHAR Title 
        VARCHAR Author
        VARCHAR Description
        CHAR ISBN
        VARCHAR Genre
        VARCHAR Publisher
        DATE PublicationDate 
        ENUM Status
    }
    MEMBERS {
        TINYINT MemberID PK
        VARCHAR FirstName
        VARCHAR LastName
        DATE DOB
        VARCHAR Phone
        VARCHAR Email
        VARCHAR AddressLine1
        VARCHAR AddressLine2
        VARCHAR City
        VARCHAR County
        CHAR Eircode
        DATE RegistrationDate
        ENUM Status
    }
    LOANS {
        TINYINT LoanID PK
        DATE LoanDate
        DATE DueDate
        TINYINT MemberID FK
    }
    LOANITEMS {
        TINYINT LoanID FK
        TINYINT BookID FK
        DATE ReturnDate
    }
    FINES {
        TINYINT FineID PK
        DECIMAL FineAmount
        ENUM Status
        TINYINT LoanID FK
        TINYINT BookID FK
    }
    USERS {
        TINYINT UserID PK
        VARCHAR Username
        VARCHAR Password
    }
```

## Installation
### Prerequisites
- XAMPP
- PHP 8
- MariaDB
- Visual Studio Code or any PHP-compatible IDE

### Steps
1. Clone the repository into your XAMPP htdocs folder:
```terminal
C:\xampp\htdocs\LibrarySYS-PHP\
```

2. Navigate to MariaDB:
```terminal
C:\> cd\xampp\mysql\bin
C:\xampp\mysql\bin> mysql -u root -p
```

3. When prompted for a password, press Enter.

4. Once you are in the MariaDB environment, create the database:
```mysql
CREATE DATABASE LibrarySYS;
```

5. Exit the MariaDB environment and execute the following:
```terminal
C:\xampp\mysql\bin>mysql.exe -u root -p LibrarySYS < \LibrarySYS.sql
```

6. When prompted for a password, press Enter.
7. The database is now loaded into the LibrarySYS database.

8. Re-enter the MariaDB environment:
```terminal
C:\xampp\mysql\bin> mysql -u root -p
```
9. Use the earlier created database and confirm the tables are present:
```mysql
USE LibrarySYS;
SHOW TABLES;
```
10. Start Apache and MariaDB in the XAMPP Control Panel.
11. Open the application in your browser: http://localhost/LibrarySYS-PHP
