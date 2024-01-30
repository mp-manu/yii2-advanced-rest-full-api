<?php

namespace backend\behaviours;

use common\models\User;
use Yii;
use yii\filters\auth\AuthMethod;


class Apiauth extends AuthMethod
{
    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'access-token';

    public $exclude = [];
    public $callback = [];


    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $user = new User();
        $headers = Yii::$app->getRequest()->getHeaders();

        $accessToken = NULL;

        if (isset($_GET['access_token'])) {
            $accessToken = $_GET['access_token'];
        } else {
            $accessToken = $headers->get('x-access_token');
        }

        if (empty($accessToken)) {
            if (isset($_GET['access-token'])) {
                $accessToken = $_GET['access-token'];
            } else {
                $accessToken = $headers->get('x-access-token');
            }
        }
        if (is_string($accessToken)) {
            $identity = $user->findIdentityByAccessToken($accessToken, get_class($this));
            var_dump($identity);
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            Yii::$app->api->sendFailedResponse('Invalid Access token');;
        }
        return null;
    }

    public function beforeAction($action)
    {

        if (in_array($action->id, $this->exclude) && !isset($_GET['access-token'])) {
            return true;
        }

        if (in_array($action->id, $this->callback) &&
            !isset($_GET['access-token'])) {
            return true;
        }


        $response = $this->response ?: Yii::$app->getResponse();

        $identity = $this->authenticate(
            $this->user ?: Yii::$app->getUser(),
            $this->request ?: Yii::$app->getRequest(),
            $response
        );

        if ($identity !== null) {
            return true;
        } else {
            $this->challenge($response);
            $this->handleFailure($response);

            Yii::$app->api->sendFailedResponse('Invalid Request');
            //return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function handleFailure($response)
    {
        Yii::$app->api->sendFailedResponse('Invalid Access token');
        //throw new UnauthorizedHttpException('You are requesting with an invalid credential.');
    }

}
