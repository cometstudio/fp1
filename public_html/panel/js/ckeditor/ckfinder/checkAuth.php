<?php
session_start();

$bootstrapDir = $_SERVER['DOCUMENT_ROOT'];

require $bootstrapDir.'/../bootstrap/autoload.php';

$app = require_once $bootstrapDir.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

////@TODO Modify this line to check if current user ($user = Auth::user()) has access rights
if (!Auth::check() && empty($_SESSION['authenticated'])) throw new \Exception();

$_SESSION['authenticated'] = 1;