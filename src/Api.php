<?php

namespace NspTeam\WeiWork;

interface Api
{
    public const USER_CREATE = '/cgi-bin/user/create?access_token=ACCESS_TOKEN';
    public const USER_GET = '/cgi-bin/user/get?access_token=ACCESS_TOKEN';
    public const USER_UPDATE = '/cgi-bin/user/update?access_token=ACCESS_TOKEN';
    public const USER_DELETE = '/cgi-bin/user/delete?access_token=ACCESS_TOKEN';
    public const USER_BATCH_DELETE = '/cgi-bin/user/batchdelete?access_token=ACCESS_TOKEN';
    public const USER_SIMPLE_LIST = '/cgi-bin/user/simplelist?access_token=ACCESS_TOKEN';
    public const USER_LIST = '/cgi-bin/user/list?access_token=ACCESS_TOKEN';
    public const USERID_TO_OPENID = '/cgi-bin/user/convert_to_openid?access_token=ACCESS_TOKEN';
    public const OPENID_TO_USERID = '/cgi-bin/user/convert_to_userid?access_token=ACCESS_TOKEN';
    public const USER_AUTH_SUCCESS = '/cgi-bin/user/authsucc?access_token=ACCESS_TOKEN';

    public const DEPARTMENT_CREATE = '/cgi-bin/department/create?access_token=ACCESS_TOKEN';
    public const DEPARTMENT_UPDATE = '/cgi-bin/department/update?access_token=ACCESS_TOKEN';
    public const DEPARTMENT_DELETE = '/cgi-bin/department/delete?access_token=ACCESS_TOKEN';
    public const DEPARTMENT_LIST = '/cgi-bin/department/list?access_token=ACCESS_TOKEN';

    public const TAG_CREATE = '/cgi-bin/tag/create?access_token=ACCESS_TOKEN';
    public const TAG_UPDATE = '/cgi-bin/tag/update?access_token=ACCESS_TOKEN';
    public const TAG_DELETE = '/cgi-bin/tag/delete?access_token=ACCESS_TOKEN';
    public const TAG_GET_USER = '/cgi-bin/tag/get?access_token=ACCESS_TOKEN';
    public const TAG_ADD_USER = '/cgi-bin/tag/addtagusers?access_token=ACCESS_TOKEN';
    public const TAG_DELETE_USER = '/cgi-bin/tag/deltagusers?access_token=ACCESS_TOKEN';
    public const TAG_GET_LIST = '/cgi-bin/tag/list?access_token=ACCESS_TOKEN';

    public const BATCH_JOB_GET_RESULT = '/cgi-bin/batch/getresult?access_token=ACCESS_TOKEN';

    public const BATCH_INVITE = '/cgi-bin/batch/invite?access_token=ACCESS_TOKEN';

    public const AGENT_GET = '/cgi-bin/agent/get?access_token=ACCESS_TOKEN';
    public const AGENT_SET = '/cgi-bin/agent/set?access_token=ACCESS_TOKEN';
    public const AGENT_GET_LIST = '/cgi-bin/agent/list?access_token=ACCESS_TOKEN';

    public const MENU_CREATE = '/cgi-bin/menu/create?access_token=ACCESS_TOKEN';
    public const MENU_GET = '/cgi-bin/menu/get?access_token=ACCESS_TOKEN';
    public const MENU_DELETE = '/cgi-bin/menu/delete?access_token=ACCESS_TOKEN';

    public const MESSAGE_SEND = '/cgi-bin/message/send?access_token=ACCESS_TOKEN';

    public const MEDIA_GET = '/cgi-bin/media/get?access_token=ACCESS_TOKEN';

    public const GET_USER_INFO_BY_CODE = '/cgi-bin/user/getuserinfo?access_token=ACCESS_TOKEN';
    public const GET_USER_DETAIL = '/cgi-bin/user/getuserdetail?access_token=ACCESS_TOKEN';

    public const GET_AGENT_TICKET = '/cgi-bin/ticket/get?access_token=ACCESS_TOKEN&type=agent_config';
    public const GET_JSAPI_TICKET = '/cgi-bin/get_jsapi_ticket?access_token=ACCESS_TOKEN';

    public const GET_CHECKIN_OPTION = '/cgi-bin/checkin/getcheckinoption?access_token=ACCESS_TOKEN';
    public const GET_TEMPLATE_DETAIL = '/cgi-bin/oa/gettemplatedetail?access_token=ACCESS_TOKEN';
    public const GET_APPROVAL_DETAIL = '/cgi-bin/oa/getapprovaldetail?access_token=ACCESS_TOKEN';
    public const SUBJECT_APPROVAL = '/cgi-bin/oa/applyevent?access_token=ACCESS_TOKEN';

    public const GET_INVOICE_INFO = '/cgi-bin/card/invoice/reimburse/getinvoiceinfo?access_token=ACCESS_TOKEN';
    public const UPDATE_INVOICE_STATUS = '/cgi-bin/card/invoice/reimburse/updateinvoicestatus?access_token=ACCESS_TOKEN';
    public const BATCH_UPDATE_INVOICE_STATUS = '/cgi-bin/card/invoice/reimburse/updatestatusbatch?access_token=ACCESS_TOKEN';
    public const BATCH_GET_INVOICE_INFO = '/cgi-bin/card/invoice/reimburse/getinvoiceinfobatch?access_token=ACCESS_TOKEN';

    public const GET_PRE_AUTH_CODE = '/cgi-bin/service/get_pre_auth_code?suite_access_token=SUITE_ACCESS_TOKEN';
    public const SET_SESSION_INFO = '/cgi-bin/service/set_session_info?suite_access_token=SUITE_ACCESS_TOKEN';
    public const GET_PERMANENT_CODE = '/cgi-bin/service/get_permanent_code?suite_access_token=SUITE_ACCESS_TOKEN';
    public const GET_AUTH_INFO = '/cgi-bin/service/get_auth_info?suite_access_token=SUITE_ACCESS_TOKEN';
    public const GET_ADMIN_LIST = '/cgi-bin/service/get_admin_list?suite_access_token=SUITE_ACCESS_TOKEN';
    public const GET_USER_INFO_BY_3RD = '/cgi-bin/service/getuserinfo3rd?suite_access_token=SUITE_ACCESS_TOKEN';
    public const GET_USER_DETAIL_BY_3RD = '/cgi-bin/service/getuserdetail3rd?suite_access_token=SUITE_ACCESS_TOKEN';

    public const GET_LOGIN_INFO = '/cgi-bin/service/get_login_info?access_token=PROVIDER_ACCESS_TOKEN';
    public const GET_REGISTER_CODE = '/cgi-bin/service/get_register_code?provider_access_token=PROVIDER_ACCESS_TOKEN';
    public const GET_REGISTER_INFO = '/cgi-bin/service/get_register_info?provider_access_token=PROVIDER_ACCESS_TOKEN';
    public const SET_AGENT_SCOPE = '/cgi-bin/agent/set_scope';
    public const SET_CONTACT_SYNC_SUCCESS = '/cgi-bin/sync/contact_sync_success';

    public function getAccessToken();
}