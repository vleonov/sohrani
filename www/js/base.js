$(function() {

	Forms.init();
	Card.init();
});

var Forms = {

	init: function() {
		$('label.atop').each(function() {
			var input = $('#' + this.getAttribute('for'));
			var label = $(this);
			if (input.val()) {
				label.hide();
			}
			input.focus(function() {
				label.hide();
			});
			input.blur(function() {
				if (input.val() == '') {
					label.show();
				}
			});
		});

	}
}
 var LinkForm = {
	 submit: function() {
		 var link = $('#link').val();
		 if (!link) {
//			 this.errorEmptyLink();
//			 return false;
		 }

		 $.ajax({
			 url: "/ajax/card/save/",
			 type: "POST",
			 data: {link: link},
			 success: function(data) {
				 Card.read(data.id, 'linkCardNew');
			 }
		 });

		 var card = this.initCard(link);

//		 setTimeout('Card.read(' + 1 + ')', 1000);

	 },

	 errorEmptyLink: function() {
		 
	 },

	 initCard: function(link) {
		var form = $('#linkForm form');

		var card = $('#linkCardBlank').clone();
		card.attr('id', 'linkCardNew');
		card.insertAfter($('#linkCardBlank'));

		card.find('.title').text('saving...');
		card.find('.link').html('<a href="'+link+'">' + link + '</a>');

		return card;
	 }
 }

var Card = {
	read: function(id, prevCard) {
		 $.ajax({
			 url: "/ajax/card/read/" + id,
			 type: "GET",
			 success: function(data){
				var existed = $('#linkCard'+id);
				if (existed.length) {
					existed.remove();
				}
				var card = $('#' + prevCard);

				card.replaceWith(data);
				card = $('#linkCard'+id);
//				card.offset({top: cardOffset.top, left: cardOffset.left});

				if (card.hasClass('unsaved')) {
					 setTimeout('Card.read(' + id + ', "linkCard' + id + '")', 1000);
				} else {
					Card.init();
				}
			 }
		 });
	},

	init: function() {
		$('.linkCard .del').click(function() {
			$(this).parents('.linkCard').remove();
			$.ajax({
				url: $(this).attr('data-href')
			});
			return false;
		});
	}
}
