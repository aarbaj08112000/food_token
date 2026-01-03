var table = '';
var file_name = "client";
var pdf_title = "Client";

$(document).ready(function() {

    $('.search-filter').on('click', function(e) {
        let part_val = $('#clientId').val();
        console.log(part_val);
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
        console.log(data);
        $("#client_unit").val(data.client_unit);
        $("#client_unit").val(data.client_unit);
        $("#contact_person").val(data.contact_person);
        $("#billing_address").val(data.billing_address);
        $("#shifting_address").val(data.shifting_address);
        $("#gst_number").val(data.gst_number)
        $("#pan_no").val(data.pan_no);
        $("#state").val(data.state);
        $("#state_no").val(data.state_no);
        $("#phone_no").val(data.phone_no);
        $("#bank_details").val(data.bank_details)
        $("#address1").val(data.address1);
        $("#location").val(data.location);
        $("#pin").val(data.pin);
        $('#t-id').val(data.id);
       
        // myModal.show();
    })

    // Initialize the DataTable
    table = $("#client").DataTable({
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
                            values.splice(14, 1);
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
                    doc.content[1].table.widths = ["8%", "7%", "7%", "7%", "7%", "7%", "7%", "7%", "7%", "7%", "7%", "7%", "7%", "7%"];
                    doc.content[1].table.body[0].forEach(function (cell) {
                        cell.fillColor = theme_color;
                    });
                    doc.content[1].table.body.forEach(function (row, index) {
                        row.splice(14, 1);
                        row.forEach(function (cell) {
                            // Set alignment for each cell
                            cell.alignment = "center"; // Change to 'left' or 'right' as needed
                        });
                    });
                },
            },
        ],
        searching: true,
        scrollX: true,
        scrollY: true,
        bScrollCollapse: true,
        columnDefs: [{ sortable: false, targets: 7 }],
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

     // Initialize form validation
     $('#update_client_form').validate({
        rules: {
            uclientUnit: {
                required: true
            },
            uclientName: {
                required: true
            },
            ucontactPerson: {
                required: true
            },
            uclientBaddress: {
                required: true
            },
            uclientSaddress: {
                required: true
            },
            ugst_no: {
                required: true
            },
            pan_no: {
                required: true
            },
            state: {
                required: true
            },
            state_no: {
                required: true
            },
            uphone_no: {
                required: true,
                number: true
            },
            bank_details: {
                required: true
            },
            address1: {
                required: true
            },
            location: {
                required: true
            },
            pin: {
                required: true
            }
        },
        messages: {
            uclientUnit: {
                required: "Please enter the contact unit."
            },
            uclientName: {
                required: "Please enter the client name."
            },
            ucontactPerson: {
                required: "Please enter the contact person."
            },
            uclientBaddress: {
                required: "Please enter the client billing address."
            },
            uclientSaddress: {
                required: "Please enter the client shipping address."
            },
            ugst_no: {
                required: "Please enter the GST number."
            },
            pan_no: {
                required: "Please enter the PAN number."
            },
            state: {
                required: "Please enter the state."
            },
            state_no: {
                required: "Please enter the state code."
            },
            uphone_no: {
                required: "Please enter the phone number.",
                number: "Please enter a valid phone number."
            },
            bank_details: {
                required: "Please enter the bank details."
            },
            address1: {
                required: "Please enter the address."
            },
            location: {
                required: "Please enter the location."
            },
            pin: {
                required: "Please enter the pin."
            }
        },
        submitHandler: function (form) {
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                success: function (response) {
                    // Handle success response
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
                    $('#exampleModal2').modal('hide'); // Hide the modal
                    form.reset(); // Reset form fields after submission
                },
                error: function (response) {
                    // Handle error response
                    alert("There was an error updating the client information. Please try again.");
                }
            });
            return false; // Prevent default form submission
        }
    });

    $('#add_mold_maintenance_form').validate({
        rules: {
            customer_part_id: {
                required: true
            },
            mold_name: {
                required: true
            },
            ownership: {
                required: true
            },
            no_of_cavity: {
                required: true,
                number: true
            },
            target_life: {
                required: true,
                number: true
            },
            target_over_life: {
                required: true,
                number: true
            }
        },
        messages: {
            customer_part_id: {
                required: "Please select a customer part."
            },
            mold_name: {
                required: "Please enter the mold name."
            },
            ownership: {
                required: "Please select ownership."
            },
            no_of_cavity: {
                required: "Please enter the number of cavities.",
                number: "Please enter a valid number."
            },
            target_life: {
                required: "Please enter the mold PM frequency.",
                number: "Please enter a valid number."
            },
            target_over_life: {
                required: "Please enter the mold life over frequency.",
                number: "Please enter a valid number."
            }
        },
        submitHandler: function (form) {
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function (response) {
                    // Handle success response
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
                    $('#addPromo1').modal('hide'); // Hide the modal
                    form.reset(); // Reset form fields after submission
                },
                error: function (response) {
                    // Handle error response
                    alert("There was an error adding the Mold Maintenance. Please try again.");
                }
            });
            return false; // Prevent default form submission
        }
    });



    // Custom search filter event
  
   



});
