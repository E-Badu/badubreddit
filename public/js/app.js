'use strict';

// $(document).on('ready', function() {

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	$.ajax('/posts', {
    type: 'GET',
    success: function() {

    }
})