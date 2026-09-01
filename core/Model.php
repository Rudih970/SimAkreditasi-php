<?php
/**
 * ============================================================================
 * SIM Akreditasi — Base Model
 * ============================================================================
 * Kelas dasar untuk semua model. Menyediakan CRUD operations dan
 * query builder sederhana menggunakan PDO prepared statements.
 * ============================================================================
 */

class Model
{
    /**
     * Instance Database
     * @var Database
     */
    protected Database $db;

    /**
     * Koneksi PDO
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * Nama tabel (override di child class)
     * @var string
     */
    protected string $table = '';

    /**
     * Primary key kolom
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Kolom yang bisa diisi (mass assignment protection)
     * @var array
     */
    protected array $fillable = [];

    /**
     * Constructor — Inisialisasi koneksi database
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    /**
     * Ambil semua data dari tabel
     * 
     * @param string $orderBy Kolom untuk sorting
     * @param string $direction ASC atau DESC
     * @return array
     */
    public function all(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `{$orderBy}` {$direction}";
        return $this->db->fetchAll($sql);
    }

    /**
     * Cari data berdasarkan ID
     * 
     * @param int $id
     * @return array|false
     */
    public function find(int $id): array|false
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    /**
     * Cari data berdasarkan kondisi WHERE
     * 
     * @param string $column Nama kolom
     * @param mixed $value Nilai
     * @return array|false Satu baris data
     */
    public function findBy(string $column, mixed $value): array|false
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$column}` = :value LIMIT 1";
        return $this->db->fetch($sql, ['value' => $value]);
    }

    /**
     * Cari semua data berdasarkan kondisi WHERE
     * 
     * @param string $column Nama kolom
     * @param mixed $value Nilai
     * @param string $orderBy Kolom sorting
     * @param string $direction ASC/DESC
     * @return array
     */
    public function where(string $column, mixed $value, string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$column}` = :value ORDER BY `{$orderBy}` {$direction}";
        return $this->db->fetchAll($sql, ['value' => $value]);
    }

    /**
     * Insert data baru ke tabel
     * 
     * @param array $data Associative array [kolom => nilai]
     * @return string|false Last insert ID atau false
     */
    public function create(array $data): string|false
    {
        // Filter hanya kolom yang diizinkan
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":{$col}", $columns);

        $sql = sprintf(
            "INSERT INTO `%s` (`%s`) VALUES (%s)",
            $this->table,
            implode('`, `', $columns),
            implode(', ', $placeholders)
        );

        $affected = $this->db->execute($sql, $data);
        return $affected > 0 ? $this->db->lastInsertId() : false;
    }

    /**
     * Update data berdasarkan ID
     * 
     * @param int $id
     * @param array $data Associative array [kolom => nilai]
     * @return int Jumlah baris yang diupdate
     */
    public function update(int $id, array $data): int
    {
        // Filter hanya kolom yang diizinkan
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        $setParts = array_map(fn($col) => "`{$col}` = :{$col}", array_keys($data));

        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE `%s` = :_id",
            $this->table,
            implode(', ', $setParts),
            $this->primaryKey
        );

        $data['_id'] = $id;
        return $this->db->execute($sql, $data);
    }

    /**
     * Hapus data berdasarkan ID
     * 
     * @param int $id
     * @return int Jumlah baris yang dihapus
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }

    /**
     * Hitung jumlah seluruh data
     * 
     * @param string|null $whereColumn Kolom filter (opsional)
     * @param mixed $whereValue Nilai filter
     * @return int
     */
    public function countAll(?string $whereColumn = null, mixed $whereValue = null): int
    {
        if ($whereColumn) {
            $sql = "SELECT COUNT(*) FROM `{$this->table}` WHERE `{$whereColumn}` = :value";
            return $this->db->count($sql, ['value' => $whereValue]);
        }
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        return $this->db->count($sql);
    }

    /**
     * Paginasi data
     * 
     * @param int $page Halaman (1-indexed)
     * @param int $perPage Jumlah per halaman
     * @param string $orderBy Kolom sorting
     * @param string $direction ASC/DESC
     * @return array ['data' => [...], 'total' => int, 'pages' => int, 'current' => int]
     */
    public function paginate(int $page = 1, int $perPage = 10, string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $total = $this->countAll();
        $pages = (int) ceil($total / $perPage);

        $sql = "SELECT * FROM `{$this->table}` ORDER BY `{$orderBy}` {$direction} LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->db->fetchAll($sql);

        return [
            'data'    => $data,
            'total'   => $total,
            'pages'   => $pages,
            'current' => $page,
            'perPage' => $perPage,
        ];
    }

    /**
     * Jalankan custom query
     * 
     * @param string $sql Query SQL
     * @param array $params Parameter
     * @return array
     */
    public function raw(string $sql, array $params = []): array
    {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Jalankan custom query untuk satu baris
     * 
     * @param string $sql Query SQL
     * @param array $params Parameter
     * @return array|false
     */
    public function rawOne(string $sql, array $params = []): array|false
    {
        return $this->db->fetch($sql, $params);
    }
}
