<?php

namespace App\Controllers;
date_default_timezone_set('UTC');

use App\DB\Grammar\ProcedureBuilder;
use App\Model\BookModel;
use App\Model\UserModel;

class IndexController
{
    protected $connection;

    public function handle($data)//Results
    {
//        var_dump($data);
        $this->connection = new \App\DB\Connector\Connection('mariadb', 'joe', 'root', 'root');
        if ($data['action'] === 'signup') {
            return $this->signup($data);
        }
        if ($data['action'] === 'addBook')
            return $this->addBook($data);

        if ($data['action'] === 'search') {
            return $this->searchBook($data);
        }
    }

    public function signup($data) //Result Users
    {

        // set the default timezone to use. Available since PHP 5.1
        date_default_timezone_set('UTC');
        unset($data['action']);
        if (array_filter($data)) {
            $username = $data['username'];
            $email = $data['email'];
            $password = $data['password'];
            $city = $data['city'];
            $state = $data['state'];
            $phone = $data['phone'];
            $zip = $data['zip'];
            $now = new \DateTime();


            //Call procedure successfully
            $procedure = new ProcedureBuilder($this->connection);
            $procedure->call('sp_signUp', [$username, $email, $password, $city, $state, $phone, $zip, $now->format('Y-m-d H:i:s'), 0]);

        }
        return $this->searchUsers();
    }


    public function searchUsers()
    {

        $userModel = new UserModel($this->connection);

        $users = $userModel->select(['user_id', 'user_Name', 'email'])->getResults();//query users

        return $users;
    }

    public function listBooks()
    {
        $BookModel = new BookModel($this->connection);
        $books = $BookModel->select(['book_id', 'isbn_13', 'isbn_10', 'title', 'author', 'publisher', 'year_published', 'book_subject'])->getResults();

        return $books;
    }

    public function addBook($data)
    {
        unset($data['action']);
        if (array_filter($data)) {
            $isbn13 = $data['isbn13'];
            $isbn10 = $data['isbn10'];
            $title = $data['title'];
            $author = $data['author'];
            $publisher = $data['publisher'];
            $year = $data['year'];
            $subject = $data['subject'];
//        $price = $data['price'];

            // call procedure
            $procedure = new ProcedureBuilder($this->connection);
            $procedure->call('sp_addBook', [$isbn13, $isbn10, $title, $author, $publisher, $year, $subject]);
        }

        return $this->listBooks();
    }

    public function listSearchBook()
    {
        $BookModel = new BookModel($this->connection);
        $searchBook = $BookModel->select(['book_id', 'isbn_13', 'isbn_10', 'title', 'author', 'publisher', 'year_published', 'book_subject'])->getResults();

        return $searchBook;
    }

    public function searchBook($data)
    {
        unset($data['action']);
        //$data = ['action' => 'search', 'title' => 'search me', 'author' => ""] => ['title' => 'search me']

        if (array_filter($data)) {//['column' => 'search_value','column1' => 'search_value','column2' => 'search_value',]
            $model = new BookModel($this->connection);

            $query = $model->select(['book_id', 'isbn_13', 'isbn_10', 'title', 'author', 'publisher', 'year_published', 'book_subject']);
            $query->where('title', 'LIKE', sprintf('%%%s%%', $data['title']));
            unset($data['title']);
            $query->where(array_filter($data));
            $results = $query->getResults();

            return $results;
        }
    }
}
