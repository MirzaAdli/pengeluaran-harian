<?php namespace App\Models;
use CodeIgniter\Model;

class PengeluaranModel extends Model {
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tanggal','keterangan','nominal'];

    public function search($keyword) {
        if ($keyword === '') {
            return $this->orderBy('tanggal','ASC')->findAll();
        }

        return $this->builder()
                    ->groupStart()
                        ->like('keterangan', $keyword)
                        ->orLike('tanggal', $keyword)
                        ->orLike('nominal', $keyword)
                    ->groupEnd()
                    ->orderBy('tanggal','ASC')
                    ->get()
                    ->getResultArray();
    }
}
