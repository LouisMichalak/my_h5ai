<?php
include 'php/treeGen.php';
$tree = new repos();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>H5AI</title>
        <base href="/my_h5ai/">
        <link href="https://fonts.googleapis.com/css?family=Muli|Raleway|Titillium+Web&display=swap" rel="stylesheet">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <meta charset="utf-8">
    </head>
    <body>
        <header>
            <span class="title"><h1>H5AI</h1></span>
            <ul>
                <?php
                $tree->displayMenu();
                ?>
            </ul>
        </header>
        <div class="nav">
            <div class="breadcrumb">
                <a href="/my_h5ai/">H5AI</a>
                <?php
                $tree->displayBreadCrumb();
                ?>
            </div>
        </div>
        <div class="moveButtons">
            <button class="before"><span class="titleBtn">RETOUR</span><span class="arrow">←</span></button>
            <button class="after"><span class="titleBtn">AVANCER</span><span class="arrow">→</span></button>
        </div>
        <div class="mainMenu">
            <div class="sortBar">
                <span><p class="name">Nom<span class="modifier"></p></span></span>
                <span>
                    <span><p class="modifDate">Date de modification<span class="modifier"></span></p></span>
                    <span><p class="fileSize">Taille<span class="modifier"></span></p></span>
                </span>
            </div>
            <div class="linksMainMenu">
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript" rel="script"></script>
        <script src="script/script.js" type="text/javascript" rel="script"></script>
    </body>
</html>