function clickEdited() {
  var checkbox = document.getElementById('editedCheckbox');
  var originalTextDiv = document.getElementById('originalTextDiv');
  var originalText = document.getElementById('originalText');
  var quote = document.getElementById('text');

  if (checkbox.checked) {
    originalTextDiv.style.display = 'block';
    originalText.value = quote.value;
  } else {
    originalTextDiv.style.display = 'none';
    originalText.value = '';
  }
}

function displayCount(box,count) {
  var countDisplay = document.getElementById(count);
  if(box.hasAttribute("suggestedlength") && (box.value.length > box.getAttribute("suggestedlength"))) {
    countDisplay.innerHTML = box.value.length + " / " + box.getAttribute("maxlength") + " characters -- Please shorten!";
    countDisplay.style.color = "red";
  } else {
    countDisplay.innerHTML = box.value.length + " / " + box.getAttribute("maxlength") + " characters";
    countDisplay.style.color = "black";
  }
}
