var mongoose = require('mongoose');

var time = new Date();

var usersSchema = mongoose.Schema({

    email:{
        type: String,
        required: true
    },
    firstName:{
        type: String,
        required: true
    },
    lastName:{
        type: String,
        required: true
    },
    organization:{
        type: String,
        required: true
    },
    department:{
        type: String,
        required: true
    },
    create_date:{
        type: Date,
        default: Date.now()
    }
});

var Users = module.exports = mongoose.model('Users',usersSchema);

//TODO: THis needs extra work it isn't complete
module.exports.getUsers = function(callback, limit){
    Users.find(callback).limit(limit);
}

module.exports.getUserByEmail = function(email, callback){
    if(Users.find(email)){
        //TODO: this if statement needs to be revised
    }

}

module.exports.addUsers = function(users, callback){
    // if(){
    //
    // }
    Users.create(users, callback);
}

