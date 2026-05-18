<?php
class Register{
    private static function validatePaymentFields(array $data) {
        $errors = [];
        $normalized = $data;

        $address = trim((string)($data['address'] ?? ''));
        $cardName = trim((string)($data['card_name'] ?? ''));
        $cardNumber = preg_replace('/\D+/', '', (string)($data['card_number'] ?? ''));
        $cardExpiry = trim((string)($data['card_expiry'] ?? ''));
        $cardCvv = preg_replace('/\D+/', '', (string)($data['card_cvv'] ?? ''));

        if ($address === '') {
            $errors['address'] = 'Delivery address is required.';
        }

        if ($cardName === '') {
            $errors['card_name'] = 'Cardholder name is required.';
        }

        if ($cardNumber === '') {
            $errors['card_number'] = 'Card number is required.';
        } elseif (!preg_match('/^\d{13,19}$/', $cardNumber)) {
            $errors['card_number'] = 'Card number must contain 13 to 19 digits.';
        }

        if ($cardExpiry === '') {
            $errors['card_expiry'] = 'Expiry date is required.';
        } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $cardExpiry)) {
            $errors['card_expiry'] = 'Expiry date must use MM/YY format.';
        }

        if ($cardCvv === '') {
            $errors['card_cvv'] = 'Security code is required.';
        } elseif (!preg_match('/^\d{3,4}$/', $cardCvv)) {
            $errors['card_cvv'] = 'Security code must contain 3 or 4 digits.';
        }

        if ($cardNumber !== '') {
            $normalized['card_number'] = $cardNumber;
        }
        if ($cardExpiry !== '') {
            $normalized['card_expiry'] = $cardExpiry;
        }
        if ($cardCvv !== '') {
            $normalized['card_cvv'] = $cardCvv;
        }
        if ($address !== '') {
            $normalized['address'] = $address;
        }
        if ($cardName !== '') {
            $normalized['card_name'] = $cardName;
        }

        return [empty($errors), $errors, $normalized];
    }

    public static function validateProfileForOrder(array $user) {
        return self::validatePaymentFields($user);
    }

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

        $gender = trim($_POST['gender'] ?? '');
        $location = trim($_POST['address'] ?? '');
        $card_name = trim($_POST['card_name'] ?? '');
        $card_number = trim($_POST['card_number'] ?? '');
        $card_expiry = trim($_POST['card_expiry'] ?? '');
        $card_cvv = trim($_POST['card_cvv'] ?? '');
        $bank_account = trim($_POST['bank_account'] ?? '');

        list($isValid, $validationErrors, $normalized) = self::validatePaymentFields([
            'address' => $location,
            'card_name' => $card_name,
            'card_number' => $card_number,
            'card_expiry' => $card_expiry,
            'card_cvv' => $card_cvv,
        ]);

        if (!$isValid) {
            return [0 => false, 1 => implode("\n", array_values($validationErrors))];
        }

        $location = $normalized['address'];
        $card_name = $normalized['card_name'];
        $card_number = $normalized['card_number'];
        $card_expiry = $normalized['card_expiry'];
        $card_cvv = $normalized['card_cvv'];

        $db = new Database();
        $ensureColumn = static function ($db, $column, $definition) {
            if (!$db->hasColumn('users', $column)) {
                $db->executeRun('ALTER TABLE `users` ADD COLUMN `' . $column . '` ' . $definition);
            }
        };

        $ensureColumn($db, 'address', "varchar(255) NOT NULL DEFAULT ''");
        $ensureColumn($db, 'card_name', "varchar(100) NOT NULL DEFAULT ''");
        $ensureColumn($db, 'card_number', "varchar(64) NOT NULL DEFAULT ''");
        $ensureColumn($db, 'card_expiry', "varchar(16) NOT NULL DEFAULT ''");
        $ensureColumn($db, 'card_cvv', "varchar(16) NOT NULL DEFAULT ''");

        $columns = [];
        $params = [];

        $appendColumn = static function (&$columns, &$params, $column, $value) {
            $columns[] = '`' . $column . '` = :' . $column;
            $params[':' . $column] = $value;
        };

        // Removed saving of full name per UI change (Full name field removed)
        if ($gender !== '' && in_array($gender, ['male', 'female', 'other'], true) && $db->hasColumn('users', 'gender')) {
            $appendColumn($columns, $params, 'gender', $gender);
        }

        if ($location !== '') {
            if ($db->hasColumn('users', 'address')) {
                $appendColumn($columns, $params, 'address', $location);
            } elseif ($db->hasColumn('users', 'city')) {
                $appendColumn($columns, $params, 'city', $location);
            }
            if ($db->hasColumn('users', 'country')) {
                $appendColumn($columns, $params, 'country', '');
            }
            if ($db->hasColumn('users', 'postcode')) {
                $appendColumn($columns, $params, 'postcode', '');
            }
        }

        if ($card_name !== '' && $db->hasColumn('users', 'card_name')) {
            $appendColumn($columns, $params, 'card_name', $card_name);
        }
        if ($card_number !== '' && $db->hasColumn('users', 'card_number')) {
            $appendColumn($columns, $params, 'card_number', $card_number);
        }
        if ($card_expiry !== '' && $db->hasColumn('users', 'card_expiry')) {
            $appendColumn($columns, $params, 'card_expiry', $card_expiry);
        }
        if ($card_cvv !== '' && $db->hasColumn('users', 'card_cvv')) {
            $appendColumn($columns, $params, 'card_cvv', $card_cvv);
        }

        if ($db->hasColumn('users', 'bank_account')) {
            $appendColumn($columns, $params, 'bank_account', $bank_account);
        }

        if (empty($columns)) {
            return [0 => true, 1 => ''];
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