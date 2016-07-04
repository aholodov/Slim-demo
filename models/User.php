<?php

class User
{
    private $data;

    private $orders = [];

    public function __construct( $data = [] )
    {
        $this->uuid = $data['id'];
        $this->data = $data;
    }

    public static function create( $data )
    {
        return new static( $data );
    }

    public static function getById($uid)
    {
        $data = FirebaseSingleton::getInstance()->get(DEFAULT_PATH . '/users/' . $uid);

        if ($data === null || $data === 'null') {
            throw new \Exception('User not found');
        }

        return new static (json_decode($data, true));
    }

    public function save()
    {
        FirebaseSingleton::getInstance()->set(DEFAULT_PATH . '/users/' . $this->uuid, $this->data);
    }

    public function getId()
    {
        return $this->uuid;
    }

    public function addOrder($order) {
        $this->orders[] = $order;
    }

    public function getOrders() {
        return $this->orders;
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
}