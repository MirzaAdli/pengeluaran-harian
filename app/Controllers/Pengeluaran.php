<?php namespace App\Controllers;
use App\Models\PengeluaranModel;

class Pengeluaran extends BaseController {

    // SEARCH
    public function search() {
        $keyword = $this->request->getGet('keyword') ?? '';
        $model   = new PengeluaranModel();
        $data    = $model->search($keyword);
        return $this->response->setJSON($data);
    }

    // VIEW
    public function index() {
        return view('pengeluaran_view');
    }

    // READ
    public function list() {
        $model = new PengeluaranModel();
        $data  = $model->orderBy('tanggal','ASC')->findAll();
        return $this->response->setJSON($data);
    }

    // CREATE
    public function create() {
        $model = new PengeluaranModel();
        $data  = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'nominal'    => $this->request->getPost('nominal')
        ];
        $model->insert($data);
        return $this->response->setJSON(['status'=>'ok']);
    }

    // UPDATE
    public function update($id) {
        $model = new PengeluaranModel();
        $data  = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'nominal'    => $this->request->getPost('nominal')
        ];
        $model->update($id,$data);
        return $this->response->setJSON(['status'=>'ok']);
    }

    // DELETE
    public function delete($id) {
        $model = new PengeluaranModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status'=>'ok']);
        }
        return $this->response->setJSON(['status'=>'error']);
    }
}
