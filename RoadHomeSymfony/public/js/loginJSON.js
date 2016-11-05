
$(document).ready(function(){

    $('#submitButton').click(function(){

        var email = $('#email').val();
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var organization = $('#organization').val();
        var groupnumber = $('#groupnumber').val();

        var jsonData = {
            "email": email,
            "firstname": firstname,
            "lastname": lastname,
            "organization": organization,
            "department": "departmentNone",
            "groupnumber": groupnumber
        };

        console.log(jsonData);

        $.ajax
        ({
            type: "POST",
            //the url where you want to sent the userName and password to
            url: 'localhost:8000/volunteers',
            crossDomain: true,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            //json object to sent to the authentication url
            data: JSON.stringify(jsonData),
            success: function () {

                alert("Thanks!");
            },
            error: function(){
                alert("Failure!");
            }
        })

    });

});


