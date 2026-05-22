<?php
ob_start();
?>

<div class="container" style="min-height:400px;">
    <div class="col-md-11">
        <h2>Edit Product</h2>
        <?php
        if(isset($test)) {
            if($test == true)
            {
        ?>
        <div class="alert alert-info">
            <strong>Product updated.</strong><a href="productAdmin">Back to list</a>
        </div>
        <?php
            } else if($test == false) {
        ?>
        <div class="alert alert-warning">
            <strong>Failed to update product.</strong><a href="productAdmin">Back to list</a>
        </div>
        <?php
            }
        } else {
        ?>
        <form method="POST" action="productEditResult?id=<?php echo $id; ?>" enctype="multipart/form-data">
            <table class="table table-bordered">
                <tr>
                    <td>Product title</td>
                    <td><input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($detail['title'] ?? '', ENT_QUOTES); ?>"></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea rows="5" name="description" class="form-control" required><?php echo htmlspecialchars($detail['description'] ?? $detail['text'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <td>Title EN</td>
                    <td><input type="text" name="title_en" class="form-control" value="<?php echo htmlspecialchars($detail['title_en'] ?? '', ENT_QUOTES); ?>"></td>
                </tr>
                <tr>
                    <td>Description EN</td>
                    <td><textarea rows="4" name="description_en" class="form-control"><?php echo htmlspecialchars($detail['description_en'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <td>Title RU</td>
                    <td><input type="text" name="title_ru" class="form-control" value="<?php echo htmlspecialchars($detail['title_ru'] ?? '', ENT_QUOTES); ?>"></td>
                </tr>
                <tr>
                    <td>Description RU</td>
                    <td><textarea rows="4" name="description_ru" class="form-control"><?php echo htmlspecialchars($detail['description_ru'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <td>Title ET</td>
                    <td><input type="text" name="title_et" class="form-control" value="<?php echo htmlspecialchars($detail['title_et'] ?? '', ENT_QUOTES); ?>"></td>
                </tr>
                <tr>
                    <td>Description ET</td>
                    <td><textarea rows="4" name="description_et" class="form-control"><?php echo htmlspecialchars($detail['description_et'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <td>Price (€)</td>
                    <td><input type="number" step="0.01" name="price" class="form-control" required value="<?php echo htmlspecialchars((string)($detail['price'] ?? ''), ENT_QUOTES); ?>"></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select name="idCategory" class="form-control">
                            <?php
                            foreach($arr as $row) {
                                echo '<option value="'.$row['id'].'"';
                                    if((int)$row['id'] === (int)($detail['category_id'] ?? 0)) echo ' selected';
                                echo '>'.$row['name'].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                  <tr>
                    <td>Current image</td>
                    <td>
                        <div>
                            <?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $detail['picture']).'" width=150>'; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>New image</td>
                    <td>
                        <div>
                            <input type=file name="picture" style="color:black;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" class="btn btn-primary" name="save">
                            <span class="glyphicon glyphicon-plus"></span> Save changes
                        </button>
                        <a href="productAdmin" class="btn btn-large btn-success">
                            <i class="glyphicon glyphicon-backward"></i> &nbsp;Back to list
                        </a>
                    </td>
                </tr>
            </table>
        </form>
        <?php
        }
        ?>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include "viewAdmin/templates/layout.php"; ?>