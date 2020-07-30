var numQuotesUnpaired = 0;
if ($("#allowToggling").length) {
    var toggleUnpaired = $("#allowToggling").find("li:eq( 1 )"); //can't select by id because of xslt calling template
    if (!$("#collapseLink").length) toggleUnpaired.wrap("<a data-toggle='collapse' id='collapseLink' href='#unpairedQuotes'></a>");
    numQuotesUnpaired = $("#unpairedQuotes div div").length;
}

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
