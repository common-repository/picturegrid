<?php
/*
Plugin Name: PictureGrid
Plugin URI: http://kierandelaney.net/projects/picturegrid/
Description: Picturegrid - Add flickr to your blog, no mess, no fuss. To make use of the lightbox functionality, you will need a lightbox plugin (<a href="http://www.4mj.it/slimbox-wordpress-plugin/">like this one</a> - no relation).
Version: 1.0
Author: Kieran Delaney
Author URI: http://kierandelaney.net/

    Copyright 2007  Kieran Delaney  (email : hello@kierandelaney.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//create options page
function picturegridOptions() {
    if (function_exists('add_options_page')) {
		add_options_page('Picturegrid Options', 'Picturegrid', 8, basename(__FILE__), 'picturegridOptionsPage');
    }
}

//build options page
function picturegridOptionsPage() {
  if (isset($_POST['info_update'])) { ?>
		<div id="message" class="updated fade">
		<p>
		<strong>
                <?php
                
                if(!$_POST['flickrID']) {
		   _e('ERROR: You need to enter a Flickr ID!<br />', 'English');
                } else {
                   _e('Flickr ID Updated<br />', 'English');
                   update_option('flickrID', $_POST['flickrID']);
                }     

                if(!$_POST['apiKey']) {
		   _e('ERROR: You need to enter a Flickr API Key!<br />', 'English');
                } else {
                   _e('API Key Updated<br />', 'English');
                   update_option('apiKey', $_POST['apiKey']);
                }            

                if($_POST['pictureSet']) {
                  if(!$_POST['setID']) { 
		   _e('ERROR: Enter a Flickr Set ID or choose recent photos.<br />', 'English');
                  } else {
                   update_option('setID', $_POST['setID']);
		   _e('Set ID Updated<br />', 'English');
                  }
                } else {
                   update_option('setID', 'recent');
		   _e('Using Recent Photos<br />', 'English');
                } 

                if($_POST['photolimit']){
                  if($_POST['photolimit'] > 500) {
   		     _e('ERROR: Limit of 500 photos.<br />', 'English');
                  } elseif($_POST['photolimit'] < 1) {
   		     _e('ERROR: You\'ve got to have at least one photo!<br />', 'English');               
                  } else {
                     _e('Photolimit Updated<br />','English');
                     update_option('photolimit', $_POST['photolimit']);
                  }
                } else {
                     _e('ERROR: No Photo Limit Defined<br />','English');
                }

                if($_POST['p_lightbox']) {
                   _e('Using Lightbox<br />', 'English');
                   update_option('p_lightbox', 1);
	        } else {
                   update_option('p_lightbox', 0);
		   _e('Linking Thumbs To Flickr<br />', 'English');
                } 
                 
               if($_POST['img_margin']) {
                   _e('Img Margin Updated<br />', 'English');
                   update_option('img_margin', $_POST['img_margin']);
	        } else {
		   _e('No Margin Entered, Using 0<br />', 'English');
                   update_option('img_margin', '0');
                } 

               if($_POST['border_size']) {
                   _e('Img Border Size Updated<br />', 'English');
                   update_option('border_size', $_POST['border_size']);
	        } else {
		   _e('No Border Size Entered, Using 0<br />', 'English');
                   update_option('border_size', '0');
                } 

               if($_POST['border_color']) {
                   _e('Img Border Colour Updated<br />', 'English');
                   update_option('border_color', str_pad(strtoupper($_POST['border_color']), 6, '0'));
	        } else {
		   _e('No Border Colour Entered, Using Black<br />', 'English');
                   update_option('border_color', '000000');
                } 
                
                ?>
                
		</strong>
		</p>
		</div>
	<?php } ?>

	<div class=wrap>
	<form method="post">
        <?php echo '<h2>Picturegrid Options</h2>'; ?>
        <p><?php _e('Picturegrid will insert a grid of flickr photos into a wordpress page anywhere you use the function <strong><em>picturegrid()</em></strong> - See the <a href="http://kierandelaney.net/blog/projects/wp-picturegrid/">Picturegrid WP Plugin Website</a> for documentation and updates. Insert photos into your sidebar, or create an entire gallery all with one simple plugin. You MUST have a lighbox plugin installed in order to use the lightbox functionality.<br /><br />Remember, insert < ? php picturegrid(); ? > (with proper php tags) into any page template to insert the picturegrid.', 'English'); ?></p>

 
        <fieldset name="flickr_ID">
		<h3><?php _e('Flickr ID', 'English'); ?></h3>
		<p>
		<label for="flickrID"><?php _e('Your Flickr ID (Try <a href="http://idgettr.com/">idgettr</a>)', 'English') ?></label>
		<input type="text" name="flickrID" id="flickrID" maxlength="20" size="20" value="<?php if(get_option('flickrID')) echo get_option('flickrID'); ?>" />
		</p>
	</fieldset>
<br />
        <fieldset name="api_key">
		<h3><?php _e('API Key', 'English'); ?></h3>
		<p>
		<label for="apiKey"><?php _e('Your Flickr API Key (Try <a href="http://www.flickr.com/services/api/keys/">Flickr API Keys</a>)', 'English') ?></label>
		<input type="text" name="apiKey" id="apiKey" maxlength="40" size="40" value="<?php if(get_option('apiKey')) echo get_option('apiKey'); ?>" />
		</p>
	</fieldset>
<br />
	<fieldset name="set_ID">
		<h3><?php _e('Flickr Set To Use', 'English'); ?></h3>
		<p>
		<label><input name="pictureSet" type="radio" value="1" class="tog" <?php if(get_option('setID') != 'recent') echo 'checked == "1" '; ?>/><?php _e(' Choose a specific set to display -', 'English') ?></label>
		<label for="setID"><?php _e('id', 'English') ?></label>
		<input type="text" name="setID" id="setID" maxlength="20" size="20" value="<?php if(get_option('setID') != 'recent') echo get_option('setID'); ?>" />
		<br />		
		<label><input name="pictureSet" type="radio" value="0" class="tog" <?php if(get_option('setID') == 'recent') echo 'checked == "1" '; ?>/><?php _e(' Use recent photos from all sets (not recomended, you cannot order photos out of sets)', 'English') ?></label>
		</p>
	</fieldset>
<br />

        <fieldset name="limit">
		<h3><?php _e('Photo Options', 'English'); ?></h3>
		<p>
		<label for="photolimit"><?php _e('Number Of Photos (1-500)', 'English') ?></label>
		<input type="text" name="photolimit" id="photolimit" maxlength="3" size="4" value="<?php if(get_option('photolimit')) echo get_option('photolimit'); ?>" />
		</p>
	</fieldset>
<br />
	<fieldset name="lbox">
		<h3><?php _e('Lightbox Options', 'English'); ?></h3>
		<p>
		<label><input name="p_lightbox" type="radio" value="1" class="tog" <?php if(get_option('p_lightbox') == 1) echo 'checked == "1" '; ?>/><?php _e(' Yes! I have a lighbox plugin installed - make me pretty.', 'English') ?></label>
		<br />
		<label><input name="p_lightbox" type="radio" value="0" class="tog" <?php if(get_option('p_lightbox') == 0) echo 'checked == "1" '; ?>/><?php _e(' No - I\'m all about the simple. Link each image to flickr plz.', 'English') ?></label>
		</p>
	</fieldset>
<br />
        <fieldset name="style">
		<h3><?php _e('Style Options', 'English'); ?></h3>
		<p>
		<label for="img_margin"><?php _e('Image Margin (pixels)  ', 'English') ?></label>
		<input type="text" name="img_margin" id="img_margin" maxlength="1" size="1" value="<?php  echo get_option('img_margin'); ?>" /><br />
		<label for="border_size"><?php _e('Image Border (pixels)  ', 'English') ?></label>
		<input type="text" name="border_size" id="border_size" maxlength="1" size="1" value="<?php echo get_option('border_size'); ?>" /><br />
		<label for="img_margin"><?php _e('Border Color (Hex)  #', 'English') ?></label>
		<input type="text" name="border_color" id="border_color" maxlength="6" size="6" value="<?php echo get_option('border_color'); ?>" /><br />
		</p>
	</fieldset>

        <div class="submit">
	<input type="submit" name="info_update" value="<?php _e('Update Options', 'English'); ?> &raquo;" />
	</div>

        </form>
        </div>
<?php } 

function picturegrid(){
  echo '<style type="text/css"> #picturegrid img {padding: 0px; margin: ' . get_option('img_margin');
  echo 'px; border: ' . get_option('border_size');
  echo 'px #' . get_option('border_color');
  echo ' solid;} #picturegrid a:hover img {filter: xray; opacity:.10; text-decoration: none;} </style>';

  $config_flickrUserId = get_option('flickrID'); 
  $config_flickrApiKey = get_option('apiKey');
  $f = new phpFlickr($config_flickrApiKey);  
  echo "<div id='picturegrid'>";
 
  $set = get_option('setID');

if ($set!='recent') {
$photoSet = $f->photosets_getInfo($set);
$photos = $f->photosets_getPhotos($set, "original_format,date_taken,date_upload", NULL, get_option('photolimit'));
} else {
$photos = $f->people_getPublicPhotos(get_option('flickrID'), NULL, get_option('photolimit'));
}

if (get_option('p_lightbox')==1) {

  foreach ($photos['photo'] as $photo) {
   	$photo_thumbs .= "<a href='" . $f->buildPhotoURL($photo, "Medium") . "' rel='lightbox[]' title='" . $photo['title'] . "' alt=''><img src='" . $f->buildPhotoURL($photo, "Square") . "' title='" . $photo['title'] . "' alt=''/></a>";
  }
} else {
  foreach ($photos['photo'] as $photo) {
   	$photo_thumbs .= "<a href='http://www.flickr.com/photos/" . get_option('flickrID') . "/" .$photo[id] . "' title='" . $photo['title'] . "' alt=''><img src='" . $f->buildPhotoURL($photo, "Square") . "' title='" . $photo['title'] . "' alt=''/></a>";
  }
}  


  echo $photo_thumbs . "</div>";
 
}

function doRegisterWidget()
{
	if (function_exists('register_sidebar_widget')){
	register_sidebar_widget('Picturegrid', 'pictureWidget');
	}
}

function pictureWidget($args)
{
	extract($args);

	echo $before_widget;
	echo $before_title;
	echo '<a href="http://kierandelaney.net/blog/projects/wp-picturegrid/">Picturegrid</a>';
	echo $after_title;

        picturegrid();

	echo $after_widget;
}


require_once('phpFlickr210/phpFlickr.php');

//set initial defaults
add_option('flickrID', '');
add_option('photolimit', 0);
add_option('setID', '');
add_option('apiKey', '');
add_option('p_lightbox', 0);
add_option('img_margin', 4);
add_option('border_size', 1);
add_option('border_color', "000000");

// Add a new submenu
add_action('admin_menu', 'picturegridOptions');
add_action('plugins_loaded', 'doRegisterWidget');
?>