var mongoose = require('mongoose');

var loginsSchema = mongoose.Schema({

    email:{
        type: String,
        required: true
    },
    active:{
        type: Boolean,
        default: false,
        required: true
    },
    create_date:{
        type: Date,
        default: Date.now()
    }
});

var Login = module.exports = mongoose.model('Login',loginsSchema);

//TODO: THis needs extra work it isn't complete
module.exports.getLoginByEmail = function(callback, limit){
    Login.find(callback).limit(limit);

}
