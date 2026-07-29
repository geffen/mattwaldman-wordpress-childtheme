(function () {
	var hamburger = document.querySelector( '.rsp-hamburger' );
	var overlay = document.querySelector( '.rsp-mobile-overlay' );
	var mobileMenu = document.getElementById( 'rsp-mobile-menu' );

	if ( ! hamburger || ! overlay || ! mobileMenu ) {
		return;
	}

	function openMobileMenu() {
		mobileMenu.hidden = false;
		overlay.hidden = false;
		hamburger.setAttribute( 'aria-expanded', 'true' );
	}

	function closeMobileMenu() {
		mobileMenu.hidden = true;
		overlay.hidden = true;
		hamburger.setAttribute( 'aria-expanded', 'false' );
	}

	hamburger.addEventListener( 'click', function () {
		var isOpen = hamburger.getAttribute( 'aria-expanded' ) === 'true';
		isOpen ? closeMobileMenu() : openMobileMenu();
	} );

	overlay.addEventListener( 'click', closeMobileMenu );

	mobileMenu.querySelectorAll( '.menu-item-has-children > a' ).forEach( function ( link ) {
		link.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			link.parentElement.classList.toggle( 'is-open' );
		} );
	} );
})();
