<?php
/**
 * ============================================================================
 * SIM Akreditasi — Core Application (Router & Dispatcher)
 * ============================================================================
 * Kelas utama yang menangani routing URL ke controller dan method yang sesuai.
 * 
 * Format URL: BASE_URL/controller/method/param1/param2
 * Contoh: /SimAkreditasi/pengajuan/detail/1
 * ============================================================================
 */

class App
{
    /**
     * Nama class controller
     * @var string
     */
    protected string $controllerName = 'DashboardController';

    /**
     * Instance controller
     * @var object|null
     */
    protected ?object $controllerInstance = null;

    /**
     * Method default
     * @var string
     */
    protected string $method = 'index';

    /**
     * Parameter URL
     * @var array
     */
    protected array $params = [];

    /**
     * Mapping URL segment ke nama controller
     * @var array
     */
    protected array $controllerMap = [
        ''              => 'DashboardController',
        'dashboard'     => 'DashboardController',
        'auth'          => 'AuthController',
        'users'         => 'UserController',
        'program-studi' => 'ProdiController',
        'instrumen'     => 'InstrumenController',
        'pengajuan'     => 'PengajuanController',
        'borang'        => 'BorangController',
        'review'        => 'ReviewController',
        'jadwal'        => 'JadwalController',
        'riwayat'       => 'RiwayatController',
        'laporan'       => 'LaporanController',
        'notifikasi'    => 'NotifikasiController',
        'activity-log'  => 'ActivityLogController',
        'profile'       => 'ProfileController',
        'settings'      => 'SettingsController',
    ];

    /**
     * Constructor — Parse URL dan dispatch ke controller
     */
    public function __construct()
    {
        $url = $this->parseUrl();

        // Tentukan controller dari URL segment pertama
        $segment = $url[0] ?? '';
        
        if (isset($this->controllerMap[$segment])) {
            $this->controllerName = $this->controllerMap[$segment];
            unset($url[0]);
        } else {
            // Controller tidak ditemukan → 404
            $this->controllerName = 'ErrorController';
            $this->method = 'notFound';
        }

        // Load file controller
        $controllerFile = ROOT_PATH . '/controllers/' . $this->controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $className = $this->controllerName;
            $this->controllerInstance = new $className();
        } else {
            // Fallback: tampilkan 404 jika file tidak ada
            require_once ROOT_PATH . '/controllers/ErrorController.php';
            $this->controllerInstance = new ErrorController();
            $this->method = 'notFound';
        }

        // Tentukan method dari URL segment kedua
        if (isset($url[1])) {
            $methodName = $this->dashToCamel($url[1]);
            if (method_exists($this->controllerInstance, $methodName)) {
                $this->method = $methodName;
                unset($url[1]);
            }
        }

        // Sisa URL segment menjadi parameter
        $this->params = $url ? array_values($url) : [];

        // Panggil controller→method(params)
        call_user_func_array([$this->controllerInstance, $this->method], $this->params);
    }

    /**
     * Parse URL menjadi array segment
     * 
     * @return array
     */
    protected function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }

    /**
     * Konversi dash-case ke camelCase
     * Contoh: "tambah-baru" → "tambahBaru"
     * 
     * @param string $string
     * @return string
     */
    protected function dashToCamel(string $string): string
    {
        return lcfirst(str_replace('-', '', ucwords($string, '-')));
    }
}
