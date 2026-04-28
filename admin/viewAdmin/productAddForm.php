<?php ob_start(); ?>
<div class="container">
    <div class="col-md-11">
        <h2>Добавить новый товар</h2>
        <?php if(isset($test) && $test): ?>
            <div class="alert alert-info">Товар успешно добавлен! <a href="productAdmin">К списку</a></div>
        <?php else: ?>
        <form method="POST" action="productAddResult" enctype="multipart/form-data">
            <table class="table table-bordered">
                <tr>
                    <td>Название товара</td>
                    <td><input type="text" name="title" class="form-control" required></td>
                </tr>
                <tr>
                    <td>Описание</td>
                    <td><textarea name="description" class="form-control" rows="5" required></textarea></td>
                </tr>
                <tr>
                    <td>Цена (€)</td>
                    <td><input type="number" step="0.01" name="price" class="form-control" required></td>
                </tr>
                <tr>
                    <td>Категория</td>
                    <td>
                        <select name="idCategory" class="form-control">
                            <?php foreach($arr as $row) echo "<option value='".$row['id']."'>".$row['name']."</option>"; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Фото товара</td>
                    <td><input type="file" name="picture" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="save" class="btn btn-primary">Сохранить</button>
                        <a href="productAdmin" class="btn btn-secondary">Назад</a>
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