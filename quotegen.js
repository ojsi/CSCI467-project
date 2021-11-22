$(document).ready(function() 
{
    //Max Number Of Lines
    var max_fields = 20;
    var CurrentLines = 1;
    //Div Wrappers
    var wrapper = $("#QuoteContent");
    var add_button = $("#addField");
    var add_line = '<div class="input-group">';
    add_line += '<div class="col-7 px-1">';
    add_line += '<input class="form-control" placeholder="Line Item" type="text" required name="lineitem[]"/>';
    add_line += '</div>';
    add_line += '<div class="col-3 px-1 input-group">';
    add_line += '<div class="input-group-prepend"><span class="input-group-text">$</span></div>';
    add_line += '<input class="form-control" pattern="^\\d+\\.?\\d\\d$" placeholder="Price" required type="text" name="price[]"/>';
    add_line += '</div>';
    var first = add_line + '</div>';
    add_line += '<div class="col-2 pl-1">';
    add_line += '<a href="#" class="delete , btn btn-danger" role="button" >Delete</a>';
    add_line += '</div>';
    add_line += '</div>';

    //Load In The First Item
    $(wrapper).on("load",add_button, AddFirstLine());

    //Add Line into Quote
    $(add_button).click(MaxLines);

    //Remove Item from Quote
    $(wrapper).on("click", ".delete", DeleteLine);

    //Signal to select Customer from Database
    $('#name').click(SelectCustomer);
    $('#contact').click(SelectCustomer);
    $('#street').click(SelectCustomer);
    $('#city').click(SelectCustomer);

    //alert Select Customer Info From Quote
    function SelectCustomer()
    {
        alert("Please Click on Select Customer Info For Quote");
    }
    //Remove the Line Item
    function DeleteLine(event) 
    {
        event.preventDefault();
        $(this).parent().parent('div').remove();
        CurrentLines--;
    };

    //Insert a New Line
    function MaxLines(event)
    {
        event.preventDefault();
        if (CurrentLines < max_fields) 
        {
            CurrentLines++;
            $(wrapper).append(add_line);
        } else {
            alert('20 Lines Maximum Per Quote')
        }
    };

    function AddFirstLine() 
    {
        $(wrapper).append(first);
    };
});