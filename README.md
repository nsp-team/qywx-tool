# qywx-tool
企业微信工具库



```injectablephp
use NspTeam\WeiWork\CorpAPI;

require_once "../vendor/autoload.php";

$template_id = '1970325xzcxzczxc16_1761336249_1496795097';
$corpAPI = new CorpAPI('wxxzcxzcxzc12c', 'NzxczxP49PABzxcxzcxzcxzcM', '301000222');
```

### 获取access-token
```injectablephp
$accessToken = $corpAPI->getAccessToken();
var_dump($accessToken);

```

### 获取审批模板详情
```injectablephp
$templateData = $corpAPI->getApprovalTemplateData($template_id);
var_dump($templateData);
```

### 提交审批申请
```injectablephp

$creator_userid= 'MaoXingPei';
$apply_data= [
    array(
        'control_type' => 'Textarea',
        'control_id' => 'Textarea-1637636930315',
        'control_value' => [
            'text' => '江苏科技有限公司'
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
                                'text' => "苗圃：贵州苗圃\n造型分类：园林\n 操作者：王显示\n 最低折扣：0.96"
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
                    )
                ],
            )
        ],
    ),
];
$approval_user= ['MaoPei', 'WangLiang'];
$notify= [];
$summary_text= ['采购明细单申请微调折扣比例'];
$approval = $corpAPI->submitApplicationForApproval($creator_userid, $template_id, $apply_data, $approval_user, $summary_text, $notify);
var_dump($approval);
```