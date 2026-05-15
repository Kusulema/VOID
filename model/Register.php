<?php
class Register{
    public static function registerUser() {
        $controll=array(0=>false,1=>'error');
        if (isset($_POST['save'])) {
            $errorString = "";
            $name = trim($_POST['name'] ?? '');
            $gender = trim($_POST['gender'] ?? 'unspecified');
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $errorString.="Неправильный email<br>";
            }
            if ($name === '') {
                $errorString.="Имя не может быть пустым<br>";
            }
            $allowedGenders = ['male', 'female', 'other', 'unspecified'];
            if (!in_array($gender, $allowedGenders, true)) {
                $errorString.="Некорректный пол пользователя<br>";
            }
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];
            if (!$password || !$confirm || mb_strlen($password) < 6) {
                $errorString.="Пароль должен быть больше 6 символов <br>";
            }

            if ($password != $confirm) {
                $errorString.="Пароли не совпадают<br>";
            }
            if (mb_strlen($errorString)==0 ) {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $legacyPassHash = $passwordHash;
                $date=Date("Y-m-d");
                $db = new Database();

                $login = strtolower((string) strstr((string) $email, '@', true));
                if ($login === '') {
                    $login = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $name));
                }

                $columns = [];
                $params = [];

                $appendColumn = static function (&$columns, &$params, $column, $value) {
                    $columns[] = '`' . $column . '`';
                    $params[':' . $column] = $value;
                };

                if ($db->hasColumn('users', 'username')) {
                    $appendColumn($columns, $params, 'username', $name);
                } elseif ($db->hasColumn('users', 'name')) {
                    $appendColumn($columns, $params, 'name', $name);
                }

                if ($db->hasColumn('users', 'gender')) {
                    $appendColumn($columns, $params, 'gender', $gender);
                }

                if ($db->hasColumn('users', 'picture')) {
                    $appendColumn($columns, $params, 'picture', null);
                }

                if ($db->hasColumn('users', 'job')) {
                    $appendColumn($columns, $params, 'job', '');
                }

                if ($db->hasColumn('users', 'email')) {
                    $appendColumn($columns, $params, 'email', $email);
                }

                if ($db->hasColumn('users', 'telefon')) {
                    $appendColumn($columns, $params, 'telefon', '');
                }

                if ($db->hasColumn('users', 'login')) {
                    $appendColumn($columns, $params, 'login', $login);
                }

                if ($db->hasColumn('users', 'password')) {
                    $appendColumn($columns, $params, 'password', $passwordHash);
                }

                if ($db->hasColumn('users', 'parol')) {
                    $appendColumn($columns, $params, 'parol', $passwordHash);
                }

                if ($db->hasColumn('users', 'pass')) {
                    $appendColumn($columns, $params, 'pass', $legacyPassHash);
                }

                if ($db->hasColumn('users', 'city')) {
                    $appendColumn($columns, $params, 'city', '');
                }

                if ($db->hasColumn('users', 'country')) {
                    $appendColumn($columns, $params, 'country', '');
                }

                if ($db->hasColumn('users', 'postcode')) {
                    $appendColumn($columns, $params, 'postcode', '');
                }

                if ($db->hasColumn('users', 'bank_account')) {
                    $appendColumn($columns, $params, 'bank_account', '');
                }

                if ($db->hasColumn('users', 'wishlist')) {
                    $appendColumn($columns, $params, 'wishlist', json_encode([]));
                }

                if ($db->hasColumn('users', 'status')) {
                    $appendColumn($columns, $params, 'status', 'user');
                }

                if ($db->hasColumn('users', 'registration_date')) {
                    $appendColumn($columns, $params, 'registration_date', $date);
                }

                $sql = 'INSERT INTO `users` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', array_keys($params)) . ')';
                $item = $db->executePrepared($sql, $params);
                    if($item)
                        $controll=array(0=>true);
                    else
                        $controll=array(0=>false,1=>'error');
            }else{
                $controll=array(0=>false,1=>$errorString);
            }
        }
        return $controll;
    }

    public static function loginUser() {
        $result = array(0=>false,1=>'error');
        if (isset($_POST['btnLogin'])) {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!$email || $password === '') {
                return array(0=>false,1=>'Введите email и пароль');
            }

            $db = new Database();
            $where = '`email` = :identifier';
            if ($db->hasColumn('users', 'login')) {
                $where = '(`email` = :identifier OR `login` = :identifier)';
            }

            $sql = 'SELECT * FROM `users` WHERE ' . $where . ' LIMIT 1';
            $item = $db->getOnePrepared($sql, ['identifier' => $email]);

            if ($item) {
                $storedHash = $item['password'] ?? ($item['parol'] ?? ($item['pass'] ?? ''));
                if ($storedHash !== '' && password_verify($password, $storedHash)) {
                    $_SESSION['sessionId'] = session_id();
                    $_SESSION['userId'] = $item['id'];
                    $_SESSION['name'] = $item['username'] ?? ($item['name'] ?? '');
                    $_SESSION['email'] = $item['email'] ?? '';
                    $_SESSION['status'] = $item['status'] ?? 'user';
                    $_SESSION['gender'] = $item['gender'] ?? 'unspecified';
                    $_SESSION['wishlist'] = $item['wishlist'] ?? json_encode([]);
                    return array(0=>true,1=>$item);
                }
            }

            return array(0=>false,1=>'Неправильный email или пароль');
        }

        return $result;
    }

    public static function logoutUser() {
        unset($_SESSION['sessionId']);
        unset($_SESSION['userId']);
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['status']);
        unset($_SESSION['gender']);
        session_destroy();
        return;
    }

    public static function getCurrentUser() {
        if (!isset($_SESSION['userId'])) {
            return null;
        }

        $db = new Database();
        $user = $db->getOnePrepared('SELECT * FROM `users` WHERE `id` = :id LIMIT 1', ['id' => (int) $_SESSION['userId']]);
        if ($user) {
            $_SESSION['wishlist'] = $user['wishlist'] ?? json_encode([]);
        }
        return $user;
    }

    public static function updateProfile() {
        if (!isset($_SESSION['userId'])) {
            return [0 => false, 1 => 'User session not found'];
        }

        $city = trim($_POST['city'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $postcode = trim($_POST['postcode'] ?? '');
        $bank_account = trim($_POST['bank_account'] ?? '');

        $db = new Database();
        $columns = [];
        $params = [];

        $appendColumn = static function (&$columns, &$params, $column, $value) {
            $columns[] = '`' . $column . '` = :' . $column;
            $params[':' . $column] = $value;
        };

        if ($db->hasColumn('users', 'city')) {
            $appendColumn($columns, $params, 'city', $city);
        }
        if ($db->hasColumn('users', 'country')) {
            $appendColumn($columns, $params, 'country', $country);
        }
        if ($db->hasColumn('users', 'postcode')) {
            $appendColumn($columns, $params, 'postcode', $postcode);
        }
        if ($db->hasColumn('users', 'bank_account')) {
            $appendColumn($columns, $params, 'bank_account', $bank_account);
        }

        if (empty($columns)) {
            return [0 => true, 1 => 'Nothing to update'];
        }

        $params[':id'] = (int)$_SESSION['userId'];
        $sql = 'UPDATE `users` SET ' . implode(', ', $columns) . ' WHERE `id` = :id';
        $updated = $db->executePrepared($sql, $params);

        if ($updated) {
            return [0 => true, 1 => 'Profile saved successfully.'];
        }

        return [0 => false, 1 => 'Failed to update profile.'];
    }

    public static function getWishlistIds($user = null) {
        $wishlist = [];
        if (is_array($user) && isset($user['wishlist'])) {
            $wishlist = json_decode($user['wishlist'] ?? '[]', true);
        } elseif (isset($_SESSION['wishlist'])) {
            $wishlist = json_decode($_SESSION['wishlist'] ?? '[]', true);
        }
        if (!is_array($wishlist)) {
            $wishlist = [];
        }
        return array_map('intval', array_values(array_unique($wishlist)));
    }

    public static function toggleWishlistItem($productId) {
        if (!isset($_SESSION['userId'])) {
            return false;
        }

        $db = new Database();
        $userId = (int)$_SESSION['userId'];
        if (!$db->hasColumn('users', 'wishlist')) {
            $db->executeRun('ALTER TABLE `users` ADD COLUMN `wishlist` TEXT NOT NULL DEFAULT \'[]\'');
        }

        $item = $db->getOnePrepared('SELECT `wishlist` FROM `users` WHERE `id` = :id LIMIT 1', ['id' => $userId]);
        $wishlist = json_decode($item['wishlist'] ?? '[]', true);
        if (!is_array($wishlist)) {
            $wishlist = [];
        }

        $productId = (int)$productId;
        if (($key = array_search($productId, $wishlist, true)) !== false) {
            unset($wishlist[$key]);
        } else {
            $wishlist[] = $productId;
        }

        $wishlist = array_values(array_unique($wishlist));
        $jsonWishlist = json_encode($wishlist);
        $db->executePrepared('UPDATE `users` SET `wishlist` = :wishlist WHERE `id` = :id', ['wishlist' => $jsonWishlist, 'id' => $userId]);
        $_SESSION['wishlist'] = $jsonWishlist;
        return $wishlist;
    }
}
?>