<body>
<link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Petit+Formal+Script' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Monsieur+La+Doulaise' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Pinyon+Script' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
<style>
    body{
        background:#000;
        background:url("<?=base_url("js/Monument_of_the_Battle_of_Chillianwala_-_inscription.jpg")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;

    }
    h2{
        color:#f66;
        text-shadow: 0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,
        0 0 3px black,0 0 3px black;
    }
    h1{
        text-align:center;
        font-size:90px;
        font-family:'Pinyon Scrip';
        color:#f66;
        margin-top:0px;
        text-shadow: 0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
        0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
        0 0 5px black,0 0 5px black,0 0 5px black, 0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
        0 0 5px black,0 0 5px black,0 0 5px black;
    }
    .link{
        font-size:40px;
        text-decoration: none;
        color:#f66;
        text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
    }
    legend   {
        text-decoration: none;
        color:#f66;
        text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
    }
   fieldset{
        text-align: center;
       width:30%;
       margin:0px;
       position:absolute;
       top:300px;
       left:50%;
       margin-left:-15%;
       background-color: rgba(255,255,255,.4);
   }

</style>
<div class="backBox">
<h2 style="text-align:center;font-size:30px;font-family:'Monsieur La Doulaise'"> Welcome to</h2>
    <h1 style=""><span>Chillianwallah 1849</span></h1>
</div>
<div style="clear:both"></div>
<fieldset ><Legend>Play As </Legend>
    <a  class="link" href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>/">Play Hotseat</a><br>
    <a  class="link" href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>/">Play Multi</a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a><br>
    <div class="attribution">
        By Dr. Sohaib ul Hassan (Wikipedia:Contact us/Photo submission) [<a target='blank' href="http://creativecommons.org/licenses/by-sa/3.0">CC-BY-SA-3.0</a>], <a target='blank' href="http://commons.wikimedia.org/wiki/File%3AMonument_of_the_Battle_of_Chillianwala_-_inscription.jpg">via Wikimedia Commons</a>    </div>
</fieldset>