<?php
require '../vendor/autoload.php';

require_once '../classes/Firebase.php';

require_once '../models/User.php';
require_once '../models/Order.php';


$app = new \Slim\Slim();

$app->get('/', function () use ($app)  {

});

$app->get('/getuser/userId/:userId', function ($userId) use ($app) {
    $user = User::getById($userId);

    echo json_encode([
        'userId' => $user->getId(),
        'userData' => $user->getData()
    ]);
});

$app->get('/getorder/orderId/:orderId', function ($orderId) use ($app) {
    $order = Order::getById($orderId);
    $user = User::getById($order->getUserId());

    echo json_encode([
        'userId' => $user->getId(),
        'userData' => $user->getData(),
        'order' => $order->getData()
    ]);
});

$app->get('/cancelorder/orderId/:orderId', function ($orderId) use ($app) {
    $order = Order::getById($orderId);
    $order->cancelOrder();

    echo json_encode([
        'status' => 'true',
        'orderId' => $order->getId()
    ]);
});

$app->get('/generate-fake-information', function () use ($app) {
    $faker = Faker\Factory::create();

    $users = [];
    for ($i = 0; $i < 10; $i++) {
        $user = User::create([
            'id' => $faker->uuid,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber
        ]);
        $user->save();

        $ordersCount = rand(1, 10);
        for ($j = 0; $j < $ordersCount; $j++) {
            $order = Order::create($user->getId(), [
                'id' => $faker->uuid,
                'total' => $faker->numberBetween(10, 1000),
                'date' => $faker->date('Y-m-d'),
                'status' => $faker->numberBetween(1, 2)
            ]);
            $order->save();
            $user->addOrder($order);
        }

        $users[] = $user;
    }

    echo json_encode([
        'saved' => 'true',
        'users' => $users
    ]);
});

$app->run();