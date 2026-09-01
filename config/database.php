<?php
/**
 * ============================================================================
 * SIM Akreditasi — Koneksi Database (PDO)
 * ============================================================================
 * Kelas Database menggunakan Singleton Pattern untuk memastikan hanya ada
 * satu instance koneksi PDO yang aktif selama siklus hidup request.
 * 
 * Fitur:
 * - Singleton Pattern (efisien, satu koneksi per request)
 * - PDO dengan prepared statements (aman dari SQL Injection)
 * - Error handling lengkap dengan try-catch
 * - Konfigurasi charset UTF8MB4
 * - Persistent connection opsional
 * ============================================================================
 */

class Database
{
    /**
     * Instance singleton
     * @var Database|null
     */
    private static ?Database $instance = null;

    /**
     * Koneksi PDO
     * @var PDO|null
     */
    private ?PDO $connection = null;

    /**
     * DSN (Data Source Name)
     * @var string
     */
    private string $dsn;

    /**
     * Username database
     * @var string
     */
    private string $username;

    /**
     * Password database
     * @var string
     */
    private string $password;

    /**
     * Opsi PDO
     * @var array
     */
    private array $options = [
        // Mode error: Exception (throw error untuk setiap masalah)
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

        // Fetch mode default: associative array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // Gunakan prepared statements native (bukan emulasi)
        PDO::ATTR_EMULATE_PREPARES   => false,

        // Persistent connection (reuse koneksi antar request)
        PDO::ATTR_PERSISTENT         => false,

        // Timeout koneksi 5 detik
        PDO::ATTR_TIMEOUT            => 5,
    ];

    /**
     * Constructor (private — gunakan getInstance())
     * 
     * Membuat koneksi PDO ke MySQL menggunakan konfigurasi dari config/app.php.
     * Jika koneksi gagal, akan menampilkan halaman error yang informatif.
     */
    private function __construct()
    {
        $this->dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );
        $this->username = DB_USER;
        $this->password = DB_PASS;

        try {
            $this->connection = new PDO(
                $this->dsn,
                $this->username,
                $this->password,
                $this->options
            );

            // Set MySQL-specific configurations
            $this->connection->exec("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        } catch (PDOException $e) {
            // Log error ke file
            $this->logError($e);

            // Tampilkan halaman error
            if (APP_DEBUG) {
                $this->showDebugError($e);
            } else {
                $this->showProductionError();
            }
            exit;
        }
    }

    /**
     * Mencegah cloning instance
     */
    private function __clone() {}

    /**
     * Mencegah unserialization instance
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * Mendapatkan instance Database (Singleton)
     * 
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Mendapatkan koneksi PDO
     * 
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Menjalankan query SELECT dan mengembalikan semua baris
     * 
     * @param string $sql Query SQL dengan placeholder
     * @param array $params Parameter untuk prepared statement
     * @return array Hasil query
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logError($e, $sql, $params);
            throw $e;
        }
    }

    /**
     * Menjalankan query SELECT dan mengembalikan satu baris
     * 
     * @param string $sql Query SQL dengan placeholder
     * @param array $params Parameter untuk prepared statement
     * @return array|false Hasil query atau false jika tidak ditemukan
     */
    public function fetch(string $sql, array $params = []): array|false
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError($e, $sql, $params);
            throw $e;
        }
    }

    /**
     * Menjalankan query INSERT/UPDATE/DELETE
     * 
     * @param string $sql Query SQL dengan placeholder
     * @param array $params Parameter untuk prepared statement
     * @return int Jumlah baris yang terpengaruh
     */
    public function execute(string $sql, array $params = []): int
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e, $sql, $params);
            throw $e;
        }
    }

    /**
     * Mendapatkan ID terakhir yang di-insert
     * 
     * @return string Last insert ID
     */
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Memulai transaksi database
     * 
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit transaksi database
     * 
     * @return bool
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Rollback transaksi database
     * 
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }

    /**
     * Menghitung jumlah baris dari query
     * 
     * @param string $sql Query SQL COUNT
     * @param array $params Parameter
     * @return int Jumlah baris
     */
    public function count(string $sql, array $params = []): int
    {
        $result = $this->fetch($sql, $params);
        return $result ? (int) reset($result) : 0;
    }

    /**
     * Mencatat error ke file log
     * 
     * @param PDOException $e Exception yang terjadi
     * @param string|null $sql Query yang gagal
     * @param array $params Parameter query
     */
    private function logError(PDOException $e, ?string $sql = null, array $params = []): void
    {
        $logDir = ROOT_PATH . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . '/database_error_' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $message = "[{$timestamp}] DATABASE ERROR\n";
        $message .= "  Message: {$e->getMessage()}\n";
        $message .= "  Code: {$e->getCode()}\n";
        if ($sql) {
            $message .= "  Query: {$sql}\n";
            $message .= "  Params: " . json_encode($params) . "\n";
        }
        $message .= "  File: {$e->getFile()}:{$e->getLine()}\n";
        $message .= "  Trace: {$e->getTraceAsString()}\n";
        $message .= str_repeat('-', 80) . "\n";

        file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
    }

    /**
     * Menampilkan halaman error detail (mode debug)
     * 
     * @param PDOException $e
     */
    private function showDebugError(PDOException $e): void
    {
        http_response_code(500);
        echo '<!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Database Error — SIM Akreditasi</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-950 text-white min-h-screen flex items-center justify-center p-4">
            <div class="max-w-2xl w-full">
                <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-8 backdrop-blur">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-red-400">Database Connection Error</h1>
                            <p class="text-gray-400 text-sm">Koneksi ke database gagal</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="bg-gray-900/50 rounded-xl p-4">
                            <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Error Message</p>
                            <p class="text-red-300 font-mono text-sm">' . htmlspecialchars($e->getMessage()) . '</p>
                        </div>
                        <div class="bg-gray-900/50 rounded-xl p-4">
                            <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Error Code</p>
                            <p class="text-amber-300 font-mono text-sm">' . htmlspecialchars($e->getCode()) . '</p>
                        </div>
                        <div class="bg-gray-900/50 rounded-xl p-4">
                            <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">File</p>
                            <p class="text-blue-300 font-mono text-sm">' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                        <p class="text-amber-300 text-sm"><strong>Tips:</strong> Pastikan MySQL/MariaDB sudah berjalan di XAMPP dan database <code class="bg-gray-800 px-1.5 py-0.5 rounded">akreditasiflow_db</code> sudah dibuat.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Menampilkan halaman error generik (mode production)
     */
    private function showProductionError(): void
    {
        http_response_code(500);
        echo '<!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error — SIM Akreditasi</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-950 text-white min-h-screen flex items-center justify-center p-4">
            <div class="text-center">
                <div class="w-20 h-20 bg-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Terjadi Kesalahan</h1>
                <p class="text-gray-400 mb-6">Maaf, sistem sedang mengalami gangguan. Silakan coba beberapa saat lagi.</p>
                <a href="' . BASE_URL . '" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </body>
        </html>';
    }

    /**
     * Menutup koneksi saat objek dihancurkan
     */
    public function __destruct()
    {
        $this->connection = null;
    }
}
