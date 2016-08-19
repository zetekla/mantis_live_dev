// plugin.php?page=Serials/json/customer.php
/*
(function(){
    $.typeahead({
        input: "#field1",
        minLength: 0,
        maxItem: 10,
        order: "asc",
        hint: true,
        cache: true,
        searchOnFocus: true,
        template: '<span class="row">' +
                '<span class="nimi">{{nimi}}</span>' +
        '</span>',
        correlativeTemplate: true,
        source: {
            joukko1: {
                url: "/mantislive/plugin.php?page=Serials/json/customer.php"
            }
        },
        callback: {
            onClickAfter: function (node, a, item, event) {
                $('#result-container').text('');
                console.log(JSON.stringify(item));
            },
            onResult: function (node, query, obj, objCount) {
                var text = "";
                if (query !== "") {
                    text = objCount + ' elements matching "' + query + '"';
                }
                $('#result-container').text(text);
            }
        },
        debug: true
    });
})();*/

"use strict";
var extTypeahead = function( _ ){
    // console.log(_.url);
    $.typeahead({
        input: _.slt,
        minLength: 0,
        maxItem: 10,
        order: "asc",
        hint: true,
        // cache: true,
        searchOnFocus: true,
        template: '<span class="row">' +
                '<span class="nimi">{{nimi}}</span>' +
        '</span>',
        correlativeTemplate: true,
        source: {
            joukko1: {
                url: _.url
            }
        },
        callback: {
            onReady: function(){
                if ($(_.slt).siblings().length>0)
                    $(_.slt).siblings(':last').remove();
                $(_.slt).focus();
            },
            onClickAfter: function (node, a, item, event) {
                console.log("§ klik: " + JSON.stringify(item));
                setTimeout(function(){
                    $('#result-container').empty();
                },3000);
                if (_.hasOwnProperty('callback')) _.callback(item);
                $("#typeahead-" + _.slt.substr(1) + " > div > .typeahead-result").remove();
                $("#typeahead-" + _.slt.substr(1)).find('.typeahead-hint').remove();
            },
            onResult: function (node, query, obj, objCount) {
                var text = "";
                if (query !== "") {
                    text = objCount + ' elements matching <b>"' + query + '"</b>';
                }
                $('#result-container').html(text);
            }
        },
        debug: true
    });
};

var ajaxPost = function( _ ){
    var jqDeferred = $.ajax({
        type:"POST",
        url: _.url,
        data: _.d,
        dataType: 'json',
    });

    jqDeferred.then( function(data) {
        if (_.hasOwnProperty('callback')) _.callback(data);
    },
    function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR, textStatus, errorThrown);
    });
};