/***
 * Control Flow Loan Contract
 * @author by Adisak
 */

/**
 *
 * @author by unknown
 * @modify by Adisak
 */
(function ($) {
    $.fn.inputFilter = function (inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    };
}(jQuery));

/**
 * เปลี่ยนค่า input ของ .input เป็น number format
 * @author by Adisak
 */
$('.number_format').inputFilter(function (value) {
    return /^-?\d*[.,]?\d{0,2}$/.test(value); //currency
});



var condition_garantor_id = "";                     //เงิื่อนไขสำหรับคนค้ำประกัน
var value_check = "";
var garantor_condition = [];
var not_condition = [];
var template_garantor = [];
var operator = {
    '>': function (x, y) {
        return x > y
    },
    '>=': function (x, y) {
        return x >= y
    },
    '<': function (x, y) {
        return x < y
    },
    '<=': function (x, y) {
        return x <= y
    },
    '=': function (x, y) {
        return x == y
    },
    '!=': function (x, y) {
        return x != y
    }
}

/**
 * ตั้งค่า Option สำหรับ LocaleString
 * @type {{maximumFractionDigits: number, minimumFractionDigits: number}}
 * @example
 *  let number = 1000;
 *  number.toLocaleString('en', format_number_option) //result 1000.00
 */
var format_number_option = {minimumFractionDigits: 2, maximumFractionDigits: 2} //


/**
 * เริ่มต้นการทำงาน loan_contract
 * @note ส่วนของตั้งค่า datepicker จำเป็นต้องวางไว้ใน ready
 */
$(document).ready(function () {

    //ตั้งค่า datepicker
    $("#createdatetime, #date_receive_money").datepicker({
        prevText: "ก่อนหน้า",
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

    //TODO: Set contract default data

    if (typeof $('.member_id').val() !== "undefined" && $('.member_id').val() !== "") {             // ตรวจหาว member id ตั้งค่าการกู้เงิน
        termCondition($('#loan_type_select').val());
    }

});

/**
 *
 * @param ele
 */
function format_the_number_decimal(ele) {
    var value = $('#' + ele.id).val();
    value = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    var num = value.split(".");
    var decimal = '';
    var num_decimal = '';
    if (typeof num[1] !== 'undefined') {
        if (num[1].length > 2) {
            num_decimal = num[1].substring(0, 2);
        } else {
            num_decimal = num[1];
        }
        decimal = "." + num_decimal;

    }

    if (value != '') {
        if (value == 'NaN') {
            $('#' + ele.id).val('');
        } else {
            value = (num[0] == '') ? 0 : parseInt(num[0]);
            value = value.toLocaleString() + decimal;
            $('#' + ele.id).val(value);
        }
    } else {
        $('#' + ele.id).val('');
    }
}

/**
 * update ตั้งค่า
 * @author Adisak
 */
$(document).on('change', '#loan_type_select', function () {
    termCondition($(this).val());
});

/**
 * กำหนดวงเงินกู้สูงสุด
 * @param amt
 * @author Adisak
 */
function setMaxLoanLimit(amt) {

    //ToDo คำนวนสิทธิ์กู้สูงสุด
    const salary = removeCommas($("input[name='data[coop_loan][salary]']").val());
    const maxLimit = removeCommas(preferences.credit_limit);
    const multipleSalary = removeCommas(preferences.less_than_multiple_salary);
    const estimateAmt = salary * multipleSalary;

    if(multipleSalary > 0) { //กุ้ได้ x เท่าของเงินเดือน
        amt = estimateAmt >= maxLimit ? maxLimit : estimateAmt;
    }

    loanAmount = creditLimit = typeof amt === "string" ? parseFloat(removeCommas(amt)) : amt;

    $('#max_loan_limit').val(addCommas(amt));
    $('#loan_amount').val(addCommas(amt));
}

/**
 * แปลงค่า number format เป็็น float
 * @param str
 * @returns {number}
 */
function removeCommas(str) {
    if (typeof str === "undefined" || str === "" || str === null) return 0;
    if (typeof str === "number") return str;
    return parseFloat(str.split(',').join(''));
}

/**
 * แปลงค่า numeric เป็น number format
 * @param str
 * @returns {number}
 */
function addCommas(amt) {
    if (typeof amt === 'undefined' || amt === null) {
        return 0;
    }
    if (typeof amt === 'string') {
        amt = parseFloat(removeCommas(amt));
    }
    return amt.toLocaleString('en', format_number_option);
}

function change_type() {
    $.ajax({
        url: base_url + 'loan_contract/change_loan_type',
        method: 'POST',
        data: {
            'type_id': $('#loan_type_choose').val()
        },
        success: function (msg) {
            $('#loan_type_select').html(msg);
        }
    });
    $('#type_name').val($('#type_id :selected').text());
}

var condition = {};
var periodAmt = 0;
var periodMaxAmt = 0;
var creditLimit = 0;
var loanAmount = 0;
var interest = 0;
var estimate = 0;
var deduct = 0;
var memberID = $('.member_id');

var preferences = {};

function setInterest(amt) {

    amt = typeof amt === "string" ? parseFloat(amt) : amt;
    $('#interest_per_year').val(amt);
    interest = amt;
}

/**
 * เริ่มตั้งค่าเงื่อนไขเงินกู้ประเภทต่างๆ
 * @param type_id ประเภทเงินกู้ย่อย จำเป็นในการดึกเงื่อนไข
 */
function termCondition(type_id) {
    if (typeof memberID.val() !== "undefined" && memberID.val() !== "" && $('#loan_id').val() !== "undefined" &&
        $('#loan_id').val() !== "") {
        immune(); //ล๊อกค่าที่ไม่ต้องการให้แก้ไข
        new Promise(getInit).then(initial).then(getContractData).then(setContract).then(shareAndDeposit).then(calcProcessing).then(function(){
            firstSet = true;
        }).catch((err) => {
            console.error("Error:", err);
        })
    } else if (typeof memberID.val() !== "undefined") {
        new Promise(getInit).then(initial).then(shareAndDeposit).then(function(){
            firstSet = true;
        }).catch(err => {
            console.error("Error:", err);
        });

    } else {
        /* TODO: Initial contract crete */
        console.log('do something....');
    }
}

function getInit(resolve, reject) {
    $.post(base_url + '/loan_contract/term_condition', {type_id: $('#loan_type_select').val()}, function (res) {
        setPreferences(res);
        resolve(res);
        return;
    }).error(function (xhr) {
        reject(xhr);
        return;
    });
    return;
}

function initial(res) {
    firstSet = false;
    deductReset();
    setMaxLoanLimit(addCommas(res.credit_limit));
    setMaxPeriod(res.max_period);
    setInterest(res.interest_rate);
    calcProcessing();
    setGuaranteePersonal();
}

/**
 * ตรวจเงื่อนไขหลักเกณการกู้
 */
let requireShareOrDeposit = 0;
function shareAndDeposit() {
    const deductShareDeposit = $('#deduct_blue_deposit');
    if (preferences.share_and_deposit_guarantee === "1") {
        const reqShareDepositPer = removeCommas(preferences.least_share_or_blue_acc_percent);
        requireShareOrDeposit = Math.round(reqShareDepositPer * loanAmount)/100;
        new Promise(resolve => {
            $.post(base_url+'loan_contract/check_share_and_deposit', { member_id: $('.member_id').val() }, function(res){
                if(res.status === 200){
                    const require = removeCommas(deductShareDeposit.val());
                    const amount = requireShareOrDeposit - (removeCommas(res.total)+require);
                    resolve(amount);
                    return;
                }
            })
        }).then((amount) => {
            const current = removeCommas(deductShareDeposit.val());
            if((current+amount) > current) {
                deductShareDeposit.val(addCommas((current+amount))).trigger('blur');
            }else{
                deductShareDeposit.trigger('blur');
            }
        });
    }
}

/**
 * ล๊อก input ที่ไม่ต้องการให้แก้ไข
 */
function immune() {
    $('#loan_type_choose').attr('readonly', 'readonly').attr('disabled', 'disabled');
    $('#loan_type_select').attr('readonly', 'readonly').attr('disabled', 'disabled');
    $('.member_id').attr('readonly', 'readonly');
    $('#test').attr('disabled', 'disabled').removeAttr('data-toggle').removeAttr('href');
}

/**
 * ปลดล๊อก input จาก method immune()
 */
function release() {
    $('#loan_type_choose').removeAttr('readonly').removeAttr('disabled');
    $('#loan_type_select').removeAttr('readonly').removeAttr('disabled');
    $('.member_id').removeAttr('readonly');
    $('#test').removeAttr('disabled').attr('data-toggle', 'modal').attr('href', '#');
}

function checkForm(){

    let txt = "";
    if($('.member_id').val() === ""){
        txt = "กรุณาระบุเลขสมาชิก";
    }else if($('#loan_amount').val() === "" || $('#loan_amount').val() === 0){
        txt = "กรุณาระบุจำนวนเงินขอกู้";
    }else if($('#period_amount').val() === "" || $('#period_amount').val() === 0){
        txt = "กรุณาระบุจำนวนงวด";
    }
    if(txt !== "") {
        swal('กรุณากรอกข้อมูลให้ครบ', txt, 'error');
        return false;
    }else{
        return  true;
    }
}

/**
 *
 */
function calcProcessing() {
    calcPeriod();
    calcDeduct();
    calcEstimateReceive();
    deductDefault();
    calcCost();
    calcEstimateReceive();

    setTimeout(function(){
        prevDeduct();
        setTimeout(function(){
            update_net();
        }, 300);
    }, 1000);

    //shareAndDeposit());
}

function setPreferences(obj) {
    preferences = obj
}

function setLoanAmount(amt) {
    loanAmount = typeof amt === 'string' ? parseFloat(removeCommas(amt)) : amt;
}

function setMaxPeriod(amt) {
    let _amt = periodMaxAmt = typeof amt === "string" ? parseInt(amt) : amt;
    setPeriodAMT(_amt);
}

function setPeriodAMT(amt){
    let _amt = periodAmt = typeof amt === "string" ? parseInt(amt) : amt;
    $('#period_amount').val(_amt);
}

function number_format(e) {
    let amt = $(e).val();
    amt = typeof amt === 'string' ? parseFloat(removeCommas(amt)) : amt;
    amt = amt === 'NaN' ? 0 : amt;
    $(e).val(addCommas(amt));
}

function calcPeriod() {
    let payType = $('input[name="data[coop_loan][pay_type]"]:checked').val();
    let period = 0;
    if (payType === '2') {
        period = effectiveRate();
    } else {
        period = flatRate();
    }

    $('#pay_amount').val(addCommas(period));
}

function flatRate() {
    return  round_nearest((loanAmount/periodAmt), undefined, 'ceil');
}

function effectiveRate() {
    return Math.round((loanAmount * ((interest / 100) / 12)) / (1 - Math.pow(1 / (1 + ((interest / 100) / 12)), periodAmt)));
}

function calcDeduct() {
    $('#deduct_amount').val(addCommas(deduct));
}

function calcEstimateReceive() {
    if(!firstSet) return;
    estimate = round((loanAmount - deduct));
    $('#deduct_pay_prev_loan').val(deduct);
    $('#estimate_money').val(addCommas(estimate));
}

function cal_estimate_money(){
    let total = 0;
    $("input[name^='data[loan_deduct]']").each(function(){
        //console.log($(this).attr('name').substring(18).substring(-1,$(this).attr('name').length-(18+1)), removeCommas($(this).val()));
        total += removeCommas($(this).val());
    });
    estimate = round(loanAmount - total);
    $('#estimate_money').val(addCommas(estimate));
}

/***
 * Guarantee
 */
function setInputDisplay(obj) {
    guaranteeName.val(obj.name);
    guaranteeEstimate.val(addCommas(obj.estimate));
    guaranteeAmount.val(addCommas(obj.amount));
}

function changeInput(x) {

    switch (x) {
        case "1" :
            callGuarantee();
            break;
        case "2" :
            callShare();
            break;
        case "3" :
            callAccount();
            break;
        case "4" :
            callState();
            break;
        default  :
            callDefault();
            break
    }
    calc_personal_guarantee();
}

var guaranteeType = $('.edit-guarantee-type');
var guarantee = $('.edit-guarantee');
var guaranteeName = $('.edit-guarantee-name');
var guaranteeEstimate = $('.edit-guarantee-estimate');
var guaranteeAmount = $('.edit-guarantee-amount');
var guaranteeRemark = $('.edit-guarantee-remark');
var clsList = ['guarantee', 'account'];

function callShare() {

    let member_id = memberID.val();
    let memberName = $('.full_name').val();
    let memberGuaranteeMax = removeCommas($('.share-collect').val()) * 10;
    let memberGuaranteeVal = (memberGuaranteeMax * 90 / 100);

    let shareElement = [
        {name: 'number', val: member_id, display: 'readOnly', type: 'default'},
        {name: 'name', val: memberName, display: 'readOnly'},
        {name: 'estimate', val: memberGuaranteeMax, display: 'readOnly'},
        {name: 'value', val: memberGuaranteeVal, event: [{'onblur': "number_format(this)"}]},
        {name: 'remark'}
    ];
    setEditor(shareElement)

}

function callGuarantee() {
    let guaranteeEle = [
        {name: 'number', type: 'member', display: 'readOnly'},
        {name: 'name', display: 'readOnly'},
        {name: 'estimate', display: 'readOnly'},
        {name: 'value', display: 'readOnly'},
        {name: 'remark'}
    ];
    setEditor(guaranteeEle)
}

function callAccount() {
    let account = [
        {name: 'number', type: 'account', event: [{'onchange': 'setGuaranteeDeposit(this)'}]},
        {name: 'name', display: 'readOnly'},
        {name: 'estimate', display: 'readOnly'},
        {name: 'value', display: 'readOnly'},
        {name: 'remark'}
    ];
    setEditor(account);
}

function callDefault() {
    let defaults = [
        {name: 'number', type: 'default', display: 'readOnly'},
        {name: 'name', display: 'readOnly'},
        {name: 'estimate', display: 'readOnly'},
        {name: 'value', display: 'readOnly'},
        {name: 'remark', display: 'readOnly'}
    ]
    setEditor(defaults)
}

function callState() {
    let state = [
        {name: 'number', type: 'default'},
        {name: 'name', display: ''},
        {name: 'estimate', display: '', event: [{'onblur': 'number_format(this)'}], addClasses: 'number_format'},
        {name: 'value', display: 'readOnly'},
        {name: 'remark'}
    ];
    setEditor(state)
}

function setEditor(obj) {
    obj.forEach(function (item, index) {
        if (typeof item.type !== "undefined") {
            setClassDisplay(item.type);
        }
        if (item.name === 'number') {
            setEditorDisplay(guarantee, item);
        }
        if (item.name === 'name') {
            setEditorDisplay(guaranteeName, item);
        }
        if (item.name === 'estimate') {
            setEditorDisplay(guaranteeEstimate, item);
        }
        if (item.name === 'value') {
            setEditorDisplay(guaranteeAmount, item);
        }
        if (item.name === 'remark') {
            setEditorDisplay(guaranteeRemark, item);
        }
    })
}

function setGuaranteeDeposit(ele) {
    new Promise((resolve, reject) => {
        $('.data .content-guarantee-type').each(function(index){
            if(parseInt($(this).val()) === 3 && parseInt($(ele).val()) === parseInt($(this).closest('.data').find('.content-guarantee-number').val())){
                reject('deposit');
                return;
            }
        });
        resolve();
        return;
    }).then(initDeposit).catch(alertGuaranteeDuplication);

}

function initDeposit() {
    new Promise(findAccountDeposit).then(setInputDisplay).then().catch((err) => {
        console.error('setGuaranteeDeposit Err =>', err)
    });
}

const depositPerGuarantee = 90;

function findAccountDeposit(resolve, reject) {
    try {
        let account_id = $('.edit-guarantee').val();
        accountList.forEach(function (account, index) {
            if (account_id === account.account_id) {
                let data = {};
                data.name = account.account_name;
                data.estimate = account.balance;
                data.amount = (parseFloat(account.balance) * depositPerGuarantee / 100);
                resolve(data);
            }
        });
        return;
    } catch (e) {
        reject(e);
        return;
    }
    return;
}


const events = ['onblur', 'onchange', 'onkeypress', 'onkeyup', 'onkeydown'];// set Event Element
const addClasses = ['number_format']; //set addClass Element
const number_format_config = ['estimate', 'value'];

function setEditorDisplay(ele, obj) {

    if (typeof obj.val !== "undefined" || obj.val !== "") {
        if (number_format_config.indexOf(obj.name) !== -1) {
            ele.val(addCommas(obj.val));
        } else {
            ele.val(obj.val)
        }
    }
    if (obj.display === 'readOnly') {
        ele.attr('readOnly', true);
    } else {
        ele.prop('readOnly', false);
    }

    if (typeof obj.event !== "undefined" && obj.event !== "") {

        obj.event.forEach(function (item, index) {
            //console.log('Event: ', Object.keys(item)[0], Object.values(item)[0]);
            ele.attr(Object.keys(item)[0], Object.values(item)[0]);
            //console.log(ele);
        });

    } else {
        events.forEach(function (event, index) {
            ele.removeAttr(event);
        })
    }

    if (typeof obj.addClasses !== "undefined" && obj.addClasses !== "") {
        if (typeof obj.addClasses === "string") {
            ele.addClass(obj.addClasses);
        }
        if (typeof obj.addClasses === 'object') {
            obj.addClasses.forEach(function (item, index) {
                ele.addClass(Object.keys(item)[0], Object.values(item)[0]);
            });
        }
    } else {
        addClasses.forEach(function (className, index) {
            ele.removeClass(className);
        });
    }
}

function guaranteeModify() {
    $('tr.data').find(':eq(1)').addClass('modify');
}

var contact = $('.content-guarantee');

function setClassDisplay(typeName) {
    typeName = typeof typeName === "undefined" ? 'default' : typeName;
    //console.log('nameType :', typeName);
    if (typeName === 'member') {
        contact.replaceWith(displayGuaranteeMemberSearch)
    } else if (typeName === 'account') {
        new Promise(getMemberAccount).then(createList, errorAccount);
    } else {
        contact.replaceWith(displayGuaranteeDefault);
    }
    contact = $('.content-guarantee');
    guarantee = $('.edit-guarantee');
}

function getMemberAccount(resolve, reject) {
    try {
        $.post(base_url + "loan_contract/getAccountList", {member_id: memberID.val()}, function (res) {
            if (res.status === 200) {
                resolve(res.data);
            } else {
                reject(`Error Ajax status: ${res.status}`);
            }
        })
    } catch (err) {
        reject(`Error during setup: ${err}`);
    }
    return;
}

var accountList = [];


function createList(accList) {

    let accountListUse = [];
    new Promise(((resolve, reject) => {
        const length = $('.data').length;
        $('.data').each(function(index){
           const type =  $(this).closest('tr').find('.content-guarantee-type').val();
           const account = $(this).closest('tr').find('.content-guarantee-number').val();
           if( parseInt(type) === 3 ){
               accountListUse.push(account)
           }

        });
        resolve(accountListUse);

    })).then((a) => {
        accountList = accList;
        let html = '<select class="form-control content-guarantee edit-guarantee" onchange="setGuaranteeDeposit(this)">';
        html += `<option value=""> เลือกบัญชีเงินฝากสหกรณ์ </option>`;
        accList.forEach(function (item, index) {
            let disabled = "";
            if(accountListUse.indexOf(item.account_id) >= 0){
                disabled = " disabled=disabled ";
            }
            html += '<option value="' + item.account_id + '" '+disabled+' >' + item.account_id + '</option>';
        });
        html += '</select>';
        contact.replaceWith(html);
        contact = $('.content-guarantee');
        guarantee = $('.edit-guarantee');
        return;
    });
}

function errorAccount(err) {
    console.log(err);
    contact.replaceWith(displayGuaranteeDefault);
    return;
}

var displayGuaranteeDefault = '<input class="form-control content-guarantee edit-guarantee" type="text" >';

var displayGuaranteeMemberSearch = '<div class="input-group content-guarantee">\
<input class="form-control guarantee_ guarantee_person_id edit-guarantee" type="text" value="" readonly>\
<span class="input-group-btn btn_search_member">\
<button type="button" class="btn btn-info btn-search" onclick="search_member_modal(\'1\')"><span class="icon icon-search"></span></button>\
</span>\
</div>';

/**
 * Add List Table
 */
var guaranteeTable = $('.guarantee-table');

function getEditValue(resolve, reject) {
    try {

        let result = {};
        let editor = guaranteeTable.find('.editor');

        if (typeof editor.find('.edit-guarantee').val() === "undefined" || editor.find('.edit-guarantee').val() === "") {
            const err = {code: 400, msg: `กรุณาระบุข้อมูลหลักประกัน`};
            reject(err);
            return;
        }

        result.typeName = editor.find('.edit-guarantee-type option:selected').text();
        result.typeId = editor.find('.edit-guarantee-type').val();
        result.number = editor.find('.edit-guarantee').val();
        result.name = editor.find('.edit-guarantee-name').val();
        result.estimate = editor.find('.edit-guarantee-estimate').val();
        result.amount = editor.find('.edit-guarantee-amount').val();
        result.remark = editor.find('.edit-guarantee-remark').val();
        console.log(result);
        resolve(result);
        return;
    } catch (e) {
        reject(`Error during :${e}`);
    }
    return;
}

function troubleMessage(reason) {

    if (typeof reason.code === "undefined") {
        console.log(`Error : ${reason}`);
    } else if (reason.code === 400) {
        swal(reason.msg, '', 'warning');
    }
}

function createRow(obj) {

    let counter = guaranteeTable.find('.editor').index();
    let editor = guaranteeTable.find('.editor');

    let row = '<tr class="data">';
    row += `<td class="text-center"><span class="text-label">${counter + 1}</span><input class="data-value" type="hidden" name="data[coop_loan_guarantee][${counter}][counter]" value="${counter}" ></td>`;
    row += `<td><span class="text-label">${obj.typeName}</span><input class="data-value content-guarantee-type" type="hidden" name="data[coop_loan_guarantee][${counter}][type]" value="${obj.typeId}" ></td>`;
    row += `<td><span class="text-label">${obj.number}</span><input class="data-value content-guarantee-number" type="hidden" name="data[coop_loan_guarantee][${counter}][number]" value="${obj.number}" ></td>`;
    row += `<td><span class="text-label">${obj.name}</span><input class="data-value content-guarantee-name" type="hidden" name="data[coop_loan_guarantee][${counter}][name]" value="${obj.name}" ></td>`;
    row += `<td><span class="text-label">${addCommas(obj.estimate)}</span><input class="data-value content-guarantee-estimate" type="hidden" name="data[coop_loan_guarantee][${counter}][estimate]" value="${removeCommas(obj.estimate)}" ></td>`;
    row += `<td><span class="text-label">${addCommas(obj.amount)}</span><input class="data-value content-guarantee-amount" type="hidden" name="data[coop_loan_guarantee][${counter}][amount]" value="${removeCommas(obj.amount)}" ></td>`;
    row += `<td><span class="text-label">${obj.remark}</span><input class="data-value content-guarantee-remark" type="hidden" name="data[coop_loan_guarantee][${counter}][remark]" value="${obj.remark}" ></td>`;
    row += `<td>\
<button class="btn btn-info btn-smaller" type="button" onclick="move(this)"><span class="icon"><i class="fa fa-pencil"></i></span></button>\
<button class="btn btn-danger btn-smaller" type="button" onclick="remove(this)"><span class="icon"><i class="fa fa-trash"></i></span></button>\
</td>`;
    row += "</tr>";
    editor.before(row);
    //editor.find('td:first-child').text(counter+2);

    if (editor.index() !== $('.guarantee-table tbody tr').length) {
        clone = editor;
        editor.remove();
        $('.guarantee-table tbody tr:last').after(clone);
    }
    $('.editor td:first').text('#');
    $('.editor .icon .fa').removeClass('fa-save').addClass('fa-plus');

    if (parseInt(obj.typeId) === 2) {
        disableType(obj.typeId);
    }

}

/**
 * Clear Input Editor Guarantee
 * @param ignore object type
 *        support array object ex. ['apple', 'banana', 'foo', 'bar']
 */
function clearData(ignore) {
    ignore = typeof ignore  === "object" ? ignore : [];
    new Promise((resolve, reject) => {
        try {
            $('[class*="edit-guarantee"]').each(function () {
                if(ignore.length > 0){
                    new Promise((resolve) => {
                        $(this).attr("class").split(" ").forEach(function(item, index){
                            if(item.search('edit-guarantee') !== -1){
                                $(this).val('');
                            }
                        });
                    }).then((key) => {
                        if(ignore.indexOf(key) === -1){
                            $(this).val('');
                        }
                    });
                }else{
                    $(this).val('');
                }
            });
            resolve(true);
            return;
        } catch (e) {
            reject(e);
            return;
        }
    }).then(callDefault).catch((err) => {
        console.log(err);
    })
}

function addGuaranteeType() {
    isModify = true;
    new Promise(getEditValue)
        .then(createRow)
        .then(clearData)
        .catch(troubleMessage)
        .finally(() => {
            console.log("Editor : done.");
        })
}

var previousDeduct = $('.previous-deduct');

function deductDefault() {
    if(PrevLoan === false) return;
    previousDeduct.each(function () {
        console.log($(this).data('type'), parseInt(preferences.type_id));
        if ($(this).data('type') === parseInt(preferences.type_id)) {
            console.log('loan_id :', $(this).val());
            $(this).prop('checked', true);
        }
    });
}

function deductReset(){
    previousDeduct.each(function(){
        $(this).prop('checked', false);
    });
}

/**
 *  Modify Guarantee
 **/

/**
 *
 * @type {null}
 */
var clone = null;


var isModify = true;
var _current = null;

function move(element) {
    const selector = $(element).closest('tr.data');
    let number = selector.index();
    const typeId = selector.find('.content-guarantee-type').val();

    new Promise((resolve => {
        if (isModify === false) {
            addGuaranteeType();
        }
        isModify = false;
        setTimeout(function () {
            resolve(isModify);
            return;
        }, 300);
    })).then((aBoolen) => {
        new Promise(resolve => {
            changeInput(typeId)
            setTimeout(function () {
                resolve();
                return;
            }, 300);
        }).then(() => {
            clone = $('tr.editor').clone();
            $('tr.editor').remove();
            let selector = $('tr.data:eq(' + number + ')');
            selector.after(clone);
            copy(number);
            selector.remove();
        })

    })
}

function copy(index) {

    let selector = $('tr.data:eq(' + index + ')');
    let typeId = selector.find('.content-guarantee-type').val();
    let number = selector.find('.content-guarantee-number').val();
    let name = selector.find('.content-guarantee-name').val();
    let estimate = selector.find('.content-guarantee-estimate').val();
    let amount = selector.find('.content-guarantee-amount').val();
    let remark = selector.find('.content-guarantee-remark').val();

    let editor = $('.editor');

    guaranteeType = $('.edit-guarantee-type');
    guarantee = $('.edit-guarantee');
    guaranteeName = $('.edit-guarantee-name');
    guaranteeEstimate = $('.edit-guarantee-estimate');
    guaranteeAmount = $('.edit-guarantee-amount');
    guaranteeRemark = $('.edit-guarantee-remark');


    editor.find('td:first').text(index + 1);
    editor.find('.edit-guarantee-type').val(typeId);
    editor.find('.edit-guarantee').val(number);
    editor.find('.edit-guarantee-name').val(name);
    editor.find('.edit-guarantee-estimate').val(estimate);
    editor.find('.edit-guarantee-amount').val(amount);
    editor.find('.edit-guarantee-remark').val(remark);
    editor.find('.icon .fa').addClass('fa-save').removeClass('fa-plus');

    if (typeId === '2') {
        enableType(typeId);
    }

}

function remove(ele) {
    let index = $(ele).closest('tr.data').index();
    new Promise(resolve => {

        if ($('.guarantee-table tbody tr:eq(' + index + ') .content-guarantee-type').val() === '2') {
            enableType(2);
        }
        $('.guarantee-table tbody tr:eq(' + index + ')').remove();
        calc_personal_guarantee();

        setTimeout(function () {
            resolve();
        }, 150);
    }).then(triggerEditor).then(sortTableGuarantee)
}

function triggerEditor() {
    $('.edit-guarantee-type').trigger('change');
}

function change() {

}

function add() {

}

function addDeductInput(index, obj, dataType) {

    let amount = removeCommas(obj.interest) + removeCommas(obj.prev_loan_total);

    $('#form_contract').append(`<input class="prev_contract" type="hidden" name="prev_loan[${index}][interest]" value="${obj.interest}" >`);
    $('#form_contract').append(`<input class="prev_contract" type="hidden" name="prev_loan[${index}][amount]" value="${amount}" >`);
    $('#form_contract').append(`<input class="prev_contract" type="hidden" name="prev_loan[${index}][id]" value="${obj.ref_id}" >`);
    $('#form_contract').append(`<input class="prev_contract" type="hidden" name="prev_loan[${index}][pay_type]" value="all" >`);
    $('#form_contract').append(`<input class="prev_contract" type="hidden" name="prev_loan[${index}][type]" value="${dataType}" >`);

}

var deductList = [];

function prevDeduct() {
    deduct = 0;
    new Promise((resolve, reject) => {
        let data = {};
        data.member_id = memberID.val();
        data.createdatetime = $('#createdatetime').val();
        $.post(base_url + '/loan_contract/get_check_prev_loan', data, function (res) {
            //console.log(res);
            if (res.status === 200) {
                //console.log('resolve => ', res.data);
                resolve(res.data);
            } else {
                //console.log('reject =>', res.data);
                reject(`something wrong`);
            }
        });
    }).then((data) => {
        $('.prev_contract').remove();

        previousDeduct.each(function (idx) {
            let _ref_id = $(this).val();
            let num = 0;
            const dataType = $(this).attr('data-type') === '99' ? 'atm' : 'loan';
            if ($(this).is(':checked') === true) {
                data.forEach(function (item, index) {
                    if (_ref_id === item.ref_id) {
                        let prevLoan = removeCommas(item.prev_loan_total);
                        let interest = removeCommas(item.interest);
                        let result = parseFloat(prevLoan + interest);
                        $("#contract_interest_"+item.ref_id).html(addCommas(interest));
                        deduct += result;
                        addDeductInput(idx, item, dataType);
                        num++;
                    }
                });
            }
        });
        return;
    }).then(() => {
        calcDeduct();
        calcEstimateReceive();
    }).catch((err) => {
        console.log(" error: " + err);
    });
}

$(document).on('change', '.previous-deduct', function () {
    prevDeduct();
});

$(document).on('change', '#loan_amount, #createdatetime', function () {
    calcProcessing();
});

function search_member_modal(id) {
    $('#input_id').val(id);
    $('#search_member_loan_modal').modal('show');
}

$('#member_loan_search').click(function () {
    if ($('#member_search_list').val() == '') {
        swal('กรุณาเลือกรูปแบบค้นหา', '', 'warning');
    } else if ($('#member_search_text').val() == '') {
        swal('กรุณากรอกข้อมูลที่ต้องการค้นหา', '', 'warning');
    } else {
        $.ajax({
            url: base_url + "ajax/search_member_by_type_jquery",
            method: "post",
            data: {
                search_text: $('#member_search_text').val(),
                search_list: $('#member_search_list').val()
            },
            dataType: "text",
            success: function (data) {
                $('#result_member_search').html(data);
            },
            error: function (xhr) {
                console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
            }
        });
    }
});

$('#search_member_loan_modal').on('hide.bs.modal', function () {
    $(".blockUI.blockOverlay").remove();
});


function get_data(member_id, member_name, member_group) {
    console.log("selected : ", member_id);
    new Promise(((resolve, reject) => {
        $.post(base_url + "/ajax/get_member",
            {
                member_id: member_id,
                for_loan: '1',
                loan_type: $('#loan_type').val(),
                value_check: member_id,
                not_condition: () => {
                    var arr = [];
                    not_condition.forEach(element => {
                        console.log(element)
                        arr.push(element.value);
                    });
                    return arr.join(",");
                },
                condition_garantor_id: condition_garantor_id
            }
            , function (result, status, xhr) {

                var obj = JSON.parse(result);
                if (obj.message.text === "") {
                    obj.member_id = member_id;
                    obj.member_name = member_name;
                    resolve(obj);
                    return;
                } else {
                    reject(obj);
                    return;
                }
            });

    })).then(guaranteePersonalDuplication)
        .then(guaranteePersonalSet)
        .then(alertGuaranteeDuplication)
        .catch(alertGuaranteeDuplication);
}

function guaranteePersonalSet(obj) {
    if (obj === false) {
        return false;
    } else {

        if (typeof obj === 'object' && obj !== null) {
            console.log("is object");
            if (obj.check_id != null) {
                not_condition.push({key: obj.member_id, value: obj.check_id});
            }
            result = obj.message;
        }

        guarantee.val(obj.member_id);
        guaranteeName.val(obj.member_name);

        $('#search_member_loan_modal').modal('hide');

        calc_personal_guarantee(1);

        return;
    }
}

function guaranteePersonalDuplication(data) {
    var checkPersonalDup = function (resolve, reject) {
        let status = false;
        const count = $('.content-guarantee-type').length;
        if (count === 0) resolve(data);
        $('.content-guarantee-type').each(function (i) {
            if ($(this).val() === "1") {
                if ($(this).closest('tr').find('.content-guarantee-number').val() === data.member_id) {
                    status = true;
                }
            }
            if (i + 1 === count) {
                console.log('checker :', status);
                if (status) {
                    resolve(false);
                } else {
                    resolve(data);
                }
            }
        });
        reject(data);
        return;
    }
    return new Promise(checkPersonalDup);
}

function alertGuaranteeDuplication(err) {

    const ignore = ['edit-guarantee-type', 'edit-guarantee'];
    if(typeof err === "object" && err.message.text !== ""){
        swal( err.message.title, err.message.text, 'error');
        clearData(ignore);
    }
    if (err === false) {
        swal("ไม่สามารถเลือกผู้ค้ำประกันคนเดิมได้", "", 'error');
        clearData(ignore);
    }
    if(err === 'deposit'){
        swal("ไม่สามารถเลือกบัญชีค้ำประกันเดิมได้", "", 'error');
        clearData(ignore);
    }

    return;
}

function calc_personal_guarantee(flag) {
    flag = typeof flag === "undefined" ? 0 : flag;
    new Promise((resolve, reject) => {
        const count = $('.content-guarantee-type').length;
        let person = 0;
        if (count >= 1) {
            $('.content-guarantee-type').each(function (i) {
                if ($(this).val() === "1") {
                    person += 1;
                }
                if (i + 1 === count) {
                    resolve(person + flag);
                }
            });
        } else {
            resolve(person + flag);
        }
    }).then(updateEstimateGuaranteePerson);
}

function updateEstimateGuaranteePerson(person) {
    let calEstimate = (loanAmount - (loanAmount % person)) / person;
    let fraction = (loanAmount % person);

    const personCount = $('.content-guarantee-type').length;
    if (personCount > 0) {
        $('.content-guarantee-type').each(function (i) {
            if ($(this).val() === "1") {
                let estimate = $(this).closest('tr').find('.content-guarantee-estimate');
                let estimateLabel = estimate.closest('td').find('.text-label');
                let amount = $(this).closest('tr').find('.content-guarantee-amount');
                let amountLabel = amount.closest('td').find('.text-label');
                if (fraction > 0) {
                    estimateLabel.text(addCommas(calEstimate + 1));
                    estimate.val(calEstimate + 1);
                    amountLabel.text(addCommas(calEstimate + 1));
                    amount.val(calEstimate + 1);
                    --fraction;
                } else {
                    estimateLabel.text(addCommas(calEstimate));
                    estimate.val(calEstimate);
                    amountLabel.text(addCommas(calEstimate));
                    amount.val(calEstimate);
                }

            }
        });
    }
    if ($('.edit-guarantee-type').val() === "1" && $('.edit-guarantee').val() !== "") {
        if (fraction > 0) {
            $('.edit-guarantee-estimate').val(addCommas(calEstimate + 1));
            $('.edit-guarantee-amount').val(addCommas(calEstimate + 1));
            --fraction;
        } else {
            $('.edit-guarantee-estimate').val(addCommas(calEstimate));
            $('.edit-guarantee-amount').val(addCommas(calEstimate));
        }
    }
}

/**
 *  Transfer Type
 */
$(document).on('change', '#transfer_type', function () {
    if ($(this).val() === '0') {
        $('.content-deposit').hide();
        $('.content-bank').hide();
    } else if ($(this).val() === '1') {
        $('.content-deposit').show();
        $('.content-bank').hide();
    } else if ($(this).val() === '2') {
        $('.content-deposit').hide();
        $('.content-bank').show();
    } else if ($(this).val() === '3') {
        $('.content-deposit').hide();
        $('.content-bank').hide();
    } else {
        $('.content-deposit').hide();
        $('.content-bank').hide();
    }
});

function confirm() {
    release();
    if(!checkForm()){
        return false;
    }else {
        setTimeout(function () {
            $('#form_contract').submit();
        }, 200);
    }
}

function getContractData() {
    const loadDataContract = function (resolve, reject) {
        $.post(base_url + "/loan/ajax_get_loan_data", {loan_id: $('#loan_id').val()}, function (result) {
            let obj = JSON.parse(result);
            resolve(obj);
            return;
        }).error(function (xhr) {
            reject(xhr.error());
            return;
        });
    };
    return new Promise(loadDataContract);
}

var PrevLoan = true;
function setContract(result) {
    new Promise((resolve) => {
        console.log("Contract Set Start");
        resolve(result)
    }).then((data) => {
        setContractDetail(data.coop_loan);
        return data;
    }).then((data) => {
        setPrevLoan(data.coop_loan_prev_deduct);
        return data;
    }).then((data) => {
        setCost(data.coop_loan_cost);
        return data;
    }).then((data) => {
        setDeduct(data.coop_loan_deduct);
        return data;
    }).then((data) => {
        setInsurance(data.coop_life_insurance);
        console.log("Contract Set End");
        return data;
    });
}

function setContractDetail(obj) {
    $('#loan_id').val(obj.id);
    $('#petition_number').val(obj.petition_number);
    $('#loan_amount').val(addCommas(obj.loan_amount));
    $('#loan').val(obj.loan_amount);
    $('#salary').val(obj.salary);
    $('#loan_reason').val(obj.loan_reason);
    $('#interest_per_year').val(obj.interest_per_year);
    $('#period_amount').val(obj.period_amount);
    $('#interest').val(obj.interest_per_year);
    $('#createdatetime').val(obj.createdatetime);
    $('#transfer_type').val(obj.transfer_type);
    $('#transfer_bank_id').val(obj.transfer_bank_id);
    $('#transfer_bank_account_id').val(obj.transfer_bank_account_id);
    $('#transfer_type').trigger("change");
    setPeriodAMT(obj.period_amount);
    setPayType(obj.pay_type);
    setLoanAmount(obj.loan_amount);
    //calcProcessing();
}

function setPrevLoan(obj) {
    PrevLoan = false;
    obj.forEach(function(object, num){
        $('.previous-deduct').each(function(index, item){
            if($(this).val() === object.ref_id && $(this).prop('checked')  !== true){
                $(this).prop('checked', true);
            }
        });
    });
}

function setGuaranteePersonal() {
    const id = $('#loan_id').val();
    if (typeof id !== "undefined" || id !== "") {
        new Promise(((resolve, reject) => {
            $.post(base_url + "loan_contract/getloanguarantee", {loan_id: id}, function (res, status, xhr) {
                if (res.status == 200) {
                    resolve(res.data)
                } else {
                    reject(xhr.responseText);
                }
            })
        })).then((res) => {
            for (let i in res) {
                createRow(res[i]);
            }
        }).catch(err => {
            console.log(err);
        });
    }
}


function setCost(obj) {
    Object.keys(obj).map((key, indx) => {
        $("input[name='data[coop_loan_cost]["+key+"]']").val(addCommas(obj[key]));
    });

}

function selected_loan_type(id) {
    $.get(base_url + "loan/get_loan_type", {id: id}, function (res) {
        $('#loan_type_choose').val(res.ref_id).trigger('change');
        setTimeout(function () {
            $('#loan_type_select').val(res.id);
        }, 800);
    });
}

function open_modal(id) {
    $('#' + id).modal('show');
}

function close_modal(id) {
    $('#' + id).modal('hide');
}

function sortTableGuarantee() {
    $(".guarantee-table .data").each(function (index) {
        $(this).find('td:first').text(index + 1);
    });
}

function disableType(type) {
    $('.edit-guarantee-type option[value="' + type + '"]').attr('disabled', 'disabled');
}

function enableType(type) {
    $('.edit-guarantee-type option[value="' + type + '"]').removeAttr('disabled');
}
var firstSet = true;
function calcCost(){
    if(!firstSet) return;
    let costTotal = 0;
    let netBalance = $('.net_balance');
    let money_use_balance_baht = removeCommas(preferences.money_use_balance_baht);
    let result = 0;
    $('.loan_cost').each(function(index){
        costTotal += removeCommas($(this).val());
    });
    result = removeCommas($("input[name='data[coop_loan][salary]']").val()) - (costTotal + money_use_balance_baht);
    netBalance.val(addCommas(result));
}
function setDeduct(obj){
    if(typeof obj === "undefined" || obj.length === 0){
        return;
    }
    obj.forEach((item, index) => {
        $("input[name='data[loan_deduct]["+item.loan_deduct_list_code+"]']").val(addCommas(item.loan_deduct_amount));
    });
}

function setPayType(payType){
    payType = typeof payType === "undefined" ? 2 : payType;
    payType = typeof payType === "string" ? parseInt(payType) : payType;
    $("input[name='data[coop_loan][pay_type]'][value='"+payType+"']").prop('checked', true);
}

function setInsurance(obj) {
    if( obj === null) return;
    if(obj.insurance_amount !==  "" && obj.insurance_amount !== null){
        $("input[name='data[coop_left_insurance]']").val(addCommas(obj.insurance_amount));
    }
}

function update_salary() {
    update_income();
    let income = 0;
    $('.income').each(function(){
         income += parseFloat(removeCommas($(this).val()));
    });
    let salary = parseFloat(removeCommas($('#update_salary').val()));
    $('input[name="data[coop_loan][salary]"]').val(addCommas(salary+income));
    $.post(base_url + "/loan/update_salary",
        {
            member_id: $('.member_id').val(),
            salary: $('#update_salary').val(),
            other_income: $('#update_other_income').val()
        }
        , function (result) {
            close_modal('update_salary_modal');
            update_net();
        });
}

function update_net(){
    let net = 0;
    $('.payment_per_month').each(function(){
        net += numeral($(this).val()).value();
    });
    let balance = numeral($("input[name='data[coop_loan][salary]']").val()).value();
    balance -= net;
    $('.net_balance').val(numeral(balance).format('0,0.00'));
}

function update_income(){
    $('.income').each(function(){
        var income_id = $(this).data("key");
        var income_value = numeral($(this).val()).value();
        $.post(base_url+"/loan_contract/update_income",
            {
                member_id: $('.member_id').val(),
                income_id: income_id,
                income_value: income_value
            }
            , function(result){
                console.log("update !!!", result);
            });
    });

}

$("body").on("change", ".income", function() {
    var key = $(this).data("key");
    var number = numeral($(this).val()).value();
    $("input[name='income["+key+"]']").val(number);
});