<?php

namespace App\Models\API;

use CodeIgniter\Model;

class Usuario extends Model
{

    protected $table      = 'usuario';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['usuario_nombre', 'username', 'password'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [
        'username'          => 'required',
        'password'          => 'required',
        'usuario_nombre'    => 'required|min_length[10]' 
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected $beforeInsert = [];
    protected $beforeUpdate = [];
    protected $afterInsert = [];
    protected $afterUpdate = [];
    protected $afterDelete = [];

    public function profile($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id')
            ->select('usuario_nombre as nombre')
            ->select(' username')
            ->where(['id' => $id]);
        $query = $builder->get()
                        ->getRowObject();
        return $query;
    }

    public function deleteAccount($id)
    {
        $builder = $this->db->table($this->table);
        $builder->where('id', $id);
        $builder->delete();
        $query = $builder->get();
        return $query->getResult();
    }

    public function pivot($company, $user)
    {
        $data = [
            'fk_empresa' => $company,
            'fk_usuario'  => $user
        ];
        $builder = $this->db->table('pivot_empresa_usuario');
        $builder->insert($data);
    }

    public function companies($id)
    {
        $builder = $this->db->table('pivot_empresa_usuario as p');
        // $builder->select('*')
        $builder->select('usuario.usuario_nombre as dueño, empresa.empresa_nombre as empresa')
            ->select('empresa.id as id_empresa, usuario.id as id_usuario')
            ->select('empresa_codigo as codigo')
            ->where(['usuario.id' => $id])
            ->join('usuario', 'usuario.id = p.fk_usuario')
            ->join('empresa', 'empresa.id = p.fk_empresa');
        $query = $builder->get();
        return $query->getResultArray();
    }
}
