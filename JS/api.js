// Display image on page
function displayImage(imageUrl, containerId) {
  const imgTag = `<img src="${imageUrl}" alt="">`;
  document.getElementById(containerId).innerHTML = imgTag;
}

function updateProgressText(text) {
  const progressWrapper = document.getElementById("progress-wrapper");
  const progressBar = document.getElementById("progress-bar");
  const progressText = document.getElementById("progress-text");

  progressWrapper.style.display = "block";

  if (text === "DONE") {
    progressBar.style.width = "100%";
    progressText.innerText = "Enhancement complete!";
    setTimeout(() => {
      progressWrapper.style.display = "none";
    }, 1500);
    return;
  }

  // If it's numeric, treat it as %
  if (!isNaN(text)) {
    progressBar.style.width = `${text}%`;
    progressText.innerText = `Processing... ${text}%`;
  } else {
    // fallback
    progressBar.style.width = "60%";
    progressText.innerText = text;
  }
}

// Select buttons
const refreshBtn = document.querySelector(".start-again");
const downloadBtn = document.querySelector(".download-enhance");
const enhanceBtn = document.querySelector(".enhance-btn");

refreshBtn.style.display = "none";
downloadBtn.style.display = "none";
enhanceBtn.style.display = "none";

// Drag & Drop
const dragDrop = document.querySelector(".drop-zone"),
  dragText = document.querySelector(".icon p"),
  button = dragDrop.querySelector(".choose-btn button"),
  input = dragDrop.querySelector(".choose-btn input");

// Enable UI on file selection
function showEnhanceUI() {
  document.querySelector(".icon p").style.display = "none";
  document.querySelector(".icon i").style.display = "none";
  document.querySelector(".icon span").style.display = "none";
  document.querySelector(".choose-btn button").style.display = "none";
  downloadBtn.style.display = "inline-block";
  refreshBtn.style.display = "inline-block";
  enhanceBtn.style.display = "inline-block";
}

// File handling
let file;
function isValidImageFile(file) {
  const acceptedTypes = ["image/jpeg", "image/png", "image/bmp"];
  return file && acceptedTypes.includes(file.type);
}

function displayError(message) {
  alert(message);
}

button.onclick = () => {
  input.click();
  showEnhanceUI();
};

input.addEventListener("change", function () {
  file = this.files[0];
  if (isValidImageFile(file)) {
    const originalImageUrl = URL.createObjectURL(file);
    displayImage(originalImageUrl, "original-image");
    dragDrop.classList.add("active");
    showEnhanceUI();
  } else {
    displayError("Please upload a valid image (JPG, PNG, BMP).");
  }
});

dragDrop.addEventListener("dragover", (e) => {
  e.preventDefault();
  dragDrop.classList.add("active");
  dragText.textContent = "Release or Drop to upload file";
});

dragDrop.addEventListener("dragleave", () => {
  dragDrop.classList.remove("active");
  dragText.textContent = "Drag & Drop to Upload File";
});

dragDrop.addEventListener("drop", (e) => {
  e.preventDefault();
  file = e.dataTransfer.files[0];
  if (isValidImageFile(file)) {
    const originalImageUrl = URL.createObjectURL(file);
    displayImage(originalImageUrl, "original-image");
    dragDrop.classList.add("active");
    showEnhanceUI();
  } else {
    displayError("Invalid file format. Please use JPG, PNG, or BMP.");
  }
});

// Reset page
refreshBtn.addEventListener("click", () => location.reload());

// Download enhanced image
downloadBtn.addEventListener("click", () => {
  const enhancedImg = document.getElementById("enhanced-image").querySelector("img");
  if (!enhancedImg) return alert("No enhanced image found.");

  const imageUrl = enhancedImg.src;
  const format = document.getElementById("format").value.toLowerCase();
  const link = document.createElement("a");
  link.href = imageUrl;
  link.download = `enhanced-image.${format === "jpg" ? "jpg" : format}`;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
});

// 🔥 Main enhance logic
async function enhanceImage(imageFile, format) {
  const API_URL = "http://127.0.0.1:5000/enhance";
  const formData = new FormData();
  formData.append("image", imageFile);
  formData.append("format", format);

  const faceEnhance = document.getElementById("face")?.checked || false;
  const clarityMode = document.getElementById("clarity")?.checked || false;
  formData.append("face_enhance", faceEnhance);
  formData.append("clarity_mode", clarityMode);

  const response = await fetch(API_URL, {
    method: "POST",
    body: formData
  });

  const reader = response.body.getReader();
  const decoder = new TextDecoder("utf-8");
  let output = "";
  let filename = null;

  while (true) {
    const { value, done } = await reader.read();
    if (done) break;
    output += decoder.decode(value, { stream: true });

    const lines = output.split("\n\n");
    for (let i = 0; i < lines.length - 1; i++) {
      const line = lines[i].trim();
      if (line.startsWith("data: ")) {
        const msg = line.replace("data: ", "").trim();

        // Detect DONE and extract filename
        if (msg.startsWith("DONE|")) {
          filename = msg.split("|")[1];
          updateProgressText("DONE");
        } else {
          updateProgressText(msg);
        }
      }
    }
    output = lines[lines.length - 1];
  }

  return filename;
}

// Fetch final enhanced image
async function fetchFinalImage(filename) {
  const response = await fetch("http://127.0.0.1:5000/get-enhanced", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ filename })
  });

  if (!response.ok) {
    throw new Error("Could not retrieve the enhanced image.");
  }

  const blob = await response.blob();
  return URL.createObjectURL(blob);
}

// Enhance Button click
enhanceBtn.addEventListener("click", async () => {
  if (!file) return alert("Please upload an image first.");

  const format = document.getElementById("format").value;
  try {
    const filename = await enhanceImage(file, format);

    if (!filename) throw new Error("Enhancement failed — no filename received.");

    const finalImageUrl = await fetchFinalImage(filename);
    displayImage(finalImageUrl, "enhanced-image");
  } catch (err) {
    updateProgressText("Enhancement failed.");
    alert("Enhancement failed: " + err.message);
    console.error("[Enhance Error]", err);
  }
});
