<body>
<style>
    body{
        background:url("<?=base_url("js/civil-war-public-domain.jpg")?>") #333 no-repeat;
        background-position:center top;
        background-size:100%;
    }
    h1{
        color:#f66;
        text-shadow: 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white, 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white
    }
    h2{
        text-align:center;
        font-size:38px;
        font-family:'Great Vibes';
        color:#f66;
        text-shadow: 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white, 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white
    }
    .rebel{
        font-size:40px;
        color:red;
    }
    .loyalist{
      font-size:40px;
        color:blue;

    }
    .link{
        font-size:40px;
        text-decoration: none;
        color:#f66;
        text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
    }
    legend   {
    color:white;
    }
   fieldset{
       background:rgba(255,255,255,.8);
       text-align:center;width:30%;
       margin:60px auto;
       border-radius:15px;
       border:3px solid black;
       box-shadow:10px 10px 10px rgba(30,30,30,.9);
   }
    .clear{
        clear:both;
    }
    a:hover{
        text-decoration: underline;
    }
</style>
<link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>

<h2 style=""> Welcome to</h2>
    <h1 style="text-align:center;font-size:90px;font-family:'Great Vibes'">Tutorials</h1>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a>
</fieldset>
