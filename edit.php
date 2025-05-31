<?php
include 'db.php';

function isImage($tmpName) {
    $validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $mime = mime_content_type($tmpName);
    return in_array($mime, $validTypes);
}

$id = (int) $_GET['id'];
$tabResult = $conn->query("SELECT * FROM tabs WHERE id = $id");
$tab = $tabResult->fetch_assoc();

$slidesResult = $conn->query("SELECT * FROM slides WHERE tab_id = $id");
$slides = [];
while ($row = $slidesResult->fetch_assoc()) {
    $slides[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['tab_title']);

    // Handle logo update if uploaded
    if (!empty($_FILES['tab_logo']['name'])) {
        if (!isImage($_FILES['tab_logo']['tmp_name'])) {
            die('Invalid file type for tab logo. Only images are allowed.');
        }
        $logoPath = 'images/' . basename($_FILES['tab_logo']['name']);
        move_uploaded_file($_FILES['tab_logo']['tmp_name'], $logoPath);
        $conn->query("UPDATE tabs SET title='$title', logo='$logoPath' WHERE id=$id");
    } else {
        $conn->query("UPDATE tabs SET title='$title' WHERE id=$id");
    }

    // Update each slide
    foreach ($_POST['slides'] as $i => $slide) {
        $slideId = (int) $slide['id'];
        $tag = $conn->real_escape_string($slide['tag']);
        $desc = $conn->real_escape_string($slide['description']);

        if (!empty($_FILES['slides']['name'][$i]['image'])) {
            $tmpName = $_FILES['slides']['tmp_name'][$i]['image'];

            if (!isImage($tmpName)) {
                die('Invalid file type for slide image. Only images are allowed.');
            }

            $imageName = $_FILES['slides']['name'][$i]['image'];
            $imgPath = 'images/' . basename($imageName);
            move_uploaded_file($tmpName, $imgPath);
            $conn->query("UPDATE slides SET tag='$tag', description='$desc', image='$imgPath' WHERE id=$slideId");
        } else {
            $conn->query("UPDATE slides SET tag='$tag', description='$desc' WHERE id=$slideId");
        }
    }

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Tab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 30px;
        }

        form {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: auto;
        }

        h2 {
            color: #0d2f4f;
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

        fieldset {
            margin-top: 20px;
            padding: 15px;
            background: #eef5fb;
            border: 1px solid #cddbea;
            border-radius: 8px;
        }

        fieldset img {
            max-width: 150px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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

<h2>Edit Tab: <?= htmlspecialchars($tab['title']) ?></h2>

<form method="POST" enctype="multipart/form-data">
    <label>Tab Title:</label>
    <input type="text" name="tab_title" value="<?= htmlspecialchars($tab['title']) ?>" required>

    <label>Tab Logo (leave empty to keep current):</label>
    <input type="file" name="tab_logo" accept="image/*">

    <h3>Edit Slides</h3>
    <?php foreach ($slides as $i => $slide): ?>
        <fieldset>
            <legend>Slide <?= $i + 1 ?></legend>
            <input type="hidden" name="slides[<?= $i ?>][id]" value="<?= $slide['id'] ?>">

            <label>Tag:</label>
            <input type="text" name="slides[<?= $i ?>][tag]" value="<?= htmlspecialchars($slide['tag']) ?>">

            <label>Description:</label>
            <input type="text" name="slides[<?= $i ?>][description]" value="<?= htmlspecialchars($slide['description']) ?>">

            <label>Image (leave empty to keep current):</label>
            <input type="file" name="slides[<?= $i ?>][image]" accept="image/*">
            <small>Current: <?= $slide['image'] ?></small><br>
            <?php if (!empty($slide['image'])): ?>
                <img src="<?= htmlspecialchars($slide['image']) ?>" alt="Current Slide Image">
            <?php endif; ?>
        </fieldset>
    <?php endforeach; ?>

    <button type="submit">Update</button>
</form>

<a href="index.php" class="back-link">← Back to Home</a>

</body>
</html>