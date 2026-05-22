<?php
declare(strict_types=1);

class Database
{
    public static array $tables = [];
    public static array $schema = [];
    public static array $history = [];
    private static array $nextIds = [];

    public function __construct()
    {
    }

    public static function reset(): void
    {
        self::$schema = [
            'category' => ['id', 'name'],
            'comments' => ['id', 'news_id', 'text', 'date', 'user_id', 'approved'],
            'product' => [
                'id', 'title', 'title_en', 'title_ru', 'title_et',
                'description', 'description_en', 'description_ru', 'description_et',
                'price', 'picture', 'category_id', 'user_id',
            ],
            'users' => [
                'id', 'username', 'email', 'password', 'status', 'gender', 'wishlist',
                'address', 'card_name', 'card_number', 'card_expiry', 'card_cvv',
                'bank_account', 'login', 'parol', 'pass', 'city', 'country', 'postcode', 'telefon',
            ],
        ];

        self::$tables = [
            'category' => [
                ['id' => 1, 'name' => 'Dead Souls (Men)'],
                ['id' => 2, 'name' => 'Dark Grace (Women)'],
                ['id' => 3, 'name' => 'Iron & Bone (Accessories)'],
            ],
            'users' => [
                [
                    'id' => 1,
                    'username' => 'voidadmin',
                    'email' => 'admin@void.com',
                    'password' => password_hash('adminpass', PASSWORD_DEFAULT),
                    'status' => 'admin',
                    'gender' => 'male',
                    'wishlist' => json_encode([6, 7]),
                    'address' => 'Tallinn',
                    'card_name' => 'Void Admin',
                    'card_number' => '4111111111111111',
                    'card_expiry' => '12/29',
                    'card_cvv' => '123',
                    'bank_account' => '',
                    'login' => 'voidadmin',
                    'parol' => password_hash('adminpass', PASSWORD_DEFAULT),
                    'pass' => password_hash('adminpass', PASSWORD_DEFAULT),
                    'city' => 'Tallinn',
                    'country' => 'EE',
                    'postcode' => '10111',
                    'telefon' => '',
                ],
                [
                    'id' => 2,
                    'username' => 'voiduser',
                    'email' => 'user@void.com',
                    'password' => password_hash('userpass', PASSWORD_DEFAULT),
                    'status' => 'user',
                    'gender' => 'female',
                    'wishlist' => json_encode([]),
                    'address' => '',
                    'card_name' => '',
                    'card_number' => '',
                    'card_expiry' => '',
                    'card_cvv' => '',
                    'bank_account' => '',
                    'login' => 'voiduser',
                    'parol' => password_hash('userpass', PASSWORD_DEFAULT),
                    'pass' => password_hash('userpass', PASSWORD_DEFAULT),
                    'city' => '',
                    'country' => '',
                    'postcode' => '',
                    'telefon' => '',
                ],
            ],
            'product' => [
                ['id' => 1, 'title' => 'T-Shirt "Shards"', 'title_en' => null, 'title_ru' => null, 'title_et' => null, 'description' => 'Torn edges, faded black. The smell of dusty roads and industrial sweat.', 'description_en' => null, 'description_ru' => null, 'description_et' => null, 'price' => 45.00, 'picture' => null, 'category_id' => 1, 'user_id' => 1],
                ['id' => 2, 'title' => 'Hoodie "Iron Cage"', 'title_en' => null, 'title_ru' => null, 'title_et' => null, 'description' => 'Heavy cotton, rough seams. Metal eyelets with a rusty coating.', 'description_en' => null, 'description_ru' => null, 'description_et' => null, 'price' => 89.00, 'picture' => null, 'category_id' => 1, 'user_id' => 1],
                ['id' => 3, 'title' => 'Jeans "Abyss"', 'title_en' => null, 'title_ru' => null, 'title_et' => null, 'description' => 'Denim thats tougher than your life. Ripped knees, welded hardware.', 'description_en' => null, 'description_ru' => null, 'description_et' => null, 'price' => 110.00, 'picture' => null, 'category_id' => 1, 'user_id' => 1],
                ['id' => 5, 'title' => 'Hoodie "Gloom"', 'title_en' => null, 'title_ru' => null, 'title_et' => null, 'description' => 'Oversized, all-concealing scent of iron and amber.', 'description_en' => null, 'description_ru' => null, 'description_et' => null, 'price' => 85.00, 'picture' => null, 'category_id' => 1, 'user_id' => 1],
                ['id' => 6, 'title' => 'Chain "Choke"', 'title_en' => null, 'title_ru' => null, 'title_et' => null, 'description' => 'Massive steel, raw links. Cold metal on your skin.', 'description_en' => null, 'description_ru' => null, 'description_et' => null, 'price' => 55.00, 'picture' => null, 'category_id' => 3, 'user_id' => 1],
                ['id' => 7, 'title' => 'Ring "Rusty Nail"', 'title_en' => null, 'title_ru' => null, 'title_et' => null, 'description' => 'Iron coiled into a spiral. Preserves the warmth of the forge and the taste of blood.', 'description_en' => null, 'description_ru' => null, 'description_et' => null, 'price' => 25.00, 'picture' => null, 'category_id' => 3, 'user_id' => 1],
                ['id' => 8, 'title' => 'Perfume "Forge & Flesh"', 'title_en' => null, 'title_ru' => null, 'title_et' => null, 'description' => 'The scent of a dying hearth. Heavy notes of molten iron, warm black amber, and a raw, metallic undertone that clings to the skin like a second wound.', 'description_en' => null, 'description_ru' => null, 'description_et' => null, 'price' => 150.00, 'picture' => null, 'category_id' => 2, 'user_id' => 1],
                ['id' => 9, 'title' => 'Wallet "Scrab"', 'title_en' => null, 'title_ru' => null, 'title_et' => null, 'description' => 'Thick, distressed matte black leather bound by raw steel rivets. Hardened by time, scarred by heavy use.', 'description_en' => null, 'description_ru' => null, 'description_et' => null, 'price' => 60.00, 'picture' => null, 'category_id' => 3, 'user_id' => 1],
            ],
            'comments' => [
                ['id' => 1, 'news_id' => 1, 'text' => 'Test comment from Copilot', 'date' => '2026-05-20 01:43:28', 'user_id' => 1, 'approved' => 1],
                ['id' => 2, 'news_id' => 0, 'text' => 'Home page review comment', 'date' => '2026-05-20 01:45:58', 'user_id' => 1, 'approved' => 0],
            ],
        ];

        self::$nextIds = [
            'category' => 4,
            'comments' => 3,
            'product' => 10,
            'users' => 3,
        ];

        self::$history = [];
    }

    public static function table(string $table): array
    {
        return self::$tables[$table] ?? [];
    }

    public static function history(): array
    {
        return self::$history;
    }

    public function disconnect()
    {
        return true;
    }

    public function connect()
    {
        return true;
    }

    public function getOne($query)
    {
        self::$history[] = ['method' => 'getOne', 'query' => $query, 'params' => []];
        $rows = $this->selectRows($query, [], true);
        return $rows[0] ?? null;
    }

    public function getOnePrepared($query, $params = [])
    {
        self::$history[] = ['method' => 'getOnePrepared', 'query' => $query, 'params' => $params];
        $rows = $this->selectRows($query, $params, true);
        return $rows[0] ?? null;
    }

    public function getAll($query)
    {
        self::$history[] = ['method' => 'getAll', 'query' => $query, 'params' => []];
        return $this->selectRows($query, [], false);
    }

    public function getAllPrepared($query, $params = [])
    {
        self::$history[] = ['method' => 'getAllPrepared', 'query' => $query, 'params' => $params];
        return $this->selectRows($query, $params, false);
    }

    public function executeRun($query)
    {
        self::$history[] = ['method' => 'executeRun', 'query' => $query, 'params' => []];

        if (preg_match('/ALTER TABLE `(?P<table>[^`]+)` ADD COLUMN `(?P<column>[^`]+)`/i', $query, $matches)) {
            self::$schema[$matches['table']] = self::$schema[$matches['table']] ?? ['id'];
            if (!in_array($matches['column'], self::$schema[$matches['table']], true)) {
                self::$schema[$matches['table']][] = $matches['column'];
            }
        }

        if (preg_match('/CREATE TABLE IF NOT EXISTS `(?P<table>[^`]+)`/i', $query, $matches)) {
            self::$schema[$matches['table']] = self::$schema[$matches['table']] ?? [];
        }

        return true;
    }

    public function executePrepared($query, $params = [])
    {
        self::$history[] = ['method' => 'executePrepared', 'query' => $query, 'params' => $params];
        $normalized = preg_replace('/\s+/', ' ', trim($query));

        if (str_starts_with($normalized, 'INSERT INTO `comments`')) {
            $this->insertCommentRow($params);
            return true;
        }

        if (str_contains($normalized, 'UPDATE `comments` SET `approved` = :a WHERE `id` = :id')) {
            $this->updateCommentApproval((int)($params['id'] ?? $params[':id'] ?? 0), (int)($params['a'] ?? $params[':a'] ?? 0));
            return true;
        }

        if (str_contains($normalized, 'DELETE FROM `comments` WHERE `id` = :id')) {
            $this->deleteRow('comments', (int)($params['id'] ?? $params[':id'] ?? 0));
            return true;
        }

        if (str_starts_with($normalized, 'INSERT INTO `product`')) {
            $this->insertProductRow($params);
            return true;
        }

        if (str_starts_with($normalized, 'UPDATE `product` SET')) {
            $this->updateProductRow($params);
            return true;
        }

        if (str_contains($normalized, 'DELETE FROM `product` WHERE `id` = :id')) {
            $this->deleteRow('product', (int)($params['id'] ?? $params[':id'] ?? 0));
            return true;
        }

        if (str_starts_with($normalized, 'INSERT INTO `users`')) {
            $this->insertUserRow($params);
            return true;
        }

        if (str_starts_with($normalized, 'UPDATE `users` SET')) {
            $this->updateUserRow($params);
            return true;
        }

        return true;
    }

    public function hasColumn($table, $column)
    {
        self::$history[] = ['method' => 'hasColumn', 'query' => $table . '.' . $column, 'params' => []];
        return in_array($column, self::$schema[$table] ?? [], true);
    }

    private function selectRows(string $query, array $params, bool $single): array
    {
        $normalized = preg_replace('/\s+/', ' ', trim($query));

        if (str_contains($normalized, 'SELECT product.*, category.name, users.username FROM product JOIN category ON product.category_id = category.id JOIN users ON product.user_id = users.id ORDER BY product.id DESC')) {
            return $this->joinProductRows();
        }

        if (preg_match('/SELECT .* FROM product WHERE category_id\s*=\s*(\d+) ORDER BY id DESC/i', $normalized, $matches)) {
            return $this->filterProductsByCategory((int)$matches[1]);
        }

        if (preg_match('/SELECT \* FROM product WHERE id\s*=\s*(\d+)/i', $normalized, $matches)) {
            $row = $this->findRow('product', (int)$matches[1]);
            return $row ? [$row] : [];
        }

        if (preg_match('/SELECT id, title, title_en, title_ru, title_et, description, description_en, description_ru, description_et, price, category_id, user_id, picture FROM product ORDER BY id DESC/i', $normalized)) {
            return $this->sortTable('product', true);
        }

        if (preg_match('/SELECT .* FROM product ORDER BY id DESC/i', $normalized)) {
            return $this->sortTable('product', true);
        }

        if (preg_match('/SELECT .* FROM product WHERE id IN \((.+)\) ORDER BY FIELD\(id, (.+)\)/i', $normalized)) {
            $ids = array_values(array_map('intval', array_slice(array_values($params), 0, (int)(count($params) / 2))));
            $ordered = [];
            foreach ($ids as $id) {
                $row = $this->findRow('product', $id);
                if ($row) {
                    $ordered[] = $row;
                }
            }
            return $ordered;
        }

        if (preg_match('/SELECT \* FROM comments WHERE approved = 1 ORDER BY id DESC LIMIT (\d+)/i', $normalized, $matches)) {
            return array_slice($this->filterComments(fn (array $row) => (int)($row['approved'] ?? 0) === 1, true), 0, (int)$matches[1]);
        }

        if (preg_match('/SELECT \* FROM comments WHERE news_id=(\d+) AND approved = 1 ORDER BY id DESC/i', $normalized, $matches)) {
            return $this->filterComments(fn (array $row) => (int)($row['news_id'] ?? 0) === (int)$matches[1] && (int)($row['approved'] ?? 0) === 1, true);
        }

        if (preg_match('/SELECT count\(id\) as \'count\' FROM comments WHERE news_id=(\d+) AND approved = 1/i', $normalized, $matches)) {
            $count = count($this->filterComments(fn (array $row) => (int)($row['news_id'] ?? 0) === (int)$matches[1] && (int)($row['approved'] ?? 0) === 1, false));
            return [['count' => $count]];
        }

        if (str_contains($normalized, 'SELECT * FROM comments ORDER BY id DESC')) {
            return $this->sortTable('comments', true);
        }

        if (preg_match('/SELECT \* FROM comments WHERE approved = 1 ORDER BY id DESC/i', $normalized)) {
            return $this->filterComments(fn (array $row) => (int)($row['approved'] ?? 0) === 1, true);
        }

        if (preg_match('/SELECT \* FROM category ORDER BY category\.name ASC/i', $normalized)) {
            $rows = self::$tables['category'] ?? [];
            usort($rows, static fn (array $a, array $b): int => strcmp((string)($a['name'] ?? ''), (string)($b['name'] ?? '')));
            return $rows;
        }

        if (preg_match('/SELECT \* FROM category$/i', $normalized)) {
            return self::$tables['category'] ?? [];
        }

        if (preg_match('/SELECT \* FROM `users` WHERE `id` = :id LIMIT 1/i', $normalized)) {
            $row = $this->findUser((int)($params['id'] ?? $params[':id'] ?? 0));
            return $row ? [$row] : [];
        }

        if (preg_match('/SELECT `wishlist` FROM `users` WHERE `id` = :id LIMIT 1/i', $normalized)) {
            $row = $this->findUser((int)($params['id'] ?? $params[':id'] ?? 0));
            return $row ? [['wishlist' => $row['wishlist'] ?? '[]']] : [];
        }

        if (preg_match('/SELECT \* FROM `users` WHERE `email` = :identifier OR `login` = :identifier LIMIT 1/i', $normalized)) {
            $identifier = (string)($params['identifier'] ?? $params[':identifier'] ?? '');
            $row = $this->findUserByIdentifier($identifier);
            return $row ? [$row] : [];
        }

        if (preg_match('/SELECT \* FROM `users` WHERE `email` = :identifier LIMIT 1/i', $normalized)) {
            $identifier = (string)($params['identifier'] ?? $params[':identifier'] ?? '');
            $row = $this->findUserByIdentifier($identifier);
            return $row ? [$row] : [];
        }

        if (preg_match('/SELECT \* FROM product WHERE id = :id/i', $normalized)) {
            $row = $this->findRow('product', (int)($params['id'] ?? $params[':id'] ?? 0));
            return $row ? [$row] : [];
        }

        if (preg_match('/SELECT \* FROM comments WHERE news_id=(\d+) AND approved = 1 ORDER BY id DESC/i', $normalized, $matches)) {
            return $this->filterComments(fn (array $row) => (int)($row['news_id'] ?? 0) === (int)$matches[1] && (int)($row['approved'] ?? 0) === 1, true);
        }

        if (preg_match('/SELECT count\(id\) as \'count\' FROM comments WHERE news_id = :news_id AND approved = 1/i', $normalized)) {
            $count = count($this->filterComments(fn (array $row) => (int)($row['news_id'] ?? 0) === (int)($params['news_id'] ?? $params[':news_id'] ?? 0) && (int)($row['approved'] ?? 0) === 1, false));
            return [['count' => $count]];
        }

        return $single ? [] : [];
    }

    private function sortTable(string $table, bool $desc): array
    {
        $rows = self::$tables[$table] ?? [];
        usort($rows, static function (array $a, array $b) use ($desc): int {
            $cmp = (int)($a['id'] ?? 0) <=> (int)($b['id'] ?? 0);
            return $desc ? -$cmp : $cmp;
        });
        return $rows;
    }

    private function filterComments(callable $predicate, bool $desc): array
    {
        $rows = array_values(array_filter(self::$tables['comments'] ?? [], $predicate));
        usort($rows, static function (array $a, array $b) use ($desc): int {
            $cmp = (int)($a['id'] ?? 0) <=> (int)($b['id'] ?? 0);
            return $desc ? -$cmp : $cmp;
        });
        return $rows;
    }

    private function filterProductsByCategory(int $categoryId): array
    {
        $rows = array_values(array_filter(self::$tables['product'] ?? [], static fn (array $row): bool => (int)($row['category_id'] ?? 0) === $categoryId));
        usort($rows, static fn (array $a, array $b): int => (int)($b['id'] ?? 0) <=> (int)($a['id'] ?? 0));
        return $rows;
    }

    private function joinProductRows(): array
    {
        $rows = $this->sortTable('product', true);
        $categories = [];
        foreach (self::$tables['category'] ?? [] as $row) {
            $categories[(int)$row['id']] = $row;
        }
        $users = [];
        foreach (self::$tables['users'] ?? [] as $row) {
            $users[(int)$row['id']] = $row;
        }

        $joined = [];
        foreach ($rows as $row) {
            $category = $categories[(int)($row['category_id'] ?? 0)] ?? [];
            $user = $users[(int)($row['user_id'] ?? 0)] ?? [];
            $joined[] = $row + [
                'name' => $category['name'] ?? '',
                'username' => $user['username'] ?? '',
            ];
        }

        return $joined;
    }

    private function findRow(string $table, int $id): ?array
    {
        foreach (self::$tables[$table] ?? [] as $row) {
            if ((int)($row['id'] ?? 0) === $id) {
                return $row;
            }
        }

        return null;
    }

    private function findUser(int $id): ?array
    {
        return $this->findRow('users', $id);
    }

    private function findUserByIdentifier(string $identifier): ?array
    {
        foreach (self::$tables['users'] ?? [] as $row) {
            if (strcasecmp((string)($row['email'] ?? ''), $identifier) === 0 || strcasecmp((string)($row['login'] ?? ''), $identifier) === 0) {
                return $row;
            }
        }

        return null;
    }

    private function insertCommentRow(array $params): void
    {
        $row = [
            'id' => self::$nextIds['comments']++,
            'news_id' => (int)($params[':news'] ?? $params['news'] ?? 0),
            'text' => (string)($params[':text'] ?? $params['text'] ?? ''),
            'date' => date('Y-m-d H:i:s'),
            'user_id' => (int)($params[':user'] ?? $params['user'] ?? 0),
            'approved' => 0,
        ];

        self::$tables['comments'][] = $row;
    }

    private function updateCommentApproval(int $id, int $approved): void
    {
        foreach (self::$tables['comments'] as &$row) {
            if ((int)($row['id'] ?? 0) === $id) {
                $row['approved'] = $approved;
                break;
            }
        }
        unset($row);
    }

    private function insertProductRow(array $params): void
    {
        $row = [
            'id' => self::$nextIds['product']++,
            'title' => (string)($params[':title'] ?? $params['title'] ?? ''),
            'title_en' => $params[':title_en'] ?? null,
            'title_ru' => $params[':title_ru'] ?? null,
            'title_et' => $params[':title_et'] ?? null,
            'description' => (string)($params[':description'] ?? $params['description'] ?? ''),
            'description_en' => $params[':description_en'] ?? null,
            'description_ru' => $params[':description_ru'] ?? null,
            'description_et' => $params[':description_et'] ?? null,
            'price' => (float)($params[':price'] ?? $params['price'] ?? 0),
            'picture' => $params[':picture'] ?? $params['picture'] ?? null,
            'category_id' => (int)($params[':category_id'] ?? $params['category_id'] ?? 0),
            'user_id' => (int)($params[':user_id'] ?? $params['user_id'] ?? 0),
        ];

        self::$tables['product'][] = $row;
    }

    private function updateProductRow(array $params): void
    {
        $id = (int)($params[':id'] ?? $params['id'] ?? 0);
        foreach (self::$tables['product'] as &$row) {
            if ((int)($row['id'] ?? 0) !== $id) {
                continue;
            }

            foreach ([
                'title', 'description', 'price', 'category_id', 'title_en', 'description_en',
                'title_ru', 'description_ru', 'title_et', 'description_et', 'picture',
            ] as $field) {
                $colonKey = ':' . $field;
                if (array_key_exists($colonKey, $params)) {
                    $row[$field] = $params[$colonKey];
                }
            }

            if (array_key_exists(':picture', $params)) {
                $row['picture'] = $params[':picture'];
            }

            break;
        }
        unset($row);
    }

    private function deleteRow(string $table, int $id): void
    {
        self::$tables[$table] = array_values(array_filter(self::$tables[$table] ?? [], static fn (array $row): bool => (int)($row['id'] ?? 0) !== $id));
    }

    private function insertUserRow(array $params): void
    {
        $row = [
            'id' => self::$nextIds['users']++,
            'username' => (string)($params[':username'] ?? $params['username'] ?? ''),
            'name' => (string)($params[':name'] ?? $params['name'] ?? ''),
            'gender' => (string)($params[':gender'] ?? $params['gender'] ?? 'unspecified'),
            'picture' => $params[':picture'] ?? null,
            'job' => (string)($params[':job'] ?? ''),
            'email' => (string)($params[':email'] ?? $params['email'] ?? ''),
            'telefon' => (string)($params[':telefon'] ?? ''),
            'login' => (string)($params[':login'] ?? $params['login'] ?? ''),
            'password' => (string)($params[':password'] ?? $params['password'] ?? ''),
            'parol' => (string)($params[':parol'] ?? $params['parol'] ?? ''),
            'pass' => (string)($params[':pass'] ?? $params['pass'] ?? ''),
            'city' => (string)($params[':city'] ?? ''),
            'country' => (string)($params[':country'] ?? ''),
            'postcode' => (string)($params[':postcode'] ?? ''),
            'bank_account' => (string)($params[':bank_account'] ?? ''),
            'wishlist' => (string)($params[':wishlist'] ?? '[]'),
            'status' => (string)($params[':status'] ?? 'user'),
            'registration_date' => (string)($params[':registration_date'] ?? date('Y-m-d')),
            'address' => (string)($params[':address'] ?? ''),
            'card_name' => (string)($params[':card_name'] ?? ''),
            'card_number' => (string)($params[':card_number'] ?? ''),
            'card_expiry' => (string)($params[':card_expiry'] ?? ''),
            'card_cvv' => (string)($params[':card_cvv'] ?? ''),
        ];

        self::$tables['users'][] = $row;
    }

    private function updateUserRow(array $params): void
    {
        $id = (int)($params[':id'] ?? $params['id'] ?? 0);
        foreach (self::$tables['users'] as &$row) {
            if ((int)($row['id'] ?? 0) !== $id) {
                continue;
            }

            foreach ($params as $key => $value) {
                if ($key === ':id' || $key === 'id') {
                    continue;
                }

                $field = ltrim((string)$key, ':');
                $row[$field] = $value;
            }

            break;
        }
        unset($row);
    }
}

Database::reset();