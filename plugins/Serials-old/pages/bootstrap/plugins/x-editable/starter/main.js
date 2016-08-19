$(document).ready(function() {
    //init user-access
    $('.user-access').editable({
      url: '/post',
      placement: 'right'
    });

    //init editables
    $('.myeditable').editable({
      url: '/post',
      placement: 'right'
    });

    //make username required
    $('#new_username').editable('option', 'validate', function(v) {
       if(!v) return 'Required field!';
    });

    $('#password').editable('option', 'validate', function(v) {
       if(!v) return 'Required field!';
    });

    $('#assembly-number').editable('option', 'validate', function(v) {
       if(!v) return 'Required field!';
    });

    $('#revision').editable('option', 'validate', function(v) {
       if(!v) return 'Required field!';
    });

    $('#sale-order').editable('option', 'validate', function(v) {
       if(!v) return 'Required field!';
    });

    //create new user
    $('#save-btn').click(function() {
       $('.myeditable').editable('submit', {
           url: '/newuser',
           ajaxOptions: {
               dataType: 'json' //assuming json response
           },
           success: function(data, config) {
               if(data && data.id) {  //record created, response like {"id": 2}
                   //set pk
                   $(this).editable('option', 'pk', data.id);
                   //remove unsaved class
                   $(this).removeClass('editable-unsaved');
                   //show messages
                   var msg = 'New session created! Now editables submit individually.';
                   $('#msg').addClass('alert-success').removeClass('alert-error').html(msg).show();
                   $('#save-btn').hide();
               } else if(data && data.errors){
                   //server-side validation error, response like {"errors": {"username": "username already exist"} }
                   config.error.call(this, data.errors);
               }
           },
           error: function(errors) {
               var msg = '';
               if(errors && errors.responseText) { //ajax error, errors = xhr object
                   msg = errors.responseText;
               } else { //validation error (client-side or server-side)
                   $.each(errors, function(k, v) { msg += k+": "+v+"<br>"; });
               }
               $('#msg').removeClass('alert-success').addClass('alert-error').html(msg).show();
           }
       });
    });

    //reset
    $('#reset-btn').click(function() {
       $('.myeditable').editable('setValue', null)
                       .editable('option', 'pk', null)
                       .removeClass('editable-unsaved');

       $('#save-btn').show();
       $('#msg').hide();
    });


    //mockjax
    $.mockjax({
        url: '/post',
        responseTime: 500,
        responseText: ''
    });

    $.mockjax({
        url: '/groups',
        responseText: {
            0: 'Guest',
            1: 'Service',
            2: 'Customer',
            3: 'Operator',
            4: 'Support',
            5: 'Admin'
        }
    });

    $.mockjax({
        url: '/newuser',
        responseTime: 300,
        responseText: '{ "id": 1 }'
    });


    $(".canedit").editable({
        type: "text", // send: 'always' above?
        title: 'enter SO', //edit description
        name:  'assembly-number', // emptytext: 'Click here to add a description!',
        placeholder: 'Description goes here.',
        mode: "inline",
        escape: false,
        validate: function(value) {
            if (value.match(/cat/i)) {
                return "What's about the dog? Please, just no talking about cats!";
            }

            if($.trim(value) == '')
                    return 'Value is required.';

            var regexp = new RegExp("[0-9]"); // http://stackoverflow.com/questions/22524791/taking-decimal-inputs-for-x-editable-field

            if (!regexp.test(value)) {
                return 'This field is not valid';
            }

        },
        ajaxOptions: { url: 'post.php'},
        params: function(params) {
            var d = new $.Deferred;
            console.log("id of element changed: " + params.name);
            console.log("new value: " + params.value);
            setTimeout(function() {
                // to simulate some asynchronous processing
                d.resolve();
            }, 500); // http://tutorials.jenkov.com/jquery/deferred-objects.html#codeBox
            return d.promise();
        }
    });
});