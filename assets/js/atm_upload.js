const submit_form = function(){
    $("#atm_file_upload").submit();
};

const check_submit = function(){
    let alert_text = "";
    if(typeof $("#file_attach").val() === "undefined" || $("#file_attach").val() === ""){
        alert_text = "กรุณาเลือกไฟล์ที่ต้องการอัพโหลด";
    }

    if(alert_text) {
        return swal("แจ้งเตือน", alert_text, "warning");
    }else{
        submit_form();
    }
};

let activated = true;
const process = function(id){
    swal({
            title: "",
            text: "คุณต้องการประมวลผลไฟล์นี้ใช่หรือไม่",
            type: "warning",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: true
        },
        function(isConfirm) {
            if(isConfirm) {
                activated = false;
                $('.fa-play').addClass('spinner_in').removeClass('fa-play');
                $.post(base_url+"loan_atm/process_file", {id : id},function (res) {
                    console.log(res);
                    if(res.msg === "success") {
                        swal("เสร็จสิ้น!", "", "success");
                        window.location.reload();
                    }else{
                        swal("ไม่สำเร็จ!", "", "error");
                        window.location.reload();
                    }
                    $('.spinner_in').addClass('fa-play').removeClass('spinner_in');
                    activated = true;
                });
            }else{
                swal("ยกเลิกแล้ว", "", "success");
            }
        });
};


const save = function(id){
    if(!activated) return;
    swal({
            title: "",
            text: "คุณต้องบันทึกรายการนี้ใช่หรือไม่",
            type: "warning",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: true
        },
        function(isConfirm) {
            if(isConfirm) {
                activated = false;
                $('.fa-save').addClass('spinner_in').removeClass('fa-save');
                $.post(base_url+"loan_atm/receive_file_transfer_data", {id: id},function (res) {
                    console.log(res);
                    if(res.status === 1){
                        swal("เสร็จสิ้น!", "", "success");
                        window.location.reload();
                    }else{
                        swal("ไม่สำเร็จ!", "", "error");
                        window.location.reload();
                    }
                    $('.spinner_in').addClass('fa-save').removeClass('spinner_in');

                    activated = true;
                });
            }else{
                swal("ยกเลิกแล้ว", "", "success");
            }
        })
};

const delete_file = function(id){
    if(!activated) return;
    swal({
            title: "",
            text: "คุณต้องลบไฟล์ข้อมูลนี้ใช่หรือไม่",
            type: "warning",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: true
        },
        function(isConfirm) {
            if(isConfirm) {
                activated = false;
                $('.fa-save').addClass('spinner_in').removeClass('fa-save');
                $.post(base_url+"loan_atm/delete_file_update", {id: id},function (res) {
                    console.log(res);
                    if(res.status === 1){
                        swal("เสร็จสิ้น!", "", "success");
                        window.location.reload();
                    }else{
                        swal("ไม่สำเร็จ!", "", "error");
                    }
                    $('.spinner_in').addClass('fa-save').removeClass('spinner_in');

                    activated = true;
                });
            }else{
                swal("ยกเลิกแล้ว", "", "success");
            }
        });
};


var call_upload = function(){
    if(!activated) return;
    swal({
        title: "",
        text: "คุณต้องอัพโหลดไฟล์ข้อมูลนี้ใช่หรือไม่",
        type: "warning",
        confirmButtonClass: "btn-primary",
        confirmButtonText: "ตกลง",
        cancelButtonText: "ยกเลิก",
        showCancelButton: true,
        closeOnConfirm: true,
        showLoaderOnConfirm: true
    },function(isConfirm){
        if(isConfirm){
            upload_file_ajax();
        }
    });
}

var upload_file_ajax = function(){
    var file_data = $('#file_attach').prop('files')[0];
    if(file_data != undefined) {
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            type: 'POST',
            url: base_url+"loan_atm/ajax_upload",
            contentType: false,
            processData: false,
            data: form_data,
            success:function(response) {
				console.log(response);
                if(response.status === 1) {
                    swal("อัพไฟล์สำเร็จ", response.msg, "success");
                    setTimeout(function(){
                        window.location.reload();
                    }, 1500)
                } else{
                    swal("อัพไฟล์ไม่สำเร็จ", response.msg, "error");
                }
                activated = true;
                $('#file_attach').val('');
            }
        });
    }else{
        swal("อัพไฟล์ไม่สำเร็จ เนื่องจากเกิดข้อผิดพลาดบางอย่าง");
    }
    return false;
}

$("#upload").prop("disabled", true);

$(function(e){
    $("#file_attach").on('change', function(){
        if(typeof $(this).val() === "undefined" || $(this).val() === ""){
            $("#upload").prop("disabled", true);
            $('#filename').html('ยังไม่ได้เลือกไฟล์');
        }else{
            $("#upload").prop("disabled", false);
        }
       $('#filename').html($(this).val().split('\\').pop());
    });

    $(".mydate").datepicker({
        prevText : "ก่อนหน้า",
        nextText: "ถัดไป",
        currentText: "Today",
        changeMonth: true,
        changeYear: true,
        isBuddhist: true,
        monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
        dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
        constrainInput: true,
        dateFormat: "dd/mm/yy",
        yearRange: "c-50:c+10",
        autoclose: true,
    });
});

function view_receive_file(id){
    const table = $("#verify_list");
    const body = table.find('tbody');
    blockUI();
    $.get(base_url+'loan_atm/check_receive_file', {id: id}, function(xhr){
        unblockUI();
        body.html(xhr);
        $('#modal_receive_check_list').modal('toggle');
    });
}
