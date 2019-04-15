# Mississippi Today 

## Notable overrides of Largo

- `single-blank.php` is an extension of the Largo single-column single-post template that removes the standard top-of-post header of top tag, headline, byline, post featured media, and post social buttons. This template enqueues a number of functions in `inc/blank-page-template.php`, which affect the following things:
	- edits the `of_get_option` return to disable the post floating social buttons and main nav
	- replaces Largo's navigation.js with `js/navigation.js` to always remain visible at the top of the page
- Has a custom homepage
- `inc/open-graph.php` is outdated by Largo 0.6, and should be removed at some point in the future
- uses INN's Developer-Driven Custom Post Classes plugin for some post classes.
- uses `partials/content.php` to change the image used on the homepage
