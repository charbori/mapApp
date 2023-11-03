<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

interface MapManagerInterface
{
    public function view();

    public function store(Request $request);

    public function edit(Request $request);

}
?>
