import os
import cv2
import numpy as np
from flask import Flask, request, send_file
from flask_cors import CORS
from PIL import Image
import io

def enhance_color(img, brightness=1.0, contrast=1.0, saturation=1.0, clip_limit=2.0, tile_grid_size=(8, 8)):
    # Apply CLAHE
    lab = cv2.cvtColor(img, cv2.COLOR_BGR2LAB)
    l, a, b = cv2.split(lab)
    clahe = cv2.createCLAHE(clipLimit=clip_limit, tileGridSize=tile_grid_size)
    cl = clahe.apply(l)
    lab_clahe = cv2.merge((cl, a, b))
    img_clahe = cv2.cvtColor(lab_clahe, cv2.COLOR_LAB2BGR)

    # Apply brightness, contrast, and saturation adjustments
    hsv = cv2.cvtColor(img_clahe, cv2.COLOR_BGR2HSV)
    h, s, v = cv2.split(hsv)
    v = np.clip(v * brightness, 0, 255).astype(hsv.dtype)
    s = np.clip(s * saturation, 0, 255).astype(hsv.dtype)
    v = np.clip(v * contrast, 0, 255).astype(hsv.dtype)
    hsv = cv2.merge([h, s, v])
    return cv2.cvtColor(hsv, cv2.COLOR_HSV2BGR)

def bilinear_interpolation(img, ratio=2):
    # Split the input image into separate color and alpha channels (if applicable)
    channels = cv2.split(img)
    if len(channels) == 4:
        b, g, r, a = channels
        color = cv2.merge([b, g, r])
    else:
        color = img

    # Get the new dimensions
    rows, cols = color.shape[:2]
    #new_rows = int(np.round(rows * (2 ** ratio)))
    #new_cols = int(np.round(cols * (2 ** ratio)))

    new_rows = int(np.round(rows * ratio))
    new_cols = int(np.round(cols * ratio))

    # Get the scaling factor
    x_scale = 1 / ratio
    y_scale = 1 / ratio

    # Create the new image with the new dimensions
    result = np.zeros((new_rows, new_cols, color.shape[2]), dtype=np.float64)
    
    # Create meshgrid for coordinates
    X, Y = np.meshgrid(np.arange(new_cols), np.arange(new_rows))
    x = (X * x_scale).astype(int)
    y = (Y * y_scale).astype(int)
    x1, y1 = x, y
    x2, y2 = np.clip(x1 + 1, 0, cols - 1), np.clip(y1 + 1, 0, rows - 1)
    
    # Calculate the values using Bilinear Interpolation
    q11 = color[y1, x1]
    q12 = color[y2, x1]
    q21 = color[y1, x2]
    q22 = color[y2, x2]
    result = (1 - (y - y1)[:, :, np.newaxis]) * ((1 - (x - x1)[:, :, np.newaxis]) * q11 + (x - x1)[:, :, np.newaxis] * q21) + (y - y1)[:, :, np.newaxis] * ((1 - (x - x1)[:, :, np.newaxis]) * q12 + (x - x1)[:, :, np.newaxis] * q22)

    # Upsample the image - (test the space image).
    upsampled_img = cv2.resize(img, (new_cols, new_rows), interpolation=cv2.INTER_LANCZOS4)
    
    # Adjust Gaussian blur parameters and alpha, beta values based on the ratio
    if ratio == 1:
        # Apply Gaussian blur to the upsampled image
        blurred_img = cv2.GaussianBlur(upsampled_img, (3, 3), 0)
        
        # Adjust the alpha and beta values for sharpening
        alpha = 1.5
        beta = -0.5
     
    elif ratio == 2: 
        blurred_img = cv2.GaussianBlur(upsampled_img, (5, 5), 0)
        
        # Adjust the alpha and beta values for sharpening
        alpha = 6.0
        beta = -5.0
        
    elif ratio == 3:
        blurred_img = cv2.GaussianBlur(upsampled_img, (5, 5), 0)
        
        # Adjust the alpha and beta values for sharpening
        alpha = 15.0
        beta = -14.0
        
    elif ratio == 4:
        blurred_img = cv2.GaussianBlur(upsampled_img, (5, 5), 0)
        
        # Adjust the alpha and beta values for sharpening
        alpha = 17.0
        beta = -16.0

    # Apply unsharp masking to the upsampled image
    sharpened_img = cv2.addWeighted(upsampled_img, alpha, blurred_img, beta, 0)

    # Apply median blur to reduce noise
    if ratio == 2:
        sharpened_img = cv2.medianBlur(sharpened_img, 5)

    elif ratio == 3 or ratio == 4:
        sharpened_img = cv2.medianBlur(sharpened_img, 7)

    # Create a new alpha channel with a value of 255 (fully # opaque)
    alpha = np.full((sharpened_img.shape[0], sharpened_img.shape[1]), 255, dtype=np.float64)

    # Merge the color and alpha channels back together (if applicable)
    if len(channels) == 4:
        alpha = a.copy()
        alpha = cv2.resize(alpha, (new_cols, new_rows), cv2.INTER_LANCZOS4)
        alpha = np.expand_dims(alpha, axis=-1)
        result = cv2.merge([sharpened_img, alpha])
    else:
        result = cv2.merge([sharpened_img])

    return result

# Create the Flask app instance and enable CORS
app = Flask(__name__)
CORS(app)


app.config['MAX_CONTENT_LENGTH'] = 100 * 1024 * 1024  # Set the maximum file size to 100 MB

@app.route('/enhance', methods=['POST'])

def enhance_image():
    ratio = int(request.form.get('ratio', 3))
    file_format = request.form.get('format', 'JPEG').upper()

    # Read the image from the request
    image = Image.open(request.files['image'])
    img = cv2.cvtColor(np.array(image), cv2.COLOR_RGB2BGR)
    print("Reading image...")


    # Apply the bilinear interpolation and enhance color operations
    result = bilinear_interpolation(img, ratio)
    print("Applying bilinear interpolation")

    result = enhance_color(result, brightness=1.05, contrast=1.05, saturation=1.00, clip_limit=0.1, tile_grid_size=(1, 1))
    print("Enhancing color...")
    
    # Save the enhanced image to disk in the selected format and bit-depth
    if file_format in ["JPEG", "JPG"]:
        result_8bit = np.clip(result, 0, 255).astype(np.uint8)
        result_pil = Image.fromarray(result_8bit)
        print("Success JPEG")
        
    elif file_format == "PNG":
        result_16bit = np.clip(result * 256, 0, 65535).astype(np.uint16)
        result_pil = Image.fromarray(result_16bit, mode='I;16')
        print("Success PNG")

    elif file_format == "BMP":
        result_8bit = np.clip(result, 0, 255).astype(np.uint8)
        result_pil = Image.fromarray(result_8bit)
        print("Success BMP")
    

    # Save the enhanced image in memory
    img_io = io.BytesIO()
    result_pil = Image.fromarray(cv2.cvtColor(result, cv2.COLOR_BGR2RGB))
    result_pil.save(img_io, format=file_format, quality=100)
    img_io.seek(0)
    print("Saving enhanced image...")


    # Send the enhanced image as a response
    print("Sending enhanced image as response...")    
    return send_file(img_io, mimetype='image/' + file_format.lower())


@app.route('/')
def home():
    return "Welcome to the Image Enhancer API!"

if __name__ == '__main__':
    app.run(debug=True)    