function postData(form,quote) {
    data = form.serializeArray();
    $.ajax({
      url : '/community-voices/api/quotes/new',
      type: "POST",
      data: data,
      success: function (data) {
          source = quote.closest(".allSources");
          // need to remove source too if there are no more unpaired quotes or other quotes with the source
          if (quote.closest(".pairedQuotes").children.length == 0 && $("#unpairedQuotes").length == 0)
              source.remove();
          else {
              quote.remove();
              manipulateIndividualUploadButton(source);
          }
      }
    });
    form.empty();
}
$(document).ready(function() {
    $(".individualQuote").each(function () {
        listSelected = [$(this).find("[formattedName = contentcategories]"),$(this).find("[formattedName = tags]")];
        allBoxes = [$(this).find(".contentCategoryCheckboxList"), $(this).find(".tagCheckboxList")];
        fillCheckBoxes(listSelected[0],allBoxes[0]);
        fillCheckBoxes(listSelected[1],allBoxes[1]);
    });
    $("[message]").each(function() {
        checkRequiredFieldEmpty($(this));
    });
    checkEntryIssuesDiv();
    $(".allSources").each(function () {
        manipulateIndividualUploadButton($(this));
    });
});

 function manipulateIndividualUploadButton(source) {
    if (source.find(".pairedQuotes").children().length && source.find(".individualUploadButton").length == 0) {
        individualUploadButton = "<div class='row'><div class='col text-center'><input type='button' form='batchUploadForm' class='btn btn-primary individualUploadButton' value='Upload Quotes with this Source' id='fileUploadButton'></input></div></div>"
        source.find(".uploadButtonContainer").append(individualUploadButton);
    } else if (source.find("pairedQuotes").children().length == 0){
        source.find(".individualUploadButton").remove();
    }
}

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
    if (! row.closest("#unpairedQuotes").length) {
        rowType = row.find(".checkboxHeader").length != 0 ? "checkbox" : "field";
        linkExists = row.parent("a").length; // have we already added a link to this? Need to check
        input = row.find($('input'));

        identifier = row.closest("[hasIdentifier = true]").attr("id");
        quoteNumber = row.closest("[quoteNumber]").length ? row.closest("[quoteNumber]").attr("quoteNumber") : "";
        columnName = row.attr("formattedName");

        strToAdd = identifier + " " + quoteNumber + " " + columnName;
        linkToAdd = strToAdd.split(' ').join('');

        isEmpty = rowType == "checkbox" ? row.find($('input:checkbox:checked')).length == 0 : input.val().length == 0

        if (isEmpty) {
            // need to signify the row has errors by setting hasErrors / hasWarnings to true
            row.is("[hasErrors]") ? row.attr("hasErrors",true) : row.attr("hasWarnings",true);
            row.wrap(function() {
                return "<a name='" + linkToAdd + "'></a>";
            });
            $("#entryIssues").find("ul").append("<a href='#" + linkToAdd + "'><li>" + strToAdd + "</li></a>");
            if (rowType == "field") input.attr("placeholder",row.attr("message"));
            else row.find(".checkboxHeader").append("<br> <strong> You must select a content category! </strong>");
        } else if (linkExists != 0) {
            // need to set hasErrors or hasWarnings attributes to false
            row.is("[hasErrors]") ? row.attr("hasErrors",false) : row.attr("hasWarnings",false);
            $("#entryIssues").find("[href ='#" + linkToAdd + "']").remove();
            row.unwrap();
            // need to take away bold text by checkboxes, but don't need to remove bold and line break
            if (rowType == "checkbox")  {
                row.find("br").remove();
                row.find("strong").remove();
            }
        }
    }
}

function checkEntryIssuesDiv() {
    if ($("#entryIssues").find("ul").children().length == 0) $("#entryIssues").hide();
    else $("#entryIssues").show();
}

$("[message]").keyup(function() {
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
    postData($("#actualForm"),quote);


}

// https://stackoverflow.com/questions/18189948/jquery-button-click-function-is-not-working
$(".uploadButtonContainer").on('click', '.individualUploadButton', function() {

    // first check if there are any errors within source div, then call uploadSourceQuotePair on each pair
    sourceElm = $(this).closest(".allSources");
    sourceNotQuote = sourceElm.find(".sourceNotQuote");

    if (sourceElm.find("[hasErrors = 'true']").length != 0)
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
    $(".allSources").each(function () {
        sourceElm = $(this);
        sourceNotQuote = sourceElm.find(".sourceNotQuote");
        sourceElm.find(".individualQuote").each(function () {
            uploadSourceQuotePair(sourceNotQuote,$(this));
        });
    });
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
      $(individualQuote).attr("quoteNumber","quote" + (parseInt($(individualQuote).prev().attr("quoteNumber").substr(-1)) + 1));
      $(individualQuote).find("[message]").each(function () { // errors in unpaired quotes are not logged until quotes are paired.
          checkRequiredFieldEmpty($(this));
       });
      checkNumUnpaired();
  }
});

// these following two functions are only for the fatal errors when the user is required to reupload.
$('#fileUploadButton').on('click', function (c)  {
	$('#file').click();
});

$("#file").change(function(){
    $('#batchUploadForm').submit();
});

$(".deleteEntry").click(function() {
    if (confirm("Are you sure?")) {
        if($(this).hasClass("quoteDelete"))  {
            quoteElm = $(this).closest(".individualQuote");
            quoteElm.remove();
        } else {
            sourceElm = $(this).closest(".allSources");
            sourceElm.remove();
        }
    }
});
