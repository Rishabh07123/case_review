<style>

    .select2-container--classic .select2-selection--multiple .select2-selection__rendered {

        max-width: 100% !important;
        overflow: hidden !important;
        white-space: normal !important;
    }
    .select_batch {

        background-color: #fff;
        border: 1px solid #aaa;
        border-radius: 4px;
        margin-right: 32px;
        height: 29px;
        margin-top: 4px;
    }
    #assign_to_filter{
        width: 255px !important;
        overflow: hidden !important;
        margin-right: 20px !important;
    }
    #page-content-wrapper ul {
        display: inline-block;
    }
</style>

<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-bootstrap.js"> </script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-reorder.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.html5.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

    /* Datatables export */

    $(document).ready(function() {

        $('.select2').select2();

        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function ( data, row, column, node ) {
                        // Strip $ from salary column to make it numeric
                        return column === 5 ?
                            data.replace( /[$,]/g, '' ) :
                        data;
                    }
                },
                columns: [1,2,3,4,5,6,7,8,9,10,11]
            }
        };

        $('table.tag_assign').DataTable( {

            dom: 'Blfrtip',
            lengthMenu: [
                [50, 25, 10, -1],
                ['50 rows', '25 rows', '10 rows', 'Show all']
            ],
            buttons: [
                $.extend( true, {}, buttonCommon, {
                    extend: 'excelHtml5',
                    className: 'btn btn-primary'
                } )
            ]
        } );
    } );

    /* Datatables reorder */
    $(document).ready(function() {
        $('#datatable-reorder').DataTable( {
            dom: 'Rlfrtip'
        });

        $('#datatable-reorder_length').hide();
        $('#datatable-reorder_filter').hide();

    });

    $(document).ready(function() {
        $('.dataTables_filter input').attr("placeholder", "Search...");
    });

    $(document).ready(function() {

        $('#selectAllTL').change(function(){

            if($(this).prop('checked')){

                $('#tag_assign_tbody').find('td input:checkbox').prop('checked', 'checked');
            }else{

                $('#tag_assign_tbody').find('td input:checkbox').prop('checked', false);
            }
        });

        renderSelect2();
        renderSelectTag();
        renderSelectTagRemove();
        renderSelectTagFilter();
    }); 


    function remove_tags()
    {
        var leadid_array = [];
        $(':checkbox:checked').each(function(i){
            leadid_array[i] = $(this).val();
        });

        var selectTagToRemove = $('#tagValueRemove').val();

//        alert(selectTagToRemove);
        if(leadid_array.length < 1){
            error = 1;
            alert('Please select a lead.');
            return false;
        }
        else if(selectTagToRemove == null || selectTagToRemove == ''){

            error = 1;
            alert('Please select a tag to remove.');
            return false;
        }
        else{

            swal('Confirm delete Tags for selected leads?', {
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                icon: "warning"
            }).then(function(isConfirm) {
                if (isConfirm) {

                    $("#test_load_img").css('display','block');

                    jQuery.ajax({

                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "index.php/Tagging/removeTag",

                        data: { leadid_array: leadid_array , selectTagToRemove: selectTagToRemove},
                        success: function(response) 
                        {	
                            if(response==1)
                            {
                                //alert("Tag Deleted Successfully");
                                $.jGrowl("Tag Deleted Successfully.", {
                                    sticky: false,
                                    position: 'top-right',
                                    theme: 'bg-green'
                                });
                                window.location.reload();
                                $("#test_load_img").css('display','none');
                            }
                            else{

                                $.jGrowl("Tag Not Deleted. " + response, {
                                    sticky: false,
                                    position: 'top-right',
                                    theme: 'bg-green'
                                });
                                //alert("Tag Not Deleted. " + response);
                                $("#test_load_img").css('display','none');
                            }

                        }
                    });
                }
            });
        }

    } 

    function add_tags()
    {
        var leadid_array = [];
        $(':checkbox:checked').each(function(i){
            leadid_array[i] = $(this).val();
        });

        var selectTag = $('#tagValue').val();


        if(leadid_array.length < 1){
            error = 1;
            alert('Please select a lead.');
            return false;
        }
        else if(selectTag == null || selectTag == ''){

            error = 1;
            alert('Please select a tag to add.');
            return false;
        }
        else{

            swal('Confirm add Tags to selected leads?', {
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                icon: "warning"
            }).then(function(isConfirm) {
                if (isConfirm) {

                    $("#test_load_img").css('display','block');

                    jQuery.ajax({

                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "index.php/Tagging/addTags",

                        data: { leadid_array: leadid_array, selectTag: selectTag},
                        success: function(response) 
                        {
                            if(response==1)
                            {
                                //alert("Tag Added Successfully");
                                $.jGrowl("Tag Added Successfully.", {
                                    sticky: false,
                                    position: 'top-right',
                                    theme: 'bg-green'
                                });                                
                                window.location.reload();
                                $("#test_load_img").css('display','none');
                            }
                            else{
                                //alert("Tag Not Added. "+ response);
                                $.jGrowl("Tag Not Added. "+ response, {
                                    sticky: false,
                                    position: 'top-right',
                                    theme: 'bg-green'
                                });
                                $("#test_load_img").css('display','none');
                            }

                        }
                    });
                }
            });
        }

    }


    //Filter Code
    function get_filtered_data(data){

        var status = $('#status :selected').text();
        var subStatus = $('#sub_status :selected').text();
        var screenDateFrom = $('#screen_date_from').val();
        var screenDateTo = $('#screen_date_to').val();
        var lastEnqDateFrom = $('#last_enq_date_from').val();
        var lastEnqDateTo = $('#last_enq_date_to').val();
        var language_filter = $('#language_filter').val();        
        var assign_to_filter = $('.js-example-basic-single').val();
        var tags = $('#tagFilter').val();
        var lead_level = $('#lead_level').val();  
        var billing_status = $('#billing_status').val(); 

        $("#test_load_img").css('display','block');

        //alert(status + ', ' + subStatus + ', ' + screenDate + ', ' + lastEnqDate + ', ' + language_filter + ', ' + assign_to_filter + ', ' + tags);
        //return true;

        jQuery.ajax({

            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/Tagging/getFilteredData",

            data: {status: status, subStatus: subStatus, screenDateFrom: screenDateFrom, screenDateTo: screenDateTo, lastEnqDateFrom: lastEnqDateFrom, lastEnqDateTo: lastEnqDateTo, language_filter:language_filter, assign_to_filter:assign_to_filter, tags:tags, lead_level:lead_level, billing_status:billing_status},
            success: function(response) 
            {
                //                alert(response);
                var table=$('.tag_assign').DataTable();

                if(response != ''){

                    var tbody = '';
                    var response_array = $.parseJSON(response);				
                    var len = response_array.length;
                    //console.log(response_array);
                    var table = $('.tag_assign').dataTable();
                    table.fnClearTable();

                    for (var i = 0; i < len; i++) {

                        var rowNode = table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['id'] + "' class='tag_cases' value='" + response_array[i]['id'] + "' class='tag_cases'>", response_array[i]['case_id_c'], response_array[i]['name'], response_array[i]['username'] , response_array[i]['status'], response_array[i]['custom_status_c'],  response_array[i]['language_val'],response_array[i]['debt_amount'], response_array[i]['cr_fee_c'] , response_array[i]['screen_date'], response_array[i]['lastinquirydate'], response_array[i]['tagName'], response_array[i]['lead_level_c'], response_array[i]['billingstatus_c'] ]);

                        /*var theNode = table.fnSettings().aoData[rowNode].nTr;
                        theNode.setAttribute('id',response_array[i]['id']);

                        $('td', theNode)[2].setAttribute( 'id', response_array[i]['user_id7_c']);*/
                    }
                    $("#test_load_img").css('display','none');
                }
                else{
                    alert("No record with given filters found.");
                    //                    $("#tag_assign_tbody").css('display','none');
                    //                                        $("#tag_assign_tbody").html('No record with given filters found');
                    //                                        $("#tag_assign_tbody").css('text-align','center');
                    $("#test_load_img").css('display','none');
                }
            } 
        })
    }


    function renderSelect2(){
        //alert('renderSelect2')	
        //$('.js-example-basic-single').prop('disabled', true);
        $('.js-example-basic-single').select2();
        $(".js-example-basic-single").select2({
            theme: "classic"
        });
    }

    function renderSelectTag(){
        $('#tagValue').select2();
        $("#tagValue").select2({
            theme: "classic"
        });
    }

    function renderSelectTagRemove(){
        $('#tagValueRemove').select2();
        $("#tagValueRemove").select2({
            theme: "classic"
        });
    }


    function renderSelectTagFilter(){
        $('#tagFilter').select2();
        $("#tagFilter").select2({
            theme: "classic"
        });
    }


    $(document).ready(function() {
        $('#status').change(function(){
            // $('#sub_status').val('');
            document.getElementById("sub_status").options.length = 0;

            var statusChange = $('#status :selected').text();
            // alert(statusChange);
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/Tagging/selectedLeadStatus",
                data: {statusChange: statusChange} ,
                success: function(items) 
                {
                    var subStatus = JSON.parse(items);	
                    $.each(subStatus.data, function (i, item) {
                        $('#sub_status').append($('<option>', { 
                            value: item,
                            text : item
                        }));
                    });
                }
            });
        });

        var statusGet = $("#status :selected").text(); ;
        // alert(statusGet);
        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/Tagging/selectedLeadStatus",
            data: {statusChange: statusGet} ,
            success: function(items) 
            {
                var subStatus = JSON.parse(items);	
                $.each(subStatus.data, function (i, item) {
                    $('#sub_status').append($('<option>', { 
                        value: item,
                        text : item
                    }));
                });
            }
        });
    });




    $( ".last_enq_date" ).datepicker({ defaultDate: -1, minDate:'-1d' });
</script>

<div id="page-content-wrapper">
    <div id="page-content">
        <div class="container" style="margin-top:20px;">

            
        <div class="panel" id="my_cases_div" style="display: block;">
                <div class="panel-body">
                    <?php 
                    $status = json_decode($status_list);
                    // print_r($status);
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label" style="font-size: medium; margin-bottom: 15px;">Tagging Tool Filters</label>
                            <div id="test_load_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/p1_new.gif" /></div>
                        </div>
                    </div>  
                    
                    <div class="row">
                        <div class="col-sm-12" id="filters" style="display: flex; margin-bottom: 20px;">
                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-5" >
                                    <font color="black" style="padding-right: 20px; text-align: left;">Lead Level</font>
                                </div>
                                <div class="col-sm-7">
                                    <select id="lead_level" class="form-control" style="">
                                    <option value="" ></option>
                                    <option value="Initial Service" >Initial Service</option>
                                    <option value="Case Review" >Case Review </option>
                                    <option value="Add Service" >Add Service</option>

                                    </select>
                                </div>
                            </div> 
                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-4" >
                                    <font color="black" style="padding-right: 20px; text-align: left;">Status</font>
                                </div>
                                <div class="col-sm-8">
                                    <select id="status" class="form-control" style="">
                                        <?php
                                        foreach($status as $kee=>$vall)
                                        {
                                            foreach($vall as $key=>$value)
                                            {
                                        ?>
                                        <option value="<?php echo $value; ?>" <?php if($key=='New Lead'){ echo "selected"; } ?> ><?php echo $value; ?></option>

                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-4">
                                    <font color="black" style="padding-right: 20px; padding-top: 3px;">Sub&nbsp;Status</font>
                                </div>
                                <div class="col-sm-8">
                                    <select id="sub_status" class="form-control" style="margin-right: 40px;">

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-6">

                                    <font color="black" style="padding-right: 20px; padding-top: 3px; ">Select&nbsp;Language</font>
                                </div>
                                <div class="col-sm-6">
                                    <select id="language_filter" class="form-control">
                                        <option value=""></option>
                                        <option value="English" selected>English</option>
                                        <option value="Spanish">Spanish</option>
                                    </select>
                                </div>
                            </div> 
                        </div>                           
                    </div>


                    <!--<div>-->
                    <div class="row">
                        <div class="col-sm-12" id="filters" style="display: flex; margin-bottom: 20px;margin-left: 12px">
                            <!--
                            <div class="col-sm-4" style="display:inline-flex;">
                            <font color="black" style="padding-right: 20px; padding-top: 3px;margin-left: 84px;">Lead&nbsp;SRC</font>
                            <select id="leadSrc" class="form-control" style="margin-right: 40px;">
                            <option value=""></option>
                            </select>

                            </div>
                            -->
                            
                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-4" style="padding:0px;">
                                    <font color="black" style="padding-top: 3px;">Screen Date From</font>
                                </div>
                                <div class="col-sm-8">
                                    <input type="date" id="screen_date_from" class="form-control" style="margin-right: 40px; margin-left: 10px;">
                                </div>
                            </div>                     


                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-5">
                                    <font color="black">Screen Date To</font>
                                </div>
                                <div class="col-sm-7">
                                    <input type="date" id="screen_date_to" class="form-control" style="width: 113%;">
                                </div>
                            </div>

                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-5" style="padding:0px;">
                                    <font color="black" style="padding-top: 3px;">Last Enquiry Date From</font>
                                </div>
                                <div class="col-sm-7">
                                    <input type="date" id="last_enq_date_from" style="width: 113%;" value="<?php //echo date('Y-m-d',strtotime('-1 days')); ?>" class="form-control" >
                                </div>
                            </div>

                            <div class="col-sm-3" style="display:inline-flex;padding:0px;">
                                <div class="col-sm-5">
                                    <font color="black" style="padding-right: 20px; padding-top: 3px;">Last Enquiry Date To</font>
                                </div>
                                <div class="col-sm-7">
                                    <input type="date" id="last_enq_date_to" value="<?php //echo date('Y-m-d',strtotime('-1 days')); ?>" class="form-control" >
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class= "row">
                        <div class="col-sm-12" id="filters" style="display: flex; margin-bottom: 20px;">

                            
                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-4">
                                    <font color="black" style="padding-right: 20px; padding-top: 3px;">Assigned&nbsp;To</font>
                                </div>
                                <!--<select id="pay_status_filter" class="form-control" style="width: 10%;  margin-right: 40px;"> -->
                                <div class="col-sm-8">
                                    <select class="js-example-basic-single form-control" id="assign_to_filter" name="assigned_filter[]" multiple="multiple" style="border: #dfe8f1 solid 1px;width: 180px;" >	 
                                        <!--<select class="js-example-basic-single form-control" id="form_name" name="form_names[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;" >-->
                                        <option value=""></option>
                                        <?php	foreach($user_list as $val){ ?>

                                        <option value="<?php echo $val->id; ?>"><?php echo $val->uname; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-4">
                                    <font color="black" style="padding-top: 3px;">Tags</font>
                                   
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control select_batch" id="tagFilter"  multiple="multiple">
                                        <option value=""></option>
                                        <?php foreach($tag_list as $key=>$val){ ?>
                                        <option value="<?php echo $val->name; ?>"><?php echo $val->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" style="display:inline-flex;">
                                <div class="col-sm-5">
                                    <font color="black" style="padding-right: 20px; padding-top: 3px;">Billing&nbsp;Status</font>
                                </div>
                                
                                <div class="col-sm-7">
                                    <select id="billing_status" class="form-control" style=""   >	 
                                        <option value=""></option>
                                        <option value="Current">Current</option>
                                        <option value="Paid in Full">Paid in Full</option>
                                        <option value="Past Due">Past Due</option>
                                        <option value="Declined">Declined</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <button type="button" style="width: 100px;" id="filter_button" class="btn btn-primary btn-block" onclick="get_filtered_data(this);" >Filter</button>
                            </div>

                            <!--
                            <div class="col-sm-4" style="display:inline-flex;">
                            <font color="black" style="padding-right: 20px; padding-top: 3px;">Qualified&nbsp;Lead&nbsp;Score</font>
                            <select id="leadScore" class="form-control" style="margin-right: 40px;">
                            <option value=""></option>
                            </select>
                            </div>
                            -->
                        </div>

                    </div>
  

                    <div class="col-sm-12">
                        <div id="reg_js_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/p1_new.gif" /></div>

                        <?php 
                        //print_r($tagging_data);
                        if($tagging_data == 0){ ?>
                        <label class="col-sm-12 control-label" style="text-align:center">No Cases Found</label>
                        <?php } else { ?>	

                        <table id="datatable-tabletools" class="table table-striped table-bordered tag_assign" cellspacing="0" width="100%">
                            <!-- style="float: left;  max-height: 400px; overflow-y: scroll;" -->
                            <thead>
                                <tr style="text-align: center">
                                    <th>Action <input type="checkbox" id="selectAllTL" class="tag_cases"/></th>

                                    <th>Case ID</th>
                                    <th>Name</th>
                                    <th>Lead Assigned To</th>
                                    <th>Status</th>
                                    <th>Sub Status</th>
                                    <th>Language</th>
                                    <th>Federal Debt Amount</th>
                                    <th>Final CR Fee</th>
                                    <th>Screen Date</th>
                                    <th>Last Inquiry Date</th>
                                    <th>Tags</th>
                                    <th>Lead Level</th>
                                    <th>Billing Status</th>
                                </tr>
                            </thead>
                            <tbody id="tag_assign_tbody">

                                <?php	
                            foreach($tagging_data as $val){ ?>

                                <tr id="<?php echo $val->id;?>" >
                                    <td><input type="checkbox" name="tagSelector[]" id="<?php echo $val->id;?>" value="<?php echo $val->id;?>" class="tag_cases checkboxClass"></td>

                                    <td><?php echo $val->case_id_c ;?></td>
                                    <td><?php echo $val->name;?></td>
                                    <td><?php echo $val->username;?></td>
                                    <td><?php echo $val->status;?></td>
                                    <td><?php echo $val->custom_status_c;?></td>
                                    <td><?php echo $val->language_val;?></td>
                                    <td><?php echo $val->debt_amount;?></td>
                                    <td><?php echo $val->cr_fee_c;?></td>
                                    <td><?php echo $val->screen_date;?></td>
                                    <td><?php echo $val->lastinquirydate;?></td>
                                    <td><?php echo $val->tagName; ?></td>
                                    <td><?php echo $val->lead_level_c; ?></td>
                                    <td><?php echo $val->billingstatus_c; ?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>

                        <?php //print_r($user_list); ?>

                        <div id="removeDiv" class="col-sm-12">				
                            <!--<button type="button" id="mass_so_assign_to" class="btn btn-primary" onclick="move_to_assignment();">Move To SO Assignment</button> -->

                            <div class="col-sm-3">

                                <select class="form-control select_batch" id="tagValueRemove"  multiple="multiple">
                                    <option value=""></option>
                                    <?php foreach($tag_list as $key=>$tagValue){ ?>
                                    <option value="<?php echo $tagValue->name; ?>"><?php echo $tagValue->name; ?></option>
                                    <?php } ?>
                                </select>

                                <!--<input type="text" name="tagValue" id="tagValue" class="form-control" />-->
                            </div>

                            <div class="col-sm-2">
                                <button type="button" id="removeTags" class="btn btn-primary" onclick="remove_tags();">Remove Tags</button>

                            </div>

                        </div>

                        <div id="addTagsDiv" class="col-sm-12" style="margin-top: 10px;">				
                            <div class="col-sm-3">

                                <select class="form-control select_batch" id="tagValue"  multiple="multiple">
                                    <option value=""></option>
                                    <?php foreach($tag_list as $key=>$val){ ?>
                                    <option value="<?php echo $val->name; ?>"><?php echo $val->name; ?></option>
                                    <?php } ?>
                                </select>

                                <!--<input type="text" name="tagValue" id="tagValue" class="form-control" />-->
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="addTags" class="btn btn-primary" onclick="add_tags();">Add Tags</button>
                            </div>
                        </div>

                        <?php } ?>	
                    </div>


                </div>



            </div>


        </div>
    </div>
</div>
