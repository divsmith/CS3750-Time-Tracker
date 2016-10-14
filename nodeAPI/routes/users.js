var express = require('express');
var router = express.Router();

/*GET to user end point TODO: This needs to be changed to a post*/
router.get('/users', function(req, res,next) {
  res.sendStatus(201);

});

module.exports = router;
