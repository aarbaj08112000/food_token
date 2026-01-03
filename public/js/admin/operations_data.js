var table = '';
var file_name = "operations_data";
var pdf_title = "Operations Data";

$(document).ready(function() {

    // Initialize the DataTable
    table = new DataTable('#operations_data', {
        dom: 'Bfrtip',
        scrollX: true,
        buttons: [
            {     
                extend: 'csv',
                text: '<i class="ti ti-file-type-csv"></i>',
                init: function(api, node, config) {
                    $(node).attr('title', 'Download CSV');
                },
                customize: function(csv) {
                    var lines = csv.split('\n');
                    var modifiedLines = lines.map(function(line) {
                        var values = line.split(',');
                        values.splice(4, 4); // Make sure this logic is correct for your use case
                        return values.join(',');
                    });
                    return modifiedLines.join('\n');
                },
                filename: file_name
            },
            {
                extend: 'pdf',
                text: '<i class="ti ti-file-type-pdf"></i>',
                init: function(api, node, config) {
                    $(node).attr('title', 'Download Pdf');
                },
                filename: file_name,
                customize: function(doc) {
                    doc.pageMargins = [15, 15, 15, 15];
                    doc.content[0].text = pdf_title;
                    doc.content[0].color = theme_color;
                    doc.content[1].table.body[0].forEach(function(cell) {
                        cell.fillColor = theme_color;
                    });
                    doc.content[1].table.body.forEach(function(row) {
                        row.forEach(function(cell) {
                            // Custom alignment logic
                            cell.alignment = 'left'; // Default to left, adjust based on your needs
                        });
                        row.splice(4, 4); // Make sure this logic is correct for your use case
                    });
                }
            },
        ],
         searching: true,
        // scrollX: true,
        scrollY: true,
        bScrollCollapse: true,
        // columnDefs: [{ sortable: false, targets: 7 }],
        pagingType: "full_numbers",
    });
    $('#serarch-filter-input').on('keyup', function() {
            table.search(this.value).draw();
        });


    $('#add_operations_data').validate({
        rules: {
            product: {
                required: true
            },
            process: {
                required: true
            },
            specification_tolerance: {
                required: true
            },
            evalution: {
                required: true
            },
            size: {
                required: true
            },
            frequency: {
                required: true
            }
        },
        messages: {
            product: {
                required: "Please enter the product name."
            },
            process: {
                required: "Please enter the process name."
            },
            specification_tolerance: {
                required: "Please provide the specification tolerance."
            },
            evalution: {
                required: "Please provide the evaluation."
            },
            size: {
                required: "Please enter the size."
            },
            frequency: {
                required: "Please enter the frequency."
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'), // Form action URL
                type: 'POST',
                data: new FormData(form), // Form data
                processData: false,
                contentType: false,
                success: function(response) {
                    // Handle the response here
                    let res = JSON.parse(response);
                    if (res.success == 1) {
                        toastr.success(res.msg);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error(res.msg);
                    }
                    // Close the modal and reset the form
                    $(form)[0].reset();
                    $('#addPromo').modal('hide');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    alert('An error occurred: ' + errorThrown);
                }
            });
            return false; // Prevent the default form submit
        }
    });




    // Custom search filter event
  
   



});
