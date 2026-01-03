var table = '';
var file_name = "uom";
var pdf_title = "UOM";

$(document).ready(function() {

    $('.search-filter').on('click', function(e) {
        let part_val = $('#part_id_selected').val();
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
        $("#id").val(data.id);
        $("#uom_name").val(data.uom_name);
        $("#uom_description").val(data.uom_description);
       
        // myModal.show();
    })

    // Initialize the DataTable
    table = $("#uom").DataTable({
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
                            values.splice(3, 1);
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
                    // doc.content[1].table.widths = ["19%", "19%", "13%", "13%", "15%", "15%"];
                    doc.content[1].table.body[0].forEach(function (cell) {
                        cell.fillColor = theme_color;
                    });
                    doc.content[1].table.body.forEach(function (row, index) {
                        row.splice(3, 1);
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
        columnDefs: [{ sortable: false, targets: 3 }],
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
    $('#update_uom').validate({
        rules: {
            uuomName: {
                required: true
            },
            uomDescription: {
                required: true
            }
        },
        messages: {
            uuomName: {
                required: "Please enter the UOM Name"
            },
            uomDescription: {
                required: "Please enter the UOM Description"
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
                            toastr.success(res['msg']);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }else{
                            toastr.error(res['msg']);
                        }
                    }
                    
                    // Optionally, close the modal
                    $('#exampleModal2').modal('hide');
                    
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

    $('#add_uom').validate({
        rules: {
            uomName: {
                required: true
            },
            uomDescription: {
                required: true
            }
        },
        messages: {
            uomName: {
                required: "Please enter the UOM Name"
            },
            uomDescription: {
                required: "Please enter the UOM Description"
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
                            toastr.success(res['msg']);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }else{
                            toastr.error(res['msg']);
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
