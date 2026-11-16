<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use App\Models\PesananItemModel;
use App\Models\MejaModel;

class Dashboard extends BaseController
{
    protected PesananModel     $pesananModel;
    protected PesananItemModel $itemModel;
    protected MejaModel        $mejaModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->itemModel    = new PesananItemModel();
        $this->mejaModel    = new MejaModel();
    }

    public function index()
    {
        $pending = $this->pesananModel->getPending();
        $last_id = 0;

        if (! empty($pending)) {
            $pendingIds = array_map('intval', array_column($pending, 'id'));
            $itemsMap   = $this->itemModel->getByPesananIds($pendingIds);

            foreach ($pending as &$p) {
                $p['items'] = $itemsMap[$p['id']] ?? [];

                if ($p['id'] > $last_id) {
                    $last_id = $p['id'];
                }
            }
            unset($p);
        }

        $hari_ini = date('Y-m-d');

        $pendapatan_row = $this->pesananModel->selectSum('total_harga')
            ->where('DATE(updated_at)', $hari_ini)
            ->where('status', 'selesai')
            ->first();

        $pendapatan = $pendapatan_row['total_harga'] ?? 0;

        $riwayatHariIni = $this->pesananModel
            ->select('pesanans.*, mejas.nomor_meja')
            ->join('mejas', 'mejas.id = pesanans.meja_id', 'left')
            ->where('DATE(pesanans.created_at)', $hari_ini)
            ->orderBy('pesanans.created_at', 'DESC')
            ->findAll();

        if (! empty($riwayatHariIni)) {
            $riwayatIds      = array_map('intval', array_column($riwayatHariIni, 'id'));
            $riwayatItemsMap = $this->itemModel->getByPesananIds($riwayatIds);

            foreach ($riwayatHariIni as &$r) {
                $r['nomor_meja'] = $r['nomor_meja'] ?? '-';
                $r['items']      = $riwayatItemsMap[$r['id']] ?? [];
            }
            unset($r);
        }

        $data = [
            'title'              => 'Antrean Pesanan — NasPad Zendry',
            'pesanan_pending'    => $pending,
            'riwayat_hari_ini'   => $riwayatHariIni,
            'total_pending'      => count($pending),
            'total_selesai'      => $this->pesananModel->getSelesaiHariIni(),
            'pendapatan'         => $pendapatan,
            'last_id'            => $last_id,
            'last_payment_time'  => date('Y-m-d H:i:s'),
        ];

        return view('kasir/dashboard', $data);
    }

    public function selesai(int $id)
    {
        $pesanan = $this->pesananModel->find($id);

        if (!$pesanan) {
            return redirect()->to(base_url('kasir/dashboard'))
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        if (($pesanan['metode_pembayaran'] ?? '') !== 'tunai') {
            return redirect()->to(base_url('kasir/dashboard'))
                ->with('error', 'Tombol selesai hanya untuk pembayaran tunai.');
        }

        $this->pesananModel->update($id, [
            'status'     => 'selesai',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('kasir/dashboard'))
            ->with('success', 'Pesanan #' . $pesanan['kode_pesanan'] . ' ditandai selesai.');
    }

    public function cek_baru($last_id)
    {
        try {
            $lastIdInt = (int) $last_id;

            $pesananBaruRows = $this->pesananModel
                ->select('pesanans.*, mejas.nomor_meja')
                ->join('mejas', 'mejas.id = pesanans.meja_id', 'left')
                ->where('pesanans.id >', $lastIdInt)
                ->where('pesanans.status_pesanan', 'menunggu')
                ->orderBy('pesanans.id', 'ASC')
                ->findAll();

            if (empty($pesananBaruRows)) {
                return $this->response->setJSON([
                    'ada' => false,
                ]);
            }

            $orderIds = array_map('intval', array_column($pesananBaruRows, 'id'));
            $itemsMap = $this->itemModel->getByPesananIds($orderIds);

            $pesananBaru = [];
            $new_last_id = $lastIdInt;

            foreach ($pesananBaruRows as $p) {
                $p['items']             = $itemsMap[$p['id']] ?? [];
                $p['waktu']             = date('H:i', strtotime($p['created_at']));
                $p['total_format']       = number_format($p['total_harga'], 0, ',', '.');
                $p['status_pembayaran'] = $p['status'];
                $p['metode_label']       = $this->formatMetodePembayaran($p['metode_pembayaran'] ?? '-');

                $pesananBaru[] = $p;

                if ($p['id'] > $new_last_id) {
                    $new_last_id = (int) $p['id'];
                }
            }

            return $this->response->setJSON([
                'ada'     => true,
                'data'    => $pesananBaru,
                'last_id' => $new_last_id,
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'ada'   => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function cek_pembayaran_berhasil()
    {
        try {
            $lastTime = $this->request->getGet('last_time');

            if (empty($lastTime)) {
                $lastTime = date('Y-m-d H:i:s');
            }

            $hari_ini = date('Y-m-d');

            $pesananLunas = $this->pesananModel
                ->select('pesanans.*, mejas.nomor_meja')
                ->join('mejas', 'mejas.id = pesanans.meja_id', 'left')
                ->where('DATE(pesanans.updated_at)', $hari_ini)
                ->where('pesanans.updated_at >', $lastTime)
                ->where('pesanans.status', 'selesai')
                ->where('pesanans.metode_pembayaran !=', 'tunai')
                ->orderBy('pesanans.updated_at', 'ASC')
                ->findAll();

            if (empty($pesananLunas)) {
                return $this->response->setJSON([
                    'ada'       => false,
                    'last_time' => $lastTime,
                ]);
            }

            $orderIds = array_map('intval', array_column($pesananLunas, 'id'));
            $itemsMap = $this->itemModel->getByPesananIds($orderIds);

            $data = [];
            $newLastTime = $lastTime;

            foreach ($pesananLunas as $p) {
                $p['nomor_meja']        = $p['nomor_meja'] ?? '-';
                $p['items']             = $itemsMap[$p['id']] ?? [];
                $p['waktu']             = date('H:i', strtotime($p['updated_at']));
                $p['created_waktu']     = date('H:i', strtotime($p['created_at']));
                $p['total_format']       = number_format($p['total_harga'], 0, ',', '.');
                $p['status_pembayaran'] = $p['status'];
                $p['metode_label']       = $this->formatMetodePembayaran($p['metode_pembayaran'] ?? '-');

                $data[] = $p;

                if (strtotime($p['updated_at']) > strtotime($newLastTime)) {
                    $newLastTime = $p['updated_at'];
                }
            }

            return $this->response->setJSON([
                'ada'       => true,
                'data'      => $data,
                'last_time' => $newLastTime,
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'ada'   => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function formatMetodePembayaran(string $metode): string
    {
        return match ($metode) {
            'tunai'    => 'Tunai',
            'qris'     => 'QRIS / E-Wallet',
            'transfer' => 'Transfer Bank',
            default    => ucfirst($metode),
        };
    }

    public function siap(int $id)
    {
        $pesanan = $this->pesananModel->find($id);

        if (! $pesanan) {
            return redirect()
                ->to(base_url('kasir/dashboard'))
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        $update = [
            'status_pesanan' => 'siap',
            'waktu_siap'     => date('Y-m-d H:i:s'),
        ];

        if (($pesanan['metode_pembayaran'] ?? '') === 'tunai') {
            $update['status'] = 'selesai';
        }

        $this->pesananModel->update($id, $update);

        $nomorAntrian = $pesanan['nomor_antrian'] ?? '-';

        return redirect()
            ->to(base_url('kasir/dashboard'))
            ->with('success', 'Pesanan nomor antrian ' . $nomorAntrian . ' sudah siap diantarkan.');
    }
}