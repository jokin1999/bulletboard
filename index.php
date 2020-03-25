<?php

// Base URL of the website, without trailing slash.
$base_url = getenv('BB_BASE_URL') ?: 'http://bbdemo.990521.xyz';

// Path to the directory to save the notes in, without trailing slash.
// Should be outside of the document root, if possible.
$save_path = getenv('BB_SAVE_PATH') ?: '_tmp';

// Password for API interface, SET NULL to close API interface (default null).
$api_passwd = getenv('BB_API_PASSWD') ?: NULL;

// Define API version
const API_VERSION = '1.0.0';

// Disable caching.
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// API Interface
if (isset($_GET['apiaction']) && isset($_GET['apipasswd']) && $api_passwd != null) {

  // Json header
  header('Content-Type: Application/json; charset=utf-8');

  // Initialize data
  $data = [
    'code'    => 1,
    'msg'     => 'Not found action.',
    'data'    => null,
    'version' => API_VERSION
  ];

  // Verify the password.
  if ($_GET['apipasswd'] != $api_passwd) {
    $data['code']   = 2;
    $data['msg']    = 'Bad Password.';
    echo json_encode($data);
    die;
  }

  $action = $_GET['apiaction'];
  $blacklist = ['.', '..', '.htaccess'];

  // Get the data list.
  if ($action == 'gdl') {
    $files = scandir($save_path);
    foreach ($files as $key => $value) {
      if (in_array($value, $blacklist) || !is_file($save_path.'/'.$value)) {
        unset($files[$key]);
      }
    }
    $data['msg'] = 'Success.';
    $data['data'] = array_merge($files);
  }

  // Clear all the data
  if ($action == 'cd') {
    $files = scandir($save_path);
    foreach ($files as $key => $value) {
      if (!in_array($value, $blacklist) && is_file($save_path.'/'.$value)) {
        unlink($save_path.'/'.$files[$key]);
      }
    }
    $data['msg'] = 'Success.';
  }

  echo json_encode($data);
  die;
}

// If no name is provided or it contains invalid characters or it is too long.
if (!isset($_GET['note']) || !preg_match('/^[a-zA-Z0-9_-]+$/', $_GET['note']) || strlen($_GET['note']) > 64) {

    // Generate a name with 5 random unambiguous characters. Redirect to it.
    header("Location: $base_url/" . substr(str_shuffle('234579abcdefghjkmnpqrstwxyz'), -6));
    die;
}

$path = $save_path . '/' . $_GET['note'];

if (isset($_POST['text'])) {

    // Make dir.
    if (!is_dir($save_path)) {
      mkdir($save_path, 0777, true);
    }

    // Update file.
    file_put_contents($path, $_POST['text']);

    // If provided input is empty, delete file.
    if (!strlen($_POST['text'])) {
        unlink($path);
    }
    die;
}

// Output raw file if client is curl or explicitly requested.
if (isset($_GET['raw']) || strpos($_SERVER['HTTP_USER_AGENT'], 'curl') === 0) {
    if (is_file($path)) {
        header('Content-type: text/plain');
        print file_get_contents($path);
    } else {
        header('HTTP/1.0 404 Not Found');
    }
    die;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Bullet Board (https://github.com/jokin1999/bulletboard)">
    <title><?php print $_GET['note']; ?></title>
    <link rel="shortcut icon" href="<?php print $base_url; ?>/favicon.ico">
    <link rel="stylesheet" href="<?php print $base_url; ?>/styles.css">
</head>
<body>
    <div class="container">
        <textarea id="content"><?php
            if (is_file($path)) {
                print htmlspecialchars(file_get_contents($path), ENT_QUOTES, 'UTF-8');
            }
        ?></textarea>
        <footer class="footer">
          <p>
            <small>Powered by <a href="https://github.com/jokin1999/bulletboard" target="_blank">Bullet Board</a></small>
          </p>
        </footer>
    </div>
    <pre id="printable"></pre>
    <script src="<?php print $base_url; ?>/script.js"></script>
</body>
</html>
