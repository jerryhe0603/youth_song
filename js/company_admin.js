
// ============ 公司 START ======================

function SelectCompanyTel() {
	var url = "../api/api.selectcompanytel.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_tel').html(html);
		  }
	});
}

function AddCompanyTel() {
	var url = "../api/api.selectcompanytel.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_tel').html(html);
		  }
	});
}

function DelCompanyTel(id) {
	var url = "../api/api.selectcompanytel.php?func=del&id="+id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_tel').html(html);
		  }
	});
}


function EditCompanyTel(id,tel_type,co_tel) {
	var url = "../api/api.selectcompanytel.php?func=edit&id="+id+"&teltype_id="+tel_type+"&co_tel="+encodeURIComponent(co_tel);
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_tel').html(html);
		  }
	});
}


function SelectCompanyEmail() {
	var url = "../api/api.selectcompanyemail.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_email').html(html);
		  }
	});
}

function AddCompanyEmail() {
	var url = "../api/api.selectcompanyemail.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_email').html(html);
		  }
	});
}

function DelCompanyEmail(id) {
	var url = "../api/api.selectcompanyemail.php?func=del&id="+id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_email').html(html);
		  }
	});
}

function EditCompanyEmail(id,email_type,co_email) {
	var url = "../api/api.selectcompanyemail.php?func=edit&id="+id+"&emailtype_id="+email_type+"&co_email="+encodeURIComponent(co_email);
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_email').html(html);
		  }
	});
}


/**
* @desc 顯示公司地址
* @return html 回傳公司地址
* @created 2013/12/26
*/
function SelectCompanyAddress() {
	var url = "../api/api.select_company_address.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_address').html(html);
		  }
	});
}

/**
* @desc 新增公司地址
* @return html 回傳公司地址
* @created 2013/12/26
*/
function AddCompanyAddress() {
	var form = $("<form id='myAjaxForm2' name='myAjaxForm2' class='form-horizontal' ENCTYPE=multipart/form-data method='post'>公司地址</form>");
	var error = false;
	var request = $.ajax({
		//url: "./index.php?func=company&action=add_addr",
		url: "../api/api.select_company_address.php?func=add",
		type: "GET",
		dataType: "html"
	});
	 
	request.done(function( msg ) {
		//alert(msg);
		form.append(msg);
	});
	
	request.fail(function( jqXHR, textStatus ) {
		error = true;
	});

	if (error) return false;

	var div = bootbox.dialog({
		message: form,
		buttons: {
			"close" : {
				"label" : "<i class='icon-remove'></i> Close",
				"className" : "btn-small"
			} 
		}
	});
	
	form.on('submit', function(){
		$.ajax({
			//url: "./index.php?func=company&action=add_addr",
			url: "../api/api.select_company_address.php?func=add",
			type: "POST",
			data: $('#myAjaxForm2').serialize(),
			success: function(responseText) {
				console.log(responseText);
				if (responseText.length == 0) {
					//alert('Success');
					div.modal("hide");
					SelectCompanyAddress();
					//form.submit();
					return true;
				} else {
					alert(response);
					return false;
				}
			},
			error: function(xhr){
				alert(xhr.status);
			}
		});
		return false;
	});
}

/**
* @desc 刪除公司地址
* @param id int 目前序號
* @return html 回傳公司地址
* @created 2013/12/26
*/
function DelCompanyAddress(id) {
	var url = "../api/api.select_company_address.php?func=del&id="+id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_address').html(html);
		  }
	});
}

/**
* @desc 編修公司地址
* @param id int 目前序號
* @return html 回傳公司地址
* @created 2013/12/26
*/
function EditCompanyAddress(id) {

	var form = $("<form id='myAjaxForm2' name='myAjaxForm2' class='form-horizontal' ENCTYPE=multipart/form-data method='post'>公司地址</form>");
	var error = false;
	var request = $.ajax({
	  url: "../api/api.select_company_address.php?func=edit&id="+id,
	  type: "GET",
	  dataType: "html"
	});
	 
	request.done(function( msg ) {
		//alert(msg);
		form.append(msg);
	});
	
	request.fail(function( jqXHR, textStatus ) {
		error = true;
	});

	if (error) return false;

	var div = bootbox.dialog({
		message: form,
		buttons: {
			"close" : {
				"label" : "<i class='icon-remove'></i> Close",
				"className" : "btn-small"
			} 
		}
	});
	
	form.on('submit', function(){
		$.ajax({
			url: "../api/api.select_company_address.php?func=edit&id="+id,
			type: "POST",
			data: $('#myAjaxForm2').serialize(),
			success: function(responseText) {
				console.log(responseText);
				if (responseText.length == 0) {
					//alert('Success');
					div.modal("hide");
					SelectCompanyAddress();
					//form.submit();
				} else {
					alert(response);
					return false;
				}
			},
			error: function(xhr){
				alert(xhr.status);
			}
		});
		return false;
	});
	
}


function SelectCompanyDept() {
	var url = "../api/api.selectcompanydept.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_dept').html(html);
		  }
	});
}

function AddCompanyDept() {
	var url = "../api/api.selectcompanydept.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_dept').html(html);
		  }
	});
}

function DelCompanyDept(id,cd_id) {
	var url = "../api/api.selectcompanydept.php?func=del&id="+id+"&cd_id="+cd_id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_dept').html(html);
		  }
	});
}

function EditCompanyDept(id,cd_name,cd_id) {
	var url = "../api/api.selectcompanydept.php?func=edit&id="+id+"&cd_id="+cd_id+"&cd_name="+encodeURIComponent(cd_name);
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_dept').html(html);
		  }
	});
}


/**
* @desc 顯示公司品牌
* @return html 回傳目前的品牌
*/
function SelectCompanyBrand() {
	var url = "../api/api.select_company_brand.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_brand').html(html);
		  }
	});
}


/**
* @desc 新增品牌
* @return html 回傳新增後的 html 欄位
*/
function AddCompanyBrand() {
	var url = "../api/api.select_company_brand.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_brand').html(html);
		  }
	});
}


/**
* @desc 刪除品牌
* @param id int 目前序號
* @param cb_id int 品牌序號
* @return html 回傳刪除後的其他品牌
*/
function DelCompanyBrand(id,cb_id) {
	var url = "../api/api.select_company_brand.php?func=del&id="+id+"&cb_id="+cb_id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_brand').html(html);
		  }
	});
}


/**
* @desc 更新修改後的品牌資訊
* @param id int 目前序號
* @param cb_name string 品牌名稱
* @param cb_id int 品牌序號
* @return html 回傳更新後品牌
*/
function EditCompanyBrand(id,cb_name,cb_id) {
	//cb_name = JSStringEscape(cb_name);
	cb_name = encodeURIComponent(cb_name);
	var url = "../api/api.select_company_brand.php?func=edit&id="+id+"&cb_id="+cb_id+"&cb_name="+cb_name;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_brand').html(html);
		  }
	});
}


/**
* @desc 顯示公司網址
* @return html 回傳目前的網址
*/
function SelectCompanyWww() {
	var url = "../api/api.select_company_www.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_www').html(html);
		  }
	});
}

/**
* @desc 新增公司網址
* @return html 回傳目前的網址
*/
function AddCompanyWww() {
	var url = "../api/api.select_company_www.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_www').html(html);
		  }
	});
}

/**
* @desc 刪除公司網址
* @param id int 目前序號
* @return html 回傳目前的網址
*/
function DelCompanyWww(id) {
	var url = "../api/api.select_company_www.php?func=del&id="+id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_www').html(html);
		  }
	});
}


/**
* @desc 刪除公司網址
* @param id int 目前序號
* @return html 回傳目前的網址
*/
function EditCompanyWww(id,www_type,co_www) {
	var url = "../api/api.select_company_www.php?func=edit&id="+id+"&wwwtype_id="+www_type+"&co_www="+encodeURIComponent(co_www);
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#company_www').html(html);
		  }
	});
}

// ============ 公司 ENDED ======================


//  ============ 公司聯絡人 START ======================

function SelectCoContactTel() {
	var url = "../api/api.select_cocontact_tel.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_tel').html(html);
		  }
	});
}

function AddCoContactTel() {
	var url = "../api/api.select_cocontact_tel.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_tel').html(html);
		  }
	});
}

function DelCoContactTel(id) {
	var url = "../api/api.select_cocontact_tel.php?func=del&id="+id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_tel').html(html);
		  }
	});
}


function EditCoContactTel(id,tel_type,ct_tel) {
	var url = "../api/api.select_cocontact_tel.php?func=edit&id="+id+"&teltype_id="+tel_type+"&ct_tel="+encodeURIComponent(ct_tel);
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_tel').html(html);
		  }
	});
}


function SelectCoContactEmail() {
	var url = "../api/api.select_cocontact_email.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_email').html(html);
		  }
	});
}

function AddCoContactEmail() {
	var url = "../api/api.select_cocontact_email.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_email').html(html);
		  }
	});
}

function DelCoContactEmail(id) {
	var url = "../api/api.select_cocontact_email.php?func=del&id="+id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_email').html(html);
		  }
	});
}

function EditCoContactEmail(id,email_type,ct_email) {
	var url = "../api/api.select_cocontact_email.php?func=edit&id="+id+"&emailtype_id="+email_type+"&ct_email="+encodeURIComponent(ct_email);
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_email').html(html);
		  }
	});
}


function SelectCoContactAddress() {
	var url = "../api/api.select_cocontact_address.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_address').html(html);
		  }
	});
}


function AddCoContactAddress() {
	var url = "../api/api.select_cocontact_address.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_address').html(html);
		  }
	});
}

function DelCoContactAddress(id) {
	var url = "../api/api.select_cocontact_address.php?func=del&id="+id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_address').html(html);
		  }
	});
}

function EditCoContactAddress(id,addr_type,ct_addr) {
	var url = "../api/api.select_cocontact_address.php?func=edit&id="+id+"&addrtype_id="+addr_type+"&ct_addr="+encodeURIComponent(ct_addr);
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#contact_address').html(html);
		  }
	});
}



//  ============ 公司聯絡人 ENDED ======================

// <returns>置換特殊字元之後的字串</returns>
// string raw
// bool inHtmlAttribute
function JSStringEscape(raw, inHtmlAttribute) {
    /*  raw=raw.replace("\r\n","\\n").replace("\r","").replace("\n","\\n");
     if (inHtmlAttribute) 
           raw=raw.replace("\"", "&quot;").replace("'", "\\'");
     else 
          raw=raw.replace("'","\\'").replace("\"","\\\""); */
     return raw;
}
