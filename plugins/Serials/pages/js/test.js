var jade = require('jade');
var jadephp = require('jade-php');

jadephp(jade);

var html = jade.render('string of jade');
console.log(html);