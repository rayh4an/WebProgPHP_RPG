<?php
session_start();

// Initialize stats and current choice if not already set
if (!isset($_SESSION['karma'])) $_SESSION['karma'] = 0;
if (!isset($_SESSION['strength'])) $_SESSION['strength'] = 1;
if (!isset($_SESSION['choice'])) $_SESSION['choice'] = 1;

// Update stats and progress through choices based on user input
if (isset($_POST['option'])) {
    $option = $_POST['option'];
    $choice = $_SESSION['choice'];

    // Define game logic for each choice based on the questline
    //village
    if ($choice == 1) {
        if ($option == "help") {
            $_SESSION['karma'] += 1;
            $_SESSION['strength'] += 1;
            $_SESSION['choice'] = 2;
        }
        elseif ($option == "run") {
            $_SESSION['karma'] -= 1;
            $_SESSION['choice'] = 2;
        }
    } elseif ($choice == 2) {
        if ($option == "attack" && $_SESSION['strength'] >= 2) { 
            $_SESSION['karma'] += 1;
            $_SESSION['strength'] += 1;
            $_SESSION['choice'] = 3; }
        elseif ($option == "befriend" && $_SESSION['karma'] <= -1) { 
            $_SESSION['strength'] += 3;
            $_SESSION['karma'] -= 2;
            $_SESSION['choice'] = 3;
        }
        elseif ($option == "alert") { $_SESSION['karma'] += 1; $_SESSION['choice'] = 3; }
    //forest
    } elseif ($choice == 3) {
        if ($option == "escort") {
            $_SESSION['choice'] = 6;
        } // Skip to mountains
        elseif ($option == "ignore") {
            $_SESSION['choice'] = 4;
        }
    } elseif ($choice == 4) {
        if ($option == "left") {
            $_SESSION['choice'] = 5;
        }
        elseif ($option == "right") {
            $_SESSION['strength'] += 1;
            $_SESSION['choice'] = 6;
        } // Escape forest to mountains
    } elseif ($choice == 5) {
        if ($option == "fight" && $_SESSION['strength'] >= 2) {
            $_SESSION['strength'] += 2;
            $_SESSION['karma'] += 1;
            $_SESSION['choice'] = 6;
        } elseif ($option == "fight" && $_SESSION['strength'] < 2) {
            $_SESSION['choice'] = 3; // Restart forest section
        } elseif ($option == "convince" && $_SESSION['karma'] >= 2) {
            $_SESSION['choice'] = 6; // Move to mountains
        }
    //mountains
    } elseif ($choice == 6) {
        if ($option == "climb" && $_SESSION['strength'] >= 4) {
            $_SESSION['strength'] += 1;
            $_SESSION['choice'] = 7; // Reach ruins
        } elseif ($option == "long_way") {
            $_SESSION['choice'] = 7;
        }
    } elseif ($choice == 7) {
        if ($option == "help_wizard") {
            $_SESSION['strength'] += 1;
            $_SESSION['karma'] += 1;
            $_SESSION['choice'] = 8;
        } elseif ($option == "ignore_wizard") {
            $_SESSION['karma'] -= 1;
            $_SESSION['choice'] = 8;
        } elseif ($option == "rob_wizard") {
            $_SESSION['karma'] -= 2;
            $_SESSION['strength'] += 1;
            $_SESSION['choice'] = 8;
        }
    } elseif ($choice == 8) {
        if ($option == "fight_bandits") {
            $_SESSION['strength'] += 1;
            $_SESSION['choice'] = 9;
        } elseif ($option == "convince_bandits" && $_SESSION['karma'] >= 2) {
            $_SESSION['choice'] = 9;
        } elseif ($option == "scare_bandits" && $_SESSION['karma'] >= 4) {
            $_SESSION['strength'] += 2;
            $_SESSION['choice'] = 9;
        }
    //ruins
    } elseif ($choice == 9) {
        if ($option == "wear_amulet") {
            $_SESSION['strength'] += 3;
            $_SESSION['karma'] -= 3;
            $_SESSION['choice'] = 10;
        } elseif ($option == "walk_away") {
            $_SESSION['choice'] = 10;
        } elseif ($option == "destroy_amulet" && $_SESSION['strength'] >= 4) {
            $_SESSION['karma'] += 3;
            $_SESSION['choice'] = 10;
        }
    } elseif ($choice == 10) {
        if ($option == "leave_ruins") {
            $_SESSION['karma'] += 1;
            $_SESSION['choice'] = 11; // Move to next section (Lake)
    } elseif ($option == "attack_spirit" && $_SESSION['strength'] >= 5) {
            $_SESSION['karma'] -= 2;
            $_SESSION['strength'] += 2;
            $_SESSION['choice'] = 11;
    } elseif ($option == "attack_spirit" && $_SESSION['strength'] < 5) {
            $_SESSION['choice'] = 9; // Restart ruins section
        }
    //lake
    } elseif ($choice == 11) {
        if ($option == "drink" && $_SESSION['strength'] >= 4) {
            $_SESSION['strength'] += 2;
            $_SESSION['choice'] = 12;
        } elseif ($option == "drink" && $_SESSION['strength'] < 4) {
            $_SESSION['choice'] = 11; // Restart lake section
        } elseif ($option == "ignore_water") {
            $_SESSION['choice'] = 12;
        }
    } elseif ($choice == 12) {
        if ($option == "avoid_fight") {
            $_SESSION['choice'] = 13; // Move to final choice at castle
        } elseif ($option == "tell_fisherman" && $_SESSION['karma'] >= 5) {
            $_SESSION['choice'] = 13;
        } elseif ($option == "fight_monster" && $_SESSION['strength'] >= 6) {
            $_SESSION['karma'] += 3;
            $_SESSION['strength'] += 3;
            $_SESSION['choice'] = 13;
        } elseif ($option == "fight_monster" && $_SESSION['strength'] < 6) {
            $_SESSION['choice'] = 11; // Restart lake section
        }
    //Castle
    } elseif ($choice == 13) { // Final choice
        if ($option == "join_tyrant" && $_SESSION['karma'] <= -5) {
            echo "<h2>You become the tyrant's loyal knight and heir.</h2>";
            session_destroy();
            exit();
        } elseif ($option == "throne_tyrant" && $_SESSION['karma'] <= -5 && $_SESSION['strength'] >= 8) {
            echo "<h2>You kill the tyrant and become the new ruler, bringing more suffering.</h2>";
            session_destroy();
            exit();
        } elseif ($option == "convince_tyrant" && $_SESSION['karma'] >= 10) {
            echo "<h2>You convince the tyrant to change his ways. You become his kingdom's counselor.</h2>";
            session_destroy();
            exit();
        } elseif ($option == "throne_tyrant" && $_SESSION['karma'] >= 5 && $_SESSION['strength'] >= 8) {
            echo "<h2>You kill the tyrant and become a great ruler, making the world a better place.</h2>";
            session_destroy();
            exit();
        } elseif ($option == "kill_tyrant" && $_SESSION['strength'] >= 8) {
            echo "<h2>You kill the tyrant and leave to seek new adventures.</h2>";
            session_destroy();
            exit();
        } elseif ($option == "throne_tyrant" && $_SESSION['strength'] < 8) {
            echo "<h2>You Challenge the Tyrant, but he ends up killing you. Your journey ends in failure.</h2>";
            session_destroy();
            exit();
        } elseif ($option == "kill_tyrant" && $_SESSION['strength'] < 8) {
            echo "<h2>You Challenge the Tyrant, but he ends up killing you. Your journey ends in failure.</h2>";
            session_destroy();
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RPG Quest</title>
    <link rel="stylesheet" href="gameStyles.css">
</head>
<body>

<div class="game-container">
    <div class="stats">
        <strong>Strength:</strong> <?php echo $_SESSION['strength']; ?> &nbsp;&nbsp;
        <strong>Karma:</strong> <?php echo $_SESSION['karma']; ?>
    </div>

    <div class="image">
    <?php
    if ($_SESSION['choice'] == 1) {
        echo '<img src="images/villageFire1.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 2) {
        echo '<img src="images/GoblinKing2.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 3) {
        echo '<img src="images/forest3.jpg" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 4) {
        echo '<img src="images/doublePath4.jpg" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 5) {
        echo '<img src="images/troll5.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 6) {
        echo '<img src="images/mountain6.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 7) {
        echo '<img src="images/wizard7.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 8) {
        echo '<img src="images/bandits8.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 9) {
        echo '<img src="images/ruins9.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 10) {
        echo '<img src="images/spirit10.jpg" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 11) {
        echo '<img src="images/lake11.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 12) {
        echo '<img src="images/seaKing12.png" style="width:100%; height:100%;">';
    } elseif ($_SESSION['choice'] == 13) {
        echo '<img src="images/Tyrant13.png" style="width:100%; height:100%;">';
    }
    ?>
    </div>

    <div class="narration">
    <?php
    // Display current scenario text based on choice
    if ($_SESSION['choice'] == 1) {
        echo "<p>Your journey starts and you decide to go to the local village to gather suppliers. </p>";
        echo "<p>When you arrive you see the village is under attack by goblins. What do you do?</p>";
    } elseif ($_SESSION['choice'] == 2) {
        echo "<p>You go wandering about and find the goblins' hideout. How do you proceed?</p>";
    } elseif ($_SESSION['choice'] == 3) {
        echo "<p>After visiting the goblins' hideout, you venture into the forest.</p>";
        echo "<p>You meet a Fairy who warns you that a dangerous Troll resides nearby. What will you do?</p>";
    } elseif ($_SESSION['choice'] == 4) {
        echo "<p>After ignoring the Fairy's advice you see there is path that splits off in 2 directions.</p>";
    } elseif ($_SESSION['choice'] == 5) {
        echo "<p>After going left, you encounter a Troll! Do you fight or try to convince it to let you go?</p>";
    } elseif ($_SESSION['choice'] == 6) {
        echo "<p>Once you leace the forest you arrive at a mountain.</p>";
        echo "<p>You see a long path that wraps around, which seems safe. You also see a steep climb up the mountain. Which path will you take?</p>";
    } elseif ($_SESSION['choice'] == 7) {
        echo "<p>On the mountain path, you encounter a wizard with a broken leg who asks for help. He says if you help he will compensate you with a very powerful bow. How will you respond?</p>";
    } elseif ($_SESSION['choice'] == 8) {
        echo "<p>You meet a group of bandits who demand your belongings. How do you handle the situation?</p>";
    } elseif ($_SESSION['choice'] == 9) {
        echo "<p>Once reaching the top of the mountain, you see a portal. You decide to head through the portal.</p>";
        echo "<p>Now you have arrived at some ancient ruins and see a glowing amulet on the ground. As you approach it, you hear ominous whispers. What do you do?</p>";
    } elseif ($_SESSION['choice'] == 10) {
        echo "<p>In the ruins, you encounter a spirit who tells you this is a graveyard and urges you to leave. How do you respond?</p>";
    } elseif ($_SESSION['choice'] == 11) {
        echo "<p>After leaving the ruins, you get to a beautiful lake. You are feeling a bit thirsty so you look at the water of the lake. Will you drink it, or ignore the urge?</p>";
    } elseif ($_SESSION['choice'] == 12) {
        echo "<p>While walking along the lake's coast, you are approached by a fisherman. He informs you of a terrible monster in the lake that has terrorized his family. Will you help him, or avoid the danger?</p>";
    } elseif ($_SESSION['choice'] == 13) {
        echo "<p>After leaving the lake you walk the path ahead and finally reach the Tyrant's castle and are granted an audience in his throne room. How will you confront him?</p>";
    }
    ?>
    </div>


    <form method="post" class="options">
    <?php
    // Display options based on choice
    if ($_SESSION['choice'] == 1) {
        echo '<button type="submit" name="option" value="help">Help villagers</button>';
        echo '<button type="submit" name="option" value="run">Run away</button>';
    } elseif ($_SESSION['choice'] == 2) {
        echo '<button type="submit" name="option" value="attack">Attack Goblin King (STRENTGH ≥ 2)</button>';
        echo '<button type="submit" name="option" value="befriend">Befriend Goblin King (KARMA ≤ -1)</button>';
        echo '<button type="submit" name="option" value="alert">Alert the village</button>';
    } elseif ($_SESSION['choice'] == 3) {
        echo '<button type="submit" name="option" value="escort">Ask Fairy to escort you out of danger</button>';
        echo '<button type="submit" name="option" value="ignore">Ignore the Fairy\'s warnings</button>';
    } elseif ($_SESSION['choice'] == 4) {
        echo '<button type="submit" name="option" value="left">Go Left</button>';
        echo '<button type="submit" name="option" value="right">Go Right. You see a shiny sword in the distance. </button>';
    } elseif ($_SESSION['choice'] == 5) {
        echo '<button type="submit" name="option" value="fight">Fight the Troll</button>';
        echo '<button type="submit" name="option" value="convince">Convince the Troll to let you go (KARMA ≥ 2)</button>';
    } elseif ($_SESSION['choice'] == 6) {
        echo '<button type="submit" name="option" value="climb">Climb the mountain path (STRENTGH ≥ 4)</button>';
        echo '<button type="submit" name="option" value="long_way">Take the long way around</button>';
    } elseif ($_SESSION['choice'] == 7) {
        echo '<button type="submit" name="option" value="help_wizard">Help the wizard</button>';
        echo '<button type="submit" name="option" value="ignore_wizard">Ignore the wizard</button>';
        echo '<button type="submit" name="option" value="rob_wizard">Rob the wizard</button>';
    } elseif ($_SESSION['choice'] == 8) {
        echo '<button type="submit" name="option" value="fight_bandits">Fight the bandits</button>';
        echo '<button type="submit" name="option" value="convince_bandits">Convince the bandits to leave you alone (KARMA ≥ 2)</button>';
        echo '<button type="submit" name="option" value="scare_bandits">Scare the bandits to give up their belongings (KARMA ≥ 4)</button>';
    } elseif ($_SESSION['choice'] == 9) {
        echo '<button type="submit" name="option" value="wear_amulet">Pick up and wear the amulet</button>';
        echo '<button type="submit" name="option" value="walk_away">Do not pick up the amulet</button>';
        echo '<button type="submit" name="option" value="destroy_amulet">Destroy the amulet (STRENTGH ≥ 4)</button>';
    } elseif ($_SESSION['choice'] == 10) {
        echo '<button type="submit" name="option" value="leave_ruins">Listen to the spirit and leave the ruins</button>';
        echo '<button type="submit" name="option" value="attack_spirit">Attack the spirit (STRENTGH ≥ 5)</button>';
    } elseif ($_SESSION['choice'] == 11) {
        echo '<button type="submit" name="option" value="drink">Drink the water</button>';
        echo '<button type="submit" name="option" value="ignore_water">Do not drink the water</button>';
    } elseif ($_SESSION['choice'] == 12) {
        echo '<button type="submit" name="option" value="avoid_fight">Tell the fisherman it is too dangerous</button>';
        echo '<button type="submit" name="option" value="tell_fisherman">Convince the fisherman to leave (KARMA ≥ 5)</button>';
        echo '<button type="submit" name="option" value="fight_monster">Fight the monster</button>';
    } elseif ($_SESSION['choice'] == 13) { // Final choice at castle
        echo '<button type="submit" name="option" value="convince_tyrant">Convince the tyrant to change his ways (KARMA ≥ 10)</button>';
        echo '<button type="submit" name="option" value="join_tyrant">Ask to join the Tyrant as his loyal knight(KARMA ≤ -5)</button>';
        echo '<button type="submit" name="option" value="throne_tyrant">Challenge Tyrant to take his throne (KARMA ≤ -5 OR KARMA ≥ 5)</button>';
        echo '<button type="submit" name="option" value="kill_tyrant">Challenge Tyrant and do not take his throne</button>';
    }
    ?>
    </form>

    <form action="reset.php" method="post">
        <button type="submit" name="reset">Reset Game</button>
    </form>

    <form action="menu.php" method="post">
        <button type="submit" name="menu">Main Menu</button>
    </form>
</div>

</body>
</html>