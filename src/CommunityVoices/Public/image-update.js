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
  console.log(cropper);
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
