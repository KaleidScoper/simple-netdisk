<?php
$directory = 'files/';
$files = scandir($directory);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $target_file = $directory . basename($_FILES['fileToUpload']['name']);
    $upload_error = $_FILES['fileToUpload']['error'];

    if ($upload_error === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
            echo "文件上传成功: " . htmlspecialchars(basename($_FILES['fileToUpload']['name']));
        } else {
            echo "文件上传失败: 目标文件无法写入.";
        }
    } else {
        switch ($upload_error) {
            case UPLOAD_ERR_INI_SIZE:
                echo "上传的文件超过了 php.ini 中设置的最大值.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "上传的文件超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "文件只有部分被上传.";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "没有文件被上传.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "缺少临时文件夹.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "文件写入失败.";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "由于 PHP 扩展导致上传停止.";
                break;
            default:
                echo "未知错误.";
                break;
        }
    }
}


if (isset($_GET['delete'])) {
    $fileToDelete = $directory . $_GET['delete'];
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        echo "文件删除成功: " . htmlspecialchars($_GET['delete']);
    } else {
        echo "文件不存在.";
    }
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>网盘</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>文件列表</h1>
    <ul>
        <?php foreach ($files as $file): ?>
            <?php if ($file !== '.' && $file !== '..'): ?>
                <li>
                    <a href="<?= $directory . $file ?>" download><?= htmlspecialchars($file) ?></a>
                    <a href="?delete=<?= urlencode($file) ?>">删除</a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <h2>上传文件</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" required>
        <input type="submit" value="上传">
    </form>
</body>
</html>
