(function () {
	document.querySelectorAll( '.rsp-article__video[data-video-id]' ).forEach( function ( wrap ) {
		var playBtn = wrap.querySelector( '.rsp-article__video-play' );

		if ( ! playBtn ) {
			return;
		}

		playBtn.addEventListener( 'click', function () {
			var videoId = wrap.getAttribute( 'data-video-id' );
			var iframe = document.createElement( 'iframe' );

			iframe.src = 'https://www.youtube.com/embed/' + encodeURIComponent( videoId ) + '?autoplay=1';
			iframe.title = 'YouTube video player';
			iframe.frameBorder = '0';
			iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
			iframe.allowFullscreen = true;

			wrap.innerHTML = '';
			wrap.appendChild( iframe );
		} );
	} );
})();

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
		document.body.classList.add( 'rsp-mobile-menu-open' );
	}

	function closeMobileMenu() {
		mobileMenu.hidden = true;
		overlay.hidden = true;
		hamburger.setAttribute( 'aria-expanded', 'false' );
		document.body.classList.remove( 'rsp-mobile-menu-open' );
	}

	hamburger.addEventListener( 'click', function () {
		var isOpen = hamburger.getAttribute( 'aria-expanded' ) === 'true';
		isOpen ? closeMobileMenu() : openMobileMenu();
	} );

	overlay.addEventListener( 'click', closeMobileMenu );

	// If the window is resized past the mobile breakpoint while the menu
	// is open, force it closed (the CSS hides it outright at that width,
	// and the hamburger that would otherwise close it is gone too).
	// Must match the 768px breakpoint in style.css.
	window.matchMedia( '(min-width: 768px)' ).addEventListener( 'change', function ( e ) {
		if ( e.matches ) {
			closeMobileMenu();
		}
	} );

	// Parent items act as accordion toggles on mobile, so a tap can't also
	// follow their link. Some parents now point at real pages (Player
	// Evaluation, Resources) rather than '#', and those pages would other-
	// wise be unreachable on mobile — so give each one an explicit
	// "Overview" link as the first entry in its own submenu.
	mobileMenu.querySelectorAll( '.menu-item-has-children' ).forEach( function ( item ) {
		var link = item.querySelector( ':scope > a' );
		var submenu = item.querySelector( ':scope > .sub-menu' );

		if ( ! link || ! submenu ) {
			return;
		}

		var href = link.getAttribute( 'href' );

		if ( href && href !== '#' ) {
			var li = document.createElement( 'li' );
			var overview = document.createElement( 'a' );

			overview.href = href;
			overview.textContent = 'Overview';
			overview.className = 'rsp-mobile-menu__overview';

			li.appendChild( overview );
			submenu.insertBefore( li, submenu.firstChild );
		}

		link.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			item.classList.toggle( 'is-open' );
		} );
	} );
})();
