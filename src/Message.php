<?php

namespace Xiaosongshu\Appmessage;

use GuzzleHttp\Client;

class Message
{

    protected $appId = '';
    protected $appkey = "";
    protected $mastersecret = "";
    protected $baseUrl = "";
    protected $packagename = '';

    /**
     * Message constructor.初始化实例
     * @param string $appId appid 必填
     * @param string $appkey appkey 必填
     * @param string $mastersecret mstaersecret 必填
     * @param string $baseUrl baseUrl 必填 个推接口地址
     * @param string $packagename packagename 必填 包名
     */
    public function __construct(string $appId, string $appkey, string $mastersecret, string $baseUrl, string $packagename)
    {
        $this->appId = $appId;
        $this->appkey = $appkey;
        $this->mastersecret = $mastersecret;
        $this->baseUrl = $baseUrl;
        $this->packagename = $packagename;
    }

    /**
     * 发送所有人
     * @param array|string[] $param =[
     *  'title'=>'',
     *  'content'=>'',
     *  'url'=>''
     * ]
     * @return array=[
     *      'code'=>200,
     *      'msg'=>'',
     *      'data'=>[]
     * ]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send_all(array $param = ['title' => '', 'content' => '', 'url' => '']): array
    {
        $token = $this->get_token();

        $client1 = new Client();
        $response = $client1->request(
            'POST',
            $this->baseUrl . '/push/all',
            [
                'headers' => [
                    'content-type' => 'application/json;charset=utf-8',
                    'token' => $token
                ],
                'json' => [//
                    'request_id' => $this->make_request_id(),
                    'group_name' => 'test',
                    'audience' => 'all',
                    'settings' => [
                        'ttl' => 3600000,
                        'strategy' => [
                            'default' => 1,
                            'ios' => 1,
                            'st' => 1,
                            'hw' => 1,
                            'vv' => 1,
                            'op' => 1,
                            'mz' => 1,
                        ]

                    ],
                    'push_message' => [
                        'transmission' => json_encode(
                            [
                                'title' => $param['title'],
                                'body' => $param['content'],
                                'click_type' => 'payload',
                                'payload' => $param['url'],
                            ]
                        ),

                    ],
                    'push_channel' => [
                        'android' => [
                            'ups' => [
                                'notification' => [
                                    'title' => $param['title'],
                                    'body' => $param['content'],
                                    'click_type' => 'intent',
                                    'intent' => 'intent:#Intent;action=android.intent.action.oppopush;launchFlags=0x04000000;component=' . $this->packagename . ';S.UP-OL-SU=true;S.title=' . $param['title'] . ';S.content=' . $param['content'] . ';S.payload=' . $param['url'] . ';end'
                                ],

                            ]
                        ],
                        "ios" => [
                            "type" => "notify",
                            "payload" => $param['url'],
                            "aps" => [
                                "alert" => [
                                    "title" => $param['title'],
                                    "body" => $param['content'],
                                ],
                                "content-available" => 0,
                                "sound" => "default",
                            ],
                            "auto_badge" => "+1",
                            'custom1' => "other information",
                        ]
                    ]
                ],
            ]
        );
        $code = $response->getStatusCode();
        if ($code == 200) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            return ['code' => 200, 'msg' => $data['msg'], 'task_id' => $data['data']];
        } else {
            return ['code' => 400, 'msg' => '消息发送失败！', 'task_id' => []];
        }
    }


    /**
     * 单设备推送
     * @param array|string[] $param =[
     *  'title'=>'',
     *  'content'=>'',
     *  'url'=>'',
     *  'cid'=>'',
     * ]
     * @return array=[
     *      'code'=>200,
     *      'msg'=>'',
     *      'data'=>[]
     * ]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send_one(array $param = ['title' => '', 'content' => '', 'url' => '', 'cid' => '']): array
    {
        $token = $this->get_token();
        $client1 = new Client();
        $response = $client1->request(
            'POST',
            $this->baseUrl . '/push/single/cid',
            [
                'headers' => [
                    'content-type' => 'application/json;charset=utf-8',
                    'token' => $token
                ],
                'json' => [
                    'request_id' => $this->make_request_id(),
                    'group_name' => 'test',
                    'audience' => [
                        'cid' => [$param['cid']]
                    ],
                    'settings' => [
                        'ttl' => 3600000,
                        'strategy' => [
                            'default' => 1,
                            'ios' => 1,
                            'st' => 1,
                        ]

                    ],
                    'push_message' => [
                        'transmission' => json_encode(
                            [
                                'title' => $param['title'],
                                'content' => $param['content'],
                                'click_type' => 'payload',
                                'payload' => $param['url']
                            ]
                        ),
                    ],
                    'push_channel' => [
                        'android' => [
                            'ups' => [
                                'notification' => [
                                    'title' => $param['title'],
                                    'body' => $param['content'],
                                    'click_type' => 'intent',
                                    'intent' => 'intent:#Intent;action=android.intent.action.oppopush;launchFlags=0x04000000;component=' . $this->packagename . ';S.UP-OL-SU=true;S.title=' . $param['title'] . ';S.content=' . $param['content'] . ';S.payload=' . $param['url'] . ';end'
                                ],

                            ]
                        ],
                        "ios" => [
                            "type" => "notify",
                            "payload" => $param['url'],
                            "aps" => [
                                "alert" => [
                                    "title" => $param['title'],
                                    "body" => $param['content'],
                                ],
                                "content-available" => 0,
                                "sound" => "default",
                            ],
                            "auto_badge" => "+1",
                            'custom1' => "other information",
                        ]
                    ]
                ],

            ]
        );
        $code = $response->getStatusCode();

        if ($code == 200) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            return ['code' => 200, 'msg' => $data['msg'], 'data' => $data['data']];
        } else {
            return ['code' => 400, 'msg' => '消息发送失败', 'data' => []];
        }
    }

    /**
     * 批量推送
     * @param array $param =[
     *  'title'=>'',
     *  'content'=>'',
     *  'url'=>'',
     *  'cid'=>[],
     * ]
     * @return array=[
     *      'code'=>200,
     *      'msg'=>'',
     *      'data'=>[]
     * ]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send_list(array $param = ['title' => '', 'content' => '', 'url' => '', 'cids' => []]): array
    {
        $cids = $param['cids'];
        $count = 0;
        $datas = [];
        foreach (array_chunk($cids, 500) as $k => $cid) {
            $task_id = $this->get_task_id($param);
            if (!$task_id) {
                return ['code' => 400, 'msg' => '第' . ($count) . '次消息批量发送失败！失败原因：获取任务Id失败，任务终止。', 'task_id' => []];
            }
            $token = $this->get_token();
            $client1 = new Client();
            $response = $client1->request(
                'POST',
                $this->baseUrl . '/push/list/cid',
                [
                    'headers' => [
                        'content-type' => 'application/json;charset=utf-8',
                        'token' => $token
                    ],
                    'json' => [
                        'request_id' => $this->make_request_id(),
                        'taskid' => $task_id,
                        'group_name' => 'test',
                        'audience' => [
                            'cid' => $cid
                        ],
                        'settings' => [
                            'ttl' => 3600000,
                            'strategy' => [
                                'default' => 1,
                                'ios' => 1,
                                'st' => 1,
                                'hw' => 1,
                                'vv' => 1,
                                'op' => 1,
                                'mz' => 1,
                            ]

                        ],
                        'push_message' => [
                            'transmission' => json_encode(
                                [
                                    'title' => $param['title'],
                                    'body' => $param['content'],
                                    'click_type' => 'payload',
                                    'payload' => $param['url'],
                                ]
                            ),

                        ],
                        'push_channel' => [
                            'android' => [
                                'ups' => [
                                    'notification' => [
                                        'title' => $param['title'],
                                        'body' => $param['content'],
                                        'click_type' => 'intent',
                                        'intent' => 'intent:#Intent;action=android.intent.action.oppopush;launchFlags=0x04000000;component=' . $this->packagename . ';S.UP-OL-SU=true;S.title=' . $param['title'] . ';S.content=' . $param['content'] . ';S.payload=' . $param['url'] . ';end'
                                    ],

                                ]
                            ],
                            "ios" => [
                                "type" => "notify",
                                "payload" => $param['url'],
                                "aps" => [
                                    "alert" => [
                                        "title" => $param['title'],
                                        "body" => $param['content'],
                                    ],
                                    "content-available" => 0,
                                    "sound" => "default",
                                ],
                                "auto_badge" => "+1",
                                'custom1' => "other information",
                            ]
                        ]
                    ],
                ]
            );
            $code = $response->getStatusCode();
            if ($code == 200) {
                $data = $response->getBody()->getContents();
                $data = json_decode($data, true);
                $datas[] = $data['data'];
                $count++;
            } else {
                return ['code' => 400, 'msg' => '第' . ($count) . '次消息批量发送失败！失败原因：其它。任务终止。', 'data' => []];
            }
        }
        return ['code' => 200, 'msg' => '分地区推送消息成功，共分为' . $count . '次推送完成！', 'data' => $datas];
    }

    /**
     * 获取token
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function get_token()
    {
        $timestamp = $this->getMillisecond();
        $client = new Client();
        $sign = $this->sha256($this->appkey . $timestamp . $this->mastersecret);
        $response = $client->request(
            'POST',
            $this->baseUrl . '/auth',
            [
                'header' => [
                    'content-type' => 'application/json;charset=utf-8',
                ],
                'json' => [
                    'sign' => $sign,
                    'timestamp' => $timestamp,
                    'appkey' => $this->appkey
                ],

            ]
        );
        $code = $response->getStatusCode();
        if ($code == 200) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            return $data['data']['token'];
        } else {
            throw new \RuntimeException("获取token失败");
        }
    }

    /**
     * 获取request_id
     * @return string
     */
    protected function make_request_id()
    {
        return 'zlf' . rand(10000, 99999) . rand(100, 999);
    }

    /**
     * 获取时间戳
     * @return float
     */
    protected function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 获取任务id
     * @param array $param
     * @return int|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function get_task_id(array $param)
    {
        $token = $this->get_token();

        $client1 = new Client();
        $response = $client1->request(
            'POST',
            $this->baseUrl . '/push/list/message',
            [

                'headers' => [
                    'content-type' => 'application/json;charset=utf-8',
                    'token' => $token
                ],
                'json' => ([
                    'request_id' => $this->make_request_id(),
                    'group_name' => 'test',
                    'is_async' => false,
                    'settings' => [
                        'ttl' => 360,
                        'strategy' => [
                            'default' => 1,
                            'ios' => 1,
                            'st' => 1,
                            'hw' => 1,
                            'vv' => 1,
                            'op' => 1,
                            'mz' => 1,
                        ]
                    ],
                    'push_message' => [
                        'transmission' => json_encode(
                            [
                                'title' => $param['title'],
                                'body' => $param['content'],
                                'click_type' => 'payload',
                                'payload' => $param['url'],
                            ]
                        ),

                    ],
                    'push_channel' => [
                        'android' => [
                            'ups' => [
                                'notification' => [
                                    'title' => $param['title'],
                                    'body' => $param['content'],
                                    'click_type' => 'intent',
                                    'intent' => 'intent:#Intent;action=android.intent.action.oppopush;launchFlags=0x04000000;component=' . $this->packagename . ';S.UP-OL-SU=true;S.title=' . $param['title'] . ';S.content=' . $param['content'] . ';S.payload=' . $param['url'] . ';end'
                                ],

                            ]
                        ],


                        "ios" => [
                            "type" => "notify",
                            "payload" => $param['url'],
                            "aps" => [
                                "alert" => [
                                    "title" => $param['title'],
                                    "body" => $param['content'],
                                ],
                                "content-available" => 0,
                                "sound" => "default",
                            ],
                            "auto_badge" => "+1",
                            'custom1' => "other information",
                        ]


                    ]
                ]),
            ]
        );
        $code = $response->getStatusCode();
        if ($code == 200) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            return $data['data']['taskid'];
        } else {
            return 0;
        }
    }

    /**
     * sha256加密
     * @param string $str
     * @return string
     */
    protected function sha256(string $str)
    {
        return hash('sha256', $str);
    }

}