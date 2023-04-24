# Image Enhancement Website - Final Year Project

Welcome to my Final Year Project! This Image Enhancement website that allows users to upscale and enhance images using various techniques such as Bilinear Interpolation,
Gaussian Blur, Median Blur, Unsharp Masking, and CLAHE. The features include upload, enhance, and download buttons with ratio and file format selection.



## Installation

1. Download the code from Github by clicking on the green button on the right that says "Code". Under the local section, you will find "Download ZIP". 
   Click on it, save it anywhere you like, and then uncompress the file.

2. Download and install XAMPP from https://www.apachefriends.org/index.html.

3. Copy the downloaded code files (without the SQL file) into the `htdocs` folder inside the XAMPP directory. 

4. Create a new folder inside `htdocs` and give it a name of your choice. For example: `C:\xampp\htdocs\image-enhancement`.

5. Run XAMPP and start the Apache and MySQL servers.

6. Open your web browser and go to http://localhost/phpmyadmin. Create a new database by clicking on the "New" tab on the left side of the screen. You can name it whatever you like.

7. Once the database is created, click on the "Import" tab at the top. Click on "Choose File" and locate the SQL file from the downloaded code. 
   Import it and then click on the "Go" button at the bottom.

8. Open the `connection.php` file from the downloaded code and update the database name in the "connecting database" section to match the name you chose for the new database.

9. In your web browser, navigate to http://localhost/your-folder-name/index.php, replacing "your-folder-name" with the name of the folder you created in the `htdocs` directory.



## Setting up the API server

1. Install Python from https://www.python.org/downloads/ if you haven't already.

2. Open the command prompt (CMD) and install Flask and Flask-CORS using the following commands:

3. pip install flask

4. pip install flask-cors

5. Navigate to the API folder you downloaded from Github and run the `app.py` file by double-clicking on it. This will start the Flask server.



## Usage

1. Register and log in to the website.

2. Use the image enhancement features to upload, enhance, and download images with your preferred ratio and format.

Enjoy enhancing your images with this Image Enhancement website!
