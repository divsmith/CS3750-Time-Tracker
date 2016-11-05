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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimple\Container;
use RoadHome\Infrastructure\MysqlVolunteerRepository;
use RoadHome\Domain\StringLiteral;
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

$app->get('/loginPage', function(){
    //This route will be used to display the html

    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/html');
    $response->setContent(file_get_contents("/home/kidlappy/PhpstormProjects/RoadHomeSymfony/login.html"));
    return $response;
});
/**
 * gets the login css
 */
$app->get('/css/login', function(){
    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/css');
    $response->setContent(file_get_contents("/home/kidlappy/PhpstormProjects/RoadHomeSymfony/public/stylesheets/login.css"));
    return $response;

});

/**
 * gets the login javascript
 */
$app->get('/javascript/login', function(){
    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/javascript');
    $response->setContent(file_get_contents("/home/kidlappy/PhpstormProjects/RoadHomeSymfony/public/js/loginJSON.js"));
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

    var_dump($request->getContent());


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

 //   $content = volunteerSanitation($request);

    $content = $request->getContent();

    if($content == '')
    {
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    $jsonArray = json_decode($content, true);

    $volunteer = new Volunteer(new StringLiteral($jsonArray["email"]),new StringLiteral($jsonArray["firstname"]),
        new StringLiteral($jsonArray["lastname"]), new StringLiteral($jsonArray["organization"]), new StringLiteral($jsonArray["department"]),
        new StringLiteral($jsonArray["groupnumber"]), new StringLiteral($jsonArray["location"]));

    $alreadyExists = $mysqlrepo->add($volunteer);

    /**
     * Mysql will return a 0 if the email is already in use.
     */
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
 * @modified 11/3/2016 by Mark Richardson (added the location StringLiteral location for revised DB)
 */
$app->put('/volunteers', function (Request $request) use ($mysqlrepo) {

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
        new StringLiteral($jsonArray["groupnumber"]), new StringLiteral($jsonArray["location"]));

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
 * @modified 11/3/2016 by Mark Richardson (The login table is no longer used or supported)
 * ALL COMMENTED OUT CODE BELOW HAS BEEN DEPRECATED THE LOGIN TABLE IS NO LONGER USED
 */
//
///**
// * Created 10-22-2016
// * Simple implementation(No real logic, NOT TESTED)
// */
//$app->get('/logins', function (Request $request) use($mysqllogin){
//
//    if($request->getContent() !== '')
//    {
//        $response = new Response();
//        $response->setStatusCode(400);
//        return $response;
//    }
//
//    $data = $mysqllogin->findAll();
//    $jsonData = json_encode($data, true);
//
//    $response = new Response();
//    $response->setStatusCode(200);
//    $response->setContent($jsonData);
//    return $response;
//});
//
///**
// * Created 10-22-2016
// * Simple implementation(No real logic, NOT TESTED)
// */
//$app->get('/logins/{id}', function (Request $request, $id) use($mysqllogin){
//
//    if($request->getContent() !== '')
//    {
//        $response = new Response();
//        $response->setStatusCode(400);
//        return $response;
//    }
//
//    $intToStrLit = new StringLiteral($id);
//
//    $data = $mysqllogin->findById($intToStrLit);
//    $jsonData = json_encode($data, true);
//
//    $response = new Response();
//    $response->setStatusCode(200);
//    $response->setContent($jsonData);
//    return $response;
//});
//
///**
// * Created 10-22-2016
// * Simple implementation(NOT TESTED)
// * Does not consider logging out just logging in
// */
//$app->post('/logins', function (Request $request) use($mysqlrepo, $mysqllogin){
//
//    if($request->getMethod() != 'POST'){
//        $response = new Response();
//        $response->setStatusCode(204);
//        return $response;
//    }
//
//    if(0 !== strpos($request->headers->get('Content-Type'), 'application/json')){
//        $response = new Response();
//        $response->setStatusCode(400);
//        return $response;
//    }
//
//    $content = $request->getContent();
//
//    if($content === ''){
//        $response = new Response();
//        $response->setStatusCode(400);
//        return $response;
//    }
//
//    $data = json_decode($content, true);
//    $email = new StringLiteral($data["email"]);
//    $volunteer_record = $mysqlrepo->findByEmail($email);
//    $id = new StringLiteral($volunteer_record["id"]);
//
//    $mysqllogin->add($email, $id);
//
//    $response = new Response();
//    $response->setStatusCode(201);
//    return $response;
//
//});
//
///**
// * Created 10-22-2016
// * Simple implementation(No real logic, NOT TESTED)
// */
//$app->put('/logins/{id}', function (Request $request, $id) use($mysqllogin, $mysqlrepo){
//
//    //TODO: this may be used think of how the admins will get the ID they need to update
//    //TODO: this could be provided via the volunteers get endpoint(all volunteers)
//
//    if(0 !== strpos($request->headers->get('Content-Type'), 'application/json')){
//        $response = new Response();
//        $response->setStatusCode(400);
//        return $response;
//    }
//
//    $content = $request->getContent();
//
//    if($content === ''){
//        $response = new Response();
//        $response->setStatusCode(400);
//        return $response;
//    }
//
//    //TODO: This will break if a valid id from volunteers table doesn't exist.(foreign key volunteer_id in logins table)
//
//    $volunteer = $mysqlrepo->findById(new StringLiteral($id));
//    var_dump($volunteer);
//
//    if(count($volunteer) === 0){
//        $response = new Response();
//        $response->setStatusCode(500);
//        $response->setContent("An Error with the DB has occurred");
//        return $response;
//    }
//
//    $mysqllogin->add(new StringLiteral('email@email.com'),new StringLiteral($volunteer['id']));
//
//
//    $response = new Response();
//    $response->setStatusCode(202);
//    return $response;
//
//});


$app->run();

/**
 * Function that will sanitize all volunteer input data from forms
 * (NOT TESTED)
 * Created: 10-30-2016
 * @param Request $request
 * @return array
 */
function volunteerSanitation(Request $request) {

    $content = $request->getContent();
    $jsonContent = json_decode($content, true);

    $cleanEmail = filter_var($jsonContent['email'],FILTER_SANITIZE_EMAIL);
    $cleanFirst = filter_var($jsonContent['firstname'],FILTER_SANITIZE_URL);
    $cleanLast = filter_var($jsonContent['lastname'],FILTER_SANITIZE_URL);
    $cleanOrganization = filter_var($jsonContent['organization'],FILTER_SANITIZE_URL);
    $cleanDepartment = filter_var($jsonContent['department'],FILTER_SANITIZE_URL);
    $cleanGroupnumber = filter_var($jsonContent['groupnumber'],FILTER_SANITIZE_URL);

    $responseData = ['email' => $cleanEmail, 'firstname' => $cleanFirst, 'lastname' => $cleanLast,
        'organization' => $cleanOrganization, 'department' => $cleanDepartment, 'groupnumber' => $cleanGroupnumber];
    return $responseData;
}

/**
 * Function that will sanitize all login input data from forms
 * (NOT TESTED)
 * Created: 10-30-2016
 * @param Request $request
 * @return array
 */
function loginSanitation(Request $request){

    $content = $request->getContent();
    $jsonContent = json_decode($content,true);

    $cleanEmail = filter_var($jsonContent['email'],FILTER_SANITIZE_EMAIL);

    return $cleanEmail;
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

    return $dic;
}