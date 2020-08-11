$(document).ready(function() {
    $(".individualQuote").each(function () {
        listSelected = [$(this).find("[formattedName = contentcategories]"),$(this).find("[formattedName = tags]")];
        allBoxes = [$(this).find(".contentCategoryCheckboxList"), $(this).find(".tagCheckboxList")];
        fillCheckBoxes(listSelected[0],allBoxes[0]);
        fillCheckBoxes(listSelected[1],allBoxes[1]);
    });
    $("[essentialorrecoomendedcolumn]").each(function() {
        checkRequiredFieldEmpty($(this));
    });
    checkEntryIssuesDiv();
});

function fillCheckBoxes (listSelected, allBoxes) {
    listSelected.find("li").each(function () {
        selectedInput = $(this);
        allBoxes.find("div").each(function () {
            checkboxLabel = $(this).find("label");
            checkbox = $(this).find("input");
            if(checkboxLabel.text().toLowerCase() == selectedInput.text().toLowerCase()) {
                checkbox.attr("checked",true);
                return false; // Since we have already found a match we should break, moving onto next user-entered content category */
            }
        });
    });
}

function checkRequiredFieldEmpty(row) {
    rowType = row.find(".checkboxHeader").length != 0 ? "checkbox" : "field";
    linkExists = row.parent("a").length; // have we already added a link to this? Need to check
    input = row.find($('input'));

    identifier = row.closest("[hasidentifier = true]").attr("id");
    quoteNumber = "";
    columnName = row.attr("formattedname");
    if(row.attr("haserrors")=="quote") {
        quoteNumber = row.closest("[quotenumber]").attr("quotenumber");
    }

    strToAdd = identifier + " " + quoteNumber + " " + columnName;
    linkToAdd = strToAdd.split(' ').join('');

    if(rowType == "checkbox") isEmpty = row.find($('input:checkbox:checked')).length == 0;
    else isEmpty = input.val().length == 0;
    if (isEmpty) {
        row.wrap(function() {
            return "<a name='" + linkToAdd + "'></a>";
        });
        $("#entryIssues").find("ul").append("<a href='#" + linkToAdd + "'><li>" + strToAdd + "</li></a>");
        if (rowType == "field") input.attr("placeholder",input.attr("message"));
        else row.find(".checkboxHeader").append("<br> <strong> You must select a content category! </strong>");
    } else if (linkExists != 0) {
        $("#entryIssues").find("[href ='#" + linkToAdd + "']").remove();
        row.unwrap();
        // need to take away bold text by checkboxes, but don't need to remove bold and line break
        if (rowType == "checkbox")  {
            row.find("br").remove();
            row.find("strong").remove();
        }
    }
}

function checkEntryIssuesDiv() {
    if ($("#entryIssues").find("ul").children().length == 0) $("#entryIssues").hide();
    else $("#entryIssues").show();
}

$("[essentialorrecoomendedcolumn]").keyup(function() {
    checkRequiredFieldEmpty($(this).closest(".row"));
    checkEntryIssuesDiv();
});

$("input:checkbox").change(function() {
    checkRequiredFieldEmpty($(this).closest(".row"));
    checkEntryIssuesDiv();
});

function uploadSourceQuotePair(source,quote) {
    $("#actualForm").append(source.clone());
    $("#actualForm").append(quote.clone());
    $("#actualForm").append("<input name='quotationMarks' value='on'></input>");
    // need to have this field to prevent PDO error -- issue to fix later
    $("#actualForm").find("[name=contentCategories]").each(function () {
        if(!$(this).val())
            $(this).parent().parent().remove();
    });
    $("#actualForm").find("[name=tags]").each(function () {
        if(!$(this).val())
            $(this).parent().parent().remove();
    });
    $("#actualForm").submit();


}

$(".individualUploadButton").click(function() {
    // first check if there are any errors within source div, then call uploadSourceQuotePair on each pair
    if ($(this).find("[haserrors]").length != 0)
        alert("Cannot upload quotes with this source. Please check errors.");
    else {
        sourceElm = $(this).closest(".allSources");
        sourceNotQuote = sourceElm.find(".sourceNotQuote");
        sourceElm.find(".individualQuote").each(function () {
            uploadSourceQuotePair(sourceNotQuote,$(this));
        });
    }
});

$("#submitAll").click(function() {
    $("#actualForm").submit();
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

$(".pairWithIdentifier").click(function() { // pair unpaired Quote with an identifier
  individualQuote = $(this).closest(".individualQuote");
  identifierToAppend = individualQuote.find(".validIdentifiers")
  if (identifierToAppend.val()) {
      identifierElm = $('#'.concat(identifierToAppend.val())).find(".pairedQuotes");
      individualQuote.find(".identifiersFormElm").remove(); // need to remove pairing field and pair button after pairing
      $(this).remove();
      $(identifierElm).append(individualQuote);
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
        if($(this).hasClass("sourceDelete") && $("#unpairedQuotes").length) {
            sourceId = $(this).parent().parent().parent().parent().attr("id");
            $(".validIdentifiers".concat(' .',(sourceId))).remove(); // remove option to pair with this source
            $(this).parent().parent().parent().parent().remove();
        } else {
            $(this).parent().parent().parent().remove();
            checkNumUnpaired();
        }
    }
});
