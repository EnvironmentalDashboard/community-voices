const allErrorsApiUrl = '/community-voices/api/errors-log',
    optionsUrl = window.location.pathname.split('log')[1], // the number of lines from end to include .e.g. /500
    dataTypeToReceive = "JSON",
    outerArrayKey = 'errorsLog',
    errorsDisplayElm = $('#errors'),
    startDate = "1/1/2010",
    endDate = moment();
var data = {},
    numberOfLines = 0, // tracks how far from end of file to optimize adding lines
    clkBtn = null,
    listOfErrors = [];

$(document).ready(function() {
    data['firstTime'] = true;
    errorsRequest(data);

    function setHtml(l,append) {
        var errorMessages = '';
        $.each(l, function (index, value) {
            errorMessages += '<li class="list-group-item">' + value.Time + value.Message + '</li>';
        });
        if (append) {
            errorsDisplayElm.append(errorMessages);
        } else {
            errorsDisplayElm.html(errorMessages);
        }
    }

    function errorsRequest(data){
        $.ajax({
            dataType: dataTypeToReceive,
            url: allErrorsApiUrl.concat(optionsUrl),
            data: data,
            success: function(response) {
                if(response) {
                    //console.log(response);
                    var errorsArray = response[outerArrayKey][0];
                    listOfErrors = errorsArray; // allows us to pass in array to php to filter array for date selection
                    setHtml(listOfErrors,(clkBtn == 'linesSubmit'));
                    numberOfLines = response[outerArrayKey][1];
                }

            },
            error: function(xhr, status, error) {
                errorsDisplayElm.html(xhr.responseText);
            }
        });
    }

    $('.daterange').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        startDate: startDate,
        endDate: endDate,
        locale: {
            format: 'M/DD/YY H:mm'
        },
    });

    $('#byLines').on('click',function() {
        $("#searchLines").css("display","inline");
        $("#searchDates").css("display","none");
    });

    $('#byDates').on('click',function() {
        $("#searchLines").css("display","none");
        $("#searchDates").css("display","inline");
    });

    // allows us to see which button was clicked as the submit button
    $('input[type="submit"]').click(function(evt) {
        clkBtn = evt.target.id;
      });

    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        data['firstTime'] = '';
        if (clkBtn == 'linesSubmit') {
            data['numLines'] = $("#numLines").val();
            data['dateRange'] = '';
            data['linePos'] = numberOfLines;
            errorsRequest(data);
        } else {
            data['dateRange'] = $("#dateRange").val();
            data['numLines'] = '';
            data['linePos'] = '';
            errorsRequest(data);
        }
    });
});
