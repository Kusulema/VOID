<?php
ob_start();
?>
<article>
    <div id="main" class="container">
        <h3>Comments moderation</h3>
        <table style="width:100%;border-collapse:collapse;">
            <tr><th>ID</th><th>User</th><th>Text</th><th>Date</th><th>Approved</th><th>Actions</th></tr>
            <?php foreach ($arr as $c): ?>
                <tr style="border-top:1px solid #333;">
                    <td><?php echo htmlspecialchars($c['id']); ?></td>
                    <td><?php echo htmlspecialchars($c['user_id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($c['text']); ?></td>
                    <td><?php echo htmlspecialchars($c['date']); ?></td>
                    <td><?php echo empty($c['approved']) ? 'No' : 'Yes'; ?></td>
                    <td>
                        <a href="commentAction?id=<?php echo (int)$c['id']; ?>&action=approve">Approve</a> |
                        <a href="commentAction?id=<?php echo (int)$c['id']; ?>&action=deny">Deny</a> |
                        <a href="commentAction?id=<?php echo (int)$c['id']; ?>&action=delete" onclick="return confirm('Delete comment?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</article>
<?php
$content = ob_get_clean();
include "viewAdmin/templates/layout.php";
?>
