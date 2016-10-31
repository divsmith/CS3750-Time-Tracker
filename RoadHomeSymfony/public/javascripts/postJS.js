
$.ajax({
    type: "POST",
    url: 'localhost:3000/api/users',
    data: "{ 'email': 'test', 'firstName': 'test', 'lastName': 'test', 'organization': 'test', 'department': 'test' }",
    success: function(){alert("post success");},
    error: function(){alert("post fail");},
    dataType: dataType
});

