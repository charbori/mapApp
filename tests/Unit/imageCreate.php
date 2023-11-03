<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Image\ImageManager;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $imageManager = new ImageManager();
        //$imageManager->store();

        $this->assertTrue(true);
    }
}
