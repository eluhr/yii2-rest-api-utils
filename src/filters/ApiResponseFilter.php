<?php

namespace eluhr\restApiUtils\filters;

use Yii;
use yii\base\ActionFilter;
use yii\base\InvalidConfigException;
use yii\web\Response as WebResponse;
use yii\web\User;

/**
 * Class ApiResponseFilter
 *
 * This filter ensures consistent API response handling, enforcing JSON format
 * and handling authentication/session configurations for all requests.
 */
class ApiResponseFilter extends ActionFilter
{

    /**
     * @var \yii\web\User|string|null the user to be modified. If not set, the `user` application component will be
     * used.
     */
    public $user;

    /**
     * @var bool $allowCredentials Whether to allow credentials in cross-origin requests (CORS).
     * This sets the `Access-Control-Allow-Credentials` header.
     */
    public $allowCredentials = true;

    /**
     * @var bool $forceJsonResponse Whether to force all responses to be in JSON format.
     * This is useful for API applications where all responses should be JSON, including error responses.
     */
    public $forceJsonResponse = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // If $user is a string, try to resolve it as a component
        if (is_string($this->user)) {
            $this->user = Yii::$app->get($this->user);
        }

        // If $user is still null, use the default 'user' component from Yii::$app
        if ($this->user === null) {
            $this->user = Yii::$app->getUser();
        }

        // Ensure the user component is valid
        if (!$this->user instanceof User) {
            throw new InvalidConfigException('The "user" property must be an instance of yii\web\User.');
        }
    }

    /**
     * Runs before an action is executed.
     * Used to modify response settings and handle authentication/session configurations.
     *
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        // Disable session for the user, meaning user authentication will be stateless.
        // See: https://www.yiiframework.com/doc/guide/2.0/en/rest-authentication
        // This is important for RESTful APIs where session-based authentication is not appropriate.
        $this->user->enableSession = false;

        // Prevent redirects to login page on unauthorized requests,
        // instead returning a 403 (Forbidden) HTTP status.
        $this->user->loginUrl = null;

        // Force the response to be in JSON format if specified.
        if ($this->forceJsonResponse) {
            // Ensure that we are dealing with a web response before trying to modify it.
            // Some response types (like console responses) do not support the format property.
            if (isset($action->controller->response) && ($action->controller->response instanceof WebResponse)) {
                $action->controller->response->format = WebResponse::FORMAT_JSON;
            }
        }

        // Handle Cross-Origin Resource Sharing (CORS) credentials.
        // If allowCredentials is true, the Access-Control-Allow-Credentials header is set,
        // allowing client-side JavaScript to send cookies and HTTP authentication.
        if ($this->allowCredentials) {
            // Since the Cors filter doesn’t allow this header to be set when `Origin = '*'`,
            // we set it manually here.
            $action->controller->response->getHeaders()->set('Access-Control-Allow-Credentials', 'true');
        }

        // Proceed with the next filter or action.
        return parent::beforeAction($action);
    }

}
