<?php
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';
$type = $params->get('show_type', 'button');
$id = $params->get('contactus_id');
$css = $params->get('css');
$recaptcha = (int) $params->get('recaptcha_verify', '1');
$siteKey = $params->get('recaptcha_site_key');
$btnClass = $params->get('btn_class');
$btnStyle = $params->get('btn_style');
$btnIconClass = $params->get('btn_icon_class');
$btnLabel = $params->get('btn_label');
$formTitle = $params->get('form_title',($type == 'button') ? $btnLabel : '');
$formTitleIconClass = $params->get('form_title_icon_class',($type == 'button') ? $btnIconClass : '');
$formTitleDesc = $params->get('form_desc',NULL);
$form_elements = explode("\r\n",$params->get('form_elements',NULL));
$elements = array();
for($i=0;$i<count($form_elements);$i++){
    $tmp = explode("|",$form_elements[$i]);
    if(count($tmp) < 4) continue;
    $elements[$i] = (object) array(
        "label" => $tmp[0],
        "name" => $tmp[1],
        "type" => $tmp[2],
        "required" => ($tmp[3] == "true") ? true : false,
        "options" =>""
    );
    if(isset($tmp[4])){
        $elements[$i]->options = explode(",",$tmp[4]);
    }
}
$sendBtnClass = $params->get('send_btn_class');
$sendBtnStyle = $params->get('send_btn_style');
$sendBtnIconClass = $params->get('send_btn_icon_class');
$sendBtnLabel = $params->get('send_btn_label',JText::_('MOD_CONTACTUS_OPTION_SEND'));
$closeBtnClass = $params->get('close_btn_class');
$closeBtnStyle = $params->get('close_btn_style');
$closeBtnIconClass = $params->get('close_btn_icon_class');
$closeBtnLabel = $params->get('close_btn_label',JText::_('MOD_CONTACTUS_OPTION_CLOSE'));
$sendMessage = $params->get('send_message',JText::_('MOD_CONTACTUS_MESSAGE_SENDED'));
$btnId = "contactus_btn_".strtotime(date('Y-m-d'));
$modalId = "contactus_modal_".strtotime(date('Y-m-d'));
$submitBtnId = "contactus_submit_btn_".strtotime(date('Y-m-d'));
$msgId = "contactus_msg_".strtotime(date('Y-m-d'));
$ajaxUrl ='index.php?option=com_ajax&module=contactus&format=ajax&method=sendMail&title='.$module->title.'&'. JSession::getFormToken() .'=1';
require JModuleHelper::getLayoutPath('mod_contactus');