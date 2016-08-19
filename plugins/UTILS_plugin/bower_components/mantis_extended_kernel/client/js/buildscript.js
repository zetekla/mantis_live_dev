"use strict";
/*var multipleScript = function(_){
    if (_ instanceof Array) {
        _.map(function(o){
            console.log(o);
            originalloadScript(o);
        });
    } else if ( _ instanceof Object)
        originalloadScript(_);
};*/

/*  loadScript({ path :"/directory/" , ref: "filename" })

    intakes multiple objects
    intakes following parameters
    @ params:
        .path
        .ref
        .async  (optional, override)
            true || false  onload js_file, default=false!!important
        .type   (optional, override)
            type: "media" || type: "all", default="text/css"

    @ credits: PK-zeTek (zenithtekla@github.com)
*/

var loadScript = function(){
    var length = arguments.length;
    var loaded = [];
    var ignored = [];
    for (var i = 0; i < length; i++) {
        var _ = arguments[i];
        if (typeof _.ref === 'string') { _.ref = [_.ref]; }
        var type = _.ref[0].slice(_.ref[0].lastIndexOf('.')+1);
    	switch (type) {
    		case 'js':
    		    _.ref.forEach(function(file){
    				var scripts = document.getElementsByTagName("script");
    				for(var i = 0; i < scripts.length; i++){
    					var src = scripts[i].getAttribute('src');
    					if(src)
    			        if(src.indexOf(file) > -1){
    			            ignored.push(file);
    			            return;
    			        }
    			    }
    				var link = document.createElement('script');
    				link.src = _.path + file;
    				link.type = 'text/javascript'; link.async = _.async || false;
    				document.getElementsByTagName('body')[0].appendChild(link);
    				loaded.push(file);
    			});
    			break;
    		case 'css':
    			_.ref.forEach(function(file){
    				for(var i = 0; i < document.styleSheets.length; i++){
    			        if(document.styleSheets[i].href.indexOf(file) > -1){
    			            ignored.push(file);
    			            return;
    			        }
    			    }
    			    var head  = document.getElementsByTagName('head')[0];
    			    var link  = document.createElement('link');
    			    link.rel  = 'stylesheet';
    			    link.type = _.type || 'text/css';
    			    link.href = _.path + file;
    			    head.appendChild(link);
    			    loaded.push(file);
    			});
    			break;

    		default:
    			// code
    	}
    }
    if (typeof ignored !== 'undefined' && ignored!= null && ignored.length > 0)
        console.log(loaded, " have been loaded;", ignored, " have been ignored.");
};

var UTILS_BOWER_URL = "plugins/UTILS_plugin/bower_components";
var MANTIS_EXTENDED_KERNEL = UTILS_BOWER_URL + "/mantis_extended_kernel";

// Plugin URLs
var PLUGIN_URL_SERIALS = "plugins/Serials/pages";