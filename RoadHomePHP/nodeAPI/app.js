var express = require('express');
var path = require('path');
var favicon = require('serve-favicon');
var logger = require('morgan');
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser');
var fs = require('fs');

// var mysql = require('mysql');
//
// //MYSQL has not been set up yet
// var connection = mysql.createConnection({
//     host     : 'localhost',
//     user     : 'me',
//     password : 'password',
//     database : 'Users'
// });

var routes = require('./routes/index');
var users = require('./routes/users');
var login = require('./routes/login');
var mongoosedb = require('mongoose');

var app = express();
var sanitizer = require('sanitizer');

// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'hjs');
app.use(express.static('public'));

// uncomment after placing your favicon in /public
//app.use(favicon(path.join(__dirname, 'public', 'favicon.ico')));
app.use(logger('dev'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));
app.use(require('./routes'));

app.use('/', routes);
app.use('/users', users);
app.use('/login',login);

Users = require('./models/users');
Logins = require('./models/logins');


//TODO: set up Database connection etc.
mongoosedb.connect('mongodb://localhost/users');
var db = mongoosedb.connection;
//Routes below

app.get('/login', function(req, res, next) {


    res.sendFile(path.join(__dirname + '/login.html'));

});

app.get('/users',function(req,res){
    //This is a temporary redirect until we have something for this page.
    res.redirect('/api/users');
});

app.get('/thanks', function(req, res){

    res.sendFile(path.join(__dirname + '/views/usersEndpoint.html'));

    //res.redirect('/login');
});


app.get('/api/logins',function(req,res){

  // Logins.getUserByEmail(function(err,login){
  //   if(err){
  //     throw err;
  //   }
  //   res.json(login);
  // });
    res.sendStatus(404);

});

app.post('/api/logins', function(req, res, next){

//TODO: this route has not been defined

  if(false){
    res.sendStatus(201);
  }
  else{
    res.sendStatus(404);
  }

});

app.get('/api/users',function(req, res, next){

  Users.getUsers(function(err,users){
    if(err){
      throw err;
    }
    res.json(users);
  });

});

app.post('/api/users', function(req, res) {

    var email = sanitizer.escape(req.body.username);
    var firstName = sanitizer.escape(req.body.firstname);
    var lastName = sanitizer.escape(req.body.lastname);
    var organization = sanitizer.escape(req.body.organization);
    var department = sanitizer.escape(req.body.department);
    var time = new Date();
    console.log(time.getHours(),time.getMinutes(),time.getSeconds());

  //check to see if user exists if so send 400 TODO: this will come later right now persist all posts
  //else persist data to DB then login user and record time
  //This does not make a 'User' Object but it does the trick.
  var user = {
      email: email,
      firstName: firstName,
      lastName: lastName,
      organization: organization,
      department: department
    }

    Users.addUsers(user, function(err,user){
      if(err){
          res.status(400);
        throw err;
      }
      res.status(201);
        console.log("Status Code: 201");
      res.redirect('/thanks');
    });

//TODO: this needs a more verbose error status send back process

});

// catch 404 and forward to error handler
app.use(function(req, res, next) {
  var err = new Error('Not Found');
  err.status = 404;
  next(err);
});

// error handlers

// development error handler
// will print stacktrace
if (app.get('env') === 'development') {
  app.use(function(err, req, res, next) {
    res.status(err.status || 500);
    res.render('error', {
      message: err.message,
      error: err
    });
  });
}

// production error handler
// no stacktraces leaked to user
app.use(function(err, req, res, next) {
  res.status(err.status || 500);
  res.render('error', {
    message: err.message,
    error: {}
  });
});


module.exports = app;
