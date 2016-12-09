
$(document).ready(function(){

    var $email = $('#email');
    var $firstname = $('#firstname');
    var $lastname = $('#lastname');
    var $organization = $('#organization');
    var $department = $('#department');
    var $groupnumber = $('#groupnumber');
    var $location = $('#location');





    $('#submitButton').click(function(){

        var email = $email.val();
        var firstname = $firstname.val();
        var lastname = $lastname.val();
        var organization = $organization.val();
        var department = $department.val();
        var groupnumber = $groupnumber.val();
        var location = $location.val();

        var jsonData = {
            "email": email,
            "firstname": firstname,
            "lastname": lastname,
            "organization": organization,
            "department": department,
            "groupnumber": groupnumber,
            "location": location
        };

        console.log(jsonData);

        $.ajax
        ({
            type: "POST",
            //the url where you want to sent the userName and password to
            url: 'http://localhost:8000/volunteers',
            crossDomain: true,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            //json object to sent to the authentication url
            data: JSON.stringify(jsonData),
            success: function () {
                clearInput();
            },
            error: function(){
                clearInput();
            }
        })

    });

    function clearInput(){
        $email.val('');
        $firstname.val('');
        $lastname.val('');
        $organization.val('');
        $department.val('Art Club');
        $location.val('Midvale, UT');
        $groupnumber.val('');
    }

});


