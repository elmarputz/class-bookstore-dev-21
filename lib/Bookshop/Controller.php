<?php 
namespace Bookshop;


class Controller extends BaseObject {


    const ACTION = 'action';
    const PAGE = 'page';
    const ACTION_ADD = 'addToCart';
    const ACTION_REMOVE = 'removeFromCart';

    private static $instance = false; 

    private function __construct() {}

    public static function getInstance() : Controller {

        if (!self::$instance) {
            self::$instance = new Controller();
        }
        return self::$instance;

    }

    public function invokePostAction() : bool  {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new \Exception('Controller can only handle POST requests');
            return false;
        }
        elseif (!isset($_REQUEST[self::ACTION])) {
            throw new \Exception(self::ACTION . ' not defined');
            return false;
        }

        $action = $_REQUEST[self::ACTION];
        
        switch ($action) {
            case self::ACTION_ADD : 
                ShoppingCart::add($_REQUEST['bookId']);
                Util::redirect();   
                break;

            case self::ACTION_REMOVE : {
                ShoppingCart::remove($_REQUEST['bookId']);
                Util::redirect();   
                break;
            }

            default : 
                throw new \Exception('Unkown controller action: ' . $action);
                break;

        }

        return true;

    
    }







}