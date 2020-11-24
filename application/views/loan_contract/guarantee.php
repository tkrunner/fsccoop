<style type="text/css">
    .guarantee>thead>tr>th{
        text-align: center;
    }
</style>
<div class="table-responsive">
    <table class="table guarantee-table">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th>หลักประกัน</th>
            <th>เลขที่</th>
            <th>ชื่อหลักประกัน/เลขที่โฉนด</th>
            <th>สิทธิค้ำ/ประเมิน</th>
            <th>จำนวนเงินค้ำ</th>
            <th>หมายเหตุ</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            <tr class="editor">
                <td class="text-center">#</td>
                <td>
                    <select class="form-control edit-guarantee-type" onchange="changeInput(this.value);">
                        <option value="">ไม่ระบุ</option>
                        <option value="1">สมาชิกคำประกัน</option>
                        <option value="2">หุ้นค้ำประกัน</option>
                        <option value="3">เงินฝากค้ำประกัน</option>
                        <option value="4">สินทรัพย์ค้ำประกัน</option>
                    </select>
                </td>
                <td><input class="form-control content-guarantee edit-guarantee" type="text"  readonly></td>
                <td><input class="form-control edit-guarantee-name" type="text" readonly></td>
                <td><input class="form-control edit-guarantee-estimate" type="text" readonly></td>
                <td><input class="form-control edit-guarantee-amount" type="text" readonly></td>
                <td><input class="form-control edit-guarantee-remark" type="text" readonly></td>
                <td><button type="button" class="btn btn-info btn-smaller" onclick="addGuaranteeType();"><span class="icon"><i class="fa fa-plus"></i></span></button></td>
            </tr>
        </tbody>
    </table>
</div>
