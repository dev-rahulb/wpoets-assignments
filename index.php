<?php
include 'db.php';

$tabs = [];
$sql = "SELECT * FROM tabs";
$result = $conn->query($sql);

while ($tab = $result->fetch_assoc()) {
    $tabId = $tab['id'];
    $tab['slides'] = [];

    $slideSql = "SELECT * FROM slides WHERE tab_id = $tabId";
    $slideResult = $conn->query($slideSql);

    while ($slide = $slideResult->fetch_assoc()) {
        $tab['slides'][] = $slide;
    }

    $tabs[$tab['title']] = $tab;
}
$tabTitles = array_keys($tabs);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tabs + Slider + Accordion + Mobile Background</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="assets/styles.css?v=1.0">
</head>
<body>
 <div style="padding:10px;">
    <button class="manage-btn" onclick="document.getElementById('crud-panel').classList.toggle('show');">☰ Manage</button>
</div>

<div id="crud-panel" class="crud-panel">
    <h2>Manage Tabs</h2>
    <a href="create.php"><button>Add New Tab + Slides</button></a>
    <ul>
        <?php foreach ($tabs as $tab): ?>
            <li style="margin:10px 0;">
                <strong><?= $tab['title'] ?></strong>
                <a href="edit.php?id=<?= $tab['id'] ?>">[Edit]</a>
                <a href="delete.php?id=<?= $tab['id'] ?>" onclick="return confirm('Delete this tab?')">[Delete]</a>
                <ul>
                    <?php foreach ($tab['slides'] as $slide): ?>
                        <li><?= $slide['description'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="container">
    <div class="sidebar">
        <?php foreach ($tabTitles as $index => $title): ?>
            <?php $logo = $tabs[$title]['logo']; ?>
            <div class="tab <?= $index === 0 ? 'active' : '' ?>" data-tab="<?= $index ?>">
                <img src="<?= $logo ?>" alt="<?= $title ?> logo">
                <?= $title ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="slider-area">
        <?php foreach ($tabs as $tabTitle => $tabData): ?>
            <?php $tabIndex = array_search($tabTitle, $tabTitles); ?>
            <div class="slider-container <?= $tabIndex === 0 ? 'active' : '' ?>" data-tab="<?= $tabIndex ?>">
                <?php foreach ($tabData['slides'] as $i => $slide): ?>
                  <div class="slide-content <?= $i === 0 ? 'active' : '' ?>" data-slide="<?= $i ?>">
    <div class="tag"><?= $slide['tag'] ?></div>
    <h3><?= $slide['description'] ?></h3>
    <a href="#" class="learn-more">Learn More →</a>
</div>
                <?php endforeach; ?>
                <div class="dot-container">
                    <?php foreach ($tabData['slides'] as $i => $_): ?>
                        <span class="dot <?= $i === 0 ? 'active' : '' ?>" data-slide="<?= $i ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="image-area">
        <?php foreach ($tabs as $tabTitle => $tabData): ?>
            <?php $tabIndex = array_search($tabTitle, $tabTitles); ?>
            <?php foreach ($tabData['slides'] as $i => $slide): ?>
                <img src="<?= $slide['image'] ?>" class="<?= ($tabIndex === 0 && $i === 0) ? 'active' : '' ?>" data-tab="<?= $tabIndex ?>" data-slide="<?= $i ?>" alt="Slide Image">
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <div class="mobile-accordion">
        <?php foreach ($tabs as $tabTitle => $tabData): ?>
            <?php $tabIndex = array_search($tabTitle, $tabTitles); $logo = $tabData['logo']; ?>
            <div class="accordion">
                <div class="accordion-header" data-tab="<?= $tabIndex ?>">
                    <span class="accordion-title">
                        <img src="<?= $logo ?>" alt="<?= $tabTitle ?> logo" style="width:35px!important;">
                        <?= $tabTitle ?>
                    </span>
                    <span class="accordion-icon">
                        <img src="images/plus-01.svg" class="icon-plus" alt="plus icon">
                        <img src="images/minus-01.svg" class="icon-minus" alt="minus icon" style="display:none;">
                    </span>
                </div>
                <div class="accordion-body" data-tab="<?= $tabIndex ?>">
                   <?php foreach ($tabData['slides'] as $i => $slide): ?>
    <div class="slide <?= $i === 0 ? 'active' : '' ?>" data-slide="<?= $i ?>" style="background-image: url('<?= str_replace("\\", "/", $slide['image']) ?>');">
        <div class="tag"><?= $slide['tag'] ?></div>
        <h3 style="
    background: none;
"><?= $slide['description'] ?></h3>
        <a href="#" class="learn-more">Learn More →</a>

        <!-- Moved dot-container inside each slide -->
        <div class="dot-container">
            <?php foreach ($tabData['slides'] as $j => $_): ?>
                <span class="dot <?= $j === $i ? 'active' : '' ?>" data-slide="<?= $j ?>"></span>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    const tabs = document.querySelectorAll('.tab');
    const sliderContainers = document.querySelectorAll('.slider-container');
    const images = document.querySelectorAll('.image-area img');
    let currentTabIndex = 0;

    function activateTab(index) {
        currentTabIndex = index;

        tabs.forEach((tab, i) => tab.classList.toggle('active', i === index));

        sliderContainers.forEach(container => {
            const tabId = parseInt(container.getAttribute('data-tab'));
            container.classList.toggle('active', tabId === index);

            if (tabId === index) {
                const slides = container.querySelectorAll('.slide-content');
                const dots = container.querySelectorAll('.dot');
                slides.forEach((s, i) => s.classList.toggle('active', i === 0));
                dots.forEach((d, i) => d.classList.toggle('active', i === 0));
                updateImage(index, 0);
            }
        });
    }

    function updateImage(tabIndex, slideIndex) {
        images.forEach(img => {
            const imgTab = parseInt(img.getAttribute('data-tab'));
            const imgSlide = parseInt(img.getAttribute('data-slide'));
            img.classList.toggle('active', imgTab === tabIndex && imgSlide === slideIndex);
        });
    }

    tabs.forEach((tab, i) => {
        tab.addEventListener('click', () => activateTab(i));
    });

    sliderContainers.forEach(container => {
        const tabIndex = parseInt(container.getAttribute('data-tab'));
        const dots = container.querySelectorAll('.dot');
        const slides = container.querySelectorAll('.slide-content');

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                if (tabIndex !== currentTabIndex) return;
                dots.forEach(d => d.classList.remove('active'));
                slides.forEach(s => s.classList.remove('active'));
                dot.classList.add('active');
                slides[i].classList.add('active');
                updateImage(tabIndex, i);
            });
        });
    });

    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const tab = header.getAttribute('data-tab');
            const currentBody = document.querySelector(`.accordion-body[data-tab="${tab}"]`);
            const currentIcon = header.querySelector('.accordion-icon');
            const plusIcon = currentIcon.querySelector('.icon-plus');
            const minusIcon = currentIcon.querySelector('.icon-minus');
            const isActive = currentBody.classList.contains('active');

            document.querySelectorAll('.accordion-body').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.accordion-icon .icon-plus').forEach(i => i.style.display = 'inline');
            document.querySelectorAll('.accordion-icon .icon-minus').forEach(i => i.style.display = 'none');

            if (!isActive) {
                currentBody.classList.add('active');
                plusIcon.style.display = 'none';
                minusIcon.style.display = 'inline';
            }
        });
    });

    document.querySelectorAll('.accordion-body').forEach(body => {
        let touchStartX = 0;
        let touchEndX = 0;
        const slides = body.querySelectorAll('.slide');
        const dots = body.querySelectorAll('.dot');

        function showSlide(index) {
            slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
            dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
        }

        body.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        body.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            const activeIndex = [...slides].findIndex(slide => slide.classList.contains('active'));
            if (touchEndX < touchStartX - 30 && activeIndex < slides.length - 1) {
                showSlide(activeIndex + 1);
            } else if (touchEndX > touchStartX + 30 && activeIndex > 0) {
                showSlide(activeIndex - 1);
            }
        });
    });

    activateTab(0);

</script>
</body>
</html>