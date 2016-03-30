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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">
    <style>
        body {
            font-size: 10px;
        }

        .container {
            width: 100%;
        }

        #all-subbreddits {
            height: 600px;
            overflow: scroll;
        }

        #posts {
            height: 300px;
            overflow: scroll;
        }
    </style>

</head>
<body>
    <div id="content"></div>
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.3/backbone-min.js"></script>
    <!-- <script src="{{ asset('js/app.js') }}"></script>-->
    <script> // All here should be in app.js, but errors keep happening.
    
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var PostModel = Backbone.Model.extend({
        urlRoot: '/api/posts/',
        idAttribute: 'id',

        parse: function(response) {
            if (response.subbreddit) {
                response.subbreddit = new SubbredditModel(response.subbreddit);
            }
            return response;
        }
    });

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
                            <div class="twelve columns" id="posts"></div>\
                        </div>\
                        <div class="row">\
                            <div class="twelve columns"></div>\
                        </div>\
                    </div>\
                    <div class="three columns" id="all-subbreddits"></div>\
                </div>\
            </div>\
        ',

        insertSubbreddits: function() {
            var subbreddits = new SubbredditsCollection();
            subbreddits.fetch();
            var subbredditsListView = new SubbredditsListView({ 
                collection: subbreddits
            });
            this.$el.find('#all-subbreddits').html(subbredditsListView.render().el);
        },

        insertPosts: function() {
            var posts = new PostsCollection();
            posts.fetch();
            var postsListView = new PostsListView({ 
                collection: posts
            });
            this.$el.find('#posts').html(postsListView.render().el);
        },

        render: function() {
            this.insertSubbreddits();
            this.insertPosts();

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
    });

    var PostsListView = Backbone.View.extend({
        el: '<ul></ul>',
        template: _.template('\
            <% posts.each(function(post) { %>\
                <li>\
                    <a href="#"><%= post.get("title") %></a>\
                    <% if (post.get("subbreddit")) { %>\
                        <small><%= post.get("subbreddit").get("name") %></small>\
                    <% } %>\
                </li>\
            <% }) %>\
        '),

        initialize: function() {
            this.listenTo(this.collection, 'update', this.render);
        },

        render: function() {
            this.$el.html(this.template({ posts: this.collection }));
            return this;
        }
    });


    var homeView = new HomeView();
    $('#content').html(homeView.render().el);

    </script>

</body>
</html>
