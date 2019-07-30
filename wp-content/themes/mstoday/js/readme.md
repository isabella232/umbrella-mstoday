## navigation.js

`mstoday/js/navigation.js` is a clone of the current Largo navigation.js, with the code related to showing/hiding the nav on scroll removed. This is so that the sticky nav on the "blank page" template remains always visible.

To update that file, copy largo's `navigation.js` into this folder, then remove all the `direction`-related code.

The file is only enqueued on the `single-blank.php` template, and is enqueued by the function `mstoday_blank_page_largo_nav_js` in `inc/blank-page-template.php`.
