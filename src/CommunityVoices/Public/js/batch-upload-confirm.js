var numQuotesUnpaired = 0;
if ($("#allowToggling").length) {
    var toggleUnpaired = $("#allowToggling").find("li:eq( 1 )"); //can't select by id because of xslt calling template
    if (!$("#collapseLink").length) toggleUnpaired.wrap("<a data-toggle='collapse' id='collapseLink' href='#unpairedQuotes'></a>");
    numQuotesUnpaired = $("#unpairedQuotes div div").length;
    var deleteAllUnpaired = $("#allowToggling").find("li:eq( 2 )");
    deleteAllUnpaired.wrap("<a id='deleteAllUnpaired' href=''></a>");
}

$("#deleteAllUnpaired").click(function(e) {
    if (confirm("Are you sure?")) {
        e.preventDefault();
        $("#unpairedQuotes").remove();
        $("#allowToggling").remove();
    }
});

function checkNumUnpaired() {
    numQuotesUnpaired = $("#unpairedQuotes div div").length;
    if(numQuotesUnpaired == 0 && $("#unpairedQuotes").length) {
        $("#unpairedQuotes").remove();
        $("#allowToggling").remove();
    }
}

$("#unpairedQuotes div div form").submit(function(event) {
  event.preventDefault();
  identifierToAppend = $(this).find("div div .validIdentifiers");
  if (identifierToAppend.val()) {
      identifierElm = $('#'.concat(identifierToAppend.val(), ' .pairedQuotes'));
      $(this).find(".identifiersFormElm").remove();
      $(this).find(".pairButton").remove();
      $(identifierElm).append($(this).parent());
      checkNumUnpaired();
  }
});

$('#fileUploadButton').on('click', function (c)  {
	$('#file').click();
});

$("#file").change(function(){
    $('#batchUploadForm').submit();
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

$(".deleteEntry").click(function() {
    if (confirm("Are you sure?")) {
        if($(this).hasClass("sourceDelete") && $("#unpairedQuotes").length) { // need to remove the option to pair with this
            sourceId = $(this).parent().parent().parent().parent().attr("id");
            $(".validIdentifiers".concat(' .',(sourceId))).remove(); // remove option to pair with this source
            $(this).parent().parent().parent().parent().remove();
        } else {
            $(this).parent().parent().parent().remove();
            checkNumUnpaired();
        }
    }
});
