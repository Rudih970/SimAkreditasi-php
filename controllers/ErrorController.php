<?php
/**
 * ============================================================================
 * SIM Akreditasi — Error Controller
 * ============================================================================
 */

class ErrorController extends Controller
{
    public function notFound(): void
    {
        http_response_code(404);
        $this->view('errors.404', [
            'pageTitle'  => '404 Tidak Ditemukan',
            'activePage' => '',
        ]);
    }

    public function forbidden(): void
    {
        http_response_code(403);
        $this->view('errors.403', [
            'pageTitle'  => '403 Akses Ditolak',
            'activePage' => '',
        ]);
    }
}
