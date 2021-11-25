<?php

namespace NspTeam\WeiWork\api;

use NspTeam\WeiWork\exception\ParameterError;

trait Approval
{
    /**
     * 获取审批模板详情
     * @param string $template_id 模板的唯一标识id
     * @throws ParameterError
     * @throws \Exception
     */
    public function getApprovalTemplateData(string $template_id): array
    {
        if ($this->access_token === null) {
            throw new ParameterError('access_token参数为空');
        }

        $url = self::DOMAIN . self::GET_APPROVAL_DETAIL;
        $real_url = str_replace("ACCESS_TOKEN", $this->access_token, $url);
        $body = [
            'template_id' => $template_id
        ];

        return post_method($real_url, $body, ["Content-type" => "application/json"]);
    }

    /**
     * 获取审批申请详情
     * @param string $sp_no
     * @return array
     * @throws ParameterError
     * @throws \Exception
     */
    public function getApprovalDetail(string $sp_no): array
    {
        if ($this->access_token === null) {
            throw new ParameterError('access_token参数为空');
        }

        $url = self::DOMAIN . self::GET_APPROVAL_DETAIL;
        $real_url = str_replace("ACCESS_TOKEN", $this->access_token, $url);
        $body = [
            'sp_no' => $sp_no
        ];

        return post_method($real_url, $body, ["Content-type" => "application/json"]);
    }

    /**
     * 提交审批申请
     * @param string $creator_userid 申请人userid，此审批申请将以此员工身份提交，申请人需在应用可见范围内
     * @param string $template_id   模板id
     * @param array $apply_data     审批申请数据
     * @param array $approval_user  审批用户：['MaoXingPei', 'WangZhongLiang']
     * @param array $summary_text   摘要信息: ['摘要1'，'摘要2']
     * @param array $notify
     * @return array
     * @throws \Exception
     */
    public function submitApplicationForApproval(string $creator_userid, string $template_id, array $apply_data, array $approval_user, array $summary_text=[], array $notify = []): array
    {
        // 审批申请数据多维
        $apply_data_content = array();
        foreach ($apply_data as $apply_datum) {
            $apply_data_content[] = [
                'control' => $apply_datum['control_type'],  // 控件类型
                'id' => $apply_datum['control_id'],         // 控件id
                'value' => $apply_datum['control_value'],   // 控件值
            ];
        }

        // 摘要信息
        $summary_list = array();
        foreach ($summary_text as $text) {
            $summary_list[] = array(
                'summary_info' => array(
                    array(
                        'text' => $text,  //不要超过20个字符
                        'lang' => 'zh_CN'
                    )
                )
            );
        }

        $url = self::DOMAIN . self::SUBJECT_APPROVAL;
        $real_url = str_replace("ACCESS_TOKEN", $this->access_token, $url);
        $body = [
            'creator_userid' => $creator_userid,
            'template_id' => $template_id,
            'use_template_approver' => 0,   // 审批人模式：0-通过接口指定审批人、抄送人（此时approver、notifyer等参数可用）; 1-使用此模板在管理后台设置的审批流程，支持条件审批。默认为0
            'approver' => array(
                array(
                    'attr' => 2,        // 节点审批方式：1-或签；2-会签，仅在节点为多人审批时有效
                    'userid' => $approval_user,     // 审批节点审批人userid列表，若为多人会签、多人或签，需填写每个人的userid
                )
            ),
            'apply_data' => array(
                'contents' => $apply_data_content
            ),
            'summary_list' => $summary_list
        ];
        // 抄送人
        if (!empty($notify) && $body['use_template_approver'] === 0) {
            $body['notifyer'] = $notify['notifier'];        // 抄送人节点userid列表，仅use_template_approver为0时生效。
            $body['notify_type'] = $notify['notify_type'];  // 抄送方式：1-提单时抄送（默认值）； 2-单据通过后抄送；3-提单和单据通过后抄送。仅use_template_approver为0时生效
        }
        return post_method($real_url, $body, ["Content-type" => "application/json"]);
    }
}