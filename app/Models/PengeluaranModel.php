<?php namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table      = 'pengeluaran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tanggal', 'keterangan', 'nominal'];

    public function tambah(array $data){ return $this->insert($data); }
    public function semua(): array { return $this->orderBy('tanggal','ASC')->findAll(); }
    public function detail(int $id){ return $this->find($id); }
    public function ubah(int $id, array $data){ return $this->update($id,$data); }
    public function hapus(int $id){ return $this->delete($id); }

    public function search(string $keyword = ''): array
    {
        $builder = $this->builder();

        if ($keyword !== '') {
            $keyword = strtolower(trim($keyword));

            $builder->groupStart()
                ->like('LOWER(keterangan)', $keyword)
                ->orLike('LOWER(tanggal)', $keyword)
                ->orLike('LOWER(nominal)', $keyword)
            ->groupEnd();
        }

        return $builder->orderBy('tanggal','ASC')->get()->getResultArray();
    }


}
