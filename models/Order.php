<?php


class Order
{
    private $data;

    public function __construct($user_id, $data = [] )
    {
        $this->uuid = $data['id'];
        $data['user_id'] = $user_id;
        $this->data = $data;
    }

    public static function create( $user_id, $data )
    {
        return new static( $user_id, $data );
    }

    public static function getById($order_id)
    {
        $data = FirebaseSingleton::getInstance()->get(DEFAULT_PATH . '/orders/' . $order_id);

        if ($data === null || $data === 'null') {
            throw new \Exception('Order not found');
        }

        $decodedData = json_decode($data, true);
        return new static ($decodedData['user_id'], $decodedData);
    }

    public function cancelOrder()
    {
        if ($this->data['status'] === 2) {
            throw new \Exception('Order already canceled');
        }

        $this->data['status'] = 2;
        FirebaseSingleton::getInstance()->update(DEFAULT_PATH . '/orders/' . $this->uuid, $this->data);
    }

    public function save()
    {
        FirebaseSingleton::getInstance()->set(DEFAULT_PATH . '/orders/' . $this->uuid, $this->data);
    }

    public function getUserId()
    {
        return $this->data['user_id'];
    }

    public function __get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    public function getData() {
        return $this->data;
    }

    public function getId() {
        return $this->uuid;
    }
}