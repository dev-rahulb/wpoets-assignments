<?php
include 'db.php';

function isImage($tmpName) {
    $validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $mime = mime_content_type($tmpName);
    return in_array($mime, $validTypes);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tabTitle = $_POST['tab_title'];
    $logoPath = 'images/' . basename($_FILES['tab_logo']['name']);

    if (!isImage($_FILES['tab_logo']['tmp_name'])) {
        die('Invalid file type for tab logo. Only images are allowed.');
    }

    move_uploaded_file($_FILES['tab_logo']['tmp_name'], $logoPath);

    $conn->query("INSERT INTO tabs (title, logo) VALUES ('$tabTitle', '$logoPath')");
    $tabId = $conn->insert_id;

    foreach ($_POST['slides'] as $i => $slide) {
        $tag = $conn->real_escape_string($slide['tag']);
        $desc = $conn->real_escape_string($slide['description']);

        $imageName = $_FILES['slides']['name'][$i]['image'];
        $tmpName = $_FILES['slides']['tmp_name'][$i]['image'];
        $imgPath = 'images/' . basename($imageName);

        if (!isImage($tmpName)) {
            die('Invalid file type for slide image. Only images are allowed.');
        }

        move_uploaded_file($tmpName, $imgPath);

        $conn->query("INSERT INTO slides (tab_id, tag, description, image)
                      VALUES ($tabId, '$tag', '$desc', '$imgPath')");
    }

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Tab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 30px;
        }

        h2 {
            margin-bottom: 20px;
            color: #0d2f4f;
        }

        form {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: auto;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .slide-block {
            padding: 15px;
            margin-top: 20px;
            background: #eef5fb;
            border: 1px solid #cddbea;
            border-radius: 8px;
        }

        button {
            background-color: #0d2f4f;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #145589;
        }

        .add-slide-btn {
            background: #55b8d8;
            margin-top: 10px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Add New Tab & Slides</h2>

<form method="POST" enctype="multipart/form-data">
    <label for="tab_title">Tab Title:</label>
    <input type="text" name="tab_title" id="tab_title" required>

    <label for="tab_logo">Tab Logo:</label>
    <input type="file" name="tab_logo" id="tab_logo" accept="image/*" required>

    <div id="slides">
        <div class="slide-block">
            <label>Slide Tag:</label>
            <input type="text" name="slides[0][tag]">

            <label>Description:</label>
            <input type="text" name="slides[0][description]">

            <label>Image:</label>
            <input type="file" name="slides[0][image]" accept="image/*">
        </div>
    </div>

    <button type="button" class="add-slide-btn" onclick="addSlide()">+ Add Slide</button>
    <br>
    <button type="submit">Save Tab</button>
</form>

<a href="index.php" class="back-link">← Back to Home</a>

<script>
let slideIndex = 1;
function addSlide() {
    const slides = document.getElementById('slides');
    const block = document.createElement('div');
    block.className = 'slide-block';
    block.innerHTML = `
        <label>Slide Tag:</label>
        <input type="text" name="slides[\${slideIndex}][tag]">

        <label>Description:</label>
        <input type="text" name="slides[\${slideIndex}][description]">

        <label>Image:</label>
        <input type="file" name="slides[\${slideIndex}][image]" accept="image/*">
    `;
    slides.appendChild(block);
    slideIndex++;
}
</script>

</body>
</html>