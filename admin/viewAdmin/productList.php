<?php ob_start(); ?>
<div class="container">
    <h2>Product Management</h2>
    <div style="margin: 20px 0;"><a href="productAdd" class="btn btn-success">Add product</a></div>
    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Information</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($arr as $row): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['picture']); ?>" width="100"></td>
                <td>
                    <strong><?php echo $row['title']; ?></strong><br>
                    <small>Category: <?php echo $row['name']; ?></small>
                </td>
                <td><strong><?php echo $row['price']; ?> €</strong></td>
                <td>
                    <a href="productEdit?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="productDel?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php 
$content = ob_get_clean(); 
include "viewAdmin/templates/layout.php"; 
?>