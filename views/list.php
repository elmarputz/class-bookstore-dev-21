<?php require_once('views/partials/header.php'); 
?>

<div class="page-header">
    <h2>List of books by category</h2>
</div>

<?php 
    $book = new \Bookshop\Book(1, 1, 'Testbuch', 'testautor', 12.45);
    var_dump($book);

    $cat = new \Bookshop\Category(1, 'test');
    var_dump($cat);

    $user = new \Bookshop\User(1, 'test', 'laskdjfls');
    var_dump($user);
?>    

<?php require_once('views/partials/footer.php'); ?>