<?php
    require_once('lib/db.php');
    require_once('db_config.php');
    
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
        $content = trim(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS));

        if ($title === '' || $content === '') {
            $error = 'タイトルと内容は必須です';
        } else {
            $db = dbconnect();
            $stmt = $db->prepare('INSERT INTO memos (title, content) VALUES (?, ?)');
            if (!$stmt) {
                die($db->error);
            }

            $stmt->bind_param('ss', $title, $content);
            $success = $stmt->execute();
            if (!$success) {
                die($db->error);
            }

            header('Location: index.php');
            exit();
        }
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
        <form action="create.php" method="post">
            <div class="post-create">
                <div class="post-input">
                    <input type="text" name="title" placeholder="Title">
                    <textarea name="content" placeholder="Write your memo here..."></textarea>
                    <?php if($error): ?>
                        <p>※Please enter a title and note.</p>
                    <?php endif; ?>
                </div>
                <div class="post-save">
                    <a href="index.php">← BACK</a>
                    <button type="submit">SAVE</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>