<?php
// No direct access
defined('_JEXEC') or die;
JText::script('MOD_CONTACTUS_MESSAGE_SENDED');
?>
    <style type="text/css">
        .form-control {
            width: 100%%;
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
            width: 95%;
            resize: vertical;
        }

        .has-error {
            border: 1px solid #e9322d !important;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        }

        span.star {
            color: red;
            font-weight: bold;
        }

        <?php echo $css; ?>
    </style>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript">
        jQuery(function () {
            jQuery('#<?php echo $submitBtnId; ?>').click(function () {
					var btn = jQuery(this);
					btn.showLoading();
                    var valid = true;
                    var first = null;
                    jQuery('#<?php echo $id; ?> input[type=text],#<?php echo $id; ?> input[type=email],#<?php echo $id; ?> textarea').each(function (idx, item) {
                        var element = jQuery(item);
                        var type = element.attr('type');
                        if(type == 'email'){
                            if(!isEmail(jQuery.trim(element.val())))
                                element.val("");
                        }
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
						var data = jQuery('#<?php echo $id; ?>').find("[name]:not(iframe)");
                        jQuery.ajax({
                            type: 'POST',
                            data: data.serialize(),
                            url: '<?php echo $ajaxUrl; ?>',
                            dataType: 'json',
                            success: function (response) {
                                if (response == true) {
                                    noty({layout:'center',type:'success',theme:'relax',text:jQuery('#<?php echo $msgId; ?>').val()})
                                    <?php if($type == 'button') : ?>
									jQuery('#<?php echo $id; ?> input:not([type=hidden], [type=radio]),#<?php echo $id; ?> textarea').val("")
                                    jQuery('#<?php echo $modalId; ?>').modal("hide");
                                    <?php elseif($type == 'page') : ?>
                                    jQuery('#<?php echo $id; ?> input:not([type=hidden], [type=radio]),#<?php echo $id; ?> textarea').val("")
                                    <?php endif; ?>
                                    <?php if ($recaptcha == 1) echo "grecaptcha.reset();"; ?>
									btn.hideLoading();
                                } else if (response == 'recaptcha') {
                                    jQuery('.g-recaptcha>div>div').addClass('has-error');
									btn.hideLoading();
                                }
                            }
                        });
                    } else{
						btn.hideLoading();
						first.focus();
					}
                        
                }
            )
        })
        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
    </script>
<input type="hidden" id="<?php echo $msgId; ?>" value="<?php echo $sendMessage; ?>">
<?php if ($type == 'button'): ?>
    <script type="text/javascript">
        jQuery(function () {
            jQuery('#<?php echo $btnId; ?>').click(function () {
                jQuery('#<?php echo $id; ?> input:not([type=hidden], [type=radio]),#<?php echo $id; ?> textarea').val("")
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
                            <small class="pull-xs-right"><?php echo $formTitleDesc; ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="modal-body">
						<fieldset>
							<dl class="dl-horizontal">
								<?php foreach ($elements as $element) : ?>
										<dt class="col-sm-3"><?php echo $element->label; ?>
											<?php if ($element->required == true): ?>
												<span class="star">&nbsp;*</span>
											<?php endif; ?>
										</dt>
										<dd class="col-sm-9 text-xs-left">
											<?php if ($element->type == 'text') : ?>
												<input id="<?php echo $element->name; ?>"
													   name="<?php echo $element->name; ?>"
													   type="text"
													   style="margin-bottom:0;"
													   class="form-control" <?php if ($element->required) echo "required"; ?>
													   placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>">
											<?php elseif ($element->type == 'email') : ?>
												<input id="<?php echo $element->name; ?>" name="<?php echo $element->name; ?>"
													   type="email"
													   style="margin-bottom:0;"
													   class="form-control" <?php if ($element->required) echo "required"; ?>
													   placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>">
											<?php elseif ($element->type == 'textarea') : ?>
												<textarea id="<?php echo $element->name; ?>"
														  name="<?php echo $element->name; ?>"
														  style="margin-bottom:0;"
														  class="form-control-textarea" <?php if ($element->required) echo "required"; ?>
														  placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>"></textarea>
											<?php elseif ($element->type == 'select') : ?>
												<select name="<?php echo $element->name; ?>"
														class="c-select" <?php if ($element->required) echo "required"; ?>
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
										</dd>
								<?php endforeach; ?>
								<?php if ($recaptcha == 1) : ?>
									<dt class="col-sm-3">
										<?php echo JText::_('MOD_CONTACTS_RECAPTCHA_VERIFY'); ?>
										<span class="star">&nbsp;*</span>
									</dt>
									<dd class="col-sm-9">
										<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
									</dd>
								<?php endif; ?>
							</dl>
						</fieldset>
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
		<?php if ($formTitle) : ?>
			<h4 style="border-bottom:1px solid #d8d8d8;" class="p-b-1">
				<i class="<?php echo $formTitleIconClass; ?>"></i>&nbsp;<?php echo $formTitle; ?>
			</h4>
		<?php endif; ?>
		<?php if ($formTitleDesc) : ?>
			<small class="pull-xs-right"><?php echo $formTitleDesc; ?></small>
			<div class="clearfix"></div>
		<?php endif; ?>
		<fieldset>
			<dl class="dl-horizontal">
				<?php foreach ($elements as $element) : ?>
						<dt class="col-sm-3"><?php echo $element->label; ?>
							<?php if ($element->required == true): ?>
								<span class="star">&nbsp;*</span>
							<?php endif; ?>
						</dt>
						<dd class="col-sm-9 text-xs-left">
							<?php if ($element->type == 'text') : ?>
								<input id="<?php echo $element->name; ?>"
									   name="<?php echo $element->name; ?>"
									   type="text"
									   style="margin-bottom:0;"
									   class="form-control" <?php if ($element->required) echo "required"; ?>
									   placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>">
							<?php elseif ($element->type == 'email') : ?>
								<input id="<?php echo $element->name; ?>" name="<?php echo $element->name; ?>"
									   type="email"
									   style="margin-bottom:0;"
									   class="form-control" <?php if ($element->required) echo "required"; ?>
									   placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>">
							<?php elseif ($element->type == 'textarea') : ?>
								<textarea id="<?php echo $element->name; ?>"
										  name="<?php echo $element->name; ?>"
										  style="margin-bottom:0;"
										  class="form-control-textarea" <?php if ($element->required) echo "required"; ?>
										  placeHolder="<?php echo JText::_('MOD_CONTACTUS_OPTION_PLEASE_FILL'); ?><?php echo $element->label; ?>"></textarea>
							<?php elseif ($element->type == 'select') : ?>
								<select name="<?php echo $element->name; ?>"
										class="c-select" <?php if ($element->required) echo "required"; ?>
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
						</dd>
				<?php endforeach; ?>
				<?php if ($recaptcha == 1) : ?>
					<dt class="col-sm-3">
						<?php echo JText::_('MOD_CONTACTS_RECAPTCHA_VERIFY'); ?>
						<span class="star">&nbsp;*</span>
					</dt>
					<dd class="col-sm-9">
						<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
					</dd>
				<?php endif; ?>
			</dl>
			<button class="<?php echo $sendBtnClass; ?>" id="<?php echo $submitBtnId; ?>" type="button" style="<?php echo $sendBtnStyle; ?>">
				<i class="<?php echo $sendBtnIconClass; ?>"></i>&nbsp;<?php echo $sendBtnLabel ?>
			</button>
		</fieldset>
    </div>
<?php endif;