<?php declare(strict_types=1);

use eluhr\restApiUtils\filters\ApiResponseFilter;
use PHPUnit\Framework\TestCase;
use yii\base\Module;

final class ApiResponseFilterTest extends TestCase
{
    public function testWIP(): void
    {
        $this->assertTrue(true);
    }
}

class CustomModule extends Module
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['api-response-filter'] = [
            'class' => ApiResponseFilter::class
        ];
        return $behaviors;
    }
}
