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
                url: "/plugin.php?page=Serials/json/customer.php"
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

var input_key_cond = (function(){
        return function(x){
        return range_cond(x,47,91) || range_cond(x,95,106);
        };
    })();

var range_cond = (function(){
    return function(x,min,max){
    return (min < x && x < max);
    };
})();

var timer_delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

var ajaxPost = function( _ ){
    var jqDeferred = $.ajax({
        type:"POST",
        url: _.url,
        data: _.data || "",
        dataType: 'json'
    });

    jqDeferred.then( function(data) {
        if (_.hasOwnProperty('callback')) _.callback(data);
    },
    function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR, textStatus, errorThrown);
    });
};

/*// url, data, suggest = "<div style='padding:6px'>{{value}}</div>", callback
// Bloodhound AJAX joins the course!
var bloodhoundAjax = function( _ ){
    if(!_.hasOwnProperty('suggest')) _['suggest'] = "<div style='padding:6px'>{{value}}</div>";
    var settings = {
        type:"POST"
    };
    if (_.hasOwnProperty('data')) settings['data'] = _.data;
    settings['dataType'] = (_.hasOwnProperty('dataType')) ? _.dataType : 'json';

    var jqDeferred = $.ajax( $.extend( settings, {url: _.url} ) );
    // { url: _.url,  settings });

    jqDeferred.then( function(data) {
    // constructs the suggestion engine
    var engine = new Bloodhound({
      datumTokenizer: function (datum) {
        return Bloodhound.tokenizers.whitespace(datum.value);
      },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      // `data` is an array of country names defined in "The Basics"
      local: $.map(data, function(oj) {
          oj.i = p_idx;
          return { value : oj.i(0), eg: oj.i(1) };
      }),
      limit: 10
    });

    // kicks off the loading/processing of `local` and `prefetch`
    engine.initialize();

    // Instantiate the Typeahead UI
    $(_.slt).typeahead(null, {
        name: 'data',
        displayKey: 'value',
        hint: true,
        highlight: true,
        minLength: 1,
        source: engine.ttAdapter(),
        templates: {
            empty: [
              '<div class="empty-message">',
                'Result not found',
              '</div>'
            ].join('\n'),
            suggestion: Handlebars.compile(_.suggest),
            footer: function (data) {
              // return Handlebars.compile("<div>Searched for <strong> {{data.query}} </strong></div>");
              return '<div>Searched for <strong>' + data.query + '</strong></div>';
            }
        }
    });
    if (_.hasOwnProperty('callback')) _.callback();
    },
    function(jqXHR, textStatus, errorThrown){
    console.log(jqXHR, textStatus, errorThrown);
    });
};*/