<?php include 'includes/header.php'; ?>

<main>
    <h2>Dashboard</h2>

    <?php
    if ($section === 'dashboard') {
        switch ($action) {
            case 'view':
                include 'dashboard/home.php';
                break;
            case 'create':
                break;
            case 'edit':
                break;
            case 'update':
                break;
            case 'delete':
                break;
            default:
                echo 'Invalid dashboard action';
                break;
        }
    } else {
        echo 'Invalid section';
    }
    ?>
</main>

<?php include 'includes/footer.php'; ?>