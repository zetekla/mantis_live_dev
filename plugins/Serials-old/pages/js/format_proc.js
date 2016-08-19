(function () {
    "use strict";
    var dnm_data = {
        time: $('.login-info-middle :first-child').text(),
        list_count: 0
    };

    var xhr = {};
    var old;
    var oldh = {};

    var fn1 = function(){
        return new Promise(function(suc, err){
            extTypeahead({
                slt: "#field1",
                url: "/development/mantislive/plugin.php?page=Serials/json/customer.php",
                callback: function(item){
                    var hash = {};
                    if (localStorage.getItem("xhr")) {
                        old = localStorage.getItem("xhr");
                        oldh = JSON.parse(localStorage.getItem("xhr"));
                        hash = oldh;
                    }
                    hash.id = item.id;
					hash.name = item.nimi;
					hash.group = item.group;
					
                    localStorage.setItem("xhr", JSON.stringify(hash));
                    console.log(" # post-assign@fn1 $ print xhr ");
                    console.log(localStorage.getItem("xhr"));
                    console.log(" # post-assign@fn1 $ print old ");
                    console.log(old);
                    setTimeout(function(){
                        suc(hash);
                    },10);
                }
            });
        });
    };

    /*dnm_data.customer_name = xhr.nimi;
    dnm_data.customer_id = xhr.id;*/
    var fn2 = function (o){
        return new Promise(function(suc, err){
            extTypeahead({
                slt: "#field2",
                url: {
                    type: "POST",
                    url : "/development/mantislive/plugin.php?page=Serials/json/assembly.php",
                    data: { 'customer_id' : o.id }
                },
                callback: function(item){
                    var hash = JSON.parse(localStorage.getItem("xhr"));
                    o.number = item.nimi;
                    hash.number = item.nimi;
                    // console.log(dnm_data);
                    localStorage.setItem("xhr", JSON.stringify(hash));
                    console.log(" # post-async@fn2 $ ");
                    console.log(localStorage.getItem("xhr"));
                    // $("#field3").val('');
                    setTimeout(function(){
                        suc(o);
                    },10);
                }
            });
        });
    };

    var fn3 = function (o){
        return new Promise(function(suc,err){
            /* global extTypeahead */
            extTypeahead({
                slt: "#field3",
                url: {
                    type: "POST",
                    url: "/development/mantislive/plugin.php?page=Serials/json/revision.php",
                    data : {
                        number: o.number,
                        id: o.id
                    }
                },
                callback: function(item){
                    var hash = JSON.parse(localStorage.getItem("xhr"));
                    hash.revision = item.nimi;
                    hash.assembly_id = item.id;

                    o = {
                        revision: item.nimi,
                        assembly_id: item.id
                    };

                    localStorage.setItem("xhr", JSON.stringify(hash));
                    console.log(" # post-async@fn3 $ print O and xhr ");
                    console.log(JSON.stringify(o));
                    console.log(localStorage.getItem("xhr"));

                    /* global ajaxPost */
                    ajaxPost({
                        url: "/development/mantislive/plugin.php?page=Serials/json/format.php",
                        d: { "id" : o.assembly_id },
                        callback: addformat
                    });
					console.log(" # ajax post " + o.assembly_id);
                    setTimeout(function(){
                        suc(o);
                    },10);
                    // $("#field4").focus();
                    // console.log(dnm_data);
                }
            });
        });
    };
    // A().then(B).then(C).then(D);
    var exec = function(){
        fn1()
        .then(function(v){
                return fn2(v);
            })
        .then(function(v){
                return fn3(v);
                // $("#field3").focus();
            })
        ;
    };
    exec();

    $("#field3").on('keyup', function(e){
        e.preventDefault();
        var v = $(this).val();
        console.log(" ?oldh " + oldh.revision + " ?current " + v);

        if ( t_cond(e.which) ){
            var o ={
                cond: oldh.revision !== v && v && $("#field2").val() === oldh.assembly,
                id: $(this).attr("id"),
                a: [4,5]
            };
            keyupFn(o);
        }
    });

    $("#field2").on('keyup', function(e){
        e.preventDefault();
        var v = $(this).val();
        console.log(" ?oldh " + oldh.assembly + " ?current " + v);
        // define 'o' prior to call rinse(o.a) here;
        
        if ( t_cond(e.which) ){
            // $("#typeahead-field3 > div > .typeahead-result").remove();
            var o ={
                // cond: oldh.assembly !== v && v && $("#field1").val()===oldh.customer,
                cond: oldh.assembly !== v && v,
                id: $(this).attr("id"),
                a: [3,4,5]
            };
            keyupFn(o);
        }
    });

    $("#field1").on('keyup', function(e){
        e.preventDefault();
        // e.stopPropagation();
        var v = $(this).val();

        // if ( (47 < e.which && e.which < 91) || ( 95 < e.which && e.which < 106) ){
        if ( t_cond(e.which) ){
            var o ={
                // cond: oldh.customer !== v && oldh.customer_name && v && $("#field2").val(),
                cond: oldh.customer !== v && v,
                id: $(this).attr("id"),
                a: [2,3,4,5]
            };
            keyupFn(o);
        }
    });

    var keyupFn = (function(){
        return function(_){
        if (_.cond) {
            var s = "#typeahead-" + _.id + " > div";
            // console.log($(s).find('.typeahead-result').length);
            if (!$(s).find('.typeahead-result').length)
            {
                // $(s).find('.typeahead-result').not(":eq(n)").remove();
                rinse(_.a);
                console.log(" # INIT " + _.id);
                switch (_.id) {
                    case 'field1':
                        $("#typeahead-field2 > div > .typeahead-result").remove();
                        oldh = {};
                        exec();
                        break;
                    case 'field2':
                        fn2(oldh)
                        .then(function(v){
                            return fn3(v);
                        });
                        break;
                    case 'field3':
                        fn3(oldh);
                        break;

                    default:
                        // code
                }
            }
        }
    };
    })();
    
    var rinse = (function(){
        return function(a){
            for (var i of a) {
                $("#field"+i).val('');
                $("#field"+i).siblings().remove();
                $("#typeahead-field"+ i +" > div > .typeahead-result").remove();
            }
        };
    })();
    
    var addformat = function(data){
        $.map(data, function(obj) {
           /*  dnm_data.format = obj.format;
            dnm_data.format_id = obj.format_id;
            dnm_data.format_example = obj.format_example; */
            $("#field4").val(obj.nimi);
            $("#field5").val(obj.sample);
            // console.log(" # check if old is accessible inside ajax of fn3 " + old);
        });
    };

    var t_cond = (function(){
        return function(x){
        return range(x,47,91) || range(x,95,106);
        };
    })();

    var range = (function(){
        return function(x,min,max){
        return (min < x && x < max);
        };
    })();

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();
})();