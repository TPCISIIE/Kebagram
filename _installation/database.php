<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

$config = require __DIR__ . '/../bootstrap/settings.php';

$capsule = new Manager();
$capsule->addConnection($config['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

Manager::schema()->dropIfExists('photos');
Manager::schema()->dropIfExists('users');

Manager::schema()->create('users', function (Blueprint $table) {
    $table->increments('user_id');
    $table->string('session_id', 48)->nullable();
    $table->string('user_name', 64)->unique();
    $table->string('user_slug', 64)->unique();
    $table->string('user_password_hash');
    $table->string('user_email')->unique();
    $table->boolean('user_active')->default(0);
    $table->boolean('user_deleted')->default(0);
    $table->boolean('user_account_type')->default(1);
    $table->boolean('user_has_avatar')->default(0);
    $table->string('user_remember_me_token', 64)->nullable();
    $table->bigInteger('user_suspension_timestamp')->nullable();
    $table->bigInteger('user_last_login_timestamp')->nullable();
    $table->boolean('user_failed_logins')->default(0);
    $table->integer('user_last_failed_login')->nullable();
    $table->string('user_activation_hash')->nullable();
    $table->boolean('user_profile')->default(1);
    $table->string('user_password_reset_hash')->nullable();
    $table->bigInteger('user_password_reset_timestamp')->nullable();
    $table->timestamps();
});

Manager::schema()->create('photos', function (Blueprint $table) {
    $table->increments('id');
    $table->text('description');
    $table->text('url');
    $table->timestamps();
    $table->integer('user_id')->unsigned();
    $table->foreign('user_id')->references('user_id')->on('users');
});
