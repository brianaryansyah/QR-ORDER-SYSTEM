<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }

        // 2. Cek apakah role user termasuk role yang diizinkan di Route.
        // Mendukung multi-role, misal: 'role:kasir,admin' => $arguments = ['kasir', 'admin']
        if ($arguments) {
            $allowed = array_map('trim', (array) $arguments);
            $role    = (string) session()->get('role');

            if (!in_array($role, $allowed, true)) {
                // Jika kasir mencoba akses halaman admin (atau sebaliknya),
                // tendang ke dashboard masing-masing. Fallback ke /auth bila role tak dikenal.
                if ($role === 'admin' || $role === 'kasir') {
                    return redirect()->to('/' . $role . '/dashboard');
                }

                return redirect()->to('/auth');
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
