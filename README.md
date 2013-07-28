# Particle Wordpress Base Theme

Particle is an HTML5 wordpress theme built around an MVC structure. In doing so I tried to remove logic from the templates files as much as possible. Most logic is handled by controllers which are automatically loaded based on the template (and therefore path) being shown to the viewer. 

The theme is made to be customized via a child theme. By design, if a controller of the same name appears in the child theme, then the child theme controller will load in place of the one in this theme. If you would like to inherit functionality from the controllers in this theme, then the child theme controllers should extend the controller in this theme.

There is a lot of custom functionality but for the most part I tried to stick to Wordpress coding conventions, so if you know Wordpress you should be able to use this theme.

## Features
- MVC structure
- Compass (SASS) integration
- Twitter Bootstrap
- Child Theme Ready
- Maintenance Page (stops all access to the site except for editors and admins)
- Customizable Header and Footer Menus
- Widget Ready

## Dependencies
- [naked-utils](https://github.com/dremonkey/wp-naked-utils)