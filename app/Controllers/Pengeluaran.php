<?php namespace App\Controllers;

use App\Models\PengeluaranModel;

class Pengeluaran extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new PengeluaranModel();
    }

    public function index()
    {
        return view('pengeluaran_view');
    }

    public function search()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        $data    = $this->model->search($keyword);

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => $data ? 'Data ditemukan' : 'Tidak ada data',
            'data'    => $data
        ]);
    }

    public function list()
    {
        $data = $this->model->semua();

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => $data ? 'Data berhasil dimuat' : 'Tidak ada data',
            'data'    => $data
        ]);
    }

    public function create()
    {
        $data = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'nominal'    => $this->request->getPost('nominal')
        ];

        if ($this->model->tambah($data)) {
            return $this->response->setJSON(['status' => 'ok', 'message' => 'Data berhasil ditambah']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menambah data']);
    }

    public function update(int $id)
    {
        $data = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'nominal'    => $this->request->getPost('nominal')
        ];

        if ($this->model->ubah($id, $data)) {
            return $this->response->setJSON(['status' => 'ok', 'message' => 'Data berhasil diubah']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengubah data']);
    }

    public function delete(int $id)
    {
        if ($this->model->hapus($id)) {
            return $this->response->setJSON(['status' => 'ok', 'message' => 'Data berhasil dihapus']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }
}
