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

            return [
                    "username" => $user['Username']
                ];
        }
    }
?>