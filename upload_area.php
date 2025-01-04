<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #aaaaaa;
        }

        div {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .field_data label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .field_data textarea {
            width: 100%;
            border-radius: 5px;
            resize: none;
        }

        input[type="file"] {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }

        button.btn {
            background-color: #7851a9;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.btn:hover {
            background-color: #7851a9;
        }

       
        #image_preview {
            max-width: 100%;
            margin-top: 15px;
            display: none;
        }
    </style>
</head>
<body>
    <div>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="field_data">
                <label for="content">Description:</label>
                <textarea name="content" id="content" rows="5" required></textarea>
            </div>
            <input type="file" name="file" id="file_path" required onchange="previewImage(event)">
            
            <img id="image_preview" src="" alt="Image Preview">
            <button type="submit" class="btn" name="submit">Upload</button>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function() {
                const output = document.getElementById('image_preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            
            if (file) {
                reader.readAsDataURL(file); 
            }
        }
    </script>
</body>
</html>
