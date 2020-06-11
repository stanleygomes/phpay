<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helper\Helper;

class Cart extends Model {
    use SoftDeletes;

    protected $table = 'cart';
    protected $fillable = [
        'user_id',
        'user_cpf',
        'user_name',
        'user_email',
        'user_phone',
        'address_id',
        'address_name',
        'address_zipcode',
        'address_street',
        'address_number',
        'address_complement',
        'address_district',
        'address_city',
        'address_state',
        'payment_methods_available_id',
        'price_total',
        'last_status',
        'order_date',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'order_date' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $cartStatus = [
        'CREATED' => 'Criado',
        'PAYMENT_PENDING' => 'Pendente',
        'CANCELED' => 'Cancelado',
        'PAID' => 'Pago'
    ];

    protected $maxInstallments = 10;

    public function getMaxInstallments() {
        return $this->maxInstallments;
    }

    public function getCartStatusByCode($code) {
        return $this->cartStatus[$code];
    }

    public function statusColorCart($status){
        $status = strtolower($status);

        if ($status === 'cancelado') {
            return 'danger';
        } else if ($status === 'pendente') {
            return 'warning';
        } else if ($status === 'pago') {
            return 'success';
        } else if ($status === 'criado') {
            return 'primary';
        } else {
            return 'secondary';
        }
    }

    public function getCartById($id) {
        return Cart::where('id', $id)
            ->first();
    }

    public function getCartUserById($id) {
        $loggedUser = Auth::user();
        return Cart::where('id', $id)
            ->where('user_id', $loggedUser->id)
            ->first();
    }

    public function getCartsByDayChart($dateStart, $dateEnd) {
        $cartsResume = Cart::select([DB::raw('DATE(created_at) as date'), DB::raw('count(*) as qty')])
            // ->where('date_order', '>=', $dateStart)
            // ->where('date_order', '<=', $dateEnd)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $x = [];
        $y = [];

        for ($i = 0; $i < count($cartsResume); $i++) {
            $item = $cartsResume[$i];
            array_push($x, $item->date);
            array_push($y, $item->qty);
        }

        return [
            'x' => $x,
            'y' => $y
        ];
    }

    public function getCartsResumeByYearMonth($dateStart, $dateEnd) {
        $statusList = [
            $this->cartStatus['PAID'],
            $this->cartStatus['PAYMENT_PENDING'],
            $this->cartStatus['CANCELED']
        ];

        $cartsResume = Cart::whereIn('last_status', $statusList)
            // ->where('date_order', '>=', $dateStart)
            // ->where('date_order', '<=', $dateEnd)
            ->select([DB::raw('SUM(price_total) as price_total'), 'last_status'])
            ->groupBy('last_status')
            ->get();

        $resume = [];

        for ($i = 0; $i < count($statusList); $i++) {
            $status = $statusList[$i];
            $resume[$i]['last_status'] = $status;
            $resume[$i]['price_total'] = 0;

            for ($j = 0; $j < count($cartsResume); $j++) {
                $cartResume = $cartsResume[$j];

                if ($cartResume->last_status === $status) {
                    $resume[$i]['price_total'] = $cartResume->price_total;
                }
            }
        }

        return $resume;
    }

    public function getCartList($filter = null, $paginate = false, $limit = 15) {
        $cart = Cart::orderBy('id', 'desc');

        $loggedUser = Auth::user();

        if ($loggedUser != null && $loggedUser->profile === 'CUSTOMER') {
            $cart->where('user_id', $loggedUser->id);
        }

        if ($filter != null && isset($filter['is_order_date']) && $filter['is_order_date'] === true) {
            $cart->whereNotNull('order_date');
        }

        if ($filter != null && isset($filter['id']) && $filter['id'] != '') {
            $cart->where('id', intval($filter['id']));
        }

        if ($paginate === true) {
            $cart = $cart->paginate($limit);
        } else {
            $cart = $cart->get();
        }

        return $cart;
    }

    public function updateCartStatus($cartId, $status, $dateOrder = null) {
        $cart = $this->getCartById($cartId);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $cartId . '] não encontrado.');
        }

        $cart->last_status = $status;

        if ($dateOrder === true) {
            $cart->order_date = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        }

        $cart->save();

        return [
            'message' => 'Status atualizado.',
            'data' => $cart
        ];
    }

    public function getCreateSessionCartId() {
        $cartSessionId = Helper::getSessionCartId();

        if ($cartSessionId == null) {
            $cart = $this->storeCart();
            $cartId = $cart['data']->id;

            Helper::putSessionCartId($cartId);
            return $cartId;
        }

        return $cartSessionId;
    }

    public function getSessionCartId() {
        $cartSessionId = Helper::getSessionCartId();
        return $cartSessionId;
    }

    public function setUser($user) {
        $cartId = $this->getSessionCartId();
        $cart = $this->getCartById($cartId);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $cartId . '] não encontrado.');
        }

        $cart->user_id = $user->id;
        $cart->user_cpf = $user->cpf;
        $cart->user_name = $user->name;
        $cart->user_email = $user->email;
        $cart->user_phone = $user->phone;

        $cart->save();

        return [
            'message' => 'Usuário atualizado.',
            'data' => $cart
        ];
    }

    public function setAddress($address) {
        $cartId = $this->getSessionCartId();
        $cart = $this->getCartById($cartId);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $cartId . '] não encontrado.');
        }

        $cart->address_id = $address->id;
        $cart->address_name = $address->name;
        $cart->address_zipcode = $address->zipcode;
        $cart->address_street = $address->street;
        $cart->address_number = $address->number;
        $cart->address_complement = $address->complement;
        $cart->address_district = $address->district;
        $cart->address_city = $address->city;
        $cart->address_state = $address->state;

        $cart->save();

        return [
            'message' => 'Endereço ' . $address->name . ' selecionado.',
            'data' => $cart
        ];

    }

    public function storeCart() {
        $cart = new Cart();

        $createdStatus = $this->cartStatus['CREATED'];

        $cart->address_id = null;
        $cart->price_total = 0;
        $cart->last_status = $createdStatus;

        $loggedUser = Auth::user();

        if ($loggedUser != null) {
            $cart->user_id = $loggedUser->id;
            $cart->created_by = $loggedUser->id;
        } else {
            $cart->user_id = null;
            $cart->created_by = null;
        }

        $cart->save();

        $cartHistoryInstance = new CartHistory();
        $cartHistoryInstance->storeCartHistory($cart->id, $createdStatus);

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $cart
        ];
    }

    public function updatePriceTotal($cartId) {
        $totalPrice = 0;
        $cartItemInstance = new CartItem();
        $filter = [
            'cart_item' => $cartId
        ];

        $cartItems = $cartItemInstance->getCartItemList($filter, false);

        for ($i = 0; $i < count($cartItems); $i++) {
            $cartItem = $cartItems[$i];
            $totalPrice = $totalPrice + ($cartItem->product_price * $cartItem->qty);
        }

        $cart = $this->getCartById($cartId);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $cartId . '] não encontrado.');
        }

        $cart->price_total = $totalPrice;

        $cart->save();
    }

    public function updateCart($request, $id) {
        $cart = $this->getCartById($id);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $cart->name = $request->name;

        $cart->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $cart
        ];
    }

    public function deleteCart ($id) {
        $cart = $this->getCartById($id);

        if ($cart == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $cart->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $cart->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }

    public function sendMailCancel($cart, $request) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'to_email' => env('MAIL_TO_ADDRESS'),
            'to_name' => env('MAIL_TO_NAME')
        ];
        $subject = 'Solicitação de cancelamento no site';
        $template = 'cart-cancel';
        $data = $cart;
        $data->description = $request->description;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(AppException $e) {
            Log::error($e);
            throw new AppException('Erro ao enviar email.');
        }
    }

    public function sendMailCartOrdered($cart) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'cc_email' => env('MAIL_FROM_ADDRESS'),
            'cc_name' => env('MAIL_FROM_NAME'),
            'to_email' => $cart->user_email,
            'to_name' => $cart->user_name
        ];
        $subject = 'Seu pedido foi recebido';
        $template = 'cart-order';
        $data = $cart;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(AppException $e) {
            Log::error($e);
            throw new AppException('Erro ao enviar email.');
        }
    }
}
