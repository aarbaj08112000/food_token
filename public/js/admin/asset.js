var table = '';
var file_name = "asset";
var pdf_title = "Asset";

$(document).ready(function() {

    // Initialize the DataTable
    table = new DataTable('#asset', {
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

    $('#add_shifts_form').validate({
        rules: {
            namess: {
                required: true
            }
        },
        messages: {
            namess: {
                required: "Please enter the name."
            }
        },
        submitHandler: function (form) {
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                success: function (response) {
                    // Handle success response
                    alert("Shift has been added successfully.");
                    $('#addPromo').modal('hide');
                    form.reset(); // Reset form fields after submission
                },
                error: function (response) {
                    // Handle error response
                    alert("There was an error submitting the form. Please try again.");
                }
            });
            return false; // Prevent default form submission
        }
    });

});
