<?php

class ModContactUsHelper
{
    public static function sendMailAjax()
    {
        $tokenRes = JSession::checkToken('get');
        if (!$tokenRes) {
            echo json_encode('token');
            return;
        }
        $title = JRequest::getVar("title");
        $data = JRequest::get('post');
        $user = JFactory::getUser();
        $name = ($user) ? $user->name : "使用者";
        $module = JModuleHelper::getModule('mod_contactus', $title);
        $params = new JRegistry($module->params);
        $recaptcha = (int) $params->get('recaptcha_verify', '1');
        if($recaptcha == 1 && JRequest::getVar("g-recaptcha-response") == ""){
            echo json_encode('recaptcha');
            return;
        }
        $form_elements = explode("\r\n", $params->get('form_elements', NULL));
        $elements = array();
        for ($i = 0; $i < count($form_elements); $i++) {
            $tmp = explode("|", $form_elements[$i]);
            if (count($tmp) < 4) continue;
            $elements[$tmp[1]] = $tmp[0];
        }
        $html = array();
        foreach ($data as $idx => $value) {
            if($idx == 'g-recaptcha-response') continue;
            $html[] = "<div style='margin-left:20px;'>".$elements[$idx] . "：" . $value."</div>";
        }
        $html = implode("", $html);
        $config = JFactory::getConfig();
        $email = array("email" => $config->get("smtpuser"), "name" => $config->get("sitename"));
        $subject = $title . "：" . JRequest::getVar('question_title');
        $body = JText::sprintf("MOD_CONTACTUS_MESSAGE_BODY", $name, date("Y-m-d H:i:s"), $html);
        $mail = JFactory::getMailer();
        $mail->setSender(array($email["email"], $email["name"]));
        $mail->addRecipient($email["email"]);
        $mail->setSubject($subject);
        $mail->setBody($body);
        $mail->addReplyTo($email["email"], $email["name"]);
        $mail->isHtml(true);
        $result = $mail->Send();
        echo json_encode($result);
    }
}