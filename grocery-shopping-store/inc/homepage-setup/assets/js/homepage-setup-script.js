/**
 * Getting Started page: setup steps, plugin installer, demo import,
 * coupon copy buttons and the welcome notice dismiss.
 */
( function ( $ ) {
	'use strict';

	var params = window.grocery_shopping_store_setup || {};
	var i18n = params.i18n || {};

	var Setup = {

		init: function () {
			this.$root = $( '#omg-setup' );
			if ( ! this.$root.length ) {
				return;
			}
			this.total = parseInt( this.$root.data( 'total' ), 10 ) || this.$root.find( '.omg-step' ).length;

			this.$root.on( 'click', '.omg-step__head', this.onToggle.bind( this ) );
			this.$root.on( 'click', '[data-omg-complete]', this.onComplete.bind( this ) );
			this.$root.on( 'change', '.omg-plugin__check', this.updatePluginCount.bind( this ) );
			this.$root.on( 'click', '.omg-install-plugins', this.installPlugins.bind( this ) );
			this.$root.on( 'click', '.omg-import-demo', this.importDemo.bind( this ) );

			this.updatePluginCount();
		},

		// Accordion: only one step open at a time.
		openStep: function ( $step ) {
			this.$root.find( '.omg-step.is-open' ).not( $step ).removeClass( 'is-open' )
				.find( '.omg-step__head' ).attr( 'aria-expanded', 'false' );
			$step.addClass( 'is-open' ).find( '.omg-step__head' ).attr( 'aria-expanded', 'true' );
		},

		closeStep: function ( $step ) {
			$step.removeClass( 'is-open' ).find( '.omg-step__head' ).attr( 'aria-expanded', 'false' );
		},

		onToggle: function ( e ) {
			var $step = $( e.currentTarget ).closest( '.omg-step' );
			if ( $step.hasClass( 'is-open' ) ) {
				this.closeStep( $step );
			} else {
				this.openStep( $step );
			}
		},

		onComplete: function ( e ) {
			var $el = $( e.currentTarget );
			// Links (e.g. "View your site") keep opening normally.
			if ( ! $el.is( 'a' ) ) {
				e.preventDefault();
			}
			this.completeStep( $el.data( 'omg-complete' ), true );
		},

		/**
		 * Mark a step done, then move on to the next unfinished one.
		 *
		 * @param {string}  id   Step id.
		 * @param {boolean} save Store it on the server (imports save themselves).
		 */
		completeStep: function ( id, save ) {
			var self = this;
			var $step = this.$root.find( '.omg-step[data-step="' + id + '"]' );

			if ( save ) {
				$.post( params.ajaxurl, { action: 'grocery_shopping_store_setup_step', wpnonce: params.wpnonce, step: id } );
			}

			$step.addClass( 'is-done' );
			this.updateProgress();
			$( document ).trigger( 'omg:step-complete', [ id ] );

			window.setTimeout( function () {
				var $next = $step.nextAll( '.omg-step:not(.is-done)' ).first();
				if ( ! $next.length ) {
					$next = self.$root.find( '.omg-step:not(.is-done)' ).first();
				}
				if ( $next.length ) {
					self.openStep( $next );
				} else {
					self.closeStep( $step );
				}
			}, 450 );
		},

		updateProgress: function () {
			var done = this.$root.find( '.omg-step.is-done' ).length;
			var pct = Math.max( 3, Math.round( done / this.total * 100 ) );

			this.$root.find( '.omg-setup__done' ).text( done );
			this.$root.find( '.omg-progress' ).attr( 'aria-valuenow', done );
			this.$root.find( '.omg-progress__bar' ).css( 'width', pct + '%' );
			this.$root.toggleClass( 'is-complete', done >= this.total );
		},

		showError: function ( $step, message ) {
			$step.find( '.omg-step__error' ).text( message ).prop( 'hidden', ! message );
		},

		setBusy: function ( $btn, busy, label ) {
			$btn.toggleClass( 'is-busy', busy ).prop( 'disabled', busy );
			if ( label ) {
				$btn.find( '.omg-btn__label' ).text( label );
			}
		},

		/* Plugins
		--------------------------------------------- */

		pendingPlugins: function () {
			return this.$root.find( '.omg-plugin:not(.is-ready)' ).filter( function () {
				return $( this ).find( '.omg-plugin__check' ).is( ':checked' );
			} );
		},

		updatePluginCount: function () {
			var count = this.pendingPlugins().length;
			var $btn = this.$root.find( '.omg-install-plugins' );
			$btn.find( '.omg-btn__count' ).text( '(' + count + ')' );
			if ( ! $btn.hasClass( 'is-busy' ) ) {
				$btn.prop( 'disabled', ! count );
			}
		},

		setPluginStatus: function ( $item, state, text ) {
			$item.find( '.omg-plugin__status' )
				.attr( 'class', 'omg-plugin__status omg-plugin__status--' + state )
				.text( text );
		},

		/**
		 * Install / update / activate one plugin through TGMPA.
		 * Our ajax handler returns the next TGMPA action until the plugin is active.
		 */
		installPlugin: function ( $item ) {
			var self = this;
			var deferred = $.Deferred();
			var lastHash = '';

			function fail() {
				$item.removeClass( 'is-working' );
				self.setPluginStatus( $item, 'failed', i18n.failed );
				deferred.reject();
			}

			function request() {
				$.post( params.ajaxurl, {
					action: 'setup_plugins',
					wpnonce: params.wpnonce,
					slug: $item.data( 'slug' )
				} ).done( handle ).fail( fail );
			}

			function handle( response ) {
				if ( ! response || 'object' !== typeof response ) {
					return fail();
				}
				if ( response.done ) {
					$item.removeClass( 'is-working' ).addClass( 'is-ready' );
					$item.find( '.omg-plugin__check' ).prop( 'disabled', true );
					self.setPluginStatus( $item, 'active', i18n.active );
					return deferred.resolve();
				}
				// Same action twice in a row means TGMPA could not finish it.
				if ( ! response.url || response.hash === lastHash ) {
					return fail();
				}
				lastHash = response.hash;
				self.setPluginStatus( $item, 'working', response.message || i18n.installing );
				$.post( response.url, response ).always( request );
			}

			$item.addClass( 'is-working' );
			this.setPluginStatus( $item, 'working', i18n.installing );
			request();

			return deferred.promise();
		},

		installPlugins: function ( e ) {
			var self = this;
			var $btn = $( e.currentTarget );
			var $step = $btn.closest( '.omg-step' );
			var queue = this.pendingPlugins().toArray();
			var failed = 0;

			if ( ! queue.length ) {
				return;
			}

			this.showError( $step, '' );
			this.setBusy( $btn, true, i18n.installing );
			$step.find( '.omg-plugin__check, .omg-skip' ).prop( 'disabled', true );

			// One plugin at a time, TGMPA does not like parallel requests.
			( function next() {
				var item = queue.shift();
				if ( item ) {
					self.installPlugin( $( item ) ).fail( function () {
						failed++;
					} ).always( next );
					return;
				}

				$step.find( '.omg-skip' ).prop( 'disabled', false );
				$step.find( '.omg-plugin:not(.is-ready) .omg-plugin__check' ).each( function () {
					// Required plugins stay locked.
					var required = $( this ).closest( '.omg-plugin' ).find( '.omg-plugin__status--required' ).length;
					$( this ).prop( 'disabled', !! required );
				} );

				if ( failed ) {
					self.setBusy( $btn, false, i18n.install );
					self.updatePluginCount();
					self.showError( $step, i18n.pluginsErr );
				} else {
					self.setBusy( $btn, false, i18n.installed );
					$btn.prop( 'disabled', true ).find( '.omg-btn__count' ).text( '' );
					self.completeStep( 'plugins', true );
				}
			}() );
		},

		/* Demo import
		--------------------------------------------- */

		importDemo: function ( e ) {
			var self = this;
			var $btn = $( e.currentTarget );
			var $step = $btn.closest( '.omg-step' );
			var label = $btn.find( '.omg-btn__label' ).text();
			// Elementor themes queue the Easy Demo Import for Omega Themes requests instead of the theme's own import.
			var queue = ( params.importSteps || [ { action: 'grocery_shopping_store_setup_widgets', wpnonce: params.wpnonce } ] ).slice();

			function fail( message ) {
				self.setBusy( $btn, false, label );
				self.showError( $step, message || i18n.importErr );
			}

			this.showError( $step, '' );
			this.setBusy( $btn, true, i18n.importing );

			( function next() {
				var data = queue.shift();
				if ( ! data ) {
					self.setBusy( $btn, false, i18n.imported );
					$btn.prop( 'disabled', true );
					// The theme's own import saves the step itself.
					self.completeStep( 'demo', false === params.importSaves );
					return;
				}
				// Read as text: PHP notices from the import should not count as a failure.
				$.ajax( {
					url: params.ajaxurl,
					type: 'POST',
					dataType: 'text',
					data: data
				} ).done( function ( text ) {
					var response = parseJson( text );
					if ( response && false === response.success ) {
						return fail( 'string' === typeof response.data ? response.data : '' );
					}
					next();
				} ).fail( function () {
					fail( '' );
				} );
			}() );
		}
	};

	/**
	 * Parse a JSON reply that may have PHP notices printed before it.
	 *
	 * @param {string} text Raw response.
	 * @return {Object|null}
	 */
	function parseJson( text ) {
		try {
			return JSON.parse( text );
		} catch ( err ) {
			try {
				return JSON.parse( String( text ).replace( /^[\s\S]*?(\{[\s\S]*\})\s*$/, '$1' ) );
			} catch ( err2 ) {
				return null;
			}
		}
	}

	/* Focus mode: only the setup card is usable until the demo is imported
	--------------------------------------------- */

	var Focus = {

		init: function () {
			this.$wrap = $( '.omg-wrap.is-focus' );
			if ( ! this.$wrap.length ) {
				return;
			}
			this.$tip = this.$wrap.find( '.omg-focus-tip' );
			// Keep keyboard users inside the setup card too.
			this.$blurred = this.$wrap.children().not( '.omg-layout' ).add( this.$wrap.find( '.omg-main' ) );
			this.$blurred.attr( 'inert', '' );

			this.$tip.on( 'click', '.omg-focus-ok', this.hideTip.bind( this ) );
			this.$tip.on( 'click', '.omg-focus-exit', this.exit.bind( this ) );
			$( document ).on( 'omg:step-complete', function ( e, id ) {
				if ( 'demo' === id ) {
					Focus.exit();
				}
			} );

			$( '#omg-setup' )[0].scrollIntoView( { block: 'nearest' } );
		},

		hideTip: function () {
			var $tip = this.$tip;
			$tip.addClass( 'is-leaving' );
			window.setTimeout( function () {
				$tip.remove();
			}, 350 );
		},

		exit: function () {
			if ( ! this.$wrap.hasClass( 'is-focus' ) ) {
				return;
			}
			this.hideTip();
			this.$wrap.removeClass( 'is-focus' );
			this.$blurred.removeAttr( 'inert' );

			// Reloading the page should not bring the blur back.
			if ( window.history.replaceState ) {
				window.history.replaceState( null, '', window.location.href.replace( /([?&])omg-focus=1(&|$)/, function ( m, p1, p2 ) {
					return p2 ? p1 : '';
				} ) );
			}
		}
	};

	/* Coupon copy buttons
	--------------------------------------------- */

	function copyText( text ) {
		if ( navigator.clipboard && window.isSecureContext ) {
			return navigator.clipboard.writeText( text );
		}
		var $tmp = $( '<textarea readonly>' ).val( text ).css( { position: 'fixed', opacity: 0 } ).appendTo( 'body' );
		$tmp[0].select();
		try {
			document.execCommand( 'copy' );
		} catch ( err ) {}
		$tmp.remove();
		return $.Deferred().resolve().promise();
	}

	$( document ).on( 'click', '[data-omg-copy]', function () {
		var $btn = $( this );
		var original = $btn.data( 'omg-label' ) || $btn.text();
		$btn.data( 'omg-label', original );

		copyText( String( $btn.data( 'omg-copy' ) ) ).then( function () {
			$btn.addClass( 'is-copied' ).text( i18n.copied );
			window.clearTimeout( $btn.data( 'omg-timer' ) );
			$btn.data( 'omg-timer', window.setTimeout( function () {
				$btn.removeClass( 'is-copied' ).text( original );
			}, 1800 ) );
		} );
	} );

	/* Welcome notice
	--------------------------------------------- */

	$( document ).on( 'click', '.omg-notice__close', function ( e ) {
		var $notice = $( this ).closest( '.omg-notice' );
		e.preventDefault();

		$.post( params.ajaxurl, { action: 'grocery_shopping_store_dismiss_notice', nonce: $notice.data( 'nonce' ) } );
		$notice.addClass( 'is-leaving' );
		window.setTimeout( function () {
			$notice.slideUp( 200, function () {
				$notice.remove();
			} );
		}, 250 );
	} );

	$( function () {
		Setup.init();
		Focus.init();
	} );

}( jQuery ) );
