<?php 
    class Book {
        private int $id;
        private string $title;
        private string $author;
        private string $description;
        private string $isbn;
        private string $genre;
        private string $publisher;
        private string $publicationDate;
        private string $status;

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

        public function getId() : int {
            return $this->id;
        }

        public function getTitle() : string {
            return $this->title;
        }

        public function getAuthor() : string {
            return $this->author;
        }

        public function getDescription() : string {
            return $this->description;
        }

        public function getIsbn() : string {
            return $this->isbn;
        }

        public function getGenre() : string {
            return $this->genre;
        }

        public function getPublisher() : string {
            return $this->publisher;
        }

        public function getPublicationDate() : string {
            return $this->publicationDate;
        }

        public function getStatus() : string {
            return $this->status;
        }

        public function isAvailable() : bool {
            return $this->status === 'A';
        }
    }
?>