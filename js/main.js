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

    }
};

// index.html
function loadMain() {
    $(document).ready(function () {
        // Timer
        function updateTime() {
            $("#homeCurrentTime").text(moment().format('h:mm:ss a'));
            $("#homeCurrentDate").text(moment().format('MMMM Do YYYY'));
        };

        updateTime();

        setInterval(function () {
            updateTime();
        }, 5000);

        // Login
        function loginAdmin() {
            swal({
                title: 'Success!',
                text: 'Login successful. Redirecting to admin page in 2 seconds.',
                type: 'success',
                timer: 2000,
                showLoaderOnConfirm: true,
                onClose: function () {
                    window.location = 'admin.html';
                }
            }).catch(swal.noop);
        }

        $('#adminPwd').on('keydown', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                loginAdmin();
            }
        });

        $('#loginAdmin').on('click', function (e) {
            e.preventDefault();
            loginAdmin();
        });
    });
}

// admin.html
function loadAdmin() {
    $(document).ready(function () {
        // Init - Setup Views
        var views = [];
        views.push($('#homeDiv'));
        views.push($('#activityDiv'));
        views.push($('#reportsDiv'));

        var links = [];
        links.push($('#homeLink'));
        links.push($('#activityLink'));
        links.push($('#reportsLink'));
        
        // Go to default view
        changeView($('#homeDiv'), $('#homeLink'));

        // Logout
        function logout() {
            window.location = '.';
        }

        $('#signOut').on('click', function (e) {
            e.preventDefault();
            logout();
        });

        // View changes - Setup on click handlers
        function changeView(onView, onLink) {
            views.forEach(function (selView) {
                selView.hide();
            });

            onView.show();

            links.forEach(function (l) {
                l.parent().removeClass('active');
            });

            onLink.parent().addClass('active');
        }

        $('#homeLink').on('click', function (e) {
            e.preventDefault();
            changeView($('#homeDiv'), $('#homeLink'));
        });

        $('#activityLink').on('click', function (e) {
            e.preventDefault();
            changeView($('#activityDiv'), $('#activityLink'));
        });

        $('#reportsLink').on('click', function (e) {
            e.preventDefault();
            changeView($('#reportsDiv'), $('#reportsLink'));
        });
    });
}