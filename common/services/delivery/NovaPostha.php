<?php

namespace common\services\delivery;

use yii\helpers\Html;
use yii\httpclient\Client;
use Yii;

class NovaPostha implements DeliveryService
{
    protected $client;
    protected $url;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client(['transport' => 'yii\httpclient\CurlTransport']); // only cURL supports the options we need
        $this->url = Yii::$app->params['novaposhta']['url'];
        $this->apiKey = Yii::$app->params['novaposhta']['apiKey'];
    }

    public function getCities()
    {
        $data = [];
        $post = Yii::$app->request->post();

        $response = $this->client->createRequest()
            ->setMethod('GET')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($this->url)
            ->setData([
                'apiKey' => $this->apiKey,
                'modelName' => 'Address',
                'calledMethod' => 'getCities',
                'methodProperties' => [
                    'FindByString' => Html::decode($post['city'])
                ]
            ])
            ->send();
        if (!$response->data['success'])
            return json_encode(['success' => false, 'data' => $response->data['errors'],]);
        foreach ($response->data['data'] as $item) {
            $data[$item['AreaDescription']][] = [
                'description' => $item['Description'], //Дніпрельстан
                'ref' => $item['Ref'], //eb54475c-e5e4-11e9-b48a-005056b24375
                'type' => $item['SettlementTypeDescription'], //село
                'area' => $item['AreaDescription'] //Запорізька
            ];
        }

        return json_encode(['success' => true, 'data' => $data,]);
    }

    public function getWarehouses()
    {
        $data = [];
        $post = Yii::$app->request->post();

        $response = $this->client->createRequest()
            ->setMethod('GET')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($this->url)
            ->setData([
                'apiKey' => $this->apiKey,
                'modelName' => 'AddressGeneral',
                'calledMethod' => 'getWarehouses',
                'methodProperties' => [
                    'CityRef' => Html::decode($post['cityRef']),
                    'TypeOfWarehouseRef' => '9a68df70-0267-42a8-bb5c-37f427e36ee4' //Вантажне відділення
                ]
            ])
            ->send();
        if (!$response->data['success'])
            return json_encode(['success' => false, 'data' => $response->data['errors'],]);
        foreach ($response->data['data'] as $item) {
            $data[] = [
                'description' => $item['Description'], //Пункт приймання-видачі (до 30 кг): вул. Леніна, 54
                'ref' => $item['Ref'] //3294c49f-23ea-11ea-8ac1-0025b502a04e
            ];
        }

        return json_encode(['success' => true, 'data' => $data,]);
    }

    public function getStreets()
    {
        $data = [];
        $post = Yii::$app->request->post();

        $response = $this->client->createRequest()
            ->setMethod('GET')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($this->url)
            ->setData([
                'apiKey' => $this->apiKey,
                'modelName' => 'Address',
                'calledMethod' => 'getStreet',
                'methodProperties' => [
                    'CityRef' => Html::decode($post['cityRef']),
                    'FindByString' => Html::decode($post['street'])
                ]
            ])
            ->send();
        if (!$response->data['success'])
            return json_encode(['success' => false, 'data' => $response->data['errors'],]);
        foreach ($response->data['data'] as $item) {
            $data[] = [
                'description' => $item['Description'] . ' ' . $item['StreetsType'],
                'ref' => $item['Ref']
            ];
        }

        return json_encode(['success' => true, 'data' => $data,]);
    }
}