<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- the following may give a token mismatch error, fix was made by
    moving this, without script tags to app.js file -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
   
    <title>BaduBreddit</title>

    <!-- Fonts -->


    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

</head>
<body>
    <div id="content"></div>
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.3/backbone-min.js"></script>
    
    <script> // All here should be in app.js, but errors keep happening.
    
    var PostModel = Backbone.Model.extend({
        urlRoot: '/api/posts/',
        idAttribute: 'id',
    });

    var post = new PostModel();

    var SubbredditModel = Backbone.Model.extend({
        urlRoot: '/api/subbreddits/',
        idAttribute: 'id'
    });

    var CommentModel = Backbone.Model.extend({
        urlRoot: '/api/comments/',
        idAttribute: 'id'
    });

    var PostsCollection = Backbone.Collection.extend({
        url: '/api/posts/',
        model: PostModel
    });

    var SubbredditsCollection = Backbone.Collection.extend({
        url: '/api/subbreddits/',
        model: SubbredditModel
    });

    var CommentsCollection = Backbone.Collection.extend({
        url: '/api/comments/',
        model: CommentModel
    });

    var HomeView = Backbone.View.extend({
        el:'\
            <div class="container">\
                <div class="row">\
                    <div class="three columns"></div>\
                    <div class="six columns">\
                        <div class="row">\
                            <div class="twelve columns"></div>\
                        </div>\
                        <div class="row">\
                            <div class="twelve columns"></div>\
                        </div>\
                    </div>\
                    <div class="three columns" id="all-subbreddits"></div>\
                </div>\
            </div>\
        ',

        render: function() {
            var subbreddits = new SubbredditsCollection();
            //this is something you would test in the console  subbreddits.fetch();
            var subbredditsListView = new SubbredditsListView({ 
                collection: subbreddits
            });
            this.$el.find('#all-subbreddits').html(subbredditsListView.render().el);

            return this;
        }
    });

    var SubbredditsListView = Backbone.View.extend({
        el: '<ul></ul>',

        template: _.template('\
            <% subbreddits.each(function(subbreddit) { %>\
                <li><a href="#"><%= subbreddit.get("name") %></a></li>\
            <% }) %>\
        '),

        initialize: function() {
            this.listenTo(this.collection, 'update', this.render);
        },

        render: function() {
            this.$el.html(this.template({ subbreddits: this.collection }));
            return this;
        }
    })


    var homeView = new HomeView();
    $('#content').html(homeView.render().el);

    

    </script>

</body>
</html>
