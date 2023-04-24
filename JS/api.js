//Display images on website using the containerID element from HTML.
function displayImage(imageUrl, containerId) {
  let imgTag = `<img src="${imageUrl}" alt="">`;
  document.getElementById(containerId).innerHTML = imgTag;
}

//Create temporary URL for uploaded image to store it in temporary memory.
async function handleImageUpload(imageFile) {
  const originalImageUrl = URL.createObjectURL(imageFile);
  displayImage(originalImageUrl, "original-image");
}

//Connect to API using POST method.Send request to API for image enhancement inclusing form data.
async function enhanceImageWithRatio(imageFile, ratio, format) {
  const API_URL = "http://127.0.0.1:5000/enhance";
  const formData = new FormData();
  formData.append('image', imageFile);
  formData.append('ratio', ratio);
  formData.append('format', format);

  const response = await fetch(API_URL, {
    method: 'POST',
    body: formData
  });

//Show errors if it fails to connect or perform the enhancement
  if (!response.ok) {
    throw new Error(`An error occurred: ${response.statusText}`);
  }

//Get enhanced image back.
  const enhancedImageBlob = await response.blob();
  return enhancedImageBlob;
}

//Hide the elements
const refreshBtn = document.querySelector(".start-again");
const downloadBtn = document.querySelector(".download-enhance");
const enhanceBtn = document.querySelector(".enhance-btn");
refreshBtn.style.display = "none";
downloadBtn.style.display = "none";
enhanceBtn.style.display = "none";

//Show the elemets
const dragDrop = document.querySelector(".drop-zone"),
  dragText = document.querySelector(".icon p"),
  button = dragDrop.querySelector(".choose-btn button"),
  input = dragDrop.querySelector(".choose-btn input");

//Hide the elements
function hideElements() {
  document.querySelector(".icon p").style.display = "none";
  document.querySelector(".icon i").style.display = "none";
  document.querySelector(".icon span").style.display = "none";
  document.querySelector(".choose-btn button").style.display = "none";
  document.querySelector(".download-enhance").style.display = "inline-block";
  document.querySelector(".start-again").style.display = "inline-block";
  document.querySelector(".enhance-btn").style.display = "inline-block";
}

let file;

//Add validation for checking the file format. Only accept (JPG, PNG and BMP).
function isValidImageFile(file) {
  const acceptedImageTypes = ['image/jpeg', 'image/png', 'image/bmp'];
  return file && acceptedImageTypes.includes(file.type);
}

//Show errors if other type of file format is uploaded.
function displayError(message) {
  alert(message);
}

//Hide required element when uploading image.
button.onclick = () => {
  input.click();
  hideElements();
};

//Assign a varible to the uploaded file, call the required function to check the neccessry requirements.
input.addEventListener("change", function () {
  file = this.files[0];
  if (isValidImageFile(file)) {
    handleImageUpload(file);
    dragDrop.classList.add("active");
    hideElements();
  } else {
    displayError("Please upload a valid image file format. Accepted format (JPG, PNG and BMP)");
  }
});

//Customise drag and drop box to show message when user drags the image over drag and drop box.
dragDrop.addEventListener("dragover", (event) => {
  event.preventDefault();
  dragDrop.classList.add("active");
  dragText.textContent = "Release or Drop to upload file";
});

//Customise drag and drop box when users are not hovering the image over the drag and drop box.
dragDrop.addEventListener("dragleave", () => {
  dragDrop.classList.remove("active");
  dragText.textContent = "Drag & Drop to Upload File";
});

//Assign a varible when user drops the file in drag and drop box.
//Call the required function to check the neccessry requirements.
dragDrop.addEventListener("drop", (event) => {
  event.preventDefault();
  file = event.dataTransfer.files[0];
  if (isValidImageFile(file)) {
    handleImageUpload(file);
    dragDrop.classList.add("active");
    hideElements();
  } else {
    displayError("Invalid file format. Please upload a valid image file.");
  }
});

//Add referesh function to start-again button to reload the web page.
refreshBtn.addEventListener("click", () => {
  location.reload();
});

//Add download function to download button to download the enhanced image.
//Add validation to check if the image is enhanced first when clicking download button. If not show error message.
downloadBtn.addEventListener("click", () => {
  let enhancedImageElement = document.getElementById("enhanced-image").querySelector("img");
  if (enhancedImageElement) {
    let imageUrl = enhancedImageElement.src;
    let formatSelect = document.getElementById("format");
    let selectedFormat = formatSelect.value;//When clicking to download image, add selected file format with it.
    let fileExtension = selectedFormat.toLowerCase();

    //Allow to save iN JPEG and JPG format
    if (selectedFormat === "JPEG") {
      fileExtension = "jpeg";
    } else if (selectedFormat === "JPG") {
      fileExtension = "jpg";
    }

    let link = document.createElement("a");
    link.href = imageUrl;//set image URL destination
    link.download = "enhanced-image." + fileExtension; //Give a name to the enhanced image file when saving it.
    document.body.appendChild(link);//Add the link to download.
    link.click();// Trigger the link when clicking on download button.
    document.body.removeChild(link);//Remove the link untill image is downloaded.
  } else {
    alert("No enhanced image found.");
  }
});


//Show error if the image is not uploaded for enhancement
enhanceBtn.addEventListener("click", async () => {
  if (!file) {
    alert("Please upload an image first.");
    return;
  }

//Include selected features when sending the image to API for enhancement.
  const ratioSelect = document.getElementById("ratio");
  const selectedRatio = ratioSelect.value;// Include selected ratio when sending the image to API for enhancement
  const formatSelect = document.getElementById("format");
  const selectedFormat = formatSelect.value;//Include selected file format when sending the image to API for enhancement
  const enhancedImageBlob = await enhanceImageWithRatio(file, selectedRatio, selectedFormat);//Recieve the enhanced image with selected ratios and file format
  const enhancedImageUrl = URL.createObjectURL(enhancedImageBlob);
  displayImage(enhancedImageUrl, "enhanced-image");// Display the enhanced image.
});
