$(function() {
    function getData(selectedValue, targetUrl, destination) {
        $.ajax({
            type: 'get',
            url: targetUrl,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            },
            success: function(response) {
                if (response.departments) {
					destination.empty(),
					destination.append('<option value="Please Select">Please Select</option>');
					appendData(response.departments, destination); 
                }
                if (response.staffs) {
					destination.empty(),
					destination.append('<option value="Please Select">Please Select</option>');
					appendData(response.staffs, destination);
				}
				 if (response.feedbackEvents) {
					destination.empty(),
					destination.append('<option value="Please Select">Please Select</option>');
					appendData(response.feedbackEvents, destination);
				}
            },
        });
    }

	function appendData(data, destination) {
		for (var prop in data) {
			if (data.hasOwnProperty(prop)) {
			$(destination).append('<option value="' + prop + '">' + data[prop] + '</option>');
			}
		}
	}

    $('#institutions').change(function() {
        var selectedValue	=	$(this).val(),
            destination 	=	$('#departments'),
            destevent		=	$('#feedback_events');

		if(selectedValue != '')
		{
            targetUrl = $(this).attr('rel') + '?id=' + selectedValue;
            getData(selectedValue, targetUrl, destination);
            targetUrl = $(this).attr('data-rel') + '?id=' + selectedValue;
			getData(selectedValue, targetUrl, destevent);

		}	else {
        destination.empty(),
        destination.append('<option value="Select Institution First">Select Institution First</option>');
		destevent.empty(),
		destevent.append('<option value="Select Institution First">Select Institution First</option>');
		}
    });

    $('#departments').change(function() {
		var selectedValue = $(this).val(),
			  destination = $('#staffs');
		if(selectedValue != 'Please Select')
		{
			targetUrl = $(this).attr('rel') + '?id=' + selectedValue;
			getData(selectedValue, targetUrl, destination);

		}	else {
		destination.empty(),
		destination.append('<option value="Select Department First">Select Department First</option>');
		}
    });
});
