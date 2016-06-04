WebShow Module for XOOPS Content Management System

The WebShow module for the XOOPS content management system combines a MyLinks directory with 
Jeroen Wijering's JW Media Player to catalog and play Flash media files, directories and webfeeds. 

WebShow is also a web feed generator that will read and filter a media directory, 
generate an XSPF playlist and publish a web feed link button for syndication.

WebShow is a feed aggregator that will fetch, cache and parse Atom, Rss or XSPF playlist feeds.  
Display the feed in html, load the media in the player and display realtime track data as the feed plays.

Jeroen Wijering's JW Media Player is under the enclosed Non-Profit Share-A-Like Creative Commons License.
Commercial users will need to buy a commercial license from Jeroen at http://www.jeroenwijering.com/?order=form


Disclaimer:
By installing and using the WebShow module you agree that you will not use it to display, distribute or syndicate 
a third party's copyright protected media without the owner's permission. The WebShow software is not to be used 
to display or syndicate illegal or copyright protected media. The creator of this software is not responsible 
for the media content that users display.

************************************
New for Version .71_beta
Version number has increased to .71 due to new language files and the dependence on javascript.
All new language files
Javascript functions
Ajax rating
Ajax Abuse report
JS Embed code display
JS Share including email, add to favorite and AddThis
Track data display
Feed data display

************************************
New Features for WebShow v.65
Using JW MEDIA PLAYER 3.16 (www.jeroenwijering.com)
Search Function
Media Player on every page.
Many fixes.

************************************
New Features for WebShow v.63

Using JW MEDIA PLAYER 3.15 (www.jeroenwijering.com)
Added Embed Plugins for many media sharing sites.

Added Top Views page to main menu.
Added Category Menu Block
Added Media View counter.  Adds a hit whenever the media is loaded.
Revised the page hit counter to score a hit on page view instead of website visit.

Revised templates
Added templates/style.css.
Added Submit Media links to templates.
Added module display preferences for catalog and single views.

Improved the brokenlink form to include abuse, broken and copyright reports.
New user submit form.

Engaged the JW Player Config variable.  This hides the movie's variables (including media file location) 
and cleans up the embed links.
Engaged the Callback functions of the JW Player to log player start events.
New Audio variable that assigns an mp3 url to slide show or second audio to other media.

***********************************

Features added in WebShow v.61:
Selectable WYSIWYG Text Editor (Frameworks required). 
Captcha confirmation code on submit form (Frameworks required). 
TAGS- Add keywords links to each entry. (Requires TAG module) 
The revised Poster View now includes the Flash media player. 
New Playcat view displays the category and player. 
Added javascript embed code to info box. 
Revised the embed code to include object. 


Requirements:
Created and tested on WAMP 1.61 running XOOPS 2.0.18 and 2.2.6, php 4.3 and 5, mysql 5.0.22 
and formatted for screen using IE7 and Firefox 2.00.11 browsers displayed at 1024x768 pixels.

For full WebShow functionality, download the following xoops modules and classes 
and install them according to the instructions provided with each archive.

Frameworks - Provides many common functions for xoops modules, one of which is the Captcha confirmation code 
used in WebShow's user submit form.

xoops/class/xoopseditors - Allows for selectable wysiwyg editors.

XOOPS TAG Module by phppp - Displays linked keyword tags for every WebShow media link.


Notes:
Hopefully, the following issues with the JW Player will be resolved soon. 

Jeroen has removed the pre/post and overlay ad functions in JW Media Player 3.15. 
If you require these functions get version 3.12 mediaplayer.swf.

Several variables are currently not available using the new file config method.  
All control variables are working in singlelink view as it has been reverted to 
use the older addVariable method from JW Media Player 3.12.

Support:

Support for this module is available at WebShow Technical Support Support at http://wikiwebshow.com/modules/newbb/viewforum.php?forum=3 only.