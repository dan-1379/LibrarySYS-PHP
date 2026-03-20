<?php
    /**
     * Represents a book in the library system.
     * 
     * Stores all details related to a book including its title, author, ISBN, publisher and availability.
     * 
     * @author Dan
     * @version 1.0
     */
    class Book {
        /**
         * The unique identifier for the book. Defaults to 0 for new books as it will be auto incremented in mariadb
         * @var int
         */
        private int $id;

        /**
         * The title of the book.
         * @var string
         */
        private string $title;

        /**
         * The author of the book.
         * @var string
         */
        private string $author;

        /**
         * The description of the book.
         * @var string
         */
        private string $description;

        /**
         * The unique ISBN of the book.
         * @var string
         */
        private string $isbn;

        /**
         * The genre of the book.
         * @var string
         */
        private string $genre;

        /**
         * The publisher of the book.
         * @var string
         */
        private string $publisher;

        /**
         * The publication date of the book.
         * @var string
         */
        private string $publicationDate;

        /**
         * The status of the book, either Active ('A') or Unavailable ('U')
         * @var string
         */
        private string $status;

        /**
        * Creates a new Book instance.
        * 
        * @param string $title The title of the book
        * @param string $author The author of the book
        * @param string $description A brief description of the book
        * @param string $isbn The unique ISBN of the book
        * @param string $genre The genre of the book
        * @param string $publisher The publisher of the book
        * @param string $publicationDate The publication date of the book
        * @param int $bookID The unique identifier for the book, defaults to 0 for new books as it will be auto incremented in mariadb
        * @param string $status The status of the book
        */
        public function __construct(string $title, string $author, string $description, string $isbn, string $genre, 
                                    string $publisher, string $publicationDate, string $status, int $id = 0) {
            $this->title = $title;
            $this->author = $author;
            $this->description = $description;
            $this->isbn = $isbn;
            $this->genre = $genre;
            $this->publisher = $publisher;
            $this->publicationDate = $publicationDate;
            $this->status = $status;
            $this->id = $id;
        }

        /**
         * Returns the unique identifier for the book. Defaults to 0 for new books as it will be auto incremented in mariadb
         * @return int The book ID
         */
        public function getId() : int {
            return $this->id;
        }

        /**
         * Returns the title of the book.
         * @return string The book title
         */
        public function getTitle() : string {
            return $this->title;
        }

        /**
         * Returns the author of the book.
         * @return string The book author
         */
        public function getAuthor() : string {
            return $this->author;
        }

        /**
         * Returns the description of the book.
         * @return string The book description
         */
        public function getDescription() : string {
            return $this->description;
        }

        /**
         * Returns the ISBN of the book.
         * @return string The book ISBN
         */
        public function getIsbn() : string {
            return $this->isbn;
        }

        /**
         * Returns the genre of the book.
         * @return string The book genre
         */
        public function getGenre() : string {
            return $this->genre;
        }

        /**
         * Returns the publisher of the book.
         * @return string The book publisher
         */
        public function getPublisher() : string {
            return $this->publisher;
        }

        /**
         * Returns the publication date of the book.
         * @return string The book publication date
         */
        public function getPublicationDate() : string {
            return $this->publicationDate;
        }

        /**
         * Returns the status of the book.
         * @return string The book status
         */
        public function getStatus() : string {
            return $this->status;
        }

        /**
         * Checks if the book is available for loan.
         * @return bool True if the book is Available ('A'). Otherwise, false.
         */
        public function isAvailable() : bool {
            return $this->status === 'A';
        }
    }
?>