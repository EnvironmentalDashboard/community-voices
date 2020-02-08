var s = document.createElement("script"); s.async = 1;
s.src = "https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.1/cropper.min.js";
(document.head||document.documentElement).appendChild(s);
var l = document.createElement("link");
l.href = "https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.1/cropper.min.css"; l.rel = 'stylesheet';
(document.head||document.documentElement).appendChild(l);

const image = document.getElementById("cropper-img");
const crop_x = document.getElementById("crop_x");
const crop_y = document.getElementById("crop_y");
const crop_height = document.getElementById("crop_height");
const crop_width = document.getElementById("crop_width");
const checkbox = document.getElementById("crop-checkbox");
var cropper;
function enable_cropper() {
  if (checkbox.checked) {
    cropper = new Cropper(image, {checkCrossOrigin: false, viewMode: 1, crop(event) {
      crop_x.value = event.detail.x;
      crop_y.value = event.detail.y;
      crop_width.value = event.detail.width;
      crop_height.value = event.detail.height;
    }});
  } else if (cropper !== undefined) {
    cropper.destroy();
    crop_x.value = 0;
    crop_y.value = 0;
    crop_width.value = 0;
    crop_height.value = 0;
  }
}
enable_cropper();

const base = '/community-voices/uploads/';
var rect = {crop_x: 0, crop_y: 0, crop_height: 0, crop_width:0};
function load_uncropped(e) {
  var text = e.firstChild.nodeValue,
      id = e.getAttribute('data-id');
  if (text === 'Load uncropped') {
    e.firstChild.nodeValue = 'Load current crop';
    rect.crop_x = crop_x.value;
    rect.crop_y = crop_y.value;
    rect.crop_height = crop_height.value;
    rect.crop_width = crop_width.value;
    crop_x.value = 0;
    crop_y.value = 0;
    crop_height.value = 0;
    crop_width.value = 0;
    image.setAttribute('src', '');
    // it seems that the only way to break the cache is to put a delay
    setTimeout(function() {image.setAttribute('src', base + id + '?nocrop=1');}, 100);
  } else {
    e.firstChild.nodeValue = 'Load uncropped';
    crop_x.value = rect.crop_x;
    crop_y.value = rect.crop_y;
    crop_height.value = rect.crop_height;
    crop_width.value = rect.crop_width;
    image.setAttribute('src', '');
    setTimeout(function() {image.setAttribute('src', base + id);}, 100);
  }
}

// This file should contain something that sorts selected tags when they
// are selected (most likely).
