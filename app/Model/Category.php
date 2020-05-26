<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;

class Category extends Model {
    protected $table = 'category';
    protected $fillable = [
        'name',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'name' => 'required'
    ];

    public $validationMessages = [
        'name.required' => 'O campo nome é obrigatório.'
    ];

    public function getCategoryById($id) {
        return Category::where('id', $id)
            ->first();
    }

    public function getCategoryList($filter = null, $paginate = false, $limit = 15) {
        $category = Category::orderBy('id', 'desc');

        if ($filter != null && isset($filter['name']) && $filter['name'] != '') {
            $category->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if ($paginate === true) {
            $category = $category->paginate($limit);
        } else {
            $category = $category->get();
        }

        return $category;
    }

    public function storeCategory($request) {
        $category = new Category();

        $category->name = $request->name;
        $category->created_by = Auth::user()->id;

        $category->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $category
        ];
    }

    public function updateCategory($request, $id) {
        $category = Category::getCategoryById($id);

        if ($category == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $category->name = $request->name;

        $category->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $category
        ];
    }

    public function deleteCategory ($id) {
        $category = Category::getCategoryById($id);

        if ($category == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $category->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $category->save();

        return 'Cadastro deletado com sucesso.';
    }
}
