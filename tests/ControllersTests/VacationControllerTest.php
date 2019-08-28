<?php

namespace Tests\Controllers;

use App\Http\Controllers\VacationController;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class VacationControllerTest extends TestCase
{
    /**
     *  Todo: Test is fail. need to fix logic and change test case
     * A basic unit test example.
     *
     * @return void
     */
    public function testVacationSpent()
    {
        Auth::shouldReceive('user')->andreturn((object)[
            'id' => 1,
            'shift' => '1,2',
        ]);
        $controller = new VacationController();
        $i = 1;
        
        //1
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-18 10:00:00',
        ]);
        $this->assertEquals(floatval(1.5), floatval($case), "case" . $i++);
        
        //2
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-18 12:00:00',
        ]);
        $this->assertEquals(floatval(3.5), floatval($case), "case" . $i++);
        
        //3
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:30:00',
            'end'=>'2019-08-18 16:00:00',
        ]);
        $this->assertEquals(floatval(7), floatval($case), "case" . $i++);

        //4
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-18 17:00:00',
        ]);
        $this->assertEquals(floatval(8), floatval($case), "case" . $i++);

        //5
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-18 10:00:00',
        ]);
        $this->assertEquals(floatval(1), floatval($case), "case" . $i++);

        //6
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-18 12:00:00',
        ]);
        $this->assertEquals(floatval(3), floatval($case), "case" . $i++);

        //7
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-18 16:00:00',
        ]);
        $this->assertEquals(floatval(6), floatval($case), "case" . $i++);

        //8
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-18 17:00:00',
        ]);
        $this->assertEquals(floatval(7), floatval($case), "case" . $i++);

        //9
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 13:00:00',
            'end'=>'2019-08-18 16:00:00',
        ]);
        $this->assertEquals(floatval(3), floatval($case), "case" . $i++);

        //10
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 13:00:00',
            'end'=>'2019-08-18 17:00:00',
        ]);
        $this->assertEquals(floatval(4), floatval($case), "case" . $i++);

        //11
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 14:00:00',
            'end'=>'2019-08-18 16:00:00',
        ]);
        $this->assertEquals(floatval(2), floatval($case), "case" . $i++);

        //12
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 14:00:00',
            'end'=>'2019-08-18 17:00:00',
        ]);
        $this->assertEquals(floatval(3), floatval($case), "case" . $i++);
        
        //13
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-19 10:00:00',
        ]);
        $this->assertEquals(floatval(10), floatval($case), "case" . $i++);
        
        //14
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-19 12:00:00',
        ]);
        $this->assertEquals(floatval(12), floatval($case), "case" . $i++);
        
        //15
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-19 16:00:00',
        ]);
        $this->assertEquals(floatval(15), floatval($case), "case" . $i++);

        //16
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-19 17:00:00',
        ]);
        $this->assertEquals(floatval(16), floatval($case), "case" . $i++);

        //17
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-19 10:00:00',
        ]);
        $this->assertEquals(floatval(9), floatval($case), "case" . $i++);

        //18
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-19 12:00:00',
        ]);
        $this->assertEquals(floatval(11), floatval($case), "case" . $i++);

        //19
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-19 16:00:00',
        ]);
        $this->assertEquals(floatval(14), floatval($case), "case" . $i++);

        //20
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-19 17:00:00',
        ]);
        $this->assertEquals(floatval(15), floatval($case), "case" . $i++);

        //21
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 13:00:00',
            'end'=>'2019-08-19 16:00:00',
        ]);
        $this->assertEquals(floatval(11), floatval($case), "case" . $i++);

        //22
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 13:00:00',
            'end'=>'2019-08-19 17:00:00',
        ]);
        $this->assertEquals(floatval(12), floatval($case), "case" . $i++);

        //23
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 14:00:00',
            'end'=>'2019-08-19 16:00:00',
        ]);
        $this->assertEquals(floatval(10), floatval($case), "case" . $i++);

        //24
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 14:00:00',
            'end'=>'2019-08-19 17:00:00',
        ]);
        $this->assertEquals(floatval(11), floatval($case), "case" . $i++);
        
        //25
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-20 10:00:00',
        ]);
        $this->assertEquals(floatval(18), floatval($case), "case" . $i++);
        
        //26
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-20 12:00:00',
        ]);
        $this->assertEquals(floatval(20), floatval($case), "case" . $i++);
        
        //27
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-20 16:00:00',
        ]);
        $this->assertEquals(floatval(23), floatval($case), "case" . $i++);

        //27
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 08:00:00',
            'end'=>'2019-08-20 17:00:00',
        ]);
        $this->assertEquals(floatval(24), floatval($case), "case" . $i++);

        //28
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-20 10:00:00',
        ]);
        $this->assertEquals(floatval(17), floatval($case), "case" . $i++);

        //29
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-20 12:00:00',
        ]);
        $this->assertEquals(floatval(19), floatval($case), "case" . $i++);

        //30
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-20 16:00:00',
        ]);
        $this->assertEquals(floatval(22), floatval($case), "case" . $i++);

        //31
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 09:00:00',
            'end'=>'2019-08-20 17:00:00',
        ]);
        $this->assertEquals(floatval(23), floatval($case), "case" . $i++);

        //32
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 13:00:00',
            'end'=>'2019-08-20 16:00:00',
        ]);
        $this->assertEquals(floatval(19), floatval($case), "case" . $i++);

        //33
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 13:00:00',
            'end'=>'2019-08-20 17:00:00',
        ]);
        $this->assertEquals(floatval(20), floatval($case), "case" . $i++);

        //34
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 14:00:00',
            'end'=>'2019-08-20 16:00:00',
        ]);
        $this->assertEquals(floatval(18), floatval($case), "case" . $i++);

        //35
        $case = $controller->VacationSpent((object)[
            'start'=>'2019-08-18 14:00:00',
            'end'=>'2019-08-20 17:00:00',
        ]);
        $this->assertEquals(floatval(19), floatval($case), "case" . $i++);
    }
}
