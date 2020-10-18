toRemove = []; // after we upload form content, we want to delete all qutotes/sources at the same time

const fieldsToChange=[["originalquote","originalText"],["editedquotes","text"],["intervieworfileaccessdate","dateRecorded"],["interviewerresearcher",'interviewer'],["subattribution","subAttribution"],["quotationmarks","quotationMarks"]]; // add to later
// ------- VARIOUS USER DEFINED FUNCTIONS CALLED ON JQUERY EVENTS

function updateFieldName(field,newname) { // change names of fields so that they match expected field names when the quote is uploaded e.g. original quote should become original text
    field.attr("name",newname);
}

function createDeletePromise(quote) { // after we upload a quote, we should add it to a list to delete (delete happens all at once).
    toRemove.push(quote);
}

function handleDeletePromises() { // delete all quotes after sending request
    toRemove.forEach(function(element) {
        element.remove();
    });
}

function postData(form) {
    data = form.serializeArray();
    $.ajax({
      url : $("#actualForm").attr('action'),
      type: $("#actualForm").attr('method'),
      data: data,
      success: function (data) {
          // save for later
      },
      error: function (data) {
          // save for later
      }
  });
  form.submit();
  form.empty();
}

function manipulateIndividualUploadButton(source) { // only want user to be able to upload source if it has associated quotes
   uploadButtonContainer = source.find(".uploadButtonContainer");
   individualUploadButton = "<div class='row'><div class='col text-center'><input type='button' form='batchUploadForm' class='btn btn-primary individualUploadButton' value='Upload Quotes with this Source' id='fileUploadButton'></input></div></div>"
   if (source.find(".pairedQuotes").children().length && source.find(".individualUploadButton").length == 0) { // add individual upload button if source now had > 0 associated quotes.
       uploadButtonContainer.append(individualUploadButton);
   } else if (source.find(".pairedQuotes").children().length == 0) { // remove individual upload button if source now has 0 associated quotes
       uploadButtonContainer.empty();
   }
}

function fillCheckBoxes (listSelected, allBoxes) { // takes content category fields and turns them into checkboxes, filling ones that are valid and user inputted
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

function checkFieldEmpty(row) { // checks if a required/suggested field (edited quotes,content categories, attributions) is empty or not
   if (! row.closest("#unpairedQuotes").length) { // issues will only be checked for paired quotes, we will ignore issues with unpaired quotes.
       rowType = row.find(".checkboxHeader").length != 0 ? "checkbox" : "field";
       linkExists = row.parent("a").length; // have we already added a link to this? Need to check
       input = row.find($('input'));

       identifier = row.closest("[hasIdentifier = true]").attr("id");
       quoteNumber = row.closest("[quoteNumber]").length ? "quote " + row.closest("[quoteNumber]").attr("quoteNumber") : "";
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
           // need to take away bold text by checkboxes and line break
           if (rowType == "checkbox")  {
               row.find("br").remove();
               row.find("strong").remove();
           }
       }
   }
}

function checkEntryIssuesEmpty() {
   if ($("#entryIssues").find("ul").children().length == 0) $("#entryIssues").hide();
   else $("#entryIssues").show();
}

function uploadSourceQuotePair(source,quote) {
    sourceId = source.closest('.individualSource').attr('id');
    quoteNumber = quote.attr('quotenumber');

    wrapper = $("<div id='" + sourceId + quoteNumber + "' class='wrapper'></div>");
    wrapper.append(source.clone());
    wrapper.append(quote.clone());
    wrapper.append("<input name='quotationMarks' value='on'></input>");
    // need to have this field to prevent PDO error -- issue to fix later

    wrapper.find('[name="contentCategories[]"]').each(function () {
       $(this).attr("name", "contentCategories" + '[' + sourceId + quoteNumber + ']' + '[]');
    });
    wrapper.find('[name="tags[]"]').each(function () {
      $(this).attr("name", "tags" + "[" + sourceId + quoteNumber + "]" + "[]");
    });
     wrapper.find("input").not('[name^="contentCategories"],[name^="tags"]').each(function () {
        $(this).attr("name", $(this).attr("name") + '[' + sourceId + quoteNumber + ']');
    });
    $("#actualForm").append(wrapper);
    createDeletePromise(quote);
    // it is possible that a quote with warnings was updated, so we need to remove all possible warnings from box:
    quoteLink = quote.find("a[name]").attr("name");
    $("#entryIssues").find('[href ="#' + quoteLink + '"]').remove();
    checkEntryIssuesEmpty();
}

function uploadSource(source) {
    sourceNotQuote = source.find(".sourceNotQuote");
    source.find(".individualQuote").each(function () {
        uploadSourceQuotePair(sourceNotQuote,$(this));
    });
}

function checkNumUnpaired() {
    numQuotesUnpaired = $("#unpairedQuotes").find(".individualQuote").length;
    if(numQuotesUnpaired == 0 && $("#unpairedQuotes").length) {
        $("#unpairedQuotes").remove();
        $("#allowToggling").remove();
    }
}

function setQuoteNumber(quote) { // sets quote number after pairing
    previousQuoteNumber = quote.attr("quoteNumber");
    quoteNumber =  quote.prev().length ? parseInt(quote.prev().attr("quoteNumber")) + 1 : 1;
    quote.attr("quoteNumber",quoteNumber);
}

function decrementSuceedingQuoteNumbers(source,deletedQuoteNumber) { // only want to decrement quotes that come after deleted quote, not before
    source.find(".individualQuote").each(function() {
        quoteNumber = parseInt($(this).attr("quoteNumber"));
        if(quoteNumber > deletedQuoteNumber) {
            $(this).attr("quotenumber",quoteNumber - 1); // decrement the quote number

            var regexQuotes = "^(" + source.attr("id") + ")(quote)([1-9]{1,})(.*)";
            regexx = new RegExp(regexQuotes);

            $(this).find("a[name]").each(function () {
                anchor = $(this).attr("name");
                associatedLink = $("#entryIssues").find("a[href=" + "'#" + anchor + "']")
                matched = $(this).attr("name").match(regexx);
                newNumDecremented = parseInt(matched[3]) - 1;

                $(this).attr("name",matched[1] + matched[2] + newNumDecremented + matched[4]); // change anchor
                $(associatedLink).attr("href","#" + matched[1] + matched[2] + newNumDecremented + matched[4]); // change link to anchor
                $(associatedLink).find("li").text(matched[1] + " " + matched[2] + " " + newNumDecremented + " " + matched[4]);
            });
        }
    });
}


// VARIOUS JQUERY EVENTS THAT TRIGGER USER DEFINED FUNCTIONS

$(document).ready(function() {
    $(".individualQuote").each(function () {
        listSelected = [$(this).find("[formattedName = contentcategories]"),$(this).find("[formattedName = tags]")];
        allBoxes = [$(this).find(".contentCategoryCheckboxList"), $(this).find(".tagCheckboxList")];
        fillCheckBoxes(listSelected[0],allBoxes[0]);
        fillCheckBoxes(listSelected[1],allBoxes[1]);
    });
    $("[message]").each(function() {
        checkFieldEmpty($(this));
    });
    checkEntryIssuesEmpty();
    $(".individualSource").each(function () {
        manipulateIndividualUploadButton($(this));
    });

    const fieldsToBeReplaced = fieldsToChange.map(function(x) {
        return x[0];
    });
    const fieldsToReplace = fieldsToChange.map(function(x) {
        return x[1];
    });

    $("input[type='text']").each(function () {
        let inputName = $(this).attr("name");
        if(fieldsToBeReplaced.includes(inputName)) {
            $(this).attr("name",fieldsToReplace[fieldsToBeReplaced.indexOf($(this).attr("name"))]);
        }
    });
});

$("[message]").keyup(function() {
    checkFieldEmpty($(this).closest(".row"));
    checkEntryIssuesEmpty();
});

$("input:checkbox").change(function() {
    checkFieldEmpty($(this).closest(".row"));
    checkEntryIssuesEmpty();
});

// https://stackoverflow.com/questions/18189948/jquery-button-click-function-is-not-working
$(".uploadButtonContainer").on('click', '.individualUploadButton', function() {
    sourceElm = $(this).closest(".individualSource");
    if (sourceElm.find(".sourceNotQuote").find("[hasErrors = 'true']").length != 0) // only upload source if no errors
        alert("Cannot upload quotes with this source. Please check this source's errors.");
    else {
        if (sourceElm.find("[hasErrors = 'true']").length != 0)  // certain quotes have issues preventing upload
            alert("Some of your quotes have issues preventing their upload. All quotes remaining with this source have errors.");
        else {
            uploadSource(sourceElm);
            handleDeletePromises();
            postData($("#actualForm"));
            sourceElm.remove();
        }
    }
});

$("#submitAll").click(function() {
    if ($("[hasErrors = 'true']").length != 0)
        alert("All remaining quotes/sources on page have errors and cannot be uploaded");
    $(".individualSource").each(function () {
        if ($(this).find("[hasErrors = 'true']").length == 0) // only upload source if no errors here
            uploadSource($(this));
    });
    handleDeletePromises();
    postData($("#actualForm"));
    $(".individualSource").each(function () {
        if ( $(this).find("[hasErrors = 'true']").length == 0)
            $(this).remove();
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

$(".pairWithIdentifier").click(function() { // pair unpaired Quote with an identifier
  individualQuote = $(this).closest(".individualQuote");
  identifierToAppend = individualQuote.find(".validIdentifiers"); // selects the identifier chosen by the user when they click "pair"
  if (identifierToAppend.val()) {
      sourceToPair = $('#'.concat(identifierToAppend.val())); // select source to pair with based on selected id.
      sourceToPairAssociatedQuotes = sourceToPair.find(".pairedQuotes");

      individualQuote.find(".identifiersFormElm").remove(); // need to remove pairing field and pair button after pairing
      $(this).remove();

      $(sourceToPairAssociatedQuotes).append(individualQuote);
      setQuoteNumber(individualQuote);

      individualQuote.find("[message]").each(function() {
          checkFieldEmpty($(this)); // unpaired quotes don't show issues until we pair them, must check for issues then.
      });
      checkNumUnpaired();
      checkEntryIssuesEmpty();

      manipulateIndividualUploadButton(sourceToPair);
  }
});

$(".deleteEntry").click(function() {
    if (confirm("Are you sure?")) {
        if($(this).hasClass("quoteDelete"))  { // determine whether we are deleting a quote or a source
            quoteElm = $(this).closest(".individualQuote");
            associatedSource = quoteElm.closest(".individualSource");
            otherQuotesInSource = associatedSource.find(".pairedQuotes");
            deletedQuoteNumber = parseInt(quoteElm.attr("quotenumber"));

            quoteElm.remove();
            entryIssuesSearchString =  "a[href^='#" + associatedSource.attr("id") + "quote" + quoteElm.attr("quoteNumber") + "']";
            $("#entryIssues").find(entryIssuesSearchString).remove();

            decrementSuceedingQuoteNumbers(associatedSource,deletedQuoteNumber);
            manipulateIndividualUploadButton(associatedSource);
            checkNumUnpaired();
            checkEntryIssuesEmpty();

        } else {
            sourceElm = $(this).closest(".individualSource");
            sourceId = sourceElm.attr('id');
            $("option." + sourceId).remove(); // remove option to pair unpaired quotes with this source
            sourceElm.remove();

            entryIssuesSearchString =  "a[href^='#" + sourceElm.attr("id") + "']";
            $("#entryIssues").find(entryIssuesSearchString).remove();
            checkEntryIssuesEmpty();
        }
    }
});

// these following two functions are only for the fatal errors when the user is required to reupload (the reupload button won't normally appear on page)
$('#fileUploadButton').on('click', function (c)  {
	$('#file').click();
});

$("#file").change(function(){
    $('#batchUploadForm').submit();
});
