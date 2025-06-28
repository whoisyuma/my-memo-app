<?php
require_once('lib/db.php');
require_once('db_config.php');

$db = dbconnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($id) {
        $stmt = $db->prepare('DELETE FROM memos WHERE id=?');
        if ($stmt) {
            $stmt->bind_param('i', $id);
            $success = $stmt->execute();
            if (!$success) {
                die($db->error);
            }
        }
    }

    header('Location: index.php');
    exit();
}

$stmt = $db->prepare('SELECT * FROM memos ORDER BY created_at DESC');
if (!$stmt) {
    die($db->error);
}

$stmt->execute();
$result = $stmt->get_result();
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
        <a href="create.php">＋ADD</a>
    </header>
    <main>
        <?php while ($memo = $result->fetch_assoc()): ?>
            <div class="post">
                <div class="post-content">
                    <h2>
                        <a href="detail.php?id=<?php echo h($memo['id']); ?>"><?php echo h($memo['title']); ?></a>
                    </h2>
                    <p>
                        <?php
                        $content = str_replace("\r", '', $memo['content']);
                        $content = mb_strimwidth($content, 0, 300, '...', 'UTF-8');
                        echo nl2br($content);
                        ?>
                    </p>
                </div>
                <div class="post-delete">
                    <p>
                        <time datetime="<?php echo h($memo['created_at']); ?>"><?php echo h($memo['created_at']); ?></time>
                    </p>
                    <form action="index.php" method="post">
                        <input type="hidden" name="id" value="<?php echo h($memo['id']); ?>">
                        <button>DELETE</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </main>
</body>
</html>