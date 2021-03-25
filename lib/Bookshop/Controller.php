<?php 
namespace Bookshop;


class Controller extends BaseObject {


    const ACTION = 'action';
    const PAGE = 'page';
    const ACTION_ADD = 'addToCart';
    const ACTION_REMOVE = 'removeFromCart';
    const USER_NAME = 'userName';
    const USER_PASSWORD = 'password';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_ORDER = 'placeOrder';
    const CC_NAME = 'nameOnCard';
    const CC_NUMBER = 'cardNumber';

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

            case self::ACTION_LOGIN : {
                if (!AuthenticationManager::authenticate(
                    $_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD]
                )) {
                    $this->forwardRequest(array('Invalid username or password'));
                }
                Util::redirect();
                break;
            }

            case self::ACTION_LOGOUT : {
                AuthenticationManager::signOut();
                Util::redirect();
                break;

            }

            case self::ACTION_ORDER : {
                $user = AuthenticationManager::getAuthenticatedUser();
                 if ($user == null) {
                     $this->forwardRequest(['Not logged in']);
                     break;
                 }   

                 if (!$this->processCheckout($_POST[self::CC_NAME], $_POST[self::CC_NUMBER])) {
                    $this->forwardRequest(['Checkout failed']);

                 }
                 break;

            }

            default : 
                throw new \Exception('Unkown controller action: ' . $action);
                break;

        }

        return true;

    
    }

    protected function processCheckout (string $nameOnCard = null, string $cardNumber = null) : bool {

        $errors = [];

        if ($nameOnCard == null || strlen($nameOnCard) == 0) {
            $errors[] = 'Enter name on card';
        }
        if ($cardNumber == null || strlen($cardNumber) != 16 || !ctype_digit($cardNumber)) {
            $errors[] = 'invalid number';
        }

        if (sizeof($errors) > 0) {
            $this->forwardRequest($errors);
            return false; 
        }

        if (ShoppingCart::size() == 0) {
            $this->forwardRequest(['Shopping cart is empty']);
            return false; 
        }

        $user = AuthenticationManager::getAuthenticatedUser();
        $orderId = \Data\DataManager::createOrder($user->getId(), ShoppingCart::getAll(), $nameOnCard, $cardNumber);
        if (!$orderId) {
            $this->forwardRequest(['Couldn\'t create order']);
            return false; 
        }

        ShoppingCart::clear();
        Util::redirect('index.php?view=success&orderId=' . rawurlencode($orderId));

    }


    /**
   * 
   * @param array $errors : optional assign it to 
   * @param string $target : url for redirect of the request
   */
  protected function forwardRequest(array $errors = null, $target = null) {
    //check for given target and try to fall back to previous page if needed
    if ($target == null) {
      if (!isset($_REQUEST[self::PAGE])) {
        throw new Exception('Missing target for forward.');
      }
      $target = $_REQUEST[self::PAGE];
    }
    //forward request to target
    // optional - add errors to redirect and process them in view
    if (count($errors) > 0)
      $target .= '&errors=' . urlencode(serialize($errors));
    header('location: ' . $target);
    exit();
  }






}