var numQuotesUnpaired = 0;
if ($("#entryIssues").length) { // this function allows linking sheet errors on same page
    $("#entryIssues").find("ul li").each(function() {
        $(this).wrap(function() {
            return "<a href='#" + $(this).text().split(" ").join("") + "'></a>";
        });
    });
    $("[haserrors]").each(function() {
        identifier = $(this).closest("[hasidentifier = true]").attr("id");
        quoteNumber = "";
        columnName = $(this).parent().parent().find("label").attr("formattedname"); // gosh this is ugly
        if($(this).attr("haserrors")=="quote") {
            quoteNumber = $(this).closest("[quotenumber]").attr("quotenumber");
        }
        strToAdd = identifier + quoteNumber + columnName;
        $(this).wrap(function() {
            return "<a name='" + strToAdd + "'></a>";
        });
    });

}

if ($("#allowToggling").length) {
    var toggleUnpaired = $("#allowToggling").find("li:eq( 1 )"); //can't select by id because of xslt calling template
    if (!$("#collapseLink").length) toggleUnpaired.wrap("<a data-toggle='collapse' id='collapseLink' href='#unpairedQuotes'></a>");
    numQuotesUnpaired = $("#unpairedQuotes div div").length;
    var deleteAllUnpaired = $("#allowToggling").find("li:eq( 2 )");
    deleteAllUnpaired.wrap("<a id='deleteAllUnpaired' href=''></a>");
}

$("#deleteAllUnpaired").click(function(e) {
    e.preventDefault();
    if (confirm("Are you sure?")) {
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
