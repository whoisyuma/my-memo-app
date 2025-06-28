<?php
require_once('lib/db.php');
require_once('db_config.php');

// URLからidを取得する
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$id) {
    die('Could not find the ID');
}

// DBと接続する
$db = dbconnect();
// idをもとにtitle, content, created_atを取得する準備
$stmt = $db->prepare('SELECT title, content, created_at FROM memos WHERE id=?');
if (!$stmt) {
    die($db->error);
}

$stmt->bind_param('i', $id);
$stmt->execute();

$result = $stmt->get_result();
$memo = $result->fetch_assoc();

if (!$memo) {
    die('Could not find your memo');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/my-icon.png">
    <link rel="stylesheet" href="styles/style.css">
    <title>my-memo-app</title>
</head>

<body>
    <header>
        <h1 class="header-title">My Memo</h1>
    </header>
    <main>
        <div class="post-detail">
            <h1><?= h($memo['title']); ?></h1>
            <p><?php
                $content = str_replace("\r", '', $memo['content']);
                echo nl2br($content);
                ?>
            </p>
            <time datetime="<?= h($memo['created_at']); ?>"><?= h($memo['created_at']); ?></time>
            <div>
                <a href="index.php">← BACK</a>
            </div>
        </div>
    </main>
</body>
</html>