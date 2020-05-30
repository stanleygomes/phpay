<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;
use App\Helper\Helper;
use Illuminate\Database\Eloquent\SoftDeletes;

class Featured extends Model {
    use SoftDeletes;

    protected $table = 'featured';
    protected $fillable = [
        'title',
        'photo_url',
        'position',
        'placement',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'title' => 'required',
        'photo_url' => 'required',
        'position' => 'required',
        // 'placement' => 'required'
    ];

    public $validationMessages = [
        'title.required' => 'O campo título é obrigatório.',
        'photo_url.required' => 'O campo imagem é obrigatório.',
        'position.required' => 'O campo posição é obrigatório.',
        'placement.required' => 'O campo local é obrigatório.',
    ];

    public function getFeaturedById($id) {
        return Featured::where('id', $id)
            ->first();
    }

    public function getFeaturedList($filter = null, $paginate = false, $limit = 15) {
        $featured = Featured::whereNotNull('title');

        if ($filter != null && isset($filter['order_position']) && $filter['order_position'] != '') {
            $featured->orderBy('position', 'desc');
        }

        if ($filter != null && isset($filter['title']) && $filter['title'] != '') {
            $featured->where('title', 'like', '%' . $filter['title'] . '%');
        }

        if ($paginate === true) {
            $featured = $featured->paginate($limit);
        } else {
            $featured = $featured->get();
        }

        return $featured;
    }

    public function storeFeatured($request) {
        $featured = new Featured();

        $featured->title = $request->title;
        $featured->position = $request->position;
        $featured->placement = 'HOME_PAGE';
        $featured->created_by = Auth::user()->id;

        if ($request->hasFile('photo_url')) {
            $file = $request->file('photo_url');
            $fileManagerInstance = new Helper();
            $featured->photo_url = $fileManagerInstance->uploadFile($file, 'featured');
        }

        $featured->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $featured
        ];
    }

    public function updateFeatured($request, $id) {
        $featuredInstance = new Featured();
        $featured = $featuredInstance->getFeaturedById($id);

        if ($featured == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $featured->title = $request->title;
        $featured->position = $request->position;
        $featured->placement = 'HOME_PAGE';

        if ($request->hasFile('photo_url')) {
            $file = $request->file('photo_url');
            $fileManagerInstance = new Helper();
            $featured->photo_url = $fileManagerInstance->uploadFile($file, 'featured');
        }

        $featured->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $featured
        ];
    }

    public function deleteFeatured ($id) {
        $featured = $this->getFeaturedById($id);

        if ($featured == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $featured->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $featured->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }
}
