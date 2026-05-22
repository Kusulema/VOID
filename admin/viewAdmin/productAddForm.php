<?php ob_start(); ?>
<div class="container">
    <div class="col-md-11">
        <h2>Add New Product</h2>
        <?php if(isset($test) && $test): ?>
            <div class="alert alert-info">Product added successfully! <a href="productAdmin">Back to list</a></div>
        <?php else: ?>
        <form method="POST" action="productAddResult" enctype="multipart/form-data">
            <table class="table table-bordered">
                <tr>
                    <td>Product title</td>
                    <td><input type="text" name="title" class="form-control" required></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name="description" class="form-control" rows="5" required></textarea></td>
                </tr>
                <tr>
                    <td>Title EN</td>
                    <td><input type="text" name="title_en" class="form-control" placeholder="Optional English title"></td>
                </tr>
                <tr>
                    <td>Description EN</td>
                    <td><textarea name="description_en" class="form-control" rows="4" placeholder="Optional English description"></textarea></td>
                </tr>
                <tr>
                    <td>Title RU</td>
                    <td><input type="text" name="title_ru" class="form-control" placeholder="Optional Russian title"></td>
                </tr>
                <tr>
                    <td>Description RU</td>
                    <td><textarea name="description_ru" class="form-control" rows="4" placeholder="Optional Russian description"></textarea></td>
                </tr>
                <tr>
                    <td>Title ET</td>
                    <td><input type="text" name="title_et" class="form-control" placeholder="Optional Estonian title"></td>
                </tr>
                <tr>
                    <td>Description ET</td>
                    <td><textarea name="description_et" class="form-control" rows="4" placeholder="Optional Estonian description"></textarea></td>
                </tr>
                <tr>
                    <td>Price (€)</td>
                    <td><input type="number" step="0.01" name="price" class="form-control" required></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select name="idCategory" class="form-control">
                            <?php foreach($arr as $row) echo "<option value='".$row['id']."'>".$row['name']."</option>"; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Product image</td>
                    <td><input type="file" name="picture" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                        <a href="productAdmin" class="btn btn-secondary">Back</a>
                    </td>
                </tr>
            </table>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php 
$content = ob_get_clean(); 
include "viewAdmin/templates/layout.php"; 
?>