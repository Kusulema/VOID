<?php
/**
 * Simple database wrapper around PDO for the project.
 * Provides convenience methods for common queries and prepared statements.
 */
class database {
    private $conn;
    private $host;
    private $user;
    private $password;
    private $baseName;
    private $fallbackNames;

    /**
     * Construct database object and connect using configured credentials.
     */
    function __construct() {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->user = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
        $this->baseName = getenv('DB_NAME') ?: 'clothing_shop';
        $this->fallbackNames = $this->buildFallbackNames();
        try {
            $this->connect();
        } catch (Throwable $e) {
            $this->conn = null;
        }
    }

    /**
     * Build the list of database names to try in order.
     * Allows older dumps or renamed local databases to keep working.
     * @return string[]
     */
    private function buildFallbackNames(): array {
        $names = [$this->baseName, 'clothing_shop', 'olddata'];

        $extraNames = getenv('DB_FALLBACK_NAMES');
        if ($extraNames) {
            foreach (preg_split('/\s*,\s*/', $extraNames, -1, PREG_SPLIT_NO_EMPTY) as $name) {
                $names[] = $name;
            }
        }

        return array_values(array_unique(array_filter($names, static function ($name) {
            return is_string($name) && trim($name) !== '';
        })));
    }

    /**
     * Establish PDO connection (lazy if already connected).
     * @return PDO
     */
    function connect() {
        if (!$this->conn) {
            $errors = [];
            foreach ($this->fallbackNames as $databaseName) {
                try {
                    $this->conn = new PDO(
                        'mysql:host=' . $this->host . ';dbname=' . $databaseName,
                        $this->user,
                        $this->password,
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4')
                    );
                    $this->baseName = $databaseName;
                    return $this->conn;
                } catch (Throwable $e) {
                    $errors[] = $databaseName . ': ' . $e->getMessage();
                }
            }

            throw new RuntimeException('Connection failed : unable to open any configured database (' . implode('; ', $errors) . ')');
        }
        return $this->conn;
    }

    /**
     * Detect a transient or lost PDO connection error.
     */
    private function isConnectionDrop(Throwable $e): bool {
        $message = strtolower($e->getMessage());
        return str_contains($message, 'server has gone away')
            || str_contains($message, 'lost connection')
            || str_contains($message, 'server closed the connection')
            || str_contains($message, 'connection refused');
    }

    /**
     * Execute a callback with one reconnect retry if the connection dropped.
     * @param callable $callback
     * @param mixed $fallback
     * @return mixed
     */
    private function runWithReconnect(callable $callback, $fallback) {
        if (!$this->conn) {
            try {
                $this->connect();
            } catch (Throwable $e) {
                return $fallback;
            }
        }

        try {
            return $callback();
        } catch (PDOException $e) {
            if (!$this->isConnectionDrop($e)) {
                throw $e;
            }

            $this->disconnect();

            try {
                $this->connect();
            } catch (Throwable $connectionError) {
                return $fallback;
            }

            try {
                return $callback();
            } catch (PDOException $retryError) {
                if (!$this->isConnectionDrop($retryError)) {
                    throw $retryError;
                }
                return $fallback;
            }
        }
    }

    /**
     * Disconnect / destroy PDO connection.
     */
    function disconnect() { $this->conn = null; }

    /**
     * Execute a raw query and fetch single row.
     * Note: prefer prepared variants to avoid SQL injection.
     */
    function getOne($query) {
        return $this->runWithReconnect(function () use ($query) {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetch();
        }, null);
    }

    /**
     * Execute prepared query and fetch single row.
     */
    function getOnePrepared($query, $params = []) {
        return $this->runWithReconnect(function () use ($query, $params) {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetch();
        }, null);
    }

    /**
     * Execute raw query and fetch all rows.
     */
    function getAll($query) {
        return $this->runWithReconnect(function () use ($query) {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }, []);
    }

    /**
     * Execute prepared query and fetch all rows.
     */
    function getAllPrepared($query, $params = []) {
        return $this->runWithReconnect(function () use ($query, $params) {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }, []);
    }

    /**
     * Execute a non-parameterized statement (INSERT/UPDATE/DELETE).
     */
    function executeRun($query) {
        return $this->runWithReconnect(function () use ($query) {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute();
        }, false);
    }

    /**
     * Execute a prepared statement with parameters.
     */
    function executePrepared($query, $params = []) {
        return $this->runWithReconnect(function () use ($query, $params) {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        }, false);
    }

    /**
     * Check if a table has a specific column name.
     * @param string $table
     * @param string $column
     * @return bool
     */
    function hasColumn($table, $column) {
        $query = 'SHOW COLUMNS FROM `'.$table.'` LIKE :column';
        return (bool) $this->runWithReconnect(function () use ($query, $column) {
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['column' => $column]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }, false);
    }
}
?>