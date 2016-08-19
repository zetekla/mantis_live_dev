(function () {
    "use strict";

    var old;
    var oldh = {};

    var fn1 = function(){
        return new Promise(function(suc, err){
            extTypeahead({
                slt: "#field1",
                url: "/development/mantislive/plugin.php?page=Serials/model/json/customer.php",
                callback: function(item){
                    var hash = {};
                    /*global localStorage*/
                    if (localStorage.getItem("xhr")) {
                        old = localStorage.getItem("xhr");
                        oldh = JSON.parse(localStorage.getItem("xhr"));
                        hash = oldh;
                    }

                    hash.customer_name = item.nimi;
                    hash.customer_id = item.id;
                    hash.group = item.group;

                    localStorage.setItem("xhr", JSON.stringify(hash));
                    console.log(" # fn1 $ xhr ", localStorage.getItem("xhr"));
                    console.log(" # fn1 $ old ", old);
                    setTimeout(function(){
                        suc(hash);
                    },10);
                }
            });
        });
    };

    var fn2 = function (o){
        return new Promise(function(suc, err){
            extTypeahead({
                slt: "#field2",
                url: {
                    type: "POST",
                    url : "/development/mantislive/plugin.php?page=Serials/model/json/assembly.php",
                    data: { 'id' : o.customer_id }
                },
                callback: function(item){
                    var hash = JSON.parse(localStorage.getItem("xhr"));
                    o.assembly = item.nimi;
                    hash.assembly = item.nimi;
                    // console.log(dnm_data);
                    localStorage.setItem("xhr", JSON.stringify(hash));
                    console.log(" # fn2 $ xhr", localStorage.getItem("xhr"));
                    $("#field3").val('');
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
                    url: "/development/mantislive/plugin.php?page=Serials/model/json/revision.php",
                    data : {
                        nimi: o.assembly,
                        id: o.customer_id
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
                    console.log(" # fn3 $ print O ", JSON.stringify(o));

                    /* global ajaxPost */
                    ajaxPost({
                        url: "/development/mantislive/plugin.php?page=Serials/model/json/format.php",
                        data: { "id" : o.assembly_id },
                        callback: addformat
                    });

                    setTimeout(function(){
                        suc(o);
                    },10);
                    // $("#field4").focus();
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
    
    // initialization, comment out to save page loading time.
    // exec();
    
    // set focus to sales_order field

	//msg.style.visibility="visible";
    //$("#msg").hide();
    $("#field3").on('keyup', function(e){
        e.preventDefault();
        var v = $(this).val();
        console.log(" ?oldh " + oldh.revision + " ?current " + v);

        if ( input_key_cond(e.which) ){
            var o ={
                cond: oldh.revision !== v && v && $("#field2").val() === oldh.assembly,
                id: $(this).attr("id"),
                a: [4,5,6]
            };
            keyupFn(o);
        }
    });

    $("#field2").on('keyup', function(e){
        e.preventDefault();
        var v = $(this).val();
        console.log(" ?oldh " + oldh.assembly + " ?current " + v);
        // define 'o' prior to call rinse(o.a) here;
        /*global input_key_cond*/
        if ( input_key_cond(e.which) ){
            // $("#typeahead-field3 > div > .typeahead-result").remove();
            var o ={
                // cond: oldh.assembly !== v && v && $("#field1").val()===oldh.customer,
                cond: oldh.assembly !== v && v,
                id: $(this).attr("id"),
                a: [3,4,5,6]
            };
            keyupFn(o);
        }
    });

    $("#field1").on('keyup', function(e){
        e.preventDefault();
        // e.stopPropagation();
        var v = $(this).val();

        // if ( (47 < e.which && e.which < 91) || ( 95 < e.which && e.which < 106) ){
        if ( input_key_cond(e.which) ){
            var o ={
                // cond: oldh.customer !== v && oldh.customer_name && v && $("#field2").val(),
                cond: oldh.customer !== v && v,
                id: $(this).attr("id"),
                a: [2,3,4,5,6]
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
            console.log("=> rendering format ", obj);
            $("#field5").val(obj.sample);
            $("#field6").val(obj.nimi);
            var hash = JSON.parse(localStorage.getItem("xhr"));
            hash.format = obj.nimi;
            hash.format_id = obj.id;
            hash.format_example = obj.sample;
            localStorage.setItem("xhr", JSON.stringify(hash));
            console.log(localStorage.getItem("xhr"));
        });
    };
})();

