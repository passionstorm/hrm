<?php

namespace Tests\Controllers;

use App\Http\Controllers\QTController;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QTControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testVacationSpent()
    {
        Auth::shouldReceive('user')->andreturn((object)[
            'id' => 1
        ]);
        $controller = new QTController();

        $i = 1;
        $case = $controller->getSpentTime('2019-08-18 08:00:00', '2019-08-19 17:00:00');
        $this->assertEquals(floatval(16), floatval($case), "case" . $i++);

        $case = $controller->getSpentTime('2019-08-18 08:00:00', '2019-08-18 16:00:00');
        $this->assertEquals(floatval(7), floatval($case), "case" . $i++);

        $case = $controller->getSpentTime('2019-08-18 08:00:00', '2019-08-18 09:00:00');
        $this->assertEquals(floatval(1), floatval($case), "case" . $i++);


        $case = $controller->getSpentTime('2019-08-18 08:00:00', '2019-08-18 17:00:00');
        $this->assertEquals(floatval(8), floatval($case), "case" . $i++);

        $case = $controller->getSpentTime('2019-08-18 09:00:00', '2019-08-18 13:00:00');
        $this->assertEquals(floatval(3), floatval($case), "case" . $i++);

        $case = $controller->getSpentTime('2019-08-18 09:00:00', '2019-08-18 12:00:00');
        $this->assertEquals(floatval(3), floatval($case), "case" . $i++);

        $case = $controller->getSpentTime('2019-08-18 08:00:00', '2019-08-19 8:00:00');
        $this->assertEquals(floatval(8), floatval($case), "case" . $i++);

        $case = $controller->getSpentTime('2019-08-18 08:00:00', '2019-08-19 14:00:00');
        $this->assertEquals(floatval(13), floatval($case), "case" . $i++);

        $case = $controller->getSpentTime('2019-08-18 08:00:00', '2019-08-20 14:30:00');
        $this->assertEquals(floatval(21.5), floatval($case), "case" . $i++);


        $case = $controller->getSpentTime('2019-08-18 08:00:00', '2019-08-19 9:00:00');
        $this->assertEquals(floatval(9), floatval($case), "case" . $i++);

    }
}
