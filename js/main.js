/// <reference path="libs/sweetalert2.js" />

// https://limonte.github.io/sweetalert2/
function sweetTest() {
    swal({
        title: 'Success!',
        text: 'The alert showed up!',
        type: 'success',
        confirmButtonText: 'Close'
    }).done();
};