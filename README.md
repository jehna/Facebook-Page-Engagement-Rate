# Facebook Page Engagement Rate
> Calculate Engagement Rate from your Facebook Page's posts

20 most recent FB posts from a given page with calculated Engagement Rates (The number of people who clicked anywhere in your posts (divided by) The number of people who saw your Page post).

Background is [Brijan](http://thepatternlibrary.com/#brijan) by Brijan Powell, from thepatternlibrary.

The awesome [Circles plugin](https://github.com/lugolabs/circles) by Artan Sinani.


## Getting Started

Make sure the `cache` folder is writeable.

### Obtaining Access Token

Facebook is famous abuout it's confusing, shitty and ever-changing API. The methods described here (including the code) can stop working at any time. This is how you obtained neverending FB Page access token in 8/2014:

1. Make a FB App
2. Go to [Grap API Explorer](https://developers.facebook.com/tools/explorer/)
3. Select your app from the list
4. Open `Get Access Token` > `Extended Permissions` and select `manage_pages` and `read_insights`.
5. Give permissions to given app
6. Get the `Access Token` from the input box
7. Get extended access token by visiting: https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=YOUR_APP_ID&client_secret=YOUR_APP_SECRET&fb_exchange_token=YOUR_ACCESS_TOKEN
8. Put the new access toke to the [Grap API Explorer](https://developers.facebook.com/tools/explorer/)'s box
9. Go to url `me/accounts`
10. Grab the access token that is associated with the correct Page
11. Now you can check that your latest access token should never expire from [Access Token Debugger](https://developers.facebook.com/tools/debug/accesstoken/)

Paste this neverending access token to the code and you're good to go.

## Screenshot

The app produces blocks like these

![Screenshot](https://raw.githubusercontent.com/jehna/Facebook-Page-Engagement-Rate/master/screenshot.png)

## Licensing

This project is licensed under MIT license (see LICENSE).
