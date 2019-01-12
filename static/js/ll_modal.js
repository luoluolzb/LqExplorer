/**
 * 模态框：操作提示、操作确认、信息输入
 * 此功能建立在bootstrap的模态框插件基础上
 * @author luoluolzb
**/

var ll_Modal = {};

/**
 * 显示一条提示信息
 */
ll_Modal.Show = function(text, title) {
	if (title) {
		$('#ll-alertModal-title').html(title);
	} else {
		$('#ll-alertModal-title').html('提示消息');
	}
	$('#ll-alertModal-content').html(text);
	$('#ll-alertModal-toggle').trigger('click');
}

/**
 * 显示一个对话框
 */
ll_Modal.Dialog = function(options) {
	if (options.title) {
		$('#ll-dialogModal-title').html(options.title);
	} else {
		$('#ll-dialogModal-title').html('提示消息');
	}
	if (options.content) {
		$('#ll-dialogModal-content').html(options.content);
	} else {
		$('#ll-dialogModal-content').html('');
	}
	if (options.yesFun) {
		$('#ll-dialogModal-yesBtn').one('click', options.yesFun);
	}
	if (options.noFun) {
		$('#ll-dialogModal-noBtn').one('click', options.noFun);
	}
	$('#ll-dialogModal-toggle').trigger('click');
}

/**
 * 显示一个输入框
 */
ll_Modal.Input = function(options) {
	if (options.title) {
		$('#ll-InputModal-title').html(options.title);
	} else {
		$('#ll-InputModal-title').html('提示消息');
	}
	if (options.label) {
		$('#ll-InputModal-label').html(options.label);
	} else {
		$('#ll-InputModal-label').html('');
	}
	if (options.inputText) {
		$('#ll-InputModal-input').val(options.inputText);
	} else {
		$('#ll-InputModal-input').val('');
	}
	if (options.yesFun) {
		$('#ll-InputModal-yesBtn').one('click', options.yesFun);
	}
	if (options.noFun) {
		$('#ll-InputModal-noBtn').one('click', options.noFun);
	}
	$('#ll-InputModal-toggle').trigger('click');
}

/**
 * 获取输入框内容
 */
ll_Modal.GetInput = function() {
	return $('#ll-InputModal-input').val();
}

$(function(){
	$(document.body).append($('\
	<div class="modal-container">\
		<!-- Button trigger modal -->\
		<button type="button" data-toggle="modal" data-target="#ll-alertModal" class="hidden" id="ll-alertModal-toggle"></button>\
		\
		<!-- msg Modal -->\
		<div class="modal fade" id="ll-alertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
		  <div class="modal-dialog">\
		    <div class="modal-content">\
		      <div class="modal-header">\
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>\
		        <h4 class="modal-title" id="ll-alertModal-title">提示消息</h4>\
		      </div>\
		      <div class="modal-body" id="ll-alertModal-content"></div>\
		      <div class="modal-footer">\
		        <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>\
		      </div>\
		    </div>\
		  </div>\
		</div>\
		\
		<!-- Button trigger modal -->\
		<button type="button" data-toggle="modal" data-target="#ll-dialogModal" class="hidden" id="ll-dialogModal-toggle"></button>\
		\
		<!-- dialogmsg Modal -->\
		<div class="modal fade" id="ll-dialogModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
		  <div class="modal-dialog">\
		    <div class="modal-content">\
		      <div class="modal-header">\
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>\
		        <h4 class="modal-title" id="ll-dialogModal-title">操作提示</h4>\
		      </div>\
		      <div class="modal-body" id="ll-dialogModal-content"></div>\
		      <div class="modal-footer">\
		        <button type="button" class="btn btn-primary" data-dismiss="modal" id="ll-dialogModal-yesBtn">确定</button>\
		        <button type="button" class="btn btn-default" data-dismiss="modal" id="ll-dialogModal-noBtn">取消</button>\
		      </div>\
		    </div>\
		  </div>\
		</div>\
	</div>\
	\
	<!-- Button trigger modal -->\
	<button type="button" data-toggle="modal" data-target="#ll-InputModal" class="hidden" id="ll-InputModal-toggle"></button>\
	\
	<div class="modal fade" id="ll-InputModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
		  <div class="modal-dialog">\
		    <div class="modal-content">\
		      <div class="modal-header">\
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>\
		        <h4 class="modal-title" id="ll-InputModal-title">操作提示</h4>\
		      </div>\
		      <div class="modal-body">\
				<label for="ll-InputModal-input" id="ll-InputModal-label"></label>\
				<input type="text" class="form-control" id="ll-InputModal-input" />\
		      </div>\
		      <div class="modal-footer">\
		        <button type="button" class="btn btn-primary" data-dismiss="modal" id="ll-InputModal-yesBtn">确定</button>\
		        <button type="button" class="btn btn-default" data-dismiss="modal" id="ll-InputModal-noBtn">取消</button>\
		      </div>\
		    </div>\
		  </div>\
	</div>\
	'));
});
