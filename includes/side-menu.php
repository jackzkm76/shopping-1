<?php
include('config.php'); // ✅ Replace 'config.php' with your actual DB connection file
?>

<div class="side-menu animate-dropdown outer-bottom-xs">
    <div class="head"><i class="icon fa fa-align-justify fa-fw"></i> Categories</div>        
    <nav class="yamm megamenu-horizontal" role="navigation">
        <ul class="nav">
            <li class="dropdown menu-item">
                <?php 
                $sql = db_query($con, "SELECT id, categoryName FROM category");
                while($row = db_fetch_array($sql)) {
                ?>
                    <a href="category.php?cid=<?php echo $row['id'];?>" class="dropdown-toggle">
                        <i class="icon fa fa-desktop fa-fw"></i>
                        <?php echo $row['categoryName']; ?>
                    </a>
                <?php } ?>
            </li>
        </ul>
    </nav>
</div>
