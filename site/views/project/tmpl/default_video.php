<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

$this->document->addScriptDeclaration("
(function($) {
	$(document).ready(function() {
		var player	= $('#portfolio_player');
		player.attr('width', player.width());
		player.attr('height', Math.round(player.width() * 10 / 16));
	});
})(jQuery);
");

$youtube_regex	= '/(youtube\.com|youtu\.be|youtube-nocookie\.com)\/(watch\?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*)).*/i';
$vimeo_regex	= '/(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/';

$embed	= '';
if (preg_match($youtube_regex, $this->item->video_link, $match)) {
	$embed	= 'https://www.youtube.com/embed/' . $match[3];
} else if (preg_match($vimeo_regex, $this->item->video_link, $match)) {
	$embed	= 'http://player.vimeo.com/video/' . $match[1];
}

if ($embed) {
	echo '<iframe id="portfolio_player" src="' . $embed . '" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
}