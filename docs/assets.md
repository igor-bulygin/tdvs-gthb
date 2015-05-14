### Assets

The assets work in a hierarchical way. There are 3 global CSS files that are added to all (`text/html`) requests.
Those files are:

* /bootstrap-patches.css
* /fonts.css
* /global.css

Besides those, there is one global CSS file per type of device and type of layout. So, a (`text/html`) request, made
from a desktop device to the `/admin` part will have (aside from the already mentioned 3 global files) the following
extra CSS files:

* /desktop/global.css
* /desktop/admin/global.css

Note that the assets managing system is smart enough to load all those files in the correct order, so you can override
something defined in `/desktop/global.css` in `/desktop/admin/global.css`.

JS files are not any different. Order logic applies as well. The only (small) difference is that there is only one
global JS files that gets loaded with all (`text/html`) requests.

* /global.js

PHP assets (in `/assets` folder of this project) follow this hierarchy:

* AppAsset loads all global JS and CSS files, Yii and Bootstrap files
* /<b>\<type of device\></b>/GlobalAsset loads global CSS and JS files for the current device, Angular, jsTree, other
JS deps and then it loads `AppAsset`
* /<b>\<type of device\></b>/<b>\<type of layout\></b>/GlobalAsset loads global CSS and JS files for the current device
and loads `/<type of device>/GlobalAsset`

Last, it goes the asset of the current page. For example, in a request from a desktop device to `/admin/index`, that
would be `/desktop/admin/IndexAsset`). Those *final level* assets are <b>totally and completely</b> required
(Are you reading carefully? 100% mandatory!) to load the `GlobalAsset` from their own folder (type of device + type of
layout).