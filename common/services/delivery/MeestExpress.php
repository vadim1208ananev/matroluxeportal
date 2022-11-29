<?php

namespace common\services\delivery;

use yii\helpers\Html;
use yii\httpclient\Client;
use Yii;

class MeestExpress implements DeliveryService
{
    protected $client;
    protected $username;
    protected $password;
    protected $url;
    protected $token;
    protected $countryId = 'c35b6195-4ea3-11de-8591-001d600938f8'; //Ukraine
    protected $branchTypeId = 'ac82815e-10fe-4eb7-809a-c34be4553213'; //Відділення. Без обмежень ваги.

    public function __construct()
    {
        $this->client = new Client([
            'transport' => 'yii\httpclient\CurlTransport', // only cURL supports the options we need
            'baseUrl' => Yii::$app->params['meestexpress']['url'],
        ]);
        $this->username = Yii::$app->params['meestexpress']['username'];
        $this->password = Yii::$app->params['meestexpress']['password'];
        $this->token = $this->getToken();
    }

    protected function getToken()
    {
        $response = $this->client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('auth')
            ->setData([
                'username' => $this->username,
                'password' => $this->password,
            ])
            ->send();
        if ($response->data['status'] != 'OK')
            exit('Запрос не выполнился.');
        return $response->data['result']['token'];
    }

    public function getCities()
    {
        $data = [];
        $post = Yii::$app->request->post();

        $response = $this->client->createRequest()
            ->setMethod('POST')
            ->setHeaders(['token' => $this->token])
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('citySearch')
            ->setData(['filters' => ['countryID' => $this->countryId, 'cityDescr' => $post['city'] . '%',],
            ])
            ->send();
        if ($response->data['status'] != 'OK')
            exit('Запрос не выполнился.');
        foreach ($response->data['result'] as $item) {
            $data[$item['regionDescr']['descrUA']][] = [
                'description' => $item['cityDescr']['descrUA'] . (!empty($item['districtDescr']['descrUA']) ? ' (' . $item['districtDescr']['descrUA'] . ')' : ''), //Дніпрельстан
                'ref' => $item['cityID'], //eb54475c-e5e4-11e9-b48a-005056b24375
                'type' => '',
                'area' => $item['regionDescr']['descrUA'] //Запорізька
            ];
        }

        return json_encode(['success' => true, 'data' => $data,]);
    }

    public function getWarehouses()
    {
        $data = [];
        $post = Yii::$app->request->post();

        $response = $this->client->createRequest()
            ->setMethod('POST')
            ->setHeaders(['token' => $this->token])
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('branchSearch')
            ->setData([
                    'filters' => [
                        'cityID' => Html::decode($post['cityRef']),
                        'branchTypeID' => $this->branchTypeId,
                    ]
                ]
            )
            ->send();
        if ($response->data['status'] != 'OK')
            exit('Запрос не выполнился.');
        foreach ($response->data['result'] as $item) {
            $data[] = [
                'description' => $item['branchDescr']['descrSearchUA'], //Пункт приймання-видачі (до 30 кг): вул. Леніна, 54
                'ref' => $item['branchID'] //3294c49f-23ea-11ea-8ac1-0025b502a04e
            ];
        }

        return json_encode(['success' => true, 'data' => $data,]);
    }

    public function getStreets()
    {
        $data = [];
        $post = Yii::$app->request->post();

        $response = $this->client->createRequest()
            ->setMethod('POST')
            ->setHeaders(['token' => $this->token])
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('addressSearch')
            ->setData([
                    'filters' => [
                        'cityID' => Html::decode($post['cityRef']),
                        'addressDescr' => Html::decode($post['street'] . '%'),
                    ]
                ]
            )
            ->send();
        if ($response->data['status'] != 'OK')
            exit('Запрос не выполнился.');
        foreach ($response->data['result'] as $item) {
            $data[] = [
                'description' => $item['addressDescr']['descrUA'],
                'ref' => $item['addressID']
            ];
        }

        return json_encode(['success' => true, 'data' => $data,]);
    }
}