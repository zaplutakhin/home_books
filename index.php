<?php
 $host = 'localhost';
 $db   = 'global';
 $user = 'azaplutakhin';
 $pass = 'neto1877';
 $charset = 'utf8';

 $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
 $opt = [
     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::ATTR_EMULATE_PREPARES   => false,
 ];
 $pdo = new PDO($dsn, $user, $pass, $opt);

 $sqlQuery = "SELECT * FROM `books`";
 $isbn = !empty($_GET['isbn']) ? '%' . trim($_GET['isbn']) . '%' : null;
 $author = !empty($_GET['author']) ? '%' . trim($_GET['author']) . '%' : null;
 $bookname = !empty($_GET['name']) ? '%' . trim($_GET['name']) . '%' : null;
 
 if (!$isbn && !$author && !$bookname) {
     $statement = $pdo->prepare($sqlQuery);
     $statement->execute();
 } else {
     $sqlQuery = "SELECT * FROM `books` WHERE isbn LIKE ? OR author LIKE ? OR `name` LIKE ?";
     $statement = $pdo->prepare($sqlQuery);
     $statement->execute([$isbn, $author, $bookname]);
 }
?>
 <!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Книги</title>
</head>
<body>
<section class="wrap">
    <div class="form">
        <form method="GET">
            <input type="text" name="ISBN" placeholder="ISBN" id="ISBN" value="<?php if (!empty($isbn)) echo trim($isbn, '%') ?>">
            <input type="text" name="author" placeholder="Автор" id="author"
                   value="<?php if (!empty($author)) echo trim($author, '%') ?>">
            <input type="text" name="bookname" placeholder="Название" id="bookname"
                   value="<?php if (!empty($bookname)) echo trim($bookname, '%') ?>">
            <input type="submit" value="Отфильтровать">
        </form>
    </div>

<?php if ($statement->rowCount() === 0): ?>
    <a href="index.php">Назад</a>
    <ul>
        <?php if (!empty($isbn) && $statement->rowCount() === 0): ?>
            <li>По фильтру ISBN ничего не найдено</li>
        <?php endif; ?>
        <?php if (!empty($author) && $statement->rowCount() === 0): ?>
            <li>По фильтру author ничего не найдено</li>
        <?php endif; ?>
        <?php if (!empty($bookname) && $statement->rowCount() === 0): ?>
            <li>По фильтру bookname ничего не найдено</li>
        <?php endif; ?>
    </ul>
<?php endif; ?>
<?php if ($statement->rowCount() !== 0): ?>
    <table>
        <tr>
            <td>Название</td>
            <td>Автор</td>
            <td>Год</td>
            <td>Жанр</td>
            <td>ISBN</td>
        </tr>
        <?php foreach ($statement as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']) ?></td>
                <td><?php echo htmlspecialchars($row['author']) ?></td>
                <td><?php echo htmlspecialchars($row['year']) ?></td>
                <td><?php echo htmlspecialchars($row['genre']) ?></td>
                <td><?php echo htmlspecialchars($row['isbn']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</section>
</body>
</html>
    