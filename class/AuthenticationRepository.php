<?php 
    /** 
     * Handles database operations for the login authentication in the library system.
     * 
     * Provides methods to retrieve and verify login credentials on the system.
     * 
     * @author Dan
     * @version 1.0
    */
    class AuthenticationRepository {
        /**
         * The database connection.
         * 
         * @var pdo
         */
        private PDO $pdo;

        /**
         * Creates a new BookRepository instance.
         * 
         * @param PDO $pdo The database connection
         */
        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        public function processRegistration($username, $hashedPassword) : void {
            $sql = "INSERT INTO Users(email, password) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);

            try {
                $stmt->execute([$username, $hashedPassword]);
            } catch(PDOException $e) {
                throw $e;
            }
        }

        /**
         * Retrieves login credentials for user with specified username to check users entry
         * against.
         * 
         * If username exists in the database, the username and password are extracted.
         * The password entered is hashed and compared to the password in the database.
         * If this is successful, the login is successful and the username is returned for 
         * the session.
         * 
         * @param $username The username of the user.
         * @param $password The password of the user prior to hashing.
         * 
         * @return null|array An array of errors if errors occur or null if login is successful.
         */
        public function processLogin($username, $password) : ?array {
            $sql = "SELECT * FROM Users WHERE Username = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if (!$user) {
                return null;
            }

            if (!password_verify($password, $user['Password'])) {
                return null;
            }

            return ["username" => $user['Username']];
        }
    }
?>