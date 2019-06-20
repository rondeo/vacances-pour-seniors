import config from 'js/config';

export default class Scroll {
	/**
	 * resetScroll
	 *
	 * @param  positionX
	 * @param  positionY
	 * @public
	 */
	static resetScroll(positionX, positionY) {
		// console.log('resetScroll');

		if ('undefined' !== typeof positionX) {
			config.scroll.left = parseInt(positionX, 10);
		}

		if ('undefined' !== typeof positionY) {
			config.scroll.top = parseInt(positionY, 10);
		}

		window.scrollTo(
			config.scroll.left,
			config.scroll.top,
		);
	}


	/**
	 * disableScroll
	 *
	 * Lock scroll position, but retain settings for later
	 *
	 * @see  http://stackoverflow.com/a/3656618
	 */
	static disableScroll() {
		// console.log('disableScroll');

		const documentElementScrollLeft = config.html.scrollLeft;
		const documentElementScrollTop = config.html.scrollTop;

		const bodyScrollLeft = config.body.scrollLeft;
		const bodyScrollTop = config.body.scrollTop;

		// eslint-disable-next-line no-restricted-globals
		config.scroll.left = self.pageXOffset || documentElementScrollLeft || bodyScrollLeft;
		// eslint-disable-next-line no-restricted-globals
		config.scroll.top = self.pageYOffset || documentElementScrollTop || bodyScrollTop;

		config.html.style.setProperty('overflow', 'hidden');
		config.html.style.setProperty('height', '100%');

		document.ontouchmove = event => event.preventDefault();

		// eslint-disable-next-line
		Scroll.resetScroll(config.scroll.left, config.scroll.top);
	}


	/**
	 * enableScroll
	 *
	 * @param  position
	 */
	static enableScroll(position) {
		// console.log('enableScroll');

		let resumeScroll = true;
		let currentPosition = position;

		if ('undefined' === typeof currentPosition) {
			currentPosition = config.scroll.top;
		}

		if ('boolean' === typeof currentPosition && false === currentPosition) {
			resumeScroll = false;
		}

		// Enable scrolling.
		document.ontouchmove = () => true;

		// unlock scroll position
		// http://stackoverflow.com/a/3656618
		config.html.style.setProperty('overflow', 'visible');
		config.html.style.setProperty('height', 'auto');

		// resume scroll position if possible
		if (resumeScroll) {
			// eslint-disable-next-line
			Scroll.resetScroll(
				config.scroll.left,
				currentPosition,
			);
		}
	}
}
