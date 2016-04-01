jQuery(document).ready(function($) {

	/**
	* Drag & Drop Element Items
	*/
	var animation = $( '#loading-animation' );

	animation.hide();

	var dragList = $( 'ul#available-elements li' );
	var dropList = $( 'ul#document_drop_zone' );

	//alert('loaded');

	dragList.draggable({
		appendTo: "body",
		helper: "clone",
		revert: "invalid",
		scope: 'document_elements_scope'
	});



	dropList.droppable({
		activeClass: "zone-active",
		hoverClass: "zone-hover",
		accept: ":not(.ui-sortable-helper)",
		scope: 'document_elements_scope',
		
		drop: function( event, ui ) {			
			
			$( this ).find( ".placeholder" ).remove();
			$( "<li></li>" ).find( "span.element_list_heading" ).remove();
			$( "<li></li>" ).find("li:not(.ui-draggable)").remove();
			$( "<li></li>" ).html(ui.draggable.clone()).appendTo( this );
			
			// Remove empty <p> tags
			remove_empty_p();

			// Add Remove Option
			add_remove_option();
			
			
		}
	}).sortable({
		items: "li:not(.placeholder)",
		placeholder: "zone-highlight",
		sort: function() {
			// gets added unintentionally by droppable interacting with sortable
			// using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
			$( this ).removeClass( "zone-active" );

			// Remove empty <p> tags
			remove_empty_p();
		},
		/*
		update: function( event, ui ) {
			animation.show();

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'save_sort',
					order: dropList.sortable( 'toArray' ),
					security: WP_DOC_BUILDER.security
				},
				success: function( response ) {
					$( 'div#message' ).remove();
					animation.hide();
					if( true === response.success ) {
						pageTitle.after( '<div id="message" class="updated"><p>' + WP_DOC_BUILDER.success + '</p></div>' );
					} else {
						pageTitle.after( '<div id="message" class="error"><p>' + WP_DOC_BUILDER.failure + '</p></div>' );
					}
				},
				error: function( error ) {
					$( 'div#message' ).remove();
					animation.hide();
					pageTitle.after( '<div id="message" class="error"><p>' + WP_DOC_BUILDER.failure + '</p></div>' );
				}
			});
		}
		*/
	});	


	function add_remove_option() {
		$( '.edit_options' ).remove();
		$( "#document_drop_zone .document_element" ).append( "<div class='edit_options'><span class='edit_item'>edit</span> | <span class='remove_item'>remove</span></div>" );
	}
	add_remove_option();

	function remove_empty_p() {
		// Remove empty <p> tags
		$( 'p' ).each(function() {
		    var $this = $(this);
		    if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
		        $this.remove();
		});
	}
	remove_empty_p();

	$('#save_document').click(function(){
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'save_document',
				order: dropList.sortable( 'toArray' ),
				security: WP_DOC_BUILDER.security
			},
			success: function( response ) {
				$( 'div#message' ).remove();
				animation.hide();
				if( true === response.success ) {
					pageTitle.after( '<div id="message" class="updated"><p>' + WP_DOC_BUILDER.success + '</p></div>' );
				} else {
					pageTitle.after( '<div id="message" class="error"><p>' + WP_DOC_BUILDER.failure + '</p></div>' );
				}
			},
			error: function( error ) {
				$( 'div#message' ).remove();
				animation.hide();
				pageTitle.after( '<div id="message" class="error"><p>' + WP_DOC_BUILDER.failure + '</p></div>' );
			}
		});
	});

});

