$.typeahead({
    input: "#user_v1-query",
    minLength: 0,
    order: "asc",
    dynamic: true,
    hint: true,
    searchOnFocus: true,
    backdrop: {
        "background-color": "#fff"
    },
    template: '<span class="row">' +
        '<span class="avatar">' +
            '<img src="{{avatar}}" style="max-height: 48px; max-width: 48px;" >' +
        "</span>" +
        '<span class="username">{{username}}{{status}}</span>' +
        '<span class="id">({{id}})</span>' +
    "</span>",
    source: {
        user: {
            display: "username",
            href: "https://www.github.com/{{username}}",
            /*data: [{
                "id": 415849,
                "username": "an inserted user that is not inside the database",
                "avatar": "https://avatars3.githubusercontent.com/u/415849",
                "status":  " <small style='color: #777'>(contributor)</small>"
            }],*/
            url: [{
                type: "POST",
                url: "/plugin.php?page=Serials/json/ranska_data.php",

                data: {
                    q: "{{query}}"
                },
                callback: {
                    done: function (data) {
                        for (var i = 0; i < data.data.user.length; i++) {
                            if (data.data.user[i].username === 'running-coder') {
                                data.data.user[i].status = ' <small style="color: #ff1493">(owner)</small>';
                            } else {
                                data.data.user[i].status = ' <small style="color: #777">(contributor)</small>';
                            }
                        }
                        return data;
                    }
                }
            }, "data.user"]
        },
        project: {
            display: "project",
            href: function (item) {
                return '/' + item.project.replace(/\s+/g, '').toLowerCase() + '/documentation/'
            },
            url: [{
                type: "POST",
                url: "/plugin.php?page=Serials/json/ranska_data.php",
                data: {
                    q: "{{query}}"
                }
            }, "data.project"],
            template: '<span>' +
                '<span class="project-logo">' +
                    '<img src="{{image}}">' +
                '</span>' +
                '<span class="project-information">' +
                    '<span class="project">{{project}} <small>{{version}}</small></span>' +
                    '<ul>' +
                        '<li>{{demo}} Demos</li>' +
                        '<li>{{option}}+ Options</li>' +
                        '<li>{{callback}}+ Callbacks</li>' +
                    '</ul>' +
                '</span>' +
            '</span>'
        }
    },
    callback: {
        onClick: function (node, a, item, event) {

            // You can do a simple window.location of the item.href
            alert(JSON.stringify(item));

        },
        onSendRequest: function (node, query) {
            console.log('request is sent, perhaps add a loading animation?')
        },
        onReceiveRequest: function (node, query) {
            console.log('request is received, stop the loading animation?')
        }
    },
    debug: true
});