(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

// $(document).on('ready', function() {

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

// })






},{}]},{},[1]);
