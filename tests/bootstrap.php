<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set up test environment
$_ENV['APP_ENV'] = 'testing';
$_ENV['MONGO_DATABASE'] = 'test_database'; 