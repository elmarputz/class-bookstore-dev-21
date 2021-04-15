<?php  

namespace Data;

use Bookshop\Category;
use Bookshop\Book;
use Bookshop\User;


class DataManager implements IDataManager {

    private static $__connection; 

    private static function getConnection() {

        if (!isset(self::$__connection)) {

            $type = 'mysql';
            $host = 'localhost';
            $name = 'fh_scm4_bookshop';
            $user = 'root';
            $pass = ''; 

            self::$__connection = new \PDO($type . ':host=' . $host. ';dbname=' .$name .';charset=utf8', 
                $user, $pass);


        }
        return self::$__connection;
    }



    private static function closeConnection() {
        self::$__connection = null;
    }

    private static function close($cursor) {
        $cursor->closeCursor();
    }

    private static function fetchObject($cursor) {
        return $cursor->fetchObject();
    }



    public static function getCategories() : array {
        $categories = [];
        $con = self::getConnection();
        $res = $con->query( "
            SELECT id, name 
            FROM categories
        ");
        while ($cat = self::fetchObject($res)) {
            $categories[] = new Category($cat->id, $cat->name);
        }

        self::close($res);
        self::closeConnection();

        return $categories;
    }

    public static function getBooksByCategory(int $categoryId) : array {
        $res = [];
        
        return $res;
    }

    public static function getUserById (int $userId) : ?User {
      return null;
    }

    public static function getUserByUserName (string $userName) : ?User {
      return null;
    }


    public static function createOrder(int $userId, array $bookIds, string $nameOnCard, string $cardNumber) : int {
        return rand();
    }



}