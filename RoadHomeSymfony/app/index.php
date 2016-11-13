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
 * $LastChangedDate$ 11/8/2016
 * $LastChangedBy$   Mark Richardson
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true ");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimple\Container;
use RoadHome\Infrastructure\MysqlVolunteerRepository;
use RoadHome\Domain\StringLiteral;
use RoadHome\Domain\Volunteer;
use RoadHome\Infrastructure\MysqlAdminRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$dic = bootstrap();
$app = $dic['app'];
$repo = $dic['db-driver'];
$mysqlrepo = new MysqlVolunteerRepository($repo);
$mysql_admin = new MysqlAdminRepository($repo);

$app->get('/', function () {

    var_dump(file_exists("../src/Domain/Volunteer.php"));
    var_dump(__DIR__);

    $response = new Response();
    $response->setStatusCode(200);
    return $response;

});


/**
 * This route will be used to display the loginPage html
 * get the loginpage(html)
 */
$app->get('/loginPage', function(){

    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/html');

    try {
        $response->setContent(file_get_contents(__DIR__."/../RoadHomeSymfony/login.html"));
    } catch(\Exception $e){
        $e->getMessage();
    }
    return $response;
});

/**
 * gets the loginPage css
 */
$app->get('/css/login', function(){

    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/css');
    try {
        $response->setContent(file_get_contents(__DIR__."/../public/stylesheets/login.css"));
    } catch(\Exception $e){
        $e->getMessage();
    }
    return $response;
});

/**
 * gets the loginPage javascript
 */
$app->get('/javascript/login', function(){

    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/javascript');
    try {
        $response->setContent(file_get_contents(__DIR__."/../public/js/loginJSON.js"));
    } catch(\Exception $e){
        $e->getMessage();
    }
    return $response;

});

/**
 * gets the jquery javascript
 */
$app->get('/javascript/jquery',function(){
    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/javascript');
    try {
        $response->setContent(file_get_contents(__DIR__."/../public/js/jquery-3.1.1.min.js"));
    } catch(\Exception $e){
        $e->getMessage();
    }
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
 * We May deprecate this endpoint
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

    if($content == '')
    {
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    //Raw user input
    $rawjsonArray = json_decode($content, true);

    //clean user input
    $jsonArray = volunteerSanitation($rawjsonArray);

    $volunteer = new Volunteer(new StringLiteral($jsonArray["email"]),new StringLiteral($jsonArray["firstname"]),
        new StringLiteral($jsonArray["lastname"]), new StringLiteral($jsonArray["organization"]), new StringLiteral($jsonArray["department"]),
        new StringLiteral($jsonArray["groupnumber"]), new StringLiteral($jsonArray["location"]));

    $mysqlrepo->add($volunteer);

    $response = new Response();
    $response->setStatusCode(201);
    return $response;
});

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

    //This is all the raw user input
    $rawjsonArray = json_decode($content, true);

    //This is the clean user input
    $jsonArray = volunteerSanitation($rawjsonArray);

    //create a Volunteer object ot pass to the volunteer repo
    $volunteer = new Volunteer(new StringLiteral($jsonArray["email"]),new StringLiteral($jsonArray["firstname"]),
        new StringLiteral($jsonArray["lastname"]), new StringLiteral($jsonArray["organization"]), new StringLiteral($jsonArray["department"]),
        new StringLiteral($jsonArray["groupnumber"]), new StringLiteral($jsonArray["location"]));

    $alreadyExists = $mysqlrepo->findByEmail($volunteer->getEmail());

    if(count($alreadyExists) === 0){
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
 * This is the report end point that will return all the volunteers in the DB in no specific order.
 */
$app->get('/reports', function(Request $request) use($mysqlrepo){

    if($request->getMethod() != 'GET'){

        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    $content = $request->getContent();

    if($content != '')
    {
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    //create the csv file and store it
    allToCSV($mysqlrepo);

    $response = new Response();
    $response->setStatusCode(200);
    return $response;

});

/**
 * This is a get Route for the admin login page This will return an html page
 */
$app->get('/adminLogin', function(){

    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/html');
    try {
        $response->setContent(file_get_contents(__DIR__."/../admin_login.html"));
    } catch(\Exception $e){
        $e->getMessage();
    }
    return $response;

});

/**
 * This is a get Route for the admin page This will return an html page
 */
$app->get('/admin', function(){

    //if client has valid token then show page

    //else redirect to login page

    //

    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/html');
    try {
        $response->setContent(file_get_contents(__DIR__."/../admin_page.html"));
    } catch(\Exception $e){
        $e->getMessage();
    }
    return $response;

});

/**
 * Posting to the admin end point will be how you login to use the reports endpoints.
 */
$app->post('/admin', function(Request $request) use($mysql_admin) {

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

    if($content == '')
    {
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    $jsonArray = json_decode($content, true);

    $username = $jsonArray['username'];
    $passphrase = $jsonArray['passphrase'];

    $clean_username = filter_var($username, FILTER_SANITIZE_URL);
    //encrypt password with salt
    $salt = 'RoadHomeAndPI3141592!';
    $enc_pass = crypt($passphrase, $salt);

    //this will call the DB to get the credentials
    $validCredentials = $mysql_admin->verifyCredentials(new StringLiteral($clean_username), new StringLiteral($enc_pass));

    //This will check to see if a row was returned from the DB if not exit
    if(count($validCredentials) === 0){
        $response = new Response();
        $response->setStatusCode(500);
        return $response;
    }

    //TODO: On success set the client session info
    //Should I store the token in the mysql array and update it?

    $response = new Response();
    $response->setStatusCode(201);
    return $response;

});


$app->run();

/**
 * Function that will sanitize all volunteer input data from forms
 * (NOT TESTED)
 * Created: 10-30-2016
 * @param Request $request
 * @return array
 */
function volunteerSanitation($jsonArray) {

    $cleanEmail = filter_var($jsonArray['email'],FILTER_SANITIZE_EMAIL);
    $cleanFirst = filter_var($jsonArray['firstname'],FILTER_SANITIZE_URL);
    $cleanLast = filter_var($jsonArray['lastname'],FILTER_SANITIZE_URL);
    $cleanOrganization = filter_var($jsonArray['organization'],FILTER_SANITIZE_URL);
    $cleanDepartment = filter_var($jsonArray['department'],FILTER_SANITIZE_URL);
    $cleanGroupnumber = filter_var($jsonArray['groupnumber'],FILTER_SANITIZE_URL);
    $cleanLocation = filter_var($jsonArray['location'], FILTER_SANITIZE_URL);

    $responseData = ['email' => $cleanEmail, 'firstname' => $cleanFirst, 'lastname' => $cleanLast,
        'organization' => $cleanOrganization, 'department' => $cleanDepartment, 'groupnumber' => $cleanGroupnumber, 'location' => $cleanLocation];
    return $responseData;
}

/**
 * @param MysqlVolunteerRepository $mysqlrepo
 * This will convert all the Volunteers in the Database to a CSV file
 */
function allToCSV(MysqlVolunteerRepository $mysqlrepo) {

    $data = $mysqlrepo->findAll();

    //below is the file name this will create a file that will be different, 'w' flag will write from the beginning
    //this will be used because we are only writing one individual file
    //this will store the file to the CSV_Files folder temporarily
    //TODO: this needs to be changed so it isn't a direct path
    $fp = fopen(__DIR__ . '/../CSV_FIles/database_'.date("Y-m-d").'_Log'.'.csv', 'w');

    foreach ($data as $fields) {
        //output data to the csv file
        fputcsv($fp, $fields);
    }

    //close the file
    fclose($fp);
}

/**
 * @return Container
 * This is the dependency injection container
 */
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