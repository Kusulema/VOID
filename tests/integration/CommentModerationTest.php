<?php
declare(strict_types=1);

final class CommentModerationTest extends VoidTestCase
{
    public function testNewCommentIsStoredAsPendingAndHiddenFromPublicFeed(): void
    {
        $this->asUserSession(2);

        $result = Comments::insertComment('Void is listening.', 1);

        $this->assertTrue((bool)$result);
        $comments = Database::table('comments');
        $this->assertCount(3, $comments);

        $newComment = $comments[2];
        $this->assertSame('Void is listening.', $newComment['text']);
        $this->assertSame(0, (int)$newComment['approved']);
        $this->assertSame(2, (int)$newComment['user_id']);

        $publicFeed = Comments::getCommentByNewsID(1);
        $texts = array_column($publicFeed, 'text');
        $this->assertNotContains('Void is listening.', $texts);
    }

    public function testAdminCanApproveCommentThroughControllerAction(): void
    {
        $this->asUserSession(2);
        Comments::insertComment('Please approve me.', 1);
        $commentId = Database::table('comments')[2]['id'];

        $this->asAdminSession();
        $_GET = ['id' => $commentId, 'action' => 'approve'];

        $this->captureOutput(static function (): void {
            controllerAdmin::commentAction();
        });

        $approvedComment = array_values(array_filter(Database::table('comments'), static fn (array $row): bool => (int)$row['id'] === $commentId))[0] ?? null;
        $this->assertNotNull($approvedComment);
        $this->assertSame(1, (int)$approvedComment['approved']);

        $publicFeed = Comments::getCommentByNewsID(1);
        $texts = array_column($publicFeed, 'text');
        $this->assertContains('Please approve me.', $texts);
    }

    public function testAdminCanDenyCommentThroughControllerAction(): void
    {
        $this->asUserSession(2);
        Comments::insertComment('Deny me.', 1);
        $commentId = Database::table('comments')[2]['id'];

        $this->asAdminSession();
        $_GET = ['id' => $commentId, 'action' => 'deny'];

        $this->captureOutput(static function (): void {
            controllerAdmin::commentAction();
        });

        $deniedComment = array_values(array_filter(Database::table('comments'), static fn (array $row): bool => (int)$row['id'] === $commentId))[0] ?? null;
        $this->assertNotNull($deniedComment);
        $this->assertSame(0, (int)$deniedComment['approved']);

        $publicFeed = Comments::getCommentByNewsID(1);
        $texts = array_column($publicFeed, 'text');
        $this->assertNotContains('Deny me.', $texts);
    }

    public function testAdminCanDeleteCommentThroughControllerAction(): void
    {
        $this->asUserSession(2);
        Comments::insertComment('Delete me.', 1);
        $commentId = Database::table('comments')[2]['id'];

        $this->asAdminSession();
        $_GET = ['id' => $commentId, 'action' => 'delete'];

        $this->captureOutput(static function (): void {
            controllerAdmin::commentAction();
        });

        $remainingIds = array_column(Database::table('comments'), 'id');
        $this->assertNotContains($commentId, $remainingIds);
    }

    public function testPendingCommentBecomesVisibleOnlyAfterApproval(): void
    {
        $this->asUserSession(2);
        Comments::insertComment('I should be public later.', 1);
        $commentId = Database::table('comments')[2]['id'];

        $beforeApproval = Comments::getLatestComments(10);
        $beforeTexts = array_column($beforeApproval, 'text');
        $this->assertNotContains('I should be public later.', $beforeTexts);

        $this->asAdminSession();
        $_GET = ['id' => $commentId, 'action' => 'approve'];

        $this->captureOutput(static function (): void {
            controllerAdmin::commentAction();
        });

        $afterApproval = Comments::getLatestComments(10);
        $afterTexts = array_column($afterApproval, 'text');
        $this->assertContains('I should be public later.', $afterTexts);
    }
}