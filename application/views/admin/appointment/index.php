<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender_Patient();
?>
<style>
.bootstrap-datetimepicker-widget {
    overflow: visible !important
}

.doc {
    margin-left: 1%;
    margin-top: 1%;
    border-radius: 25px;
    display: inline-block;
    cursor: pointer
}
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('appointment_details'); ?></h3>
                        <div class="box-tools pull-right">
                            <!-- <a href="<?php echo base_url("admin/onlineappointment/doctors"); ?>"
                                class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i>
                                Today Doctors Appointment</a> -->
                            <?php if ($this->rbac->hasPrivilege('appointment', 'can_add')) {?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addappointment">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_appointment'); ?></a>
                            <?php }?>

                        </div>

                    </div><!-- /.box-header -->


                    <div class="box-body">
                        <div class="mb10">
                            <a class='btn <?php echo $this->input->get('doc_id') ? 'btn-info' : 'btn-success' ?> btn-border'
                                href="<?php echo base_url(); ?>admin/appointment/index">
                                All</a>
                            <?php foreach ($doctors as $dkey => $value) {?>
                            <a href="<?php echo base_url(); ?>admin/appointment/index?doc_id=<?php echo $value['id'] ?>&date=<?php echo date('d-m-Y') ?>"
                                class=' btn <?php echo $this->input->get('doc_id') != $value['id'] ? 'btn-info' : 'btn-success' ?> btn-border '>
                                <?php echo $value["name"] . " " . $value["surname"] ?></a>
                            <?php }?>
                        </div>
                        <div class="download_label"><?php echo $this->lang->line('appointed_patient_list'); ?>
                        </div>
                        <div class="">
                            <table class="table table-striped table-bordered table-hover ajaxlist"
                                data-export-title="<?php echo $this->lang->line('appointment_details'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo 'Visited'; ?></th>
                                        <th><?php echo $this->lang->line('patient_name'); ?></th>
                                        <th><?php echo $this->lang->line('appointment_no'); ?></th>
                                        <th><?php echo "Token No." ?></th>
                                        <th><?php echo $this->lang->line('appointment_date'); ?></th>
                                        <th width="10%"><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('doctor'); ?></th>
                                        <?php if ($this->module_lib->hasActive('live_consultation')) {?>
                                        <th><?php echo $this->lang->line('live_consultant'); ?></th>
                                        <?php }?>
                                        <?php
if (!empty($fields)) {
    foreach ($fields as $fields_key => $fields_value) {
        ?>
                                        <th><?php echo $fields_value->name; ?></th>
                                        <?php
}
}
?>
                                        <th><?php echo $this->lang->line('fees'); ?></th>
                                        <th width="150" class="text-right">
                                            <?php echo $this->lang->line('status'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
$data = [
    "disable_option" => $disable_option,
    "doctors" => $doctors,
    "doctor_select" => $doctor_select,
    "payment_mode" => $payment_mode,
    "yesno_condition" => $yesno_condition,
    "genderList" => $genderList,
];
$this->load->view("admin/modals/add_appointment_mdl", $data);
?>

<div class="modal fade" id="rescheduleModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('reschedule'); ?></h4>
            </div>
            <form id="rescheduleform" accept-charset="utf-8" method="post">
                <div class="">
                    <div class="modal-body pb0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <input type="hidden" name="appointment_id" id="appointment_id">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('doctor'); ?></label>
                                            <small class="req"> *</small>
                                            <div>
                                                <select class="form-control"
                                                    onchange="getDoctorShift(this);getDoctorFeesEdit(this)"
                                                    style="width:100%" id="rdoctor" disabled>
                                                    <option value="<?php echo set_value('doctor'); ?>">
                                                        <?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($doctors as $dkey => $dvalue) {
    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>">
                                                        <?php echo $dvalue["name"] . " " . $dvalue["surname"] ?>
                                                    </option>
                                                    <?php }?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('rdoctor'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label
                                                for="doctor_fees"><?php echo $this->lang->line("doctor_fees"); ?></label>
                                            <small class="req"> *</small>
                                            <div>
                                                <input type="text" name="doctor_fees" id="rdoctor_fees_edit"
                                                    class="form-control">
                                            </div>
                                            <span class="text-danger"><?php echo form_error('doctor_fees'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('shift'); ?></label><span
                                                class="req"> *</span>
                                            <select name="rglobal_shift" id="rglobal_shift_edit"
                                                onchange="getreschsduleShift()" class="select2" style="width:100%">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('rglobal_shift'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('appointment_date') ?></label>
                                            <small class="req"> *</small>
                                            <input type="text" id="rdates" name="appointment_date"
                                                class="form-control datetime" value="<?php echo set_value('dates'); ?>">
                                            <span
                                                class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="slot"><?php echo $this->lang->line('slot'); ?></label>
                                            <span class="req"> *</span>
                                            <select name="rslot" id="rslot_edit" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <input type="hidden" id="rslot_edit_field" />
                                            <span class="text-danger"><?php echo form_error('rslot'); ?></span>
                                        </div>
                                    </div>

                                    <?php if ($this->module_lib->hasActive('live_consultation')) {?>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('live_consultant_on_video_conference'); ?></label>
                                            <small class="req">*</small>
                                            <select name="live_consult" id="edit_liveconsult" class="form-control">
                                                <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
    ?>
                                                <option value="<?php echo $yesno_key ?>" <?php
if ($yesno_key == 'no') {
        echo "selected";
    }
    ?>><?php echo $yesno_value ?>
                                                </option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php }?>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="message"><?php echo $this->lang->line('message'); ?></label>
                                            <small class="req"> *</small>
                                            <textarea name="message" id="message"
                                                class="form-control"><?php echo set_value('message'); ?></textarea>
                                            <span class="text-danger"><?php echo form_error('message'); ?></span>
                                        </div>
                                    </div>
                                    <div class="" id="customfield"></div>
                                    <!-- <div class="" id="customfield" ></div>  -->
                                </div>
                                <!--./row-->
                            </div>
                            <!--./col-md-12-->
                        </div>
                        <!--./row-->
                    </div>
                    <!--./modal-body-->
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="rescheduleformbtn"
                            data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip"
                    data-original-title="<?php echo $this->lang->line('close'); ?>"
                    data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id="edit_delete">
                        <a href="#" data-target="#editModal" data-toggle="tooltip"
                            data-original-title="<?php echo $this->lang->line('edit'); ?>"><i
                                class="fa fa-pencil"></i></a><a href="#" data-toggle="tooltip"
                            onclick="delete_recordById('<?php echo base_url(); ?>admin/appointment/delete/#', '<?php echo $this->lang->line('success_message') ?>')"
                            data-original-title="<?php echo $this->lang->line('delete'); ?>"><i
                                class="fa fa-trash"></i></a>
                    </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('appointment_details'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form id="view" accept-charset="utf-8" method="get" class="pt5 pb5">
                            <div class="table-responsive">
                                <table class="table mb0 table-striped table-bordered examples">
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('patient_name'); ?></th>
                                        <td width="35%"><span id='patient_names'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('appointment_no'); ?></th>
                                        <td width="35%"><span id="appointmentno"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('appointment_date'); ?></th>
                                        <td width="35%"><span id='dating'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('gender'); ?></th>
                                        <td width="35%"><span id="genders"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('email'); ?></th>
                                        <td width="35%"><span id='emails'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('phone'); ?></th>
                                        <td width="35%"><span id="phones"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('doctor'); ?></th>
                                        <td width="35%"><span id='doctors'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('message'); ?></th>
                                        <td width="35%"><span id="messages"></span>
                                        </td>
                                    </tr>
                                    <?php if ($this->module_lib->hasActive('live_consultation')) {?>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('live_consultation'); ?></th>
                                        <td width="35%"><span id="liveconsult"></span></td>
                                        <th width="15%"><?php echo $this->lang->line('status'); ?></th>
                                        <td width="35%"><span id='status' style="text-transform: capitalize;"></span>
                                        </td>
                                    </tr>
                                    <?php }?>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('shift'); ?></th>
                                        <td width="35%"><span id="global_shift_view"></span></td>
                                        <th width="15%"><?php echo $this->lang->line('slot'); ?></th>
                                        <td width="35%"><span id='doctor_shift_view'
                                                style="text-transform: capitalize;"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('amount'); ?></th>
                                        <td width="35%"><span id='pay_amount'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('payment_mode'); ?></th>
                                        <td width="35%"><span id="payment_mode"></span>
                                        </td>
                                    </tr>
                                    <tr id="payrow" style="display:none">
                                        <th width="15%"><?php echo $this->lang->line('cheque_no'); ?></th>
                                        <td width="35%"><span id='spn_chequeno'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('cheque_date'); ?></th>
                                        <td width="35%"><span id="spn_chequedate"></span>
                                        </td>
                                    </tr>
                                    <tr id="paydocrow" style="display:none">
                                        <th width="15%"><?php echo $this->lang->line('document'); ?></th>
                                        <td width="35%" id='spn_doc'><span></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('payment_note'); ?></th>
                                        <td width="35%"><span id="payment_note"></span></td>
                                    </tr>

                                </table>
                                <table class="table mb0 table-striped table-bordered examples" id="field_data">
                                </table>
                            </div>
                        </form>
                    </div>
                    <!--./col-md-12-->
                </div>
                <!--./row-->
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('change', '.payment_mode', function() {
    var mode = $(this).val();
    if (mode == "Cheque") {
        $('.filestyle', '#addPaymentModal').dropify();
        $('.cheque_div').css("display", "block");
    } else {
        $('.cheque_div').css("display", "none");
    }
});
</script>

<script type="text/javascript">
$(function() {
    $('#easySelectable').easySelectable();
})
</script>
<script type="text/javascript">
$(function() {
    $('.select2').select2()
});


(function($) {
    //selectable html elements
    $.fn.easySelectable = function(options) {
        var el = $(this);
        var options = $.extend({
            'item': 'li',
            'state': true,
            onSelecting: function(el) {

            },
            onSelected: function(el) {

            },
            onUnSelected: function(el) {

            }
        }, options);
        el.on('dragstart', function(event) {
            event.preventDefault();
        });
        el.off('mouseover');
        el.addClass('easySelectable');
        if (options.state) {
            el.find(options.item).addClass('es-selectable');
            el.on('mousedown', options.item, function(e) {
                $(this).trigger('start_select');
                var offset = $(this).offset();
                var hasClass = $(this).hasClass('es-selected');
                var prev_el = false;
                el.on('mouseover', options.item, function(e) {
                    if (prev_el == $(this).index())
                        return true;
                    prev_el = $(this).index();
                    var hasClass2 = $(this).hasClass('es-selected');
                    if (!hasClass2) {
                        $(this).addClass('es-selected').trigger('selected');
                        el.trigger('selected');
                        options.onSelecting($(this));
                        options.onSelected($(this));
                    } else {
                        $(this).removeClass('es-selected').trigger('unselected');
                        el.trigger('unselected');
                        options.onSelecting($(this))
                        options.onUnSelected($(this));
                    }
                });
                if (!hasClass) {
                    $(this).addClass('es-selected').trigger('selected');
                    el.trigger('selected');
                    options.onSelecting($(this));
                    options.onSelected($(this));
                } else {
                    $(this).removeClass('es-selected').trigger('unselected');
                    el.trigger('unselected');
                    options.onSelecting($(this));
                    options.onUnSelected($(this));
                }
                var relativeX = (e.pageX - offset.left);
                var relativeY = (e.pageY - offset.top);
            });
            $(document).on('mouseup', function() {
                el.off('mouseover');
            });
        } else {
            el.off('mousedown');
        }
    };
})(jQuery);
</script>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#formedit").on('submit', (function(e) {
        $("#formeditbtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: baseurl + 'admin/appointment/update',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $("#formeditbtn").button('reset');
            },
            error: function() {

            }
        });
    }));

    $("#dates").on("dp.change", function(e) {
        if ($("#global_shift_edit").val() != '') {
            getShiftEdit();
        }
    });

    $("#rdates").on("dp.change", function(e) {
        if ($("#rglobal_shift_edit").val() != '') {
            getreschsduleShift();
        }
    });

    $("#rescheduleform").on('submit', (function(e) {
        $("#rescheduleformbtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: baseurl + 'admin/appointment/reschedule',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $("#rescheduleformbtn").button('reset');
            },
            error: function() {

            }
        });
    }));
});

function get_PatientDetails(id) {
    $("#patient_name").html("patient_name");
    $('#gender option').removeAttr('selected');
    $.ajax({
        url: baseurl + 'admin/patient/patientDetails',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(res) {
            if (res) {
                $('#patient_name').val(res.patient_name);
                $('#patientid').val(res.id);
                $('#guardian_name').html(res.guardian_name);
                $('#phone').val(res.mobileno);
                $('#email').val(res.email);
                $("#age").html(res.age);
                $("#bp").html(res.bp);
                $("#month").html(res.month);
                $("#symptoms").html(res.symptoms);
                $("#known_allergies").html(res.known_allergies);
                $("#address").html(res.address);
                $("#height").html(res.height);
                $("#weight").html(res.weight);
                $("#marital_status").html(res.marital_status);
                $('#gender option[value="' + res.gender + '"]').attr("selected", "selected");
            } else {
                $('#patient_name').val('');
                $('#phone').val("");
                $('#email').val("");
                $("#note").val("");
            }
        }
    });
}

function checkVisits(ele) {
    let is_visited = $(ele).attr("is_visited");
    let app_id = $(ele).attr("app_id");
    $.ajax({
        url: base_url + "admin/Onlineappointment/setVisit",
        method: "POST",
        data: {
            val: is_visited,
            app_id: app_id,
        },
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                $(ele).toggleClass('fas fa-check-circle far fa-circle');
                var f_val;
                if (is_visited == "1") {
                    f_val = 0;
                } else {
                    f_val = 1;
                }
                $(ele).attr("is_visited", f_val);
            }
        },
        error: function(err) {
            console.log(err.responseText);
        }
    });
}

function getBed(bed_group, bed = '', active, htmlid = 'bed_no') {
    var div_data = "";
    $('#' + htmlid).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
    $("#" + htmlid).select2("val", 'l');
    $.ajax({
        url: baseurl + 'admin/setup/bed/getbedbybedgroup',
        type: "POST",
        data: {
            bed_group: bed_group,
            bed_id: bed,
            active: active
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
            });
            $("#" + htmlid).html("<option value=''><?php echo $this->lang->line('select') ?></option>");
            $('#' + htmlid).append(div_data);
            $("#" + htmlid).select2().select2('val', bed);
        }
    });
}

function viewreschedule(id) {
    $('#rescheduleModal').modal('show');
    $('#appointment_id').val(id);
    $.ajax({
        url: baseurl + 'admin/appointment/getDetailsAppointment',
        type: "GET",
        data: {
            appointment_id: id
        },
        dataType: 'json',
        success: function(data) {
            $('#customfield').html(data.custom_fields_value);
            $("#rdoctor").val(data.doctor).trigger("change");
            $("#rdates").val(data.date);
            $("#rslot_edit_field").val(data.shift_id);
            $("#message").val(data.message);
            getDoctorShift("", data.doctor, data.global_shift_id);
            $('select[id="rdoctor"] option[value="' + data.doctor + '"]').attr("selected", "selected");
            $('select[id="edit_liveconsult"] option[value="' + data.live_consult + '"]').attr("selected",
                "selected");
        }
    });
}

function getRecord(id) {
    $("#viewModal").modal('hide');
    $('#myModaledit').modal('show');
    $.ajax({
        url: baseurl + 'admin/appointment/getDetailsAppointment',
        type: "GET",
        data: {
            appointment_id: id
        },
        dataType: 'json',
        success: function(data) {
            $('#customfield').html(data.custom_fields_value);
            $("#id").val(data.id);
            $("#doctor").val(data.doctor).trigger("change");
            $("#dates").val(data.date);
            $("#slot_edit_field").val(data.shift_id);
            getDoctorShift("", data.doctor, data.global_shift_id);
            $("#edit_appointment_no").val(data.appointment_no);
            $("#message").val(data.message);
            if (data.patient_id == null) {
                data.patient_id = ""
            }
            var option = new Option(data.patients_name, data.patient_id, true, true);
            $("#myModaledit .patient_list_ajax").append(option).trigger('change');
            $("#myModaledit .patient_list_ajax").trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
            $('select[id="edit_gender"] option[value="' + data.patients_gender + '"]').attr("selected",
                "selected");
            $('select[id="doctor"] option[value="' + data.doctor + '"]').attr("selected", "selected");
            $('select[id="appointment_status"] option[value="' + data.appointment_status + '"]').attr(
                "selected", "selected");
            $('select[id="edit_liveconsult"] option[value="' + data.live_consult + '"]').attr("selected",
                "selected");

        },
    })
}

function viewDetail(id) {
    $('#viewModal').modal('show');
    $.ajax({
        url: baseurl + 'admin/appointment/getDetailsAppointment',
        type: "GET",
        data: {
            appointment_id: id
        },
        dataType: 'json',
        success: function(data) {
            var table_html = '';
            $.each(data.field_data, function(i, obj) {
                if (obj.field_value == null) {
                    var field_value = "";
                } else {
                    var field_value = obj.field_value;
                }

                var name = obj.name;
                var is_patient = obj.visible_on_patient_panel;
                if (is_patient == 1) {
                    table_html += "<tr><th width='15%'><span id='vcustom_name'>" +
                        capitalizeFirstLetter(name) +
                        "</span></th> <td width='85%'><span id='vcustom_value'>" + field_value +
                        "</span></td></tr><th></th><td></td>";
                }

            });
            $("#field_data").html(table_html);
            $("#dating").html(data.date);
            $("#appointmentno").html(data.appointment_no);
            $("#patient_names").html(data.patients_name);
            $("#genders").html(data.patients_gender);
            $("#emails").html(data.patient_email);
            $("#phones").html(data.patient_mobileno);
            $("#doctors").html(data.name + " " + data.surname);
            $("#messages").html(data.message);
            $("#liveconsult").html(data.edit_live_consult);
            $("#global_shift_view").html(data.global_shift_name);
            $("#doctor_shift_view").html(data.doctor_shift_name);
            $("#pay_amount").html('<?php echo $currency_symbol; ?>' + data.amount);
            $("#payment_mode").html(data.payment_mode);
            $("#payment_note").html(data.payment_note);

            if (data.payment_mode == "Cheque") {
                $("#payrow").show();
                $("#paydocrow").show();
                $("#spn_chequeno").html(data.cheque_no);
                $("#spn_chequedate").html(data.cheque_date);
                $("#spn_doc").html(data.doc);
            } else {
                $("#payrow").hide();
                $("#paydocrow").hide();
                $("#spn_chequeno").html("");
                $("#spn_chequedate").html("");
            }

            var label = "";
            if (data.appointment_status == "approved") {
                var label = "class='label label-success'";
            } else if (data.appointment_status == "pending") {
                var label = "class='label label-warning'";
            }

            $("#status").html("<small " + label + " >" + data.appointment_status + "</small>");
            $("#edit_delete").html("<a href='#' data-toggle='tooltip'  onclick='printAppointment(" + id +
                ")' data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a> <?php if ($this->rbac->hasPrivilege('appointment', 'can_delete')) {?><a href='#' data-toggle='tooltip'  onclick='delete_record(" +
                id +
                ")' data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a><?php }?> "
            );

        },
    });
}

function viewDetail(id) {
    $('#viewModal').modal('show');
    $.ajax({
        url: baseurl + 'admin/appointment/getDetailsAppointment',
        type: "GET",
        data: {
            appointment_id: id
        },
        dataType: 'json',
        success: function(data) {
            var table_html = '';
            $.each(data.field_data, function(i, obj) {
                if (obj.field_value == null) {
                    var field_value = "";
                } else {
                    var field_value = obj.field_value;
                }

                var name = obj.name;
                var is_patient = obj.visible_on_patient_panel;
                if (is_patient == 1) {
                    table_html += "<tr><th width='15%'><span id='vcustom_name'>" +
                        capitalizeFirstLetter(name) +
                        "</span></th> <td width='85%'><span id='vcustom_value'>" + field_value +
                        "</span></td></tr><th></th><td></td>";
                }

            });
            $("#field_data").html(table_html);
            $("#dating").html(data.date);
            $("#appointmentno").html(data.appointment_no);
            $("#patient_names").html(data.patients_name);
            $("#genders").html(data.patients_gender);
            $("#emails").html(data.patient_email);
            $("#phones").html(data.patient_mobileno);
            $("#doctors").html(data.name + " " + data.surname);
            $("#messages").html(data.message);
            $("#liveconsult").html(data.edit_live_consult);
            $("#global_shift_view").html(data.global_shift_name);
            $("#doctor_shift_view").html(data.doctor_shift_name);
            $("#pay_amount").html('<?php echo $currency_symbol; ?>' + data.amount);
            $("#payment_mode").html(data.payment_mode);
            $("#payment_note").html(data.payment_note);

            if (data.payment_mode == "Cheque") {
                $("#payrow").show();
                $("#paydocrow").show();
                $("#spn_chequeno").html(data.cheque_no);
                $("#spn_chequedate").html(data.cheque_date);
                $("#spn_doc").html(data.doc);
            } else {
                $("#payrow").hide();
                $("#paydocrow").hide();
                $("#spn_chequeno").html("");
                $("#spn_chequedate").html("");
            }

            var label = "";
            if (data.appointment_status == "approved") {
                var label = "class='label label-success'";
            } else if (data.appointment_status == "pending") {
                var label = "class='label label-warning'";
            }

            $("#status").html("<small " + label + " >" + data.appointment_status + "</small>");
            $("#edit_delete").html("<a href='#' data-toggle='tooltip'  onclick='printAppointment(" + id +
                ")' data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a> <?php if ($this->rbac->hasPrivilege('appointment', 'can_delete')) {?><a href='#' data-toggle='tooltip'  onclick='delete_record(" +
                id +
                ")' data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a><?php }?> "
            );

        },
    });
}

function delete_record(id) {
    if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
        $.ajax({
            url: baseurl + 'admin/appointment/delete/' + id,
            type: "POST",
            data: {
                patient_id: id
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    $('#viewModal').modal('hide');
                    successMsg(res.message);
                    table.ajax.reload();
                }
            }
        })
    }
}
</script>
<script type="text/javascript">
function askconfirm() {

    if (confirm("<?php echo $this->lang->line('approve_appointment'); ?>")) {
        return true;
    } else {
        return false;
    }

}

$(".modalbtnpatient").click(function() {
    $('#formaddpa').trigger("reset");
    $(".dropify-clear").trigger("click");
});


$(document).ready(function(e) {
    $('#myModal,#viewModal,#myModaledit').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});
</script>
<script type="text/javascript">
function getDoctorFees(object) {
    let doctor_id = object.value;
    $.ajax({
        url: baseurl + 'admin/appointment/getDoctorFees/',
        type: "POST",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            $("#doctor_fees").val(res.fees);
            $("#charge_id").val(res.charge_id);
        }
    })
}

function getDoctorFeesEdit(object) {
    let doctor_id = object.value;
    $.ajax({
        url: baseurl + 'admin/appointment/getDoctorFees/',
        type: "POST",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            $("#doctor_fees_edit").val(res.fees);
            $("#rdoctor_fees_edit").val(res.fees);
            $("#charge_id_edit").val(res.charge_id);
        }
    })
}
</script>
<script>
function getShiftEdit() {

    var div_data = "";
    var date = $("#dates").val();
    var doctor = $("#doctor").val();
    var global_shift = $("#global_shift_edit").val();

    $.ajax({
        url: baseurl + 'admin/onlineappointment/getShift',
        type: "POST",
        data: {
            doctor: doctor,
            date: date,
            global_shift: global_shift
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                div_data += "<option value=" + obj.id + ">" + obj.start_time + " - " + obj
                    .end_time + "</option>";
            });
            $("#slot_edit").html("<option value=''><?php echo $this->lang->line('select'); ?></option>");
            $('#slot_edit').append(div_data);
            $("#slot_edit").val($("#slot_edit_field").val()).trigger('change');
        }
    });
}

function printAppointment(id) {
    $.ajax({
        url: base_url + 'admin/appointment/printAppointmentBill',
        type: "POST",
        data: {
            'appointment_id': id
        },
        dataType: 'json',
        beforeSend: function() {

        },
        success: function(data) {
            popup(data.page);
        },

        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        },
        complete: function() {}
    });
}

function popup(data) {
    var base_url = '<?php echo base_url() ?>';
    var frame1 = $('<iframe />');
    frame1[0].name = "frame1";
    frame1.css({
        "position": "absolute",
        "top": "-1000000px"
    });
    $("body").append(frame1);
    var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ?
        frame1[0]
        .contentDocument.document : frame1[0].contentDocument;
    frameDoc.document.open();
    //Create a new HTML document.
    frameDoc.document.write('<html>');
    frameDoc.document.write('<head>');
    frameDoc.document.write('<title></title>');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/bootstrap/css/bootstrap.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/dist/css/font-awesome.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/dist/css/ionicons.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/dist/css/AdminLTE.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/dist/css/skins/_all-skins.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/iCheck/flat/blue.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/morris/morris.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/datepicker/datepicker3.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
    frameDoc.document.write('</head>');
    frameDoc.document.write('<body>');
    frameDoc.document.write(data);
    frameDoc.document.write('</body>');
    frameDoc.document.write('</html>');
    frameDoc.document.close();
    setTimeout(function() {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();

    }, 500);

    return true;
}

function getreschsduleShift() {

    var div_data = "";
    var date = $("#rdates").val();
    var doctor = $("#rdoctor").val();
    var global_shift = $("#rglobal_shift_edit").val();

    $.ajax({
        url: baseurl + 'admin/onlineappointment/getShift',
        type: "POST",
        data: {
            doctor: doctor,
            date: date,
            global_shift: global_shift
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                div_data += "<option value=" + obj.id + ">" + obj.start_time + " - " + obj
                    .end_time + "</option>";
            });
            $("#rslot_edit").html("<option value=''><?php echo $this->lang->line('select'); ?></option>");
            $('#rslot_edit').append(div_data);
            $("#rslot_edit").val($("#rslot_edit_field").val()).trigger('change');
        }
    });
}

function getDoctorShift(obj, doctor_id = null, global_shift_id = null) {
    if (doctor_id == null) {
        var doctor_id = obj.value;
    }
    var select = "";
    var select_box = "<option value=''><?php echo $this->lang->line('select'); ?></option> ";
    $.ajax({
        type: 'POST',
        url: base_url + "admin/onlineappointment/doctorshiftbyid",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, list) {
                select_box += "<option value='" + list.id + "'>" + list.name + "</option>";
            });
            $("#global_shift").html(select_box);
            $("#global_shift_edit").html(select_box);
            $("#rglobal_shift_edit").html(select_box);
            if (global_shift_id != null) {
                $("#global_shift_edit").val(global_shift_id).trigger('change');
                $("#rglobal_shift_edit").val(global_shift_id).trigger('change');
            }
        }
    });
}



$(function() {});
</script>
<script type="text/javascript">
(function($) {
    'use strict';
    $(document).ready(function() {
        initDatatable('ajaxlist',
            'admin/appointment/getappointmentdatatable/<?php echo $this->input->get("doc_id") ? "?doc_id=" . $this->input->get("doc_id") . "&date=" . $this->input->get("date") : "" ?>',
            [], [], 100);
    });
}(jQuery))
</script>
<!-- //========datatable end===== -->
<?php $this->load->view('admin/patient/patientaddmodal')?>