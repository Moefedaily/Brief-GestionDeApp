<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <?php
        switch ($section) {
            
            case 'menu':
                switch ($action) {
                    case 'login':
                        include 'auth/login.php';
                        break;
                    case 'register':
                        include 'auth/register.php';
                        break;
                        case 'SetPassword':
                            include 'auth/SetPassword.php';
                            break;
                    default:
                        echo '<h2>Menu</h2>';
                        echo '<p>Welcome to the menu  section.</p>';
                        break;
                }
                break;
            
            default:
                echo '<h1>Welcome to My App</h1>';
                echo '<p>This is the home page.</p>';
                break;
        }
        ?>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
</body>
</html>