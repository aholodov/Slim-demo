<?php

const DEFAULT_URL = 'https://slim-test-project.firebaseio.com';
const DEFAULT_PATH = '';

class FirebaseSingleton
{
    private static $firebaseInstance;

    public static function getInstance()
    {
        if (null === static::$firebaseInstance) {
            static::$firebaseInstance = new \Firebase\FirebaseLib(DEFAULT_URL);
        }

        return static::$firebaseInstance;
    }

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}