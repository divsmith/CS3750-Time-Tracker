<?php
/**
 * File name: index.php
 * Project: project1
 * PHP version 5
 * @category  PHP
 * @author    mark Richardson <compynerds@gmail.com>
 * @modifier  Mark Richardson 8/6/2016
 * @copyright 2016 © donbstringham
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * $LastChangedDate$ 8/6/2016
 * $LastChangedBy$   Mark Richardson
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimple\Container;
use RoadHome\Infrastructure\MysqlVolunteerRepository;
use RoadHome\Domain\StringLiteral;
use RoadHome\Domain\ValueObject;
use RoadHome\Domain\Volunteer;

require_once __DIR__ . '/../vendor/autoload.php';


$dic = bootstrap();
$app = $dic['app'];
$repo = $dic['db-driver'];
$mysqlrepo = new MysqlVolunteerRepository($repo);

//$app->before(function (Request $request) {
//    $password = $request->getPassword();
//    $username = $request->getUser();
//
//    if ($username !== 'professor') {
//        $response = new Response();
//        $response->setStatusCode(401);
//
//        return $response;
//    }
//
//    if ($password !== '1234pass') {
//        $response = new Response();
//        $response->setStatusCode(401);
//
//        return $response;
//    }
//});
$app->get('/', function () {

    var_dump(file_exists("../src/Domain/Volunteer.php"));

    $response = new Response();
    $response->setStatusCode(200);
    return $response;

});

//$app->get('/ping', function() use ($dic) {
//    $response = new Response();
//    $driver = $dic['db-driver'];
//    if (!$driver instanceof \PDO) {
//        $response->setStatusCode(500);
//        $msg = ['msg' => 'could not connect to the database'];
//        $response->setContent(json_encode($msg));
//        return $response;
//    }
//    $repo = $dic['repo-mysql'];
//    if (!$repo instanceof RoadHome\src\Domain\VolunteerRepository) {
//        $response->setStatusCode(500);
//        $msg = ['msg' => 'repository problem'];
//        $response->setContent(json_encode($msg));
//        return $response;
//    }
//    $response->setStatusCode(200);
//    $msg = ['msg' => 'pong'];
//    $response->setContent(json_encode($msg));
//    return $response;
//});

$app->get('/login', function() {
    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent(phpversion());
    return $response;
});

$app->get('/volunteers', function() use($mysqlrepo) {

    $data = $mysqlrepo->findAll();

    $response = new Response();
    $response->setContent($data);
    $response->setStatusCode(200);
    return $response;
});

$app->get('/volunteers/{id}', function($id) {

    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent("here you go #$id");
    return $response;
});

/**
 * This route will accept the posted info and persist it to the Mysql DB
 * --Working(tested with PostMan)--
 */
$app->post('/volunteers', function (Request $request) use ($mysqlrepo){


    $content = $request->getContent();
    if($content === '')
    {
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    $jsonArray = json_decode($content, true);

    $volunteer = new Volunteer(new StringLiteral($jsonArray["email"]),new StringLiteral($jsonArray["firstname"]),
        new StringLiteral($jsonArray["lastname"]), new StringLiteral($jsonArray["organization"]), new StringLiteral($jsonArray["department"]),
        new StringLiteral($jsonArray["groupnumber"]));

    $mysqlrepo->add($volunteer);

    $response = new Response();
    $response->setStatusCode(201);
    return $response;
});

//TODO: not sure we need to have an update function for this application
$app->put('/volunteers/{id}', function ($id, Request $request) use ($dic) {
    $response = new Response();
    $response->setStatusCode(501);
    return $response;
});

$app->run();

function requestValidation() {



}



function bootstrap()
{
    $dic = new Container();
    $dic['app'] = function() {
        return new Silex\Application();
    };

    $dic['db-driver'] = function() {
        $servername = "localhost";
        $username = "root";
        $password = "one";
        $dbname = "RoadHome";
        $charset = "utf8";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $conn = null;
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

        try {
            $conn = new PDO($dsn, $username, $password, $opt);
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
    };

    $pdo = $dic['db-driver'];

    $dic['repo-mysql'] = function() use ($pdo) {
        return new MysqlVolunteerRepository($pdo);
    };

    return $dic;
}