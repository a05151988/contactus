<?php
// No direct access
defined('_JEXEC') or die;
JText::script('MOD_CONTACTUS_MESSAGE_SENDED');
?>
    <style type="text/css">
        .table th, .table td {
            border: 0;
        }

        .form-control {
            width: 100%;
            padding: 4px 6px;
        }

        .form-control-textarea {
            display: inline-block;
            padding: 4px 6px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            width: 90%;
            resize: vertical;
        }

        .has-error {
            border: 1px solid #e9322d;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
            -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
        }

        <?php echo $css; ?>
    </style>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript">
        jQuery(function () {
            jQuery('#<?php echo $submitBtnId; ?>').click(function () {
                    var valid = true;
                    var first = null;
                    jQuery('#<?php echo $formId; ?> input[type=text],#<?php echo $formId; ?> textarea').each(function (idx, item) {
                        var element = jQuery(item);
                        element.removeClass('has-error');
                        if (element.attr('required') == 'required' && jQuery.trim(element.val()) == "") {
                            if (first == null)
                                first = element;
                            valid = false;
                            element.addClass('has-error');
                        }
                    });
                    if (jQuery('.g-recaptcha').length > 0)
                        jQuery('.g-recaptcha').removeClass('has-error');
                    if (valid) {
                        jQuery.ajax({
                            type: 'POST',
                            data: jQuery('#<?php echo $formId; ?>').serialize(),
                            url: '<?php echo $ajaxUrl; ?>',
                            dataType: 'json',
                            success: function (response) {
                                if (response == true) {
                                    alert(Joomla.JText._('MOD_CONTACTUS_MESSAGE_SENDED'))
                                    <?php if($type == 'button') : ?>
                                    jQuery('#<?php echo $modalId; ?>').modal("hide");
                                    <?php elseif($type == 'page') : ?>
                                    jQuery('#<?php echo $formId; ?> input:not([type=hidden], [type=radio]),#<?php echo $formId; ?> textarea').val("")
                                    <?php endif; ?>
                                    <?php if ($recaptcha == 1) echo "grecaptcha.reset();"; ?>
                                } else if (response == 'recaptcha') {
                                    jQuery('.g-recaptcha').addClass('has-error');
                                }
                            }
                        });
                    } else
                        first.focus();
                }
            )
        })
    </script>
<?php if ($type == 'button'): ?>
    <script type="text/javascript">
        jQuery(function () {
            jQuery('#<?php echo $btnId; ?>').click(function () {
                jQuery('#<?php echo $formId; ?> input:not([type=hidden], [type=radio]),#<?php echo $formId; ?> textarea').val("")
                jQuery('#<?php echo $modalId; ?>').modal("show");
            })
        })
    </script>
    <div id="<?php echo $id; ?>">
        <button class="<?php echo $btnClass; ?>" id="<?php echo $btnId; ?>" style="<?php echo $btnStyle; ?>"
                type="button">
            <i class="<?php echo $btnIconClass; ?>"></i>&nbsp;<?php echo $btnLabel ?>
        </button>
        <div class="modal fade" id="<?php echo $modalId; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>
                            <i class="<?php echo $formTitleIconClass; ?>"></i>&nbsp;<?php echo $formTitle; ?>
                        </h4>
                        <?php if ($formTitleDesc) : ?>
                            <small><?php echo $formTitleDesc; ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="<?php echo $formId; ?>">
                            <table class="table">
                                <tbody>
                                <?php foreach ($elements as $element) : ?>
                                    <tr>
                                        <th style="width:30%;">
                                            <span><?php echo $element->label; ?></span>
                                            <?php if ($element->required == true): ?>
                                                <span style="color:red;font-weight:bold;">(*)</span>
                                            <?php endif; ?>
                                        </th>
                                        <td style="width:70%;">
                                            <?php if ($element->type == 'text') : ?>
                                                <input name="<?php echo $element->name; ?>" type="text"
                                                       style="margin-bottom:0;"
                                                       class="form-control" <?php if ($element->required) echo "required"; ?>
                                                       placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>">
                                            <?php elseif ($element->type == 'textarea') : ?>
                                                <textarea name="<?php echo $element->name; ?>" style="margin-bottom:0;"
                                                          class="form-control-textarea" <?php if ($element->required) echo "required"; ?>
                                                          placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>"></textarea>
                                            <?php elseif ($element->type == 'select') : ?>
                                                <select name="<?php echo $element->name; ?>"
                                                        class="form-control" <?php if ($element->required) echo "required"; ?>
                                                        style="margin-bottom:0;">
                                                    <?php foreach ($element->options as $option) : ?>
                                                        <option
                                                            value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php elseif ($element->type == 'radio') : ?>
                                                <?php foreach ($element->options as $idx => $option) : ?>
                                                    <label><input name="<?php echo $element->name; ?>" type="radio"
                                                                  value="<?php echo $option; ?>" <?php if ($idx == 0) echo "checked"; ?>>&nbsp;&nbsp;<?php echo $option; ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if ($recaptcha == 1) : ?>
                                    <tr>
                                        <th>
                                            <?php echo JText::_('MOD_CONTACTS_RECAPTCHA_VERIFY'); ?>
                                            <span style="color:red;font-weight:bold;">(*)</span>
                                        </th>
                                        <td>
                                            <div class="g-recaptcha"
                                                 data-sitekey="6LfBmR8TAAAAABsmHItuTYTG_I-EAzS7m5im3ymg"></div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2" style="color:red;font-weight:bold;">
                                        <?php echo JText::_('MOD_CONTACTUS_MESSAGE_STAR_IS_REQUIRED'); ?>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="<?php echo $sendBtnClass; ?>" id="<?php echo $submitBtnId; ?>" type="button"
                                style="<?php echo $sendBtnStyle; ?>">
                            <i class="<?php echo $sendBtnIconClass; ?>"></i>&nbsp;<?php echo $sendBtnLabel ?>
                        </button>
                        <button class="<?php echo $closeBtnClass; ?>" data-dismiss="modal" type="button"
                                style="<?php echo $closeBtnStyle; ?>">
                            <i class="<?php echo $closeBtnIconClass; ?>"></i>&nbsp;<?php echo $closeBtnLabel ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php elseif ($type == 'page'): ?>
    <div id="<?php echo $id; ?>">
        <form method="POST" id="<?php echo $formId; ?>">
            <table class="table">
                <thead>
                <tr>
                    <th colspan="2">
                        <h4>
                            <i class="<?php echo $formTitleIconClass; ?>"></i>&nbsp;<?php echo $formTitle; ?>
                        </h4>
                        <?php if ($formTitleDesc) : ?>
                            <small><?php echo $formTitleDesc; ?></small>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($elements as $element) : ?>
                    <tr>
                        <th style="width:30%;">
                            <span><?php echo $element->label; ?></span>
                            <?php if ($element->required == true): ?>
                                <span style="color:red;font-weight:bold;">(*)</span>
                            <?php endif; ?>
                        </th>
                        <td style="width:70%;">
                            <?php if ($element->type == 'text') : ?>
                                <input name="<?php echo $element->name; ?>" type="text"
                                       style="margin-bottom:0;"
                                       class="form-control" <?php if ($element->required) echo "required"; ?>
                                       placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>">
                            <?php elseif ($element->type == 'textarea') : ?>
                                <textarea name="<?php echo $element->name; ?>" style="margin-bottom:0;"
                                          class="form-control-textarea" <?php if ($element->required) echo "required"; ?>
                                          placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>"></textarea>
                            <?php elseif ($element->type == 'select') : ?>
                                <select name="<?php echo $element->name; ?>"
                                        class="form-control" <?php if ($element->required) echo "required"; ?>
                                        style="margin-bottom:0;">
                                    <?php foreach ($element->options as $option) : ?>
                                        <option
                                            value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php elseif ($element->type == 'radio') : ?>
                                <?php foreach ($element->options as $idx => $option) : ?>
                                    <label><input name="<?php echo $element->name; ?>" type="radio"
                                                  value="<?php echo $option; ?>" <?php if ($idx == 0) echo "checked"; ?>>&nbsp;&nbsp;<?php echo $option; ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($recaptcha == 1) : ?>
                    <tr>
                        <th>
                            <?php echo JText::_('MOD_CONTACTS_RECAPTCHA_VERIFY'); ?>
                            <span style="color:red;font-weight:bold;">(*)</span>
                        </th>
                        <td>
                            <div class="g-recaptcha"
                                 data-sitekey="6LfBmR8TAAAAABsmHItuTYTG_I-EAzS7m5im3ymg"></div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="2" style="color:red;font-weight:bold;">
                        <?php echo JText::_('MOD_CONTACTUS_MESSAGE_STAR_IS_REQUIRED'); ?>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <button class="<?php echo $sendBtnClass; ?>" id="<?php echo $submitBtnId; ?>" type="button"
                                style="<?php echo $sendBtnStyle; ?>">
                            <i class="<?php echo $sendBtnIconClass; ?>"></i>&nbsp;<?php echo $sendBtnLabel ?>
                        </button>
                    </th>
                </tr>
                </tfoot>
            </table>
        </form>
    </div>
<?php endif;