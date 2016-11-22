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
        // Init - Setup Views
        var views = [];
        views.push($('#timeDiv'));
        views.push($('#groupDiv'));

        var links = [];
        links.push($('#timeLink'));
        links.push($('#groupLink'));

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

        // Time Entry - Dropdowns
        var locationsDepartments = {
            "Downtown": ["Front Desk", "Warehouse", "Dinner", "Kid's Activity", "Women's Activity", "Special Event"],
            "Midvale": ["Warehouse", "Kid's Activity", "Dinner", "Book Club", "Playroom", "Preschool Playgroup", "Special Event"],
            "Palmer Court": ["Study Night", "Book Club", "Special Event"]
        };

        function updateDepartment() {
            // Clear existing options
            $('#department').find('option').remove();

            // Get current location.
            var locationValue = $('#location').val();

            // Populate locations
            if (locationsDepartments[locationValue]) {
                for (var i = 0; i < locationsDepartments[locationValue].length; i++) {
                    $('#department').append('<option>' + locationsDepartments[locationValue][i] + '</option>');
                }
            }
        }

        // On change of the department, update department.
        $('body').on('change', $('#department'), function (e) {
            e.preventDefault();
            updateDepartment();
        });

        // If U of U student, auto fill out school name.
        $('body').on('change', $('#uOfUStudent'), function (e) {
            e.preventDefault();

            if ($('#uOfUStudent').is(':checked')) {
                $('#education').val('University of Utah');
            } else {
                $('#education').val('');
            }
        });

        function updateGroupCount() {
            $('#groupCount').find('option').remove();

            for (var i = 1; i <= 50; i++) {
                $('#groupCount').append('<option>' + i + '</option>');
            }
        }

        function showError(message, msgElement) {

        }

        function calculateDuration() {
            try {
                var start = new Date($('#timeStart').val());
                var end = new Date($('#timeEnd').val());

                var hours = 0;
                var minutes = 0;

                if (start != 'Invalid Date' && end != 'Invalid Date') {
                    var calculated = end - start;
                    hours = Math.floor(calculated / 1000 / 60 / 60);
                    minutes = Math.floor((calculated - (hours * 60 * 60 * 1000)) / 1000 / 60);
                }

                $('#calculatedHours').text(hours);
                $('#calculatedMinutes').text(minutes);
            } catch (ex) {
                $('#calculatedHours').text('X');
                $('#calculatedMinutes').text('X');
            }
        }

        // Calculated Times
        $('body').on('change', $('#timeStart #timeEnd'), function (e) {
            calculateDuration();
        });

        // Start Time
        $('#submitButton').click(function () {
            //var email = $('#emailAddress').val();
            //var firstname = $('#firstname').val();
            //var lastname = $('#lastname').val();
            //var location = $('#location').val();
            //var organization = $('#organization').val();
            //var department = $('#department').val();
            //var groupcount = $('#groupCount').val();

            //var jsonData = {
            //    "email": email,
            //    "firstname": firstname,
            //    "lastname": lastname,
            //    "location": location,
            //    "department": department,
            //    "groupnumber": groupcount
            //};

            //console.log(jsonData);

            //$.ajax
            //({
            //    type: "POST",
            //    //the url where you want to sent the userName and password to
            //    url: 'http://localhost:8000/volunteers',
            //    crossDomain: true,
            //    dataType: 'json',
            //    contentType: 'application/json; charset=utf-8',
            //    //json object to sent to the authentication url
            //    data: JSON.stringify(jsonData),
            //    success: function () {

            //        alert("Thanks!");
            //    },
            //    error: function () {
            //        alert("Failure!");
            //    }
            //});
        });

        $('#timeLink').on('click', function (e) {
            e.preventDefault();
            changeView($('#timeDiv'), $('#timeLink'));
        });

        $('#groupLink').on('click', function (e) {
            e.preventDefault();
            changeView($('#groupDiv'), $('#groupLink'));
        });

        // Init load these functions
        // Go to default view
        changeView($('#timeDiv'), $('#timeLink'));
        updateDepartment();
        updateGroupCount();
        calculateDuration();
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