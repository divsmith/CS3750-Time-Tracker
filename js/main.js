/// <reference path="libs/sweetalert2.js" />
/// <reference path="libs/jquery.js" />
/// <reference path="libs/moment.js" />
/// <reference path="libs/jquery.validate.js" />

// https://limonte.github.io/sweetalert2/
function sweetTest() {
    $("#adminSigninForm").validate();

    if (!$("#adminEmail").valid()) {
        $("#adminEmail").parent().addClass("has-error");
        $("#adminEmail").parent().removeClass("has-success");
    } else {
        $("#adminEmail").parent().addClass("has-success");
        $("#adminEmail").parent().removeClass("has-error");
    }

    if ($("#adminSigninForm").valid()) {
        swal({
            title: 'Success!',
            text: 'The alert showed up!',
            type: 'success',
            confirmButtonText: 'Close'
        }).done();
    }
};

(function () {
    function updateTime() {
        $("#homeCurrentTime").text(moment().format('h:mm:ss a'));
        $("#homeCurrentDate").text(moment().format('MMMM Do YYYY'));
    };

    updateTime();

    setInterval(function () {
        updateTime();
    }, 5000);
})();