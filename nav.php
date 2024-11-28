<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<header>
        <h1>Bus Ticket Reservation</h1>
        <nav>
            <ul>
                <div>
                    <a href="index.php" class="text">Home |</a>
                    <a href="index.php" class="text" id="sch">Schedule |</a>
                    <a href="customer.php" class="text" id="sch">Customer |</a>
                    <?php 
                    if (isset($_SESSION['role']) && $_SESSION['role']  == 'admin' || isset($_SESSION['role']) && $_SESSION['role'] == 'agent') { 
               
                     ?>

                        <a href="bus.php" class="text" id="sch">Bus |</a>

                    <?php } ?>


                </div>
                <div style="text-align: end;">
                <?php 
                    if (isset($_SESSION['username'])) { 
                      
                     echo htmlspecialchars($_SESSION['username']); 
                     echo '  <a href="logout.php" class="text" id="log"> | LogOut</a>';
                    } else {
                         echo 'Guest |';
                        echo ' <a href="login.php" class="text" id="log">Login</a>';  // Display 'Guest' if the user is not logged in
                    }
                ?>
                   
                </div>
            </ul>
        </nav>
    </header>

    
</body>
</html>