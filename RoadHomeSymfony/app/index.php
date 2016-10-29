<?php
/**
 * File name: index.php
 * Project: RoadHomeSymfony
 * PHP version 5
 * @category  PHP
 * @author    Mark Richardson Richardson <compynerds@gmail.com>
 * @modifier  Mark Richardson 8/6/2016
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * $LastChangedDate$ 10/22/2016
 * $LastChangedBy$   Mark Richardson
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimple\Container;
use RoadHome\Infrastructure\MysqlVolunteerRepository;
use RoadHome\Domain\StringLiteral;
use RoadHome\Domain\ValueObject;
use RoadHome\Domain\Volunteer;
use RoadHome\Infrastructure\MysqlLoginsRepository;

require_once __DIR__ . '/../vendor/autoload.php';


$dic = bootstrap();
$app = $dic['app'];
$repo = $dic['db-driver'];
$mysqlrepo = new MysqlVolunteerRepository($repo);
//currently not used but this will be used to access the login table in the RoadHome DB
$mysqllogin = new MysqlLoginsRepository($repo);

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

$app->get('/loginPage', function() {
    //This route will be used to display the html
    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent(phpversion());
    return $response;
});

/**
 * Select all volunteer records
 * --Working(tested with PostMan)-- 10-22-2016
 * --preliminary criteria checks(NOT TESTED) 10-22-2016
 * @return JSON object with volunteers
 */
$app->get('/volunteers', function(Request $request) use($mysqlrepo) {

    /** If the payload isn't empty exit */
    if($request->getContent() !== ''){
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

    //These could probably be combined
    $data = $mysqlrepo->findAll();
    $jsonData = json_encode($data, true);

    $response = new Response();
    $response->setContent($jsonData);
    $response->setStatusCode(200);
    return $response;
});

/**
 * Select a single record with the specified ID
 * --Working(Test via PostMan)-- 10-22-2016
 */
$app->get('/volunteers/{id}', function(Request $request,$id) use ($mysqlrepo) {

    if($request->getContent() !== '')
    {
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

    $intToStrLit = new StringLiteral($id);

    $data = $mysqlrepo->findById($intToStrLit);
    $jsonData = json_encode($data, true);

    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent($jsonData);
    return $response;
});

/**
 * This route will accept the posted info and persist it to the Mysql DB
 * --Working(tested with PostMan)-- 10-22-2016
 * --Working(content-type working tested with PostMan)-- 10-22-2016
 * Added unique email so duplicate emails won't be persisted to mysql(Tested with PostMan) 10-23-2016
 */
$app->post('/volunteers', function (Request $request) use ($mysqlrepo){

    if($request->getMethod() != 'POST'){

        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    if(0 !== strpos($request->headers->get('Content-Type'), 'application/json')){
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

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

    $alreadyExists = $mysqlrepo->add($volunteer);

    if($alreadyExists === 0){
        $response = new Response();
        $response->setStatusCode(500);
        return $response;
    }

    //TODO: send user to end point for login/logout

    $response = new Response();
    $response->setStatusCode(201);
    return $response;
});

//TODO: not sure we need to have an update function for this application
/**
 * Not tested
 */
$app->put('/volunteers/{id}', function (Request $request) use ($mysqlrepo) {

    if($request->getMethod() != 'PUT'){

        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    if(0 !== strpos($request->headers->get('Content-Type'), 'application/json')){
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

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

    $alreadyExists = $mysqlrepo->add($volunteer);

    if($alreadyExists === 0){
        $response = new Response();
        $response->setStatusCode(500);
        return $response;
    }

    $mysqlrepo->update($volunteer);

    $response = new Response();
    $response->setStatusCode(202);
    return $response;
});

/**
 * Created 10-22-2016
 * Simple implementation(No real logic, NOT TESTED)
 */
$app->get('/logins', function (Request $request) use($mysqllogin){


    if($request->getContent() !== '')
    {
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

    $data = $mysqllogin->findAll();
    $jsonData = json_encode($data, true);

    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent($jsonData);
    return $response;
});

/**
 * Created 10-22-2016
 * Simple implementation(No real logic, NOT TESTED)
 */
$app->get('/logins/{id}', function (Request $request, $id) use($mysqllogin){


    if($request->getContent() !== '')
    {
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

    $intToStrLit = new StringLiteral($id);

    $data = $mysqllogin->findById($intToStrLit);
    $jsonData = json_encode($data, true);

    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent($jsonData);
    return $response;
});

/**
 * Created 10-22-2016
 * Simple implementation(NOT TESTED)
 * Does not consider logging out just logging in
 */
$app->post('/logins', function (Request $request) use($mysqlrepo, $mysqllogin){

    if($request->getMethod() != 'POST'){
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    if(0 !== strpos($request->headers->get('Content-Type'), 'application/json')){
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

    $content = $request->getContent();

    if($content === ''){
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

    $data = json_decode($content, true);
    $email = new StringLiteral($data["email"]);
    $volunteer_record = $mysqlrepo->findByEmail($email);
    $id = new StringLiteral($volunteer_record["id"]);

    $mysqllogin->add($email, $id);

    $response = new Response();
    $response->setStatusCode(201);
    return $response;

});

/**
 * Created 10-22-2016
 * Simple implementation(No real logic, NOT TESTED)
 */
$app->put('/logins/{id}', function (Request $request, $id) use($mysqllogin, $mysqlrepo){

    //TODO: this may be used think of how the admins will get the ID they need to update
    //TODO: this could be provided via the volunteers get endpoint(all volunteers)

    if(0 !== strpos($request->headers->get('Content-Type'), 'application/json')){
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

    $content = $request->getContent();

    if($content === ''){
        $response = new Response();
        $response->setStatusCode(400);
        return $response;
    }

    //TODO: This will break if a valid id from volunteers table doesn't exist.(foreign key volunteer_id in logins table)

    $volunteer = $mysqlrepo->findById(new StringLiteral($id));
    var_dump($volunteer);

    if(count($volunteer) === 0){
        $response = new Response();
        $response->setStatusCode(500);
        $response->setContent("An Error with the DB has occurred");
        return $response;
    }

    $mysqllogin->add(new StringLiteral('email@email.com'),new StringLiteral($volunteer['id']));


    $response = new Response();
    $response->setStatusCode(202);
    return $response;

});



$app->run();

/**
 * Function that will sanitize all input data from forms
 * @eturn boolean true/false
 */
function requestValidation() {

    //TODO: do sanitation and other needed functions to ensure some level of security


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