<?php declare(strict_types=1);

use eluhr\restApiUtils\filters\ApiResponseFilter;
use PHPUnit\Framework\TestCase;
use yii\base\Module;
use yii\web\Response;

final class ApiResponseFilterTest extends TestCase
{
    public function testAccessControlAllowCredentials(): void
    {
        Yii::$app->runAction('api1');
        $this->assertSame(Yii::$app->getResponse()->getHeaders()->get('Access-Control-Allow-Credentials'), 'true');
    }

    public function testResponseFormatIsJson(): void
    {
        Yii::$app->runAction('api1');
        $this->assertSame(Yii::$app->getResponse()->format, Response::FORMAT_JSON);
    }

    public function testSessionIsDisabled(): void
    {
        Yii::$app->runAction('api1');
        $this->assertFalse(Yii::$app->getUser()->enableSession);
    }

    public function testLoginUrl(): void
    {
        Yii::$app->runAction('api1');
        $this->assertNull(Yii::$app->getUser()->loginUrl);
    }
}
