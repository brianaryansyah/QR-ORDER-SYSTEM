<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $users = $this->userModel
            ->orderBy('role', 'ASC')
            ->orderBy('nama_lengkap', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Manajemen Akun — NasPad Zendry',
            'users' => $users,
        ];

        return view('admin/users', $data);
    }

    public function store()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[2]|max_length[100]',
            'username'     => 'required|min_length[3]|max_length[50]|alpha_dash|is_unique[users.username]',
            'password'     => 'required|min_length[6]',
            'role'         => 'required|in_list[admin,kasir]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $password = (string) $this->request->getPost('password');

        $this->userModel->insert([
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'username'     => strtolower(trim((string) $this->request->getPost('username'))),
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'role'         => (string) $this->request->getPost('role'),
        ]);

        return redirect()->to(base_url('admin/users'))->with('success', 'Akun baru berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $currentUserId = (int) session()->get('id');
        $targetId      = (int) $id;

        if ($targetId === $currentUserId) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $targetUser = $this->userModel->find($targetId);
        if (! $targetUser) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Akun tidak ditemukan.');
        }

        if (($targetUser['role'] ?? '') === 'admin') {
            $adminCount = $this->userModel->where('role', 'admin')->countAllResults();
            if ($adminCount <= 1) {
                return redirect()->to(base_url('admin/users'))->with('error', 'Tidak dapat menghapus admin terakhir pada sistem.');
            }
        }

        $this->userModel->delete($targetId);

        return redirect()->to(base_url('admin/users'))->with('success', 'Akun berhasil dihapus.');
    }
}