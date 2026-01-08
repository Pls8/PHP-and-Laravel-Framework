<?php
// <!-- Resource -->
// $link = mysql_connect('localhost', 'user', 'pass');
// mysql_close($link);


// <!-- Array -->
$arrArray = array("apple", "banana", "cherry");
// <!-- key-value -->
$arrKeyValue = array(
    "fruit1" => "apple",
    "fruit3" => "cherry"
);
$arrShort = [1, 2, 3];
$numRange = range(1, 10);
$letterRange = range('a', 'z');
$setpedRange = range(0, 100, 10);
$num1 = 1;
$num2 = 2;
$compArray = compact('num1', 'num2');
$dataExtracted = ['test' => 'value'];
extract($dataExtracted);
echo $dataExtracted['test'];


//  <!-- Conditional Statements -->
if ($arrArray[0] == "apple") {
    echo "First element is apple";
}
if (isset($arrKeyValue["fruit1"])) {
    echo $arrKeyValue["fruit1"];
}


// <!-- Ternary Operator -->
// condition ? true : false;
echo $arrArray[0] == "apple" ? "First element is apple" : "First element is not apple";
echo isset($arrKeyValue["fruit1"]) ? $arrKeyValue["fruit1"] : "Key fruit1 does not exist";


// <!-- For Loop -->
for ($i = 0; $i < count($arrArray); $i++) {
    echo $arrArray[$i];
}


// <!-- While Loop -->
$i = 0;
while ($i < count($arrArray)) {
    echo $arrArray[$i];
    $i++;
}




// <!--  While Loop -->
$i = 0;
while ($i < count($arrArray)) {
    echo $arrArray[$i];
    $i++;
}




// <!-- Do While Loop -->
$i = 0;
do {
    echo $arrArray[$i];
    $i++;
} while ($i < count($arrArray));


// <!-- Foreach Loop -->
foreach ($arrArray as $value) {
    echo $value;
}
foreach ($arrKeyValue as $key => $value) {
    echo "$key: $value";
}




// Functions
function printArray($arr)
{
    foreach ($arr as $value) {
        echo $value;
    }
}
function printKeyValue($arr)
{
    foreach ($arr as $key => $value) {
        echo "$key: $value";
    }
}





//  Global Variables
global $globalVar;
$globalVar = "I am a global variable";

function testGlobal()
{

    echo $GLOBALS['globalVar'];
    echo $_GLOBALS['globalVar'] = "I am a global variable";
    print_r($_GLOBALS);
}
testGlobal();
global $globalVar;
echo $globalVar;


// validate/sanitize
$validate = filter_var(INPUT_GET['validate'], FILTER_VALIDATE_EMAIL);
if ($validate) {
    echo "Email is valid";
} else {
    echo "Email is not valid";
}
$sanitize = filter_var(INPUT_GET['sanitize'], FILTER_SANITIZE_EMAIL);




// GET/POST
echo $_GET['test'];
echo $_POST['test'];
echo $_REQUEST['test'];
echo $_SERVER['HTTP_USER_AGENT'];
if (isset($_GET['test'])) {
    echo "test is set";
}
if (isset($_POST['test'])) {
    echo "test is set";
}
if (isset($_REQUEST['test'])) {
    echo "test is set";
}
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    echo "User agent is set";
}
$colors = ['red', 'green', 'blue'];
print_r($_GET['colors']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Form submitted via POST";
    //$username = $_POST['username'];
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        echo "Username and password are required";
    } else {
        echo "Username: $username, Password: $password";
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo "Form submitted via GET";
} else {
    echo "Form not submitted";
}

// Cookies
setcookie("cookieName", "cookieValue", time() + (86400 * 30), "/");
echo $_COOKIE['cookieName'];
if (isset($_COOKIE['cookieName'])) {
    echo "cookieName is set";
}
echo $_COOKIE['cookieName'];

setcookie("cookieName", "", time() - 3600, "/"); // delete cookie
unset($_COOKIE['cookieName']); // unset cookie variable

// secure cookie
setcookie(
    'secureCookie',
    $secureTokken,
    [
        'expires' => time() + 86400,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]
);

// Sessions
session_start();
$_SESSION['username'] = 'Name';
echo "Hi " . $_SESSION['username'];
$_SESSION['username'] = 'Name2';
echo "Hi " . $_SESSION['username'];
unset($_SESSION['username']);
session_destroy();

// Server Variables
echo $_SERVER['DOCUMENT_ROOT'];
echo $_SERVER['SERVER_NAME'];
echo $_SERVER['SERVER_SOFTWARE'];
echo $_SERVER['SERVER_ADDR'];
echo $_SERVER['SERVER_PORT'];
//GET URL
$currentURL = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
echo $currentURL;

//ENVIRONMENT VARIABLES
echo $_ENV['DB_HOST'];
echo $_ENV['API_KEY'];
echo $_ENV['APP_ENV'];

//ENV Sensitive data 
$dbConfig = [
    'host' => $_ENV['DB_HOST'],
    'user' => $_ENV['DB_USER'],
    'pass' => $_ENV['DB_PASS'],
    'db' => $_ENV['DB_NAME']
];

//File 
$_FILES['file']['name'] === UPLOAD_ERR_OK;

//Request : Contains data from $_GET, $_POST, and $_COOKIE. NOT RECOMMENDED due to security risks.
$_REQUEST['test'];
$inputRquest = $_GET['inputRquest'] ??
    $_POST['inputRquest'] ??
    $_COOKIE['inputRquest'] ??
    $_SERVER['inputRquest'] ??
    $_ENV['inputRquest'] ??
    $_FILES['inputRquest'] ??
    null;
$inputRquest2 = filter_input(INPUT_GET, 'parameter', FILTER_SANITIZE_STRING)
    ?: filter_input(INPUT_POST, 'parameter', FILTER_SANITIZE_STRING);


try {
    $result = 10 / 0;
    echo $result;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally { // always executes, regardless of exception
    echo "Execution completed.";
}

//OOP
class Car
{
    public $make;
    public $model;
    public $year;

    public function __construct($make, $model, $year)
    {
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
    }

    public function getInfo()
    {
        return "{$this->year} {$this->make} {$this->model}";
    }
}

$car1 = new Car("Ford", "Mustang", 2020);
echo $car1->getInfo();


//destruct
class dbConnection0001
{
    private $connection;

    public function __construct($host, $user, $pass, $db)
    {
        $this->connection = new mysqli($host, $user, $pass, $db);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function __destruct()
    {
        //this->connection = null;
        $this->connection->close();
        echo "Connection closed";
    }
}

//public, protected, private
class BankAccount
{
    public $accountNumber;     // Accessible anywhere --
    protected $balance = 0;    // Accessible in class and child classes
    private $pin;              // Accessible only in this class

    public function __construct($accountNumber, $pin)
    {
        $this->accountNumber = $accountNumber;
        $this->pin = $pin;
    }

    public function deposit($amount)
    {
        $this->balance += $amount;
    }

    public function withdraw($amount)
    {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
        } else {
            echo "Insufficient funds";
        }
    }

    public function getBalance()
    {
        return $this->balance;
    }
}
echo "Balance: " . $account->getBalance();
