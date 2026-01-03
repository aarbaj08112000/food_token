var table = '';
var file_name = "gst";
var pdf_title = "GST";

$(document).ready(function() {

    $('.search-filter').on('click', function(e) {
        let part_val = $('#part_id_selected').val();
        
        // Ensure that the table and column exist before applying the search
        if (table && part_val) {
            table.column(1).search(part_val).draw();
        }
        $('.close-filter-btn').trigger('click');
    });

    $('.reset-filter').on('click',function(e){
        table.column(1).search('').draw();
        $('.close-filter-btn').trigger('click');    
    })

    $(document).on("click",".edit-part",function(){
        var data = $(this).attr("data-value");
        data = JSON.parse(atob(data)); 
        
        $('input[name="sgst"]').val(data.sgst);
        $('input[name="cgst"]').val(data.cgst);
        $('input[name="igst"]').val(data.igst);
        $('input[name="tcs"]').val(data.tcs);
        $('input[name="id"]').val(data.id);
    
        // Set selected option for TCS On GST
        $('select[name="tcs_on_tax"]').val(data.tcs_on_tax).trigger('change');
    
        // Set selected option for With in State
        $('select[name="with_in_state"]').val(data.with_in_state).trigger('change');
       
        // myModal.show();
    })

    // Initialize the DataTable
    table = $("#gst").DataTable({
        dom: "Bfrtilp",
        buttons: [
            {
                extend: "csv",
                text: '<i class="ti ti-file-type-csv"></i>',
                init: function (api, node, config) {
                    $(node).attr("title", "Download CSV");
                },
                customize: function (csv) {
                        var lines = csv.split('\n');
                        var modifiedLines = lines.map(function(line) {
                            var values = line.split(',');
                            // values.splice(8, 1);
                            return values.join(',');
                        });
                        return modifiedLines.join('\n');
                    },
                    filename : file_name
                },
          
            {
                extend: "pdf",
                text: '<i class="ti ti-file-type-pdf"></i>',
                init: function (api, node, config) {
                    $(node).attr("title", "Download Pdf");
                },
                filename: file_name,
                customize: function (doc) {
                    doc.pageMargins = [15, 15, 15, 15];
                    doc.content[0].text = pdf_title;
                    doc.content[0].color = theme_color;
                    doc.content[1].table.widths = ["12.5%", "12.5%", "12.5%", "12.5%", "12.5%", "12.5%", "12.5%", "12.5%"];
                    doc.content[1].table.body[0].forEach(function (cell) {
                        cell.fillColor = theme_color;
                    });
                    doc.content[1].table.body.forEach(function (row, index) {
                        row.splice(8, 1);
                        row.forEach(function (cell) {
                            // Set alignment for each cell
                            cell.alignment = "center"; // Change to 'left' or 'right' as needed
                        });
                    });
                },
            },
        ],
        searching: true,
        // scrollX: true,
        scrollY: true,
        bScrollCollapse: true,
        columnDefs: [{ sortable: false, targets: 8 }],
        pagingType: "full_numbers",
       
        
        });
        $('#serarch-filter-input').on('keyup', function() {
            table.search(this.value).draw();
        });
        $('.dataTables_length').find('label').contents().filter(function() {
                return this.nodeType === 3; // Filter out text nodes
        }).remove();
        setTimeout(function(){
            $(".dataTables_length select").select2({
                minimumResultsForSearch: Infinity
            });
        },1000)
    $("#updateGstForm").validate({
        rules: {
            sgst: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            cgst: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            igst: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            tcs: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            id: {
                required: true
            },
            tcs_on_tax: {
                required: true
            },
            with_in_state: {
                required: true
            }
        },
        messages: {
            sgst: {
                required: "Please enter SGST value",
                number: "Please enter a valid number",
                min: "Value must be at least 0",
                max: "Value cannot exceed 100"
            },
            cgst: {
                required: "Please enter CGST value",
                number: "Please enter a valid number",
                min: "Value must be at least 0",
                max: "Value cannot exceed 100"
            },
            igst: {
                required: "Please enter IGST value",
                number: "Please enter a valid number",
                min: "Value must be at least 0",
                max: "Value cannot exceed 100"
            },
            tcs: {
                required: "Please enter TCS value",
                number: "Please enter a valid number",
                min: "Value must be at least 0",
                max: "Value cannot exceed 100"
            },
            id: {
                required: "ID is required"
            },
            tcs_on_tax: {
                required: "Please select TCS On GST option"
            },
            with_in_state: {
                required: "Please select With in State option"
            }
        },
        submitHandler: function(form) {
            // Perform AJAX form submission
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    // Handle successful response
                    if(response != '' && response != null && typeof response != 'undefined'){
                        let res = JSON.parse(response);
                        if(res['success'] == 1){
                            toastr.success(res['msg']);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }else{
                            toastr.error(res['msg']);
                        }
                    }
                    // Optionally, hide the modal
                    $('#edit').modal('hide');
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Form submission failed:', error);
                }
            });
        }
    });;

    $('#add_gst').validate({
        rules: {
            code: {
                required: true
            },
            sgst: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            cgst: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            igst: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            tcs: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            tcs_on_tax: {
                required: true
            },
            with_in_state: {
                required: true
            }
        },
        messages: {
            code: {
                required: "Please enter the Tax Code"
            },
            sgst: {
                required: "Please enter the SGST value",
                number: "Please enter a valid number",
                min: "Value cannot be less than 0",
                max: "Value cannot be more than 100"
            },
            cgst: {
                required: "Please enter the CGST value",
                number: "Please enter a valid number",
                min: "Value cannot be less than 0",
                max: "Value cannot be more than 100"
            },
            igst: {
                required: "Please enter the IGST value",
                number: "Please enter a valid number",
                min: "Value cannot be less than 0",
                max: "Value cannot be more than 100"
            },
            tcs: {
                required: "Please enter the TCS value",
                number: "Please enter a valid number",
                min: "Value cannot be less than 0",
                max: "Value cannot be more than 100"
            },
            tcs_on_tax: {
                required: "Please select an option for TCS on Taxable amount"
            },
            with_in_state: {
                required: "Please select if it's within state"
            }
        },
        submitHandler: function(form) {
            // Serialize the form data
            var formData = $(form).serialize();

            // Send the data using AJAX
            $.ajax({
                url: $(form).attr('action'), // Get the form's action attribute
                type: 'POST', // Use POST method
                data: formData,
                success: function(response) {
                    // Handle the success response
                    if(response != '' && response != null && typeof response != 'undefined'){
                        let res = JSON.parse(response);
                        if(res['success'] == 1){
                            toastr.success(res['messages']);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }else{
                            toastr.error(res['messages']);
                        }
                    }
                    // Optionally, close the modal
                    $('#exampleModal').modal('hide');
                    
                    // Optionally, reset the form
                    $(form)[0].reset();
                },
                error: function(xhr, status, error) {
                    // Handle the error response
                    alert('An error occurred: ' + error);
                }
            });
        }
    });



    // Custom search filter event
  
   



});
