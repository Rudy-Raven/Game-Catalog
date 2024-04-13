<?php
    
    session_start();
    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");
    include("classes/view_details.php");

    $name="";

    //Check if user is logged in
    if(isset($_SESSION['gamelist_userid']) & is_numeric( $_SESSION['gamelist_userid'] ))
    {
        $id = $_SESSION['gamelist_userid'];
        $login = new Login();
        $result = $login->check_login($id);

        if($result == true)
        {
            //Retrive user data
            $user= new User();
            $data=$user->user_data($id);
            $name.=$data['firstname']." ".$data['lastname'];
            
        }
        else
        {
            header("Location: login.php");
            die;
        }
    }
    else
    {
        header("Location: login.php");
        die;
    } 

    //To check if view details button is clicked
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $game_id="";
        foreach ($_POST as $key=>$value)
        {
            $game_id=$key;
        }

        $viewdetails= new ViewDetails();
        $viewdetails->create_session($game_id);
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homepage | GameList</title>
    </head>

    <style type="text/css">
        
        /* Styles for the navigation bar */
        nav {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-around;
        }

        nav a {
            text-decoration: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #555;
        }

        .active {
            background-color: #555;
        }

        #top_bar{
            height: 50px;
            background: linear-gradient(to right, #021024, #1e487f); 
            color: #ffffff;
        }
        .game-bar {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .serial-number {
            font-size: 18px;
            font-weight: bold;
            margin-right: 10px;
        }

        .game-image {
            width: 80px;
            height: 80px;
            margin-right: 10px;
        }

        .game-info {
            flex-grow: 1;
        }

        .game-name {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .game-details {
            color: blue;
            text-decoration: none;
        }

        .game-details:hover {
            text-decoration: underline;
        }
    </style>

    <body style="font-family: georgia; background-color: #7DA0CA;">
        <div id="top_bar">
            <div style="float: left;font-size: 30px; margin: 8px;">
                GameList
            </div>
            <div style="float: right;font-size: 20px;margin: 10px;">
                <div>Logged in as, <?php echo $name; ?></div>
                <!-- <a href="profile.html" target="_blank" style="color: #ffffff">Logged in as, Pranto</a> -->
            </div>
            <a href="profile.html" style="float: right; margin: 12px; color: #ffffff">
                <span>Profile</span>
            </a>
            <a href="logout.php" style="float: right; margin: 12px; color: #ffffff">
                <span>Logout</span>
            </a>
        </div>
            
        <div style="width: 900px; margin: auto; background-color: black; height: 350px;">
            <img src="cover.jpeg" style="width:900px; height: 350px;">
            <br>
            <nav>
                <a href="homepage.php" class="active">All Games</a>
                <a href="homepage_genre.php">Sort by: Genre</a>
                <a href="homepage_latest.php">Sort by: Latest</a>
                <a href="homepage_old.php">Sort by: Oldest</a>
                <a href="homepage_rating.php">Sort by: Rating</a>
            </nav>
            <?php
                include("classes/games.php");
                $games= new Games();
                $game_data=$games->extract_games();
                $serial_number=1;
                foreach ($game_data as $key=>$value)
                {
                    echo '<div class="game-bar">';
                    echo '<div class="serial-number">';
                    echo $serial_number;
                    echo '</div>';
                    echo '<img class="game-image" src="gamepic.jpg" alt="Game 1">';
                    echo '<div class="game-info">';
                    echo '<div class="game-name">';
                    echo $value['name'];
                    echo '</div>';
                    echo '<form method="post">';
                    echo '<input type="submit" value="View Details" name=';
                    echo $value['game_id'];
                    echo '>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    $serial_number++;
                }
            ?>
        </div>
        

    </body>
</html>
