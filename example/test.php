<?php

use NspTeam\WeiWork\CorpAPI;

require_once "../vendor/autoload.php";

$template_id = '3WK7KC6itNzq4h8bXusv52JodxUUWwDSa7kUPugG';
$corpAPI = new CorpAPI('ww63cb313218abe8d6', '5zuqiATsOUNNujNqZ1hGo6K-FcB1boSajaconRDVMcI', '3010040');
//$accessToken = $corpAPI->getAccessToken();
//var_dump($accessToken);
$corpAPI->access_token = 'zgjxz47u3RTG1OP0s_zyT_Nen9Ol98eYJlYCB2QTyoiCgnYvhnYHNV3nWOODLTT0K6bMCn10O3rhBZgm44pbiCLqXum-THTz2ShudGTnSjnVqIJyvDhDfDTFFQGZNE3gR0yvJgaVUJw2g6ONkgibEjUgaEbQS_8wyNFAN4dHpJmPNHboHscB2s7H_Z_-PmZCQFEuMYXXtVv92AFkwvOA2Q';


$templateData = $corpAPI->getApprovalTemplateData($template_id);
var_dump($templateData);
var_dump($templateData['body']);


//
//$creator_userid= 'MaoXingPei';
//$apply_data= [
//    array(
//        'control_type' => 'Textarea',
//        'control_id' => 'Textarea-1637636930315',
//        'control_value' => [
//            'text' => '江苏苗易宝科技有限公司'
//        ],
//    ),
//    array(
//        'control_type' => 'Table',
//        'control_id' => 'Table-1637635645139',
//        'control_value' => [
//            'children' => array(
//                [
//                    'list' => array(
//                        [
//                            'control' => 'Text',
//                            'id' => 'Text-1637635646995',
//                            'value' => array(
//                                'text' => '香樟'
//                            ),
//                        ],
//                        [
//                            'control' => 'Text',
//                            'id' => 'Text-1637635655151',
//                            'value' => array(
//                                'text' => '胸径10cm'
//                            ),
//                        ],
//                        [
//                            'control' => 'Textarea',
//                            'id' => 'Textarea-1637635660866',
//                            'value' => array(
//                                'text' => "苗圃：夏溪苗圃\n造型分类：园林\n 操作者：王显示\n 最低折扣：0.96"
//                            ),
//                        ],
//                        [
//                            'control' => 'Money',
//                            'id' => 'Money-1637635672768',
//                            'value' => array(
//                                'new_money' => "65000.42"
//                            ),
//                        ],
//                        [
//                            'control' => 'Text',
//                            'id' => 'Text-1637635684681',
//                            'value' => array(
//                                'text' => "0.96"
//                            ),
//                        ],
//                        [
//                            'control' => 'Text',
//                            'id' => 'Text-1637635692702',
//                            'value' => array(
//                                'text' => "0.50"
//                            ),
//                        ],
//                    )
//                ],
//                [
//                    'list' => array(
//                        [
//                            'control' => 'Text',
//                            'id' => 'Text-1637635646995',
//                            'value' => array(
//                                'text' => '榉树'
//                            ),
//                        ],
//                        [
//                            'control' => 'Text',
//                            'id' => 'Text-1637635655151',
//                            'value' => array(
//                                'text' => '胸径10cm'
//                            ),
//                        ],
//                        [
//                            'control' => 'Textarea',
//                            'id' => 'Textarea-1637635660866',
//                            'value' => array(
//                                'text' => "苗圃：夏溪苗圃\n造型分类：园林\n 操作者：王显示\n 最低折扣：0.96"
//                            ),
//                        ],
//                        [
//                            'control' => 'Money',
//                            'id' => 'Money-1637635672768',
//                            'value' => array(
//                                'new_money' => "65000.42"
//                            ),
//                        ],
//                        [
//                            'control' => 'Text',
//                            'id' => 'Text-1637635684681',
//                            'value' => array(
//                                'text' => "0.96"
//                            ),
//                        ],
//                        [
//                            'control' => 'Text',
//                            'id' => 'Text-1637635692702',
//                            'value' => array(
//                                'text' => "0.50"
//                            ),
//                        ],
//                    )
//                ],
//            )
//        ],
//    ),
//];
//$approval_user= ['MaoXingPei', 'WangZhongLiang'];
//$notify= [];
//$summary_text= ['采购明细单申请微调折扣比例'];
//$approval = $corpAPI->submitApplicationForApproval($creator_userid, $template_id, $apply_data, $approval_user, $summary_text, $notify);
//var_dump($approval);

