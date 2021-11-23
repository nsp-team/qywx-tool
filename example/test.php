<?php

use NspTeam\WeiWork\CorpAPI;

require_once "../vendor/autoload.php";

$template_id = '1970325089020320_1688851305310916_1761336249_1496795097';
$corpAPI = new CorpAPI('wx8b33a83c35e4412c', 'NyxeRFzGP49PABfcdbIm7a4TfeLDOr0gWpcAvVBnu-M', '3010040');
//$accessToken = $corpAPI->getAccessToken();
//var_dump($accessToken);
$corpAPI->access_token = '6nk54CQaeD0OMzQqcuDWxnVhMY12Bh-zq-ofcQVirxMz8zyWGmEiZCtgXwWXAzrzsayFdL3FRiQMezLDjAp5GW4glsv_U0am6jysfOJWnWwNdevGCtdhI9b8M_I-JLynhKA51yQlLP7c6o1tgyr7ehxAhJtIcH0cVWonLJO4xLXpjGU0rkMYFhjjPzcvw8lBF6nU6qEuWZVzJa3jABNu3A';


$templateData = $corpAPI->getApprovalTemplateData($template_id);
var_dump($templateData);



$creator_userid= 'MaoXingPei';
$apply_data= [
    array(
        'control_type' => 'Textarea',
        'control_id' => 'Textarea-1637636930315',
        'control_value' => [
            'text' => '江苏苗易宝科技有限公司'
        ],
    ),
    array(
        'control_type' => 'Table',
        'control_id' => 'Table-1637635645139',
        'control_value' => [
            'children' => array(
                [
                    'list' => array(
                        [
                            'control' => 'Text',
                            'id' => 'Text-1637635646995',
                            'value' => array(
                                'text' => '香樟'
                            ),
                        ],
                        [
                            'control' => 'Text',
                            'id' => 'Text-1637635655151',
                            'value' => array(
                                'text' => '胸径10cm'
                            ),
                        ],
                        [
                            'control' => 'Textarea',
                            'id' => 'Textarea-1637635660866',
                            'value' => array(
                                'text' => "苗圃：夏溪苗圃\n造型分类：园林\n 操作者：王显示\n 最低折扣：0.96"
                            ),
                        ],
                        [
                            'control' => 'Money',
                            'id' => 'Money-1637635672768',
                            'value' => array(
                                'new_money' => "65000.42"
                            ),
                        ],
                        [
                            'control' => 'Text',
                            'id' => 'Text-1637635684681',
                            'value' => array(
                                'text' => "0.96"
                            ),
                        ],
                        [
                            'control' => 'Text',
                            'id' => 'Text-1637635692702',
                            'value' => array(
                                'text' => "0.50"
                            ),
                        ],
                    )
                ],
                [
                    'list' => array(
                        [
                            'control' => 'Text',
                            'id' => 'Text-1637635646995',
                            'value' => array(
                                'text' => '榉树'
                            ),
                        ],
                        [
                            'control' => 'Text',
                            'id' => 'Text-1637635655151',
                            'value' => array(
                                'text' => '胸径10cm'
                            ),
                        ],
                        [
                            'control' => 'Textarea',
                            'id' => 'Textarea-1637635660866',
                            'value' => array(
                                'text' => "苗圃：夏溪苗圃\n造型分类：园林\n 操作者：王显示\n 最低折扣：0.96"
                            ),
                        ],
                        [
                            'control' => 'Money',
                            'id' => 'Money-1637635672768',
                            'value' => array(
                                'new_money' => "65000.42"
                            ),
                        ],
                        [
                            'control' => 'Text',
                            'id' => 'Text-1637635684681',
                            'value' => array(
                                'text' => "0.96"
                            ),
                        ],
                        [
                            'control' => 'Text',
                            'id' => 'Text-1637635692702',
                            'value' => array(
                                'text' => "0.50"
                            ),
                        ],
                    )
                ],
            )
        ],
    ),
];
$approval_user= ['MaoXingPei', 'WangZhongLiang'];
$notify= [];
$summary_text= ['采购明细单申请微调折扣比例'];
$approval = $corpAPI->submitApplicationForApproval($creator_userid, $template_id, $apply_data, $approval_user, $summary_text, $notify);
var_dump($approval);

