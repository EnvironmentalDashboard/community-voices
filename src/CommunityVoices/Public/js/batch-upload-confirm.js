$(document).ready(function() {
    numEntriesWrong = 0;
    $("[essentialcolumn]").each(function() {
        checkRequiredFieldEmpty($(this));
    });
    checkEntryIssuesDiv();
});

function checkRequiredFieldEmpty(field) {
    linkExists = field.parent("a").length; // have we already added a link to this?

    identifier = field.closest("[hasidentifier = true]").attr("id");
    quoteNumber = "";
    columnName = field.closest(".form-group.row").find("label").attr("formattedname"); // gosh this is ugly
    if(field.attr("haserrors")=="quote") {
        quoteNumber = field.closest("[quotenumber]").attr("quotenumber");
    }

    strToAdd = identifier + " " + quoteNumber + " " + columnName;
    linkToAdd = strToAdd.split(' ').join('');

    if (! field.val()) {
        field.wrap(function() {
            return "<a name='" + linkToAdd + "'></a>";
        });
        $("#entryIssues").find("ul").append("<a href='#" + linkToAdd + "'><li>" + strToAdd + "</li></a>");
    } else if (linkExists != 0) {
        $("#entryIssues").find("[href ='#" + linkToAdd + "']").remove();
        field.unwrap();
    }
}

function checkEntryIssuesDiv() {
    if ($("#entryIssues").find("ul").children().length == 0) $("#entryIssues").hide();
    else $("#entryIssues").show();
}

$("[essentialcolumn]").keyup(function() {
    checkRequiredFieldEmpty($(this));
    checkEntryIssuesDiv();
});

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
