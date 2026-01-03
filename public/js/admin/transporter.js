var table = '';
var file_name = "transporter";
var pdf_title = "Transporter";

$(document).ready(function() {
    // Initialize the DataTable
    
    table = $("#transporter").DataTable({
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
                            values.splice(7, 1);
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
                    doc.content[1].table.widths = ["33.333333333%", "33.333333333%", "33.333333333%"];
                    doc.content[1].table.body[0].forEach(function (cell) {
                        cell.fillColor = theme_color;
                    });
                    doc.content[1].table.body.forEach(function (row, index) {
                        row.splice(7, 1);
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
        // columnDefs: [{ sortable: false, targets: 7 }],
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

    $.validator.addMethod("regex", function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Invalid format.");

    $("#addTransporterForm").validate({
        rules: {
            namess: {
                required: true,
                minlength: 3
            },
            transporter_id: {
                required: true,
                regex: /^([0-9]{2}[0-9A-Z]{13})$/
            }
        },
        messages: {
            namess: {
                required: "Please enter the transporter name",
                minlength: "The name must be at least 3 characters long"
            },
            transporter_id: {
                required: "Please enter the transporter ID",
                regex: "Please enter a valid transporter ID"
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
                    if(response !== '' && response !== null && typeof response !== 'undefined'){
                        let res = JSON.parse(response);
                        if(res['success'] === 1){
                            toastr.success(res['msg']);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }else{
                            toastr.error(res['msg']);
                        }
                    }
                    // Optionally, hide the modal
                    $('#addPromo').modal('hide');
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Form submission failed:', error);
                }
            });

        }
    });
    // Custom search filter event
  
   



});
