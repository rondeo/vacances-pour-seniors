/* global wp */
import { AbstractBlock } from 'starting-blocks';

/**
 * LoadMore
 */
export default class LoadMore extends AbstractBlock {
	init() {
		super.init();
	}

	onPageReady() {
		this.$container = this.rootElement.querySelector('.js-container');
		this.$button = this.rootElement.querySelector('.js-button');

		this.foundPosts = parseFloat(this.$container.getAttribute('data-found-posts'));
		this.postsPerPage = parseFloat(this.$container.getAttribute('data-posts-per-page')) || 3;
		this.offset = this.$container.children.length;
		this.action = this.$container.getAttribute('data-action');

		this.update();
		this.setupEvents();
	}

	/**
	 * LoadMore.setupEvents
	 */
	setupEvents() {
		this.$button.addEventListener('click', () => {
			this.loadMore();
		});
	}


	/**
	 * LoadMore.loadMore
	 */
	loadMore() {
		// load more projects with AJAX
		this.load()
			.then(response => response.text())
			.then(html => this.append(html))
			.finally(() => this.update());
	}


	/**
	 * LoadMore.load
	 */
	load() {
		const url = new URL(window.wp.ajax_url);

		const params = {
			offset: this.offset,
			posts_per_page: this.postsPerPage,
			action: this.action,
			nonce: wp.nonce,
		};

		Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

		// Lock everything before the request.
		this.locked();

		return fetch(url);
	}


	/**
	 * LoadMore.update
	 */
	update() {
		this.offset += this.postsPerPage;

		if (this.$button) {
			this.$button.setAttribute('data-count', this.foundPosts - this.offset);
		}

		if (this.$button && (this.offset >= this.foundPosts)) {
			this.$button.style.setProperty('display', 'none');
		}

		// Ensure everything is unlocked.
		this.unlocked();
	}


	/**
	 * LoadMore.locked
	 */
	locked() {
		// console.info('LoadMore.locked');

		// add loading state to ajax container if exists
		if (this.$container) {
			this.$container.classList.add('is-loading');
		}
	}


	/**
	 * LoadMore.unlocked
	 */
	unlocked() {
		// console.info('LoadMore.unlocked');

		// Remove loading state of ajax container if exists.
		if (this.$container) {
			this.$container.classList.remove('is-loading');
		}
	}


	/**
	 * LoadMore.append
	 */
	append(html) {
		if (!html) {
			return;
		}
		this.$container.innerHTML += html;
	}
}

//
//
//
// 	/**
// 	 * LoadMore.load
// 	 */
// 	load: function() {
//
// 		var data = {
//         	action: 'ajax_load_posts',
// 	        offset: this.offset,
// 	        tag: this.tag
// 		};
//
// 		if (this.posts_per_page) {
// 	        data.posts_per_page = this.posts_per_page;
// 	    }
//
//
// 		// lock everything before the request
// 		this.lock.on.call(this);
//
// 		return $.get(window.wp.ajax_url, data);
// 	},
//
//

// };
