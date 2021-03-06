function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

function openListOfValue(table_name,mode)
{
	$('input[name="listofvalue_table"]').val(table_name);
	$('input[name="listofvalue_mode"]').val(mode);
	$('#listofvalue').hide();
	$.ajax(
		{
			url: '/ajax/listofvalue',
			data: {
				table_name: table_name,
				mode: mode
			},
			type: 'GET',
			dataType: 'html',
			success: function (data) {
				$('#listofvalue').html(data);
				$('#listofvalue').slideDown('slow');
			},
			error: function (xhr, status) {
				alert("Sorry, there is an error while getting list of value.");
			},
		}
	);
	$('#ListOfValueModal').modal()
}

function checkDirtyBit()
{
	if($('input[name="dirtybit"]').val() == 'true')
	{
		var ans = confirm('Are you sure to discard the changes?');
		return ans;
	}
	else
	{
		return true;
	}
}

$(document).ready(function() {
	$('form#mainform input').on('change keypress', function() {
		$('input[name="dirtybit"]').val('true');
	});
	$('form#mainform select').on('change', function() {
		$('input[name="dirtybit"]').val('true');
	});
});
