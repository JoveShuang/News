/*
 * 添加按钮操作
 */
$('#button-add').click(function(){
    var url = SCOPE.add_url;
    window.location.href = url;
});
/**
 * 提交form表单操作
 *
 */
$("#singcms-button-submit").click(function(){
    var data = $("#singcms-form").serializeArray();
    postData = {};
    $(data).each(function(i){
        postData[this.name] = this.value;
    });
    console.log(postData);
    //将获取到的数据post给服务器
    url = SCOPE.save_url;
    jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.status == 1){
            //success
            return dialog.success(result.message,jump_url);
        }else if(result.status == 0){
            //fail
            return dialog.error(result.message);
        }
    },"JSON");
});
/**
 * 编辑模式
 */
$('.singcms-table #singcms-edit').on('click',function(){
    var id = $(this).attr('attr-id');
    var url = SCOPE.edit_url + '&id='+id;
    window.location.href = url;
});