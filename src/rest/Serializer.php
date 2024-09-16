<?php

namespace eluhr\restApiUtils\rest;

class Serializer extends \yii\rest\Serializer
{
    /**
     * @inheritdoc
     */
    protected function serializeModelErrors($model)
    {
        $modelErrors = parent::serializeModelErrors($model);

        return [
            'name' => $this->response->statusText,
            'message' => $this->response->statusText,
            'code' => 0,
            'status' => $this->response->getStatusCode(),
            'errors' => $modelErrors
        ];
    }

}
