<?php 

use Data\DataManager;
$categories = DataManager::getCategories();
$categoryId = (int) ($_REQUEST['categoryId'] ?? null);

require_once('views/partials/header.php'); 
?>

<div class="page-header">
    <h2>List of books by category</h2>
</div>


<ul class="nav nav-tabs">
    <?php foreach ($categories as $cat) { ?>
    <li role="presentation" 
        <?php if ($cat->getId() === $categoryId) { ?> class="active" <?php } ?>>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?view=list&categoryId=<?php echo urlencode($cat->getId()); ?>"><?php echo $cat->getName(); ?></a>
    </li> 
    <?php } ?>
</ul>



<?php require_once('views/partials/footer.php'); ?>