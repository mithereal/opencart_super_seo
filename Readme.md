SUPER SEO LANGUAGE URLS
=====================

Opencart SUPER SEO LANGUAGE URLS Opencart 3.x.


Author
-------
Jason Clark(mithereal@gmail.com)
Original Author: Orestis Dimou
Ported to 3.x by Mithereal


Installation instructions For SUPER SEO LANGUAGE URLS
-------------------------------------------------

Before installing:

1.  Make a back-up of your database and OC application 
2.  Make sure vqmod is installed
3.  Install vqmodmanager (optional)

Installation:

1.  Upload the unzipped folders and files in the Upload folder (not the Upload folder itself)
2.  Enter your OC Admin and go to Modules
3.  Find the SUPER SEO LANGUAGE URLS module and install it


Additional notes: 

1.  If you are using other languages than English, you should copy the file "admin/language/english/extension/module/super_seo.php " to your other language directories in admin/languages/

2. Place .htaccess file in root of your opencart installation folder (htaccess.txt in extra folder), if you don't have one. This .htaccess is also used from opencart to treat url in a 
GET[_route_] parameter, for its SEO urls. During opencart installation you 
should already have renamed .htaccess.txt to .htaccess or else your seo urls won't be working. Enable seo urls from admin/settings/store/server in admin panel, if you haven't done already.
Also in extra folder you will find index.php, admin/index.php and vqmod folder, with vqmod and super seo installed.
or if "install languages" errors out (Download index.php from your server and open in it in a text editor like notepad. 

Find:
in your main and admin index.php file, after 

[code]
// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}
[/code]

put the following code (not code tags :-) )
[code]
if (isset($request->get["_route_"])) { // seo_language define
	$seo_path = explode('/',$request->get["_route_"]);
	foreach ($seo_path as $seo_part) {
		if (array_key_exists($seo_part,$languages)) {
			$session->data['language'] = $seo_part;
		}
	}
}
[/code]
