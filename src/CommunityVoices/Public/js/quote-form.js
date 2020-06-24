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
   const countDisplay = document.getElementById(count);
   countDisplay.innerHTML = box.value.length + " / " + box.getAttribute("maxlength") + " characters";
   if(box.hasAttribute("suggestedlength") && (box.value.length > box.getAttribute("suggestedlength"))) {
     countDisplay.insertAdjacentHTML('beforeend', '--Please Shorten!');
     countDisplay.style.color = "red";
   } else {
     countDisplay.style.color = "black";
   }
}
