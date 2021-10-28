<?php
header('Content-type: application/json'); 
$rawOutputRequired = true;
if (isset($_GET['action_load_system_feed'])) {
switch($_GET['feed_type']) {
	case "youtube":
		$type = 0;
		break;
	case "popular":
		$type = 1;
		break;
}
require('lib/common.php');
switch($type) {
	case 0:
		$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 10");
		$icon = "youtube";
		$title = "From squareBracket";
		break;
	case 1: 
		$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.views DESC LIMIT 10");
		$icon = "popular";
		$title = "Popular";
		break;
	case 2:
		$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? AND NOT v.flags = 0010 AND NOT v.flags = 0020 AND v.author = ? ORDER BY RAND() LIMIT 6", [$videoID, $videoInfo['author']]);
		break;
	default:
		$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? AND NOT v.flags = 0010 AND NOT v.flags = 0020 ORDER BY RAND() LIMIT 6", [$videoID]);
		break;
}
$twig = twigloader();
?>
{'paging': null, 'feed_html': `
<div class=\'feed-header no-metadata before-feed-content\'>
	<div class=\'feed-header-thumb\'>
		<img class=\'feed-header-icon <?php echo $icon;?>\' src=\'//web.archive.org/web/20120118121554im_/http://s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif\' alt=\'\'>
	</div>
	<div class=\'feed-header-details\'>
		<h2>
			<?php echo $title;?>
		</h2>
	</div>
</div>
<div class=\'feed-container\' data-filter-type=\'\' data-view-type=\'\'>
<div class=\'feed-page\'>
<ul>
<?php
echo $twig->render('components/feed_list.twig', [
	'videos' => $videoData,
]);
?>
</ul>
</div>
</div>
`}
<?php
}
?>